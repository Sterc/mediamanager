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

    private $sortOptions = array();
    private $filterOptions = array();

    /**
     * MediaManagerFilesHelper constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;

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
        $where[]['MediamanagerFiles.mediamanager_contexts_id'] = $this->mediaManager->contexts->getCurrentContext();
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
                        $where[]['MediamanagerFiles.file_type'] = $value;
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
     * @param string $search
     * @param array $filters
     * @param array $sorting
     * @param string $viewMode
     *
     * @return string
     */
    public function getListHtml($search = '', $filters = array(), $sorting = array(), $viewMode = 'grid')
    {
        $files = $this->getList($search, $filters, $sorting);
        $viewMode = ($viewMode === 'grid' ? 'grid' : 'list');
        $breadcrumbs = array();

        $html = '';
        foreach ($files as $file) {
            $file = $file->toArray();

            if ($viewMode === 'grid') {
                if (in_array($file['file_type'], array('jpg', 'png', 'gif', 'bmp'))) {
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
                'name' => 'Date &uarr;', // @TODO: Add lexicons
                'field' => 'upload_date',
                'direction' => 'DESC'
            ),
            array(
                'name' => 'Date &darr;',
                'field' => 'upload_date',
                'direction' => 'ASC'
            ),
            array(
                'name' => 'Name &darr;',
                'field' => 'name',
                'direction' => 'ASC'
            ),
            array(
                'name' => 'Name &uarr;',
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
                    'name' => 'All users'
                )
            ),
            'type' => array(
                array(
                    'value' => '',
                    'name' => 'All types'
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

        $filters['type'][] = array(
            'value' => 'png',
            'name' => 'PNG'
        );
        $filters['type'][] = array(
            'value' => 'txt',
            'name' => 'TXT'
        );

        $this->mediaManager->modx->cacheManager->set('filters', $filters, 3600, $options);
        return $this->filterOptions = $filters;
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
                'error'   => true,
                'message' => 'Could not create upload directory.'
            ];
        }

        // Check if file hash exists
        $file['hash'] = $this->getFileHashByPath($file['tmp_name']);

        if ($this->fileHashExists($file['hash'])) {
            return [
                'error'   => true,
                'message' => 'File already exists.'
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
                'error'   => true,
                'message' => 'File could not be uploaded.'
            ];
        }

        // Add file to database
        if (!$this->insertFile($file, $data)) {
            // @TODO: Remove file from sever
            return [
                'error'   => true,
                'message' => 'File not added to database.'
            ];
        }

        return [
            'error'   => false,
            'message' => 'File uploaded.'
        ];
    }

    private function insertFile($fileData, $data) {
        $file = $this->mediaManager->modx->newObject('MediamanagerFiles');

        $file->set('name', $fileData['unique_name']);
        $file->set('path', $this->uploadUrl . $fileData['unique_name']);
        $file->set('file_type', $fileData['extension']);
        $file->set('file_size', $fileData['size']);
        $file->set('file_hash', $fileData['hash']);
        $file->set('uploaded_by', $this->mediaManager->modx->getUser()->get('id'));
        $file->set('mediamanager_contexts_id', $this->mediaManager->contexts->getCurrentContext());

        // If file type is image set dimensions
        if (false) {
            // getimagesize()
            $dimensions = '';
            $file->set('file_dimensions', $dimensions);
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
}