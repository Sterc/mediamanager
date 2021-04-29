<?php
set_time_limit(0);

if (!function_exists('updateTableColumns')) {
    /**
     * @param modX $modx
     * @param string $table
     */
    function updateTableColumns($modx, $table)
    {
        $tableName = $modx->getTableName($table);
        $tableName = str_replace('`', '', $tableName);
        $dbname = $modx->getOption('dbname');

        $c = $modx->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = :dbName AND table_name = :tableName");
        $c->bindParam(':dbName', $dbname);
        $c->bindParam(':tableName', $tableName);
        $c->execute();

        $unusedColumns = $c->fetchAll(PDO::FETCH_COLUMN, 0);
        $unusedColumns = array_flip($unusedColumns);

        $meta = $modx->getFieldMeta($table);
        $columns = array_keys($meta);

        $m = $modx->getManager();

        foreach ($columns as $column) {
            if (isset($unusedColumns[$column])) {
                $m->alterField($table, $column);
                $modx->log(modX::LOG_LEVEL_INFO, ' -- altered column: ' . $column);
                unset($unusedColumns[$column]);
            } else {
                $m->addField($table, $column);
                $modx->log(modX::LOG_LEVEL_INFO, ' -- added column: ' . $column);
            }
        }

        foreach ($unusedColumns as $column => $v) {
            $m->removeField($table, $column);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- removed column: ' . $column);
        }
    }
}

if (!function_exists('updateTableIndexes')) {
    /**
     * @param modX $modx
     * @param string $table
     */
    function updateTableIndexes($modx, $table)
    {
        $tableName = $modx->getTableName($table);
        $tableName = str_replace('`', '', $tableName);
        $dbname = $modx->getOption('dbname');

        $c = $modx->prepare("SELECT DISTINCT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = :dbName AND table_name = :tableName AND INDEX_NAME != 'PRIMARY'");
        $c->bindParam(':dbName', $dbname);
        $c->bindParam(':tableName', $tableName);
        $c->execute();

        $oldIndexes = $c->fetchAll(PDO::FETCH_COLUMN, 0);

        $m = $modx->getManager();

        foreach ($oldIndexes as $oldIndex) {
            $m->removeIndex($table, $oldIndex);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- removed index: ' . $oldIndex);
        }

        $meta = $modx->getFieldMeta($table);
        if (is_array($meta) && count($meta) > 0) {
            //check for new indexes in fielddefinitions
            foreach ($meta as $field => $value) {
                if (isset($value['index'])) {
                    switch ($value['index']) {
                        case 'pk':
                            break;
                        default:
                            $indexmeta = array();
                            $indexmeta['type'] = strtoupper($value['index']);
                            $column = array();
                            $columns = array();
                            $columns[$field] = $column;
                            $indexmeta['columns'] = $columns;
                            //add field-indexmeta to xpdo-index-map, otherwise addIndex does not work
                            $modx->map[$table]['indexes'][$field] = $indexmeta;

                            $m->addIndex($table, $field);
                            $modx->log(modX::LOG_LEVEL_INFO, ' -- added index: ' . $field);
                            break;
                    }
                }
            }
        }
    }
}

if (!function_exists('alterTable')) {
    /**
     * @param modX $modx
     * @param string $table
     */
    function alterTable($modx, $table)
    {
        $modx->log(modX::LOG_LEVEL_INFO, ' - Updating columns');
        updateTableColumns($modx, $table);

        $modx->log(modX::LOG_LEVEL_INFO, ' - Updating indexes');
        updateTableIndexes($modx, $table);
    }
}

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modX $modx */
            $modx =& $object->xpdo;

            $tables = array(
                'MediamanagerCategories',
                'MediamanagerTags',
                'MediamanagerFiles',
                'MediamanagerDownloads',
                'MediamanagerFilesCategories',
                'MediamanagerFilesTags',
                'MediamanagerFilesContent',
                'MediamanagerFilesRelations',
                'MediamanagerFilesVersions',
                'MediamanagerFilesMeta'
            );

            $modelPath = $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path') . 'components/mediamanager/') . 'model/';
            $modx->addPackage('mediamanager', $modelPath);

            foreach ($tables as $table) {
                $modx->log(modX::LOG_LEVEL_INFO, 'Altering table: ' . $table);
                alterTable($modx, $table);
            }

            break;
    }
}

return true;