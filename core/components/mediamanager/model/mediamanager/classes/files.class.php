<?php

class MediaManagerFilesHelper
{
    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

    /**
     * The mediaSource object.
     */
    private $mediaSource = null;

    /**
     * Upload paths.
     */
    private $uploadUrl = null;
    private $uploadDirectory = null;
    private $uploadDirectoryYear = null;
    private $uploadDirectoryMonth = null;

    /**
     * Image types.
     * @var array
     */
    private $imageTypes = array();

    /**
     * Sort options.
     * @var array
     */
    private $sortOptions = array();

    /**
     * Filter options.
     * @var array
     */
    private $filterOptions = array();

    /**
     * MediaManagerFilesHelper constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;

        $this->setImageTypes();
        $this->setSortOptions();
        $this->setFilterOptions();
    }

    /**
     * Get files.
     *
     * @param string $search
     * @param array $filters
     * @param array $sorting
     *
     * @return array
     */
    public function getList($search = '', $filters = array(), $sorting = array())
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerFiles');

        $sortColumn = 'MediamanagerFiles.upload_date';
        $sortDirection = 'DESC';

        $select = $this->mediaManager->modx->getSelectColumns('MediamanagerFiles', 'MediamanagerFiles');

        $where = array();
//        $where[]['MediamanagerFiles.mediamanager_contexts_id'] = $this->mediaManager->contexts->getCurrentContext();
        $where[]['MediamanagerFiles.is_archived'] = 0;

        if (!empty($search) && strlen($search) > 2) {
            $where[]['name:LIKE'] = '%' . $search . '%';
        }

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (empty($value)) {
                    continue;
                }

                switch ($key) {
                    case 'type' :
                        if ((int) $value !== 0) {
                            $where[]['MediamanagerFiles.file_type:IN'] = $this->filterOptions[$key][$value]['types'];
                        }
                        break;

                    case 'user' :
                        $where[]['MediamanagerFiles.uploaded_by'] = (int) $value;
                        break;

                    case 'categories' : // OR filter
                        $q->innerJoin('MediamanagerFilesCategories', 'Categories');
                        $i = 0;
                        foreach ($value as $categoryId) {
                            $where[][($i++ === 0 ? '' : 'OR:') . 'Categories.mediamanager_categories_id:='] = (int) $categoryId;
                        }
                        break;

                    case 'tags' : // AND filter
                        $q->innerJoin('MediamanagerFilesTags', 'Tags');
                        foreach ($value as $tagId) {
                            $where[]['Tags.mediamanager_tags_id'] = (int) $tagId;
                        }
                        break;
                }
            }
        }

        if (!empty($sorting)) {
            $sortColumn = 'MediamanagerFiles.' . $sorting[0];
            $sortDirection = $sorting[1];
        }

        $q->select($select);
        $q->where($where);
        $q->sortby($sortColumn, $sortDirection);

        return $this->mediaManager->modx->getIterator('MediamanagerFiles', $q);
    }

    /**
     * Get files html.
     *
     * @param int $context
     * @param int $category
     * @param string $search
     * @param array $filters
     * @param array $sorting
     * @param string $viewMode
     *
     * @return string
     */
    public function getListHtml($context = 0, $category = 0, $search = '', $filters = array(), $sorting = array(), $viewMode = 'grid')
    {
        $viewMode = ($viewMode === 'grid' ? 'grid' : 'list');

        if ($category && ! isset($filters['categories'])) {
            $filters['categories'][] = $category;
        }

        $files = $this->getList($search, $filters, $sorting);

        $breadcrumbs = array();
        $html = '';

        foreach ($files as $file) {
            $file = $file->toArray();

            if ($viewMode === 'grid') {
                if ($this->isImage($file['file_type'])) {
                    $file['preview_path'] = '/connectors/system/phpthumb.php?src=' . $file['path'] . '&w=226&h=180';
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_img', $file);
                } else {
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_svg', $file);
                }
            }

            $html .= $this->mediaManager->getChunk('files/' . $viewMode . '/file', $file);
        }

        if (!empty($html)) {
            $data = array(
                'breadcrumbs' => $this->mediaManager->getChunk('files/breadcrumbs', $breadcrumbs),
                'items' => $html
            );

            $html = $this->mediaManager->getChunk('files/' . $viewMode . '/list', $data);
        } else {
            $html = $this->mediaManager->modx->lexicon('mediamanager.files.error.no_files_found');
        }

        return $html;
    }

    /**
     * Get sort options html.
     *
     * @return string
     */
    public function getSortOptionsHtml()
    {
        $html = '';
        foreach ($this->sortOptions as $option) {
            $html .= $this->mediaManager->getChunk('files/sort_option', $option);
        }

        return $html;
    }

    /**
     * Set sort options.
     */
    private function setSortOptions()
    {
        $this->sortOptions = array(
            array(
                'name' => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.date') . ' &uarr;',
                'field' => 'upload_date',
                'direction' => 'DESC'
            ),
            array(
                'name' => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.date') . ' &darr;',
                'field' => 'upload_date',
                'direction' => 'ASC'
            ),
            array(
                'name' => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.name') . ' &darr;',
                'field' => 'name',
                'direction' => 'ASC'
            ),
            array(
                'name' => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.name') . ' &uarr;',
                'field' => 'name',
                'direction' => 'DESC'
            )
        );
    }

    /**
     * Get filter options.
     *
     * @return array
     */
    public function getFilterOptionsHtml()
    {
        $html = array();
        foreach ($this->filterOptions as $key => $options) {
            $html[$key] = '';
            foreach ($options as $option) {
                $html[$key] .= $this->mediaManager->getChunk('files/filter_option', $option);
            }
        }

        return $html;
    }

    /**
     * Set filter options.
     */
    private function setFilterOptions()
    {
        $options = array(
            xPDO::OPT_CACHE_KEY => 'mediamanager',
        );

        $filters = $this->mediaManager->modx->cacheManager->get('filters', $options);
        if ($filters) {
            return $this->filterOptions = $filters;
        }

        $filters = array(
            'users' => array(
                array(
                    'value' => '',
                    'name' => $this->mediaManager->modx->lexicon('mediamanager.files.filter.all_users')
                )
            )
        );

        $users = $this->mediaManager->modx->getIterator('modUser');
        foreach ($users as $user) {
            $filters['users'][] = array(
                'value' => $user->get('id'),
                'name' => $user->get('username')
            );
        }

        $filters['type'] = array(
            array(
                'value' => 0,
                'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.all_types'),
                'types' => array()
            ),
            array(
                'value' => 1,
                'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.type_documents'),
                'types' => array(
                    'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'odt', 'ods', 'odp', 'odb', 'odg', 'odf', 'csv', 'pptx'
                )
            ),
            array(
                'value' => 2,
                'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.type_images'),
                'types' => array(
                    'jpg', 'png', 'gif', 'tiff', 'bmp', 'jpeg'
                )
            ),
            array(
                'value' => 3,
                'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.type_other'),
                'types' => array(
                    'zip', 'tar', 'mp4', 'wmv', 'avi'
                )
            )
        );

        $this->mediaManager->modx->cacheManager->set('filters', $filters, 3600, $options);
        return $this->filterOptions = $filters;
    }

    /**
     * Set image types.
     */
    private function setImageTypes()
    {
        $this->imageTypes = array(
            'jpg',
            'jpeg',
            'png',
            'gif',
            'bmp'
        );
    }

    /**
     * Check if file type is image.
     *
     * @param string $type
     * @return bool
     */
    public function isImage($type)
    {
        return in_array($type, $this->imageTypes);
    }

    /**
     * Add file.
     *
     * @return bool
     */
    public function addFile()
    {
        // Get file and data
        $file = $_FILES['file'];
        $data = $_REQUEST;

        // Get media source settings
        $source = $this->mediaManager->modx->getObject('modMediaSource', $this->mediaManager->modx->getOption('mediamanager.media_source'));
        $this->mediaSource = json_decode(json_encode($source->getProperties()));

        // Set upload directory, year and month
        $year = date('Y');
        $month = date('m');

        $this->uploadUrl            = $this->addSlashes($this->mediaSource->baseUrl->value) .
                                      $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
        $this->uploadDirectory      = $this->addTrailingSlash(MODX_BASE_PATH) .
                                      $this->addTrailingSlash(trim($this->mediaSource->basePath->value, DIRECTORY_SEPARATOR));
        $this->uploadDirectoryYear  = $this->uploadDirectory . $year . DIRECTORY_SEPARATOR;
        $this->uploadDirectoryMonth = $this->uploadDirectoryYear . $month . DIRECTORY_SEPARATOR;

        // Create upload directory
        if (!$this->createUploadDirectory()) {
            return [
                'status'  => 'error',
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_directory', array('file' => $file['name'])), 'danger')
            ];
        }

        // Check if file hash exists
        $file['hash'] = $this->getFileHashByPath($file['tmp_name']);

        if ($this->fileHashExists($file['hash'])) {
            return [
                'status'  => 'error',
                'message' => $this->alertMessageHtml(
                    $this->mediaManager->modx->lexicon('mediamanager.files.error.file_exists', array('file' => $file['name'])), 'danger')
            ];
        }

        // Add unique id to file name if needed
        $fileInformation = pathinfo($file['name']);
        $fileName = $this->createUniqueFile($this->sanitizeFileName($fileInformation['filename']), $fileInformation['extension']);

        $file['extension'] = $fileInformation['extension'];
        $file['unique_name'] = $fileName;

        // Upload file
        if (!$this->uploadFile($file)) {
            return [
                'status'  => 'error',
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_upload', array('file' => $file['name'])), 'danger')
            ];
        }

        // Add file to database
        if (!$this->insertFile($file, $data)) {
            // Remove file from server if saving failed
            $this->removeFile($file);

            return [
                'status'  => 'error',
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_save', array('file' => $file['name'])), 'danger')
            ];
        }

        return [
            'status'  => 'success',
            'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.success.file_upload', array('file' => $file['unique_name'])), 'success')
        ];
    }

    /**
     * Save file.
     *
     * @param array $fileData
     * @param array $data
     *
     * @return bool
     */
    private function insertFile($fileData, $data) {
        $file = $this->mediaManager->modx->newObject('MediamanagerFiles');

        $file->set('name', $fileData['unique_name']);
        $file->set('path', $this->uploadUrl . $fileData['unique_name']);
        $file->set('file_type', $fileData['extension']);
        $file->set('file_size', $fileData['size']);
        $file->set('file_hash', $fileData['hash']);
        $file->set('uploaded_by', $this->mediaManager->modx->getUser()->get('id'));
//        $file->set('mediamanager_contexts_id', $this->mediaManager->contexts->getCurrentContext());

        // If file type is image set dimensions
        if ($this->isImage($fileData['extension'])) {
            $image = getimagesize($this->uploadDirectoryMonth . $fileData['unique_name']);
            if ($image) {
                $file->set('file_dimensions', $image[0] . 'x' . $image[1]);
            }
        }

        $file->save();

        $fileId = $file->get('id');

        foreach ($data['categories'] as $categoryId) {
            $category = $this->mediaManager->modx->newObject('MediamanagerFilesCategories');
            $category->set('mediamanager_files_id', $fileId);
            $category->set('mediamanager_categories_id', $categoryId);
            $category->save();
        }

        foreach ($data['tags'] as $tagId) {
            $tag = $this->mediaManager->modx->newObject('MediamanagerFilesTags');
            $tag->set('mediamanager_files_id', $fileId);
            $tag->set('mediamanager_tags_id', $tagId);
            $tag->save();
        }

        return true;
    }

    /**
     * Move files.
     *
     * @param array $files
     * @param int $category
     *
     * @return array
     */
    public function moveFiles($files, $category)
    {
//        foreach ($files as $fileId) {
//            $file = $this->mediaManager->modx->getObject('MediamanagerFilesCategories', array(
//                $fileId
//            ));
//            $file->set();
//        }

        return [

        ];
    }

    /**
     * Archive files.
     *
     * @param $files
     * @return array
     */
    public function archiveFiles($files)
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerFilesContent');
        $q->select('MediamanagerFilesContent.mediamanager_files_id AS id');
        $q->groupby('MediamanagerFilesContent.mediamanager_files_id');
        $q->prepare();

        $query = $this->mediaManager->modx->query($q->toSQL());
        $results = $query->fetchAll(PDO::FETCH_OBJ);

var_dump($results);
die();
        foreach ($files as $fileId) {
            $file = $this->mediaManager->modx->getObject('MediamanagerFiles', $fileId);
            $file->set('is_archived', 1);
            $file->set('archive_date', time());
            $file->set('archive_path', '');
            $file->save();
        }

        return [];
    }

    /**
     * Create unique file name.
     *
     * @param string $name
     * @param string $extension
     * @param string $uniqueId
     *
     * @return string
     */
    private function createUniqueFile($name, $extension, $uniqueId = '') {
        $file = $this->uploadDirectoryMonth . $name . $uniqueId . '.' . $extension;
        if (file_exists($file)) {
            $uniqueId = uniqid('-');
            return $this->createUniqueFile($name, $extension, $uniqueId);
        }

        return $name . $uniqueId . '.' . $extension;
    }

    /**
     * Create upload directory if not exists.
     *
     * @return bool
     */
    private function createUploadDirectory()
    {
        if (!file_exists($this->uploadDirectory)) {
            if (!$this->createDirectory($this->uploadDirectory)) return false;
        }

        if (!file_exists($this->uploadDirectoryYear)) {
            if (!$this->createDirectory($this->uploadDirectoryYear)) return false;
        }

        if (!file_exists($this->uploadDirectoryMonth)) {
            if (!$this->createDirectory($this->uploadDirectoryMonth)) return false;
        }

        return true;
    }

    /**
     * Create directory.
     *
     * @param string $directoryPath
     * @param int $mode
     *
     * @return bool
     */
    private function createDirectory($directoryPath, $mode = 0755)
    {
        return mkdir($directoryPath, $mode);
    }

    /**
     * Upload file.
     *
     * @param array $file
     * @return bool
     */
    private function uploadFile($file)
    {
        $target = $this->uploadDirectoryMonth . $file['unique_name'];
        $uploadFile = move_uploaded_file($file['tmp_name'], $target);
        if ($uploadFile) {
            return true;
        }

        return false;
    }

    /**
     * Remove file.
     *
     * @param string $file
     * @return bool
     */
    private function removeFile($file)
    {
        $target = $this->uploadDirectoryMonth . $file['unique_name'];

        return unlink($target);
    }

    /**
     * Sanitize file name.
     * Only allow a-z, 0-9, _ and -
     *
     * @param string $fileName
     * @return string
     */
    public function sanitizeFileName($fileName)
    {
        $find = array(
            ' '
        );

        $replace = array(
            '-'
        );

        $fileName = strtolower($fileName);
        $fileName = str_replace($find, $replace, $fileName);
        $fileName = preg_replace('/[^a-z0-9_-]+/', '', $fileName);

        return $fileName;
    }

    /**
     * Get file hash.
     *
     * @param string $filePath
     * @return string
     */
    public function getFileHashByPath($filePath)
    {
        return md5_file($filePath);
    }

    /**
     * Check if file hash exists.
     *
     * @param string $fileHash
     * @return bool
     */
    public function fileHashExists($fileHash)
    {
        $fileObject = $this->mediaManager->modx->getObject('MediamanagerFiles',
            array(
                'file_hash' => $fileHash
            )
        );

        if ($fileObject === null) {
            return false;
        }

        return true;
    }

    /**
     * Add slashes to string.
     *
     * @param string $string
     * @return string
     */
    public function addSlashes($string)
    {
        return DIRECTORY_SEPARATOR . trim($string, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Add trailing slash to string.
     *
     * @param string $string
     * @return string
     */
    public function addTrailingSlash($string)
    {
        return rtrim($string, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function alertMessageHtml($message, $type)
    {
        return '<div class="alert alert-' . $type . '" role="alert">' . $message . '</div>';
    }
}