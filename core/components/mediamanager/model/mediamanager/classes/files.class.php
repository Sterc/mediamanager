<?php

class MediaManagerFilesHelper
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    const ARCHIVE_DIRECTORY = 'archive';
    const DOWNLOAD_DIRECTORY = 'download';
    const DOWNLOAD_EXPIRATION = 14;

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
     * Archive paths.
     */
    private $archiveUrl = null;
    private $archiveDirectory = null;

    /**
     * Download paths.
     */
    private $downloadUrl = null;
    private $downloadDirectory = null;

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
     * Get single file.
     *
     * @param int $fileId
     * @return array
     */
    public function getFile($fileId)
    {
        // Get file
        $file = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $fileId));

        // Get file categories
        $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $q->innerJoin('MediamanagerFilesCategories', 'Files');
        $q->where(array('Files.mediamanager_files_id' => $fileId));

        $categories = $this->mediaManager->modx->getIterator('MediamanagerCategories', $q);

        // Get file tags
        $q = $this->mediaManager->modx->newQuery('MediamanagerTags');
        $q->innerJoin('MediamanagerFilesTags', 'Files');
        $q->where(array('Files.mediamanager_files_id' => $fileId));

        $tags = $this->mediaManager->modx->getIterator('MediamanagerTags', $q);

        // Get file relations
        $q = $this->mediaManager->modx->newQuery('MediamanagerFiles');
        $q->innerJoin('MediamanagerFilesRelations', 'Files');
        $q->where(array(
            'Files.mediamanager_files_id' => $fileId,
            'OR:Files.mediamanager_files_id_relation' => $fileId
        ));

        $relations = $this->mediaManager->modx->getIterator('MediamanagerFiles', $q);

        // Get file content
//        $q = $this->mediaManager->modx->newQuery('MediamanagerFilesContent');
//        $q->innerJoin('MediamanagerContent', 'Content');
//        $q->where('MediamanagerFilesTags.mediamanager_files_id', $fileId);

//        $content = $this->mediaManager->modx->getIterator('MediamanagerFilesTags', $q);

        $content = null;

        // Get user
        $user = $this->mediaManager->modx->getObject('modUser', array('id' => $file->get('uploaded_by')));
        $profile = $user->getOne('Profile');

        return [
            'file'       => $file,
            'tags'       => $tags,
            'categories' => $categories,
            'relations'  => $relations,
            'content'    => $content,
            'user'       => $profile
        ];
    }

    /**
     * Get single file html.
     *
     * @param int $fileId
     * @return string
     */
    public function getFileHtml($fileId)
    {
        $chunkData = array();
        $data = $this->getFile($fileId);

        $file = $data['file']->toArray();
        $file['file_size'] = $this->formatFileSize($file['file_size']);
        $file['uploaded_by_name'] = $data['user']->get('fullname');
        $file['full_link'] = $this->removeSlashes($this->mediaManager->modx->getOption('site_url')) . $file['path'];

        $chunkData['file'] = $file;

        if ($this->isImage($file['file_type'])) {
            $chunkData['preview'] = '<img src="/connectors/system/phpthumb.php?src=' . $file['path'] . '&w=230&h=180&zc=1" />';
        } else {
            $chunkData['preview'] = $this->mediaManager->getChunk('files/file_preview_svg', $file);
        }

        foreach ($data['categories'] as $category) {
            $chunkData['categories'] .= '<option value="' . $category->get('id') . '" selected="selected">' . $category->get('name') . '</option>';
        }

        foreach ($data['tags'] as $tag) {
            $chunkData['tags'] .= '<option value="' . $tag->get('id') . '" selected="selected">' . $tag->get('name') . '</option>';
        }

        return [
            'body'   => $this->mediaManager->getChunk('files/popup/preview', $chunkData),
            'footer' => $this->mediaManager->getChunk('files/popup/buttons/preview', array(
                'download_link' => $file['full_link']
                )
            )
        ];
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
    public function getList($search = '', $filters = array(), $sorting = array(), $isArchive = 0)
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerFiles');

        $sortColumn = 'MediamanagerFiles.upload_date';
        $sortDirection = 'DESC';

        $select = $this->mediaManager->modx->getSelectColumns('MediamanagerFiles', 'MediamanagerFiles');

        $where = array();
//        $where[]['MediamanagerFiles.mediamanager_contexts_id'] = $this->mediaManager->contexts->getCurrentContext();
        $where[]['MediamanagerFiles.is_archived'] = $isArchive;

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
     * @param array $selectedFiles
     *
     * @return string
     */
    public function getListHtml($context = 0, $category = 0, $search = '', $filters = array(), $sorting = array(), $viewMode = 'grid', $selectedFiles = array())
    {
        $viewMode = ($viewMode === 'grid' ? 'grid' : 'list');

        if ($category > 0 && ! isset($filters['categories'])) {
            $filters['categories'][] = $category;
        }

        $isArchive = 0;
        if ($category === -1) {
            $isArchive = 1;
        }

        $files = $this->getList($search, $filters, $sorting, $isArchive);

        $selectedFilesIds = array();
        foreach ($selectedFiles as $selectedFile) {
            $selectedFilesIds[] = $selectedFile['id'];
        }

        $breadcrumbs = array();
        $html = '';

        foreach ($files as $file) {
            $file = $file->toArray();
            $file['selected'] = 0;
            $file['file_size'] = $this->formatFileSize($file['file_size']);

            if (in_array($file['id'], $selectedFilesIds)) {
                $file['selected'] = 1;
            }

            if ($viewMode === 'grid') {
                if ($this->isImage($file['file_type'])) {
                    $file['preview_path'] = '/connectors/system/phpthumb.php?src=' . $file['path'] . '&w=230&h=180&zc=1';
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_img', $file);
                } else {
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_svg', $file);
                }
            }

            $html .= $this->mediaManager->getChunk('files/' . $viewMode . '/file', $file);
        }

        if (empty($html)) {
            $html = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.no_files_found'), 'info');
        }

        $data = array(
            'breadcrumbs' => $this->mediaManager->getChunk('files/breadcrumbs', $breadcrumbs),
            'items'       => $html
        );

        return $this->mediaManager->getChunk('files/' . $viewMode . '/list', $data);
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
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.date') . ' &uarr;',
                'field'     => 'upload_date',
                'direction' => 'DESC'
            ),
            array(
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.date') . ' &darr;',
                'field'     => 'upload_date',
                'direction' => 'ASC'
            ),
            array(
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.name') . ' &darr;',
                'field'     => 'name',
                'direction' => 'ASC'
            ),
            array(
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.name') . ' &uarr;',
                'field'     => 'name',
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
                    'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.all_users')
                )
            )
        );

        $users = $this->mediaManager->modx->getIterator('modUser');
        foreach ($users as $user) {
            $filters['users'][] = array(
                'value' => $user->get('id'),
                'name'  => $user->get('username')
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

        // Create upload directory
        if (!$this->createUploadDirectory()) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_directory'), 'danger')
            ];
        }

        // Check if file hash exists
        $file['hash'] = $this->getFileHashByPath($file['tmp_name']);

        if ($this->fileHashExists($file['hash'])) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml(
                    $this->mediaManager->modx->lexicon('mediamanager.files.error.file_exists', array('file' => $file['name'])), 'danger')
            ];
        }

        // Add unique id to file name if needed
        $fileInformation = pathinfo($file['name']);
        $fileName = $this->createUniqueFile($this->uploadDirectoryMonth, $this->sanitizeFileName($fileInformation['filename']), $fileInformation['extension']);

        $file['extension']   = $fileInformation['extension'];
        $file['unique_name'] = $fileName;

        // Upload file
        if (!$this->uploadFile($file)) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_upload', array('file' => $file['name'])), 'danger')
            ];
        }

        // Add file to database
        if (!$this->insertFile($file, $data)) {
            // Remove file from server if saving failed
            $this->removeFile($file);

            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_save', array('file' => $file['name'])), 'danger')
            ];
        }

        return [
            'status'  => self::STATUS_SUCCESS,
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
    private function insertFile($fileData, $data)
    {
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

        $categories = explode(',', $data['categories']);
        foreach ($categories as $categoryId) {
            $category = $this->mediaManager->modx->newObject('MediamanagerFilesCategories');
            $category->set('mediamanager_files_id', $fileId);
            $category->set('mediamanager_categories_id', $categoryId);
            $category->save();
        }

        $tags = explode(',', $data['tags']);
        foreach ($tags as $tagId) {
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
     * @param array $selectedFiles
     * @param int $category
     *
     * @return array
     */
    public function moveFiles($selectedFiles, $category)
    {
        foreach ($selectedFiles as $file) {
            $fileCategory = $this->mediaManager->modx->getObject('MediamanagerFilesCategories', array(
                'mediamanager_files_id' => $file['id'],
                'mediamanager_categories_id' => $file['category']
            ));

            if (!$fileCategory) {
                $fileCategory = $this->mediaManager->modx->newObject('MediamanagerFilesCategories');
                $fileCategory->set('mediamanager_files_id', $file['id']);
            }

            $fileCategory->set('mediamanager_categories_id', $category);
            $fileCategory->save();
        }

        return [
            'status'  => self::STATUS_SUCCESS,
            'message' => $this->mediaManager->modx->lexicon('mediamanager.files.success.files_moved')
        ];
    }

    /**
     * Archive files.
     *
     * @param array|int $selectedFiles
     * @return array
     */
    public function archiveFiles($selectedFiles)
    {
        $response = [
            'status'        => self::STATUS_SUCCESS,
            'message'       => '',
            'archivedFiles' => []
        ];

        $fileIds = [];

        if (is_int($selectedFiles)) {
            $fileIds[] = $selectedFiles;
        } else {
            foreach ($selectedFiles as $file) {
                $fileIds[] = $file['id'];
            }
        }

        // Check if files are linked to a resource
        $q = $this->mediaManager->modx->newQuery('MediamanagerFilesContent');
        $q->select('
            MediamanagerFilesContent.mediamanager_files_id AS id,
            Files.name AS name
        ');
        $q->innerJoin('MediamanagerFiles', 'Files');
        $q->where(array(
            'MediamanagerFilesContent.mediamanager_files_id:IN' => $fileIds
        ));
        $q->groupby('MediamanagerFilesContent.mediamanager_files_id');
        $q->prepare();

        $query = $this->mediaManager->modx->query($q->toSQL());
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        // Send error message
        if (!empty($results)) {
            $message = '';

            foreach ($results as $result) {
                $message .= $this->mediaManager->modx->lexicon('mediamanager.files.error.file_linked', array('file' => $result->name)) . '<br />';
            }

            $response['status']  = self::STATUS_ERROR;
            $response['message'] = $this->alertMessageHtml($message, 'danger');
            return $response;
        }

        // Create archive directory
        if (!$this->createUploadDirectory()) {
            $response['status']  = self::STATUS_ERROR;
            $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_directory'), 'danger');
            return $response;
        }

        // Archive files
        foreach ($fileIds as $key => $id) {
            $file = $this->mediaManager->modx->getObject('MediamanagerFiles', $id);

            $old = $this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file->get('path'));
            $new = $this->createUniqueFile($this->archiveDirectory, time(), $file->get('file_type'), uniqid('-'));

            if (!$this->renameFile($old, $this->archiveDirectory . $new)) {
                $response['status'] = self::STATUS_ERROR;
                $response['message'] .= $this->mediaManager->modx->lexicon('mediamanager.files.error.file_archive', array('id' => $id)) . '<br />';
                continue;
            }

            $file->set('is_archived', 1);
            $file->set('archive_date', time());
            $file->set('archive_path', $this->archiveUrl . $new);
            $file->save();

            $response['archivedFiles'][] = $key;
        }

        if (!empty($response['message'])) {
            $response['message'] = $this->alertMessageHtml($response['message'], 'danger');
        }

        return $response;
    }

    /**
     * Share files.
     *
     * @param array|int $selectedFiles
     * @return array
     */
    public function shareFiles($selectedFiles)
    {
        $response = [
            'status'  => self::STATUS_SUCCESS,
            'message' => ''
        ];

        $fileIds = [];

        if (is_int($selectedFiles)) {
            $fileIds[] = $selectedFiles;
        } else {
            foreach ($selectedFiles as $file) {
                $fileIds[] = $file['id'];
            }
        }

        // Create download directory
        if (!$this->createUploadDirectory()) {
            $response['status']  = self::STATUS_ERROR;
            $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_directory'), 'danger');
            return $response;
        }

        // Check if files are linked to a resource
        $files = $this->mediaManager->modx->getIterator('MediamanagerFiles', array(
            'id:IN' => $fileIds
        ));

        // Create zip
        $zip         = new ZipArchive();
        $zipName     = $this->createUniqueFile($this->downloadDirectory, sha1(time()), 'zip', uniqid('-'));
        $zipLocation = $this->downloadDirectory . $zipName;
        $zipUrl      = $this->downloadUrl . $zipName;

        $zipFile = $zip->open($zipLocation, ZipArchive::CREATE);
        if ($zipFile !== true) {
            $response['status']  = self::STATUS_ERROR;
            $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_zip'), 'danger');
            return $response;
        }

        foreach ($files as $file) {
            $zip->addFile($this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file->get('path')), $file->get('path'));
        }

        $zip->close();

        // Check if zip is created
        if (!file_exists($zipLocation)) {
            $response['status']  = self::STATUS_ERROR;
            $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_zip'), 'danger');
            return $response;
        }

        // Add download to database
        $expirationDate = time() + ((3600 * 24) * self::DOWNLOAD_EXPIRATION);

        $download = $this->mediaManager->modx->newObject('MediamanagerDownloads');
        $download->set('expires_on', $expirationDate);
        $download->set('path', $zipUrl);
        $download->set('hash', md5($zipUrl));
        $download->save();

        // Return download link
        $response['message'] = $this->mediaManager->modx->lexicon('mediamanager.files.share_download', array(
            'link' => '<pre>' . $this->removeSlashes($this->mediaManager->modx->getOption('site_url')) . $zipUrl . '</pre>',
            'expiration' => self::DOWNLOAD_EXPIRATION
        ));

        return $response;
    }

    /**
     * Create unique file name.
     *
     * @param string $directory
     * @param string $name
     * @param string $extension
     * @param string $uniqueId
     *
     * @return string
     */
    private function createUniqueFile($directory, $name, $extension, $uniqueId = '')
    {
        $file = $directory . $name . $uniqueId . '.' . $extension;
        if (file_exists($file)) {
            $uniqueId = uniqid('-');
            return $this->createUniqueFile($directory, $name, $extension, $uniqueId);
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
        // Get media source settings
        $source = $this->mediaManager->modx->getObject('modMediaSource', $this->mediaManager->modx->getOption('mediamanager.media_source'));
        $this->mediaSource = json_decode(json_encode($source->getProperties()));

        // Set upload directory, year and month
        $year = date('Y');
        $month = date('m');

        // Upload paths
        $this->uploadUrl            = $this->addSlashes($this->mediaSource->baseUrl->value) .
            $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
        $this->uploadDirectory      = $this->addTrailingSlash(MODX_BASE_PATH) .
            $this->addTrailingSlash(trim($this->mediaSource->basePath->value, DIRECTORY_SEPARATOR));
        $this->uploadDirectoryYear  = $this->uploadDirectory . $year . DIRECTORY_SEPARATOR;
        $this->uploadDirectoryMonth = $this->uploadDirectoryYear . $month . DIRECTORY_SEPARATOR;

        // Archive paths
        $this->archiveUrl           = $this->addSlashes($this->mediaSource->baseUrl->value) . self::ARCHIVE_DIRECTORY . DIRECTORY_SEPARATOR;
        $this->archiveDirectory     = $this->uploadDirectory . self::ARCHIVE_DIRECTORY . DIRECTORY_SEPARATOR;

        // Download paths
        $this->downloadUrl          = $this->addSlashes($this->mediaSource->baseUrl->value) . self::DOWNLOAD_DIRECTORY . DIRECTORY_SEPARATOR;
        $this->downloadDirectory    = $this->uploadDirectory . self::DOWNLOAD_DIRECTORY . DIRECTORY_SEPARATOR;

        if (!file_exists($this->uploadDirectory)) {
            if (!$this->createDirectory($this->uploadDirectory)) return false;
        }

        if (!file_exists($this->uploadDirectoryYear)) {
            if (!$this->createDirectory($this->uploadDirectoryYear)) return false;
        }

        if (!file_exists($this->uploadDirectoryMonth)) {
            if (!$this->createDirectory($this->uploadDirectoryMonth)) return false;
        }

        if (!file_exists($this->archiveDirectory)) {
            if (!$this->createDirectory($this->archiveDirectory)) return false;
        }

        if (!file_exists($this->downloadDirectory)) {
            if (!$this->createDirectory($this->downloadDirectory)) return false;
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
        $target     = $this->uploadDirectoryMonth . $file['unique_name'];
        $uploadFile = move_uploaded_file($file['tmp_name'], $target);
        if ($uploadFile) {
            return true;
        }

        return false;
    }

    /**
     * Rename or move file.
     *
     * @param string $old
     * @param string $new
     *
     * @return bool
     */
    private function renameFile($old, $new)
    {
        return rename($old, $new);
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
     * Get file hash by path.
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

    /**
     * Remove slashes from string.
     *
     * @param string $string
     * @return string
     */
    public function removeSlashes($string)
    {
        return trim($string, DIRECTORY_SEPARATOR);
    }

    /**
     * Alert message.
     *
     * @param string $message
     * @param string $type Use the bootstrap class success, info, warning or danger
     *
     * @return string
     */
    public function alertMessageHtml($message, $type = 'danger')
    {
        return '<div class="alert alert-' . $type . '" role="alert">' . $message . '</div>';
    }

    /**
     * Format file size.
     *
     * @param int $bytes
     * @param int $precision
     *
     * @return string
     */
    public function formatFileSize($bytes, $precision = 0) {
        $unit = ["B", "KB", "MB", "GB"];
        $exp = floor(log($bytes, 1024)) | 0;

        return round($bytes / (pow(1024, $exp)), $precision) . ' ' . $unit[$exp];
    }
}