<?php

require_once __DIR__ . '/../../../libs/tinify/lib/Tinify/Exception.php';
require_once __DIR__ . '/../../../libs/tinify/lib/Tinify/ResultMeta.php';
require_once __DIR__ . '/../../../libs/tinify/lib/Tinify/Result.php';
require_once __DIR__ . '/../../../libs/tinify/lib/Tinify/Source.php';
require_once __DIR__ . '/../../../libs/tinify/lib/Tinify/Client.php';
require_once __DIR__ . '/../../../libs/tinify/lib/Tinify.php';

class MediaManagerFilesHelper
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    const UPLOAD_DIRECTORY = 'uploads';
    const ARCHIVE_DIRECTORY = 'archive';
    const DOWNLOAD_DIRECTORY = 'download';
    const VERSION_DIRECTORY = 'versions';
    const DOWNLOAD_EXPIRATION = 14;
    const MAX_FILE_SIZE = 50;
    const MAX_FILE_SIZE_IMAGES = 5;

    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

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
     * Version paths.
     */
    private $versionUrl = null;
    private $versionDirectory = null;

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
     * Tinify options.
     * @var bool
     */
    private $tinifyEnabled = false;
    private $tinifyApiKey = false;
    private $tinifyLimit = 0;

    /**
     * MediaManagerFilesHelper constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager  = $mediaManager;
        $this->tinifyEnabled = $this->mediaManager->modx->getOption('mediamanager.tinify.enabled', null, false);
        $this->tinifyApiKey  = $this->mediaManager->modx->getOption('mediamanager.tinify.api_key', null, false);
        $this->tinifyLimit   = (int) $this->mediaManager->modx->getOption('mediamanager.tinify.limit', null, 500);

        $this->setImageTypes();
        $this->setSortOptions();
        $this->setFilterOptions();

        $this->cleanupArchive();
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
        $q->groupby('Files.mediamanager_categories_id');

        $categories = $this->mediaManager->modx->getIterator('MediamanagerCategories', $q);

        // Get file tags
        $q = $this->mediaManager->modx->newQuery('MediamanagerTags');
        $q->innerJoin('MediamanagerFilesTags', 'Files');
        $q->where(array('Files.mediamanager_files_id' => $fileId));
        $q->groupby('Files.mediamanager_tags_id');

        $tags = $this->mediaManager->modx->getIterator('MediamanagerTags', $q);

        // Get file relations
        $q = $this->mediaManager->modx->newQuery('MediamanagerFiles');
        $q->innerJoin('MediamanagerFilesRelations', 'Relations');
        $q->where(array('Relations.mediamanager_files_id_relation' => $fileId));

        $relations = $this->mediaManager->modx->getIterator('MediamanagerFiles', $q);

        $q = $this->mediaManager->modx->newQuery('MediamanagerFiles');
        $q->innerJoin('MediamanagerFilesRelations', 'Relations2');
        $q->where(array('Relations2.mediamanager_files_id' => $fileId));

        $relations2 = $this->mediaManager->modx->getIterator('MediamanagerFiles', $q);

        // Get file content
//        $q = $this->mediaManager->modx->newQuery('MediamanagerFilesContent');
//        $q->select('
//            modResource.id,
//            modResource.pagetitle
//        ');
//        $q->where('MediamanagerFilesContent.mediamanager_files_id', $fileId);
//        $q->innerJoin('modResource', 'modResource');
//
//        $content = $this->mediaManager->modx->getIterator('modResource', $q);

        $content = array();

        // Get user
        $user = $this->mediaManager->modx->getObject('modUser', array('id' => $file->get('uploaded_by')));
        $profile = $user->getOne('Profile');

        return [
            'file'       => $file,
            'tags'       => $tags,
            'categories' => $categories,
            'relations'  => $relations,
            'relations2' => $relations2,
            'content'    => $content,
            'user'       => $profile
        ];
    }

    /**
     * Get single file html.
     *
     * @param int $fileId
     * @param string $template
     *
     * @return string
     */
    public function getFileHtml($fileId, $template)
    {
        $bodyData   = array();
        $footerData = array();

        $footerData['button'] = array(
            'edit'     => 1,
            'crop'     => 1,
            'share'    => 1,
            'download' => 1,
            'archive'  => 1,
            'archive_replace' => 1,
            'delete'   => 1,
            'history'  => 1,
            'copy'     => 0
        );

        $data                     = $this->getFile($fileId);
        $file                     = $data['file']->toArray();

        $file['file_size']        = $this->formatFileSize($file['file_size']);
        $file['uploaded_by_name'] = $data['user']->get('fullname');
        $file['full_link']        = $this->removeSlashes($this->mediaManager->modx->getOption('site_url')) . $file['path'];
        $file['is_archived']      = (int) $file['is_archived'];

        $bodyData['file']         = $file;
        $footerData['file']       = $file;

        // Set file type
        if ($this->isImage($file['file_type'])) {
            $bodyData['preview'] = '<img src="/connectors/system/phpthumb.php?src=' . $file['path'] . '&w=230&h=180&md5s=' . $file['file_hash'] . '" />';
            $bodyData['is_image'] = 1;
        } elseif($file['file_type'] === 'pdf' && extension_loaded('Imagick')) {
            $bodyData['preview'] = '<img src="' . str_replace('.pdf', '_thumb.jpg', $file['path']) . '" />';
            $bodyData['is_image'] = 0;
            $footerData['button']['crop'] = 0;
        } else {
            $bodyData['preview'] = $this->mediaManager->getChunk('files/file_preview_svg', $file);
            $bodyData['is_image'] = 0;
            $footerData['button']['crop'] = 0;
        }

        // File categories
        foreach ($data['categories'] as $category) {
            $bodyData['categories'] .= '<option value="' . $category->get('id') . '" selected="selected">' . $category->get('name') . '</option>';
        }

        // File tags
        foreach ($data['tags'] as $tag) {
            if ($tag->get('media_sources_id') === 0) {
                $tagSource = 'tags';
            } else {
                $tagSource = 'source_tags';
            }
            $bodyData[$tagSource] .= '<option value="' . $tag->get('id') . '" selected="selected">' . $tag->get('name') . '</option>';
        }

        // File content
        $bodyData['content'] = [];
        foreach ($data['content'] as $content) {
            $bodyData['content'][] = '<a href="?a=resource/update&id=' . $content->get('id') . '">' . $content->get('pagetitle') . '</a>';
        }
        $bodyData['content'] = implode(', ', $bodyData['content']);

        // File relations
        $bodyData['relations'] = [];
        foreach ($data['relations'] as $relation) {
            $bodyData['relations'][] = '<a href="#" data-file-id="' . $relation->get('id') . '">' . $relation->get('file_dimensions') . '</a>';
        }
        foreach ($data['relations2'] as $relation) {
            $bodyData['relations'][] = '<a href="#" data-file-id="' . $relation->get('id') . '">' . $relation->get('file_dimensions') . '</a>';
        }
        $bodyData['relations'] = implode(', ', $bodyData['relations']);

        // File and user permissions
        $bodyData['can_edit'] = 1;
        if ($file['is_archived'] === 1) {
            $footerData['button']['edit']     = 0;
            $footerData['button']['crop']     = 0;
            $footerData['button']['share']    = 0;
            $footerData['button']['download'] = 0;
            $footerData['button']['archive']  = 0;
            $footerData['button']['archive_replace'] = 0;

            $bodyData['can_edit']             = 0;
        } else {
            $footerData['button']['delete']   = 0;
        }

        if (!$this->mediaManager->permissions->edit()) {
            $footerData['button']['edit']     = 0;
            $footerData['button']['crop']     = 0;
            $footerData['button']['share']    = 0;
            $footerData['button']['download'] = 0;
            $footerData['button']['archive']  = 0;
            $footerData['button']['archive_replace'] = 0;
            $footerData['button']['delete']   = 0;
            $footerData['button']['copy']     = 1;

            $bodyData['can_edit']             = 0;
        }

        if (!$this->mediaManager->permissions->delete()) {
            $footerData['button']['delete']   = 0;
        }

        //File history
        $v = $this->mediaManager->modx->newQuery('MediamanagerFilesVersions');
        $v->where(
            array(
                'mediamanager_files_id' => $fileId,
            )
        );
        $v->sortBy('version', 'desc');

        $versions = $this->mediaManager->modx->getIterator('MediamanagerFilesVersions', $v);

        $bodyData['history'] = '';
        foreach ($versions as $version) {
            $versionArr = $version->toArray();
            if(
                isset($versionArr['created_by']) &&
                $versionArr['created_by'] != 0
            ) {
                $user                       = $this->mediaManager->modx->getObject('modUser', array('id' => $versionArr['created_by']));
                $profile                    = $user->getOne('Profile');
                $versionArr['created_by']   = $profile->get('fullname');
            }

            $fileInformation                = pathinfo($versionArr['path']);
            $versionArr['type']             = strtolower($fileInformation['extension']);
            $versionArr['file_size']        = $this->formatFileSize($versionArr['file_size']);
            $versionArr['active_version']   = $file['version'];

            $versionArr['replaceHtml'] = '';
            if($versionArr['action'] == 'replace') {
                if($versionArr['replaced_file_id'] != 0){
                    $oldFile = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $versionArr['replaced_file_id']));

                    if($oldFile){
                        $versionArr['replaceHtml'] = '<a href="' . $oldFile->get('path') . '" target="_blank">' . $oldFile->get('name') . '</a> was replaced by <a href="' . $versionArr['path'] . '">' . $versionArr['file_name'] . '</a>.';
                    }
                }
            }

            $bodyData['history'] .= $this->mediaManager->getChunk('files/history', $versionArr);
        }

        return [
            'body'   => $this->mediaManager->getChunk('files/popup/' . $template, $bodyData),
            'footer' => $this->mediaManager->getChunk('files/popup/buttons/' . $template, $footerData)
        ];
    }

    /**
     * Get files.
     *
     * @param string $search
     * @param array $filters
     * @param array $sorting
     * @param int $isArchive
     *
     * @return array
     */
    public function getList($search = '', $filters = array(), $sorting = array(), $isArchive = 0)
    {
        $sourceId     = $this->mediaManager->sources->getCurrentSource();
        $sortColumn    = 'MediamanagerFiles.upload_date';
        $sortDirection = 'DESC';
        $where         = array();

        $q      = $this->mediaManager->modx->newQuery('MediamanagerFiles');
        $select = $this->mediaManager->modx->getSelectColumns('MediamanagerFiles', 'MediamanagerFiles');

        $where[]['MediamanagerFiles.is_archived'] = $isArchive;

        if ($sourceId !== $this->mediaManager->sources->getDefaultSource()) {
            $where[]['MediamanagerFiles.media_sources_id'] = $sourceId;
        }

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

                    case 'categories' :
                        if (count($value) === 1) {
                            $categories = $this->mediaManager->categories->getCategories();
                            $categories = $this->mediaManager->getCategoryChildIds($categories, $value[0]);
                            $categories[] = $value[0];
                            $value = $categories;
                        }

                        $q->innerJoin('MediamanagerFilesCategories', 'Categories');
                        $where[]['Categories.mediamanager_categories_id:IN'] = $value;
                        break;

                    case 'tags' :
                        $q->innerJoin('MediamanagerFilesTags', 'Tags');
                        $where[]['Tags.mediamanager_tags_id:IN'] = $value;
                        break;
                    case 'date' :
                        if (!empty($filters['date']['from'])) {
                            if (is_numeric($filters['date']['from'])) {
                                $filters['date']['from'] = substr($filters['date']['from'], 0, 10);
                                $where[]['MediamanagerFiles.upload_date:>='] = date('Y-m-d 00:00:00', $filters['date']['from']);
                            } else {
                                $where[]['MediamanagerFiles.upload_date:>='] = date('Y-m-d 00:00:00', strtotime($filters['date']['from']));
                            }
                        }

                        if (!empty($filters['date']['to'])) {
                            $where[]['MediamanagerFiles.upload_date:<='] = date('Y-m-d 23:59:59', strtotime($filters['date']['to']));
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
        $q->groupby('MediamanagerFiles.id');

        return $this->mediaManager->modx->getIterator('MediamanagerFiles', $q);
    }

    /**
     * Get files html.
     *
     * @param int $category
     * @param string $search
     * @param array $filters
     * @param array $sorting
     * @param string $viewMode
     * @param array $selectedFiles
     *
     * @return string
     */
    public function getListHtml($category = 0, $search = '', $filters = array(), $sorting = array(), $viewMode = 'grid', $selectedFiles = array())
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

        $selectedFilesIds = [];
        foreach ($selectedFiles as $selectedFile) {
            $selectedFilesIds[] = $selectedFile['id'];
        }

        $breadcrumbs = [];
        $html        = '';

        foreach ($files as $file) {
            $file = $file->toArray();
            $file['categories'] = [];
            $file['selected']   = 0;
            $file['file_size']  = $this->formatFileSize($file['file_size']);

            if (in_array($file['id'], $selectedFilesIds)) {
                $file['selected'] = 1;
            }

            if ($viewMode === 'grid') {
                if ($this->isImage($file['file_type'])) {
                    $file['preview_path'] = '/connectors/system/phpthumb.php?src=' . $file['path'] . '&w=230&h=180&md5s=' . $file['file_hash'] . '';
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_img', $file);
                } elseif($file['file_type'] === 'pdf' && extension_loaded('Imagick')) {
                    $file['preview_path'] = str_replace('.pdf', '_thumb.jpg', $file['path']);
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_img', $file);
                } else {
                    $file['preview'] = $this->mediaManager->getChunk('files/file_preview_svg', $file);
                }
            } else {
                $user = $this->mediaManager->modx->getObject('modUser', array('id' => $file['uploaded_by']));
                $profile = $user->getOne('Profile');
                $file['uploaded_by'] = $profile->get('fullname');

                $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
                $q->innerJoin('MediamanagerFilesCategories', 'Files');
                $q->where(array('Files.mediamanager_files_id' => $file['id']));

                $categories = $this->mediaManager->modx->getIterator('MediamanagerCategories', $q);

                foreach ($categories as $category) {
                    $file['categories'][] = $category->get('name');
                }
                $file['categories'] = implode(', ', $file['categories']);
            }

            $html .= $this->mediaManager->getChunk('files/' . $viewMode . '/file', $file);
        }

        if (empty($html)) {
            $html = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.no_files_found'), 'info');
        }

        $data = [
            'breadcrumbs' => $this->mediaManager->getChunk('files/breadcrumbs', $breadcrumbs),
            'items'       => $html
        ];

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
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.date'),
                'field'     => 'upload_date',
                'direction' => 'DESC'
            ),
            array(
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.date.asc'),
                'field'     => 'upload_date',
                'direction' => 'ASC'
            ),
            array(
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.name.asc'),
                'field'     => 'name',
                'direction' => 'ASC'
            ),
            array(
                'name'      => $this->mediaManager->modx->lexicon('mediamanager.files.sorting.name'),
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
            ),
            'dates' => array(
                array(
                    'value' => 'all',
                    'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.all_dates')
                ),
                array(
                    'value' => 'recent',
                    'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.date_recent')
                ),
                array(
                    'value' => 'custom',
                    'name'  => $this->mediaManager->modx->lexicon('mediamanager.files.filter.date_custom')
                )
            )
        );

        $users = $this->mediaManager->modx->getIterator('modUser');
        foreach ($users as $user) {
            if (!$this->mediaManager->permissions->isMediaManagerUser($user)) {
                continue;
            }
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
        $this->imageTypes = array('jpg', 'png', 'gif', 'tiff', 'bmp', 'jpeg');
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
     * @return array
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

        $hashExists = $this->fileHashExists($file['hash']);
        if ($hashExists) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml(
                    $this->mediaManager->modx->lexicon('mediamanager.files.error.file_exists', array(
                        'file' => $file['name'],
                        'link' => '<a href="#" data-file-id="' . $hashExists . '" data-preview-link>Link</a>'
                    )), 'danger')
            ];
        }

        // Add unique id to file name if needed
        $fileInformation = pathinfo($file['name']);
        $fileName = $this->createUniqueFile($this->uploadDirectoryMonth, $this->sanitizeFileName($fileInformation['filename']), $fileInformation['extension']);

        $file['extension']   = strtolower($fileInformation['extension']);
        $file['unique_name'] = $fileName;

        // Upload file
        if (!$this->uploadFile($file)) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_upload', array('file' => $file['name'])), 'danger')
            ];
        }

        // Tinify image
        if ($file['extension'] === 'jpg' || $file['extension'] === 'png') {
            if($this->tinify($this->uploadDirectoryMonth . $file['unique_name'])) {
                $file['size'] = filesize($this->uploadDirectoryMonth . $file['unique_name']);
            }
        }

        if ($file['extension'] === 'pdf' && extension_loaded('Imagick')) {
            $pdfHandle = fopen($this->uploadDirectoryMonth . $file['unique_name'], 'rb');
            $previewName = str_replace('.pdf', '_thumb.jpg', $file['unique_name']);

            $pdfPreview = new Imagick();
            $pdfPreview->setResolution(230, 180);
            $pdfPreview->readImageFile($pdfHandle);
            $pdfPreview->setIteratorIndex(0);
            $pdfPreview->setImageFormat('jpeg');
            $pdfPreview->writeImage($this->uploadDirectoryMonth . $previewName);
            $pdfPreview->clear();
            $pdfPreview->destroy();
        }

        $file['version']    = $this->createVersionNumber();
        $file['upload_dir'] = $this->uploadDirectoryMonth;

        // Add file to database
        $fileId         = $this->insertFile($file, $data);
        $versionCreated = $this->saveFileVersion($fileId, $file, 'create');
        if (!$fileId || !$versionCreated) {
            // Remove file from server if saving failed
            $this->removeFile($file);

            return [
                'status' => self::STATUS_ERROR,
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
        $file->set('version', $fileData['version']);
        $file->set('path', $this->uploadUrl . $fileData['unique_name']);
        $file->set('file_type', $fileData['extension']);
        $file->set('file_size', $fileData['size']);
        $file->set('file_hash', $fileData['hash']);
        $file->set('uploaded_by', $this->mediaManager->modx->getUser()->get('id'));
        $file->set('edited_by', $this->mediaManager->modx->getUser()->get('id'));

        if (isset($fileData['source'])) {
            $file->set('media_sources_id', $fileData['source']);
        } else {
            $file->set('media_sources_id', $this->mediaManager->sources->getCurrentSource());
        }

        // If file type is image set dimensions
        if ($this->isImage($fileData['extension'])) {
            $image = getimagesize($this->uploadDirectoryMonth . $fileData['unique_name']);
            if ($image) {
                $file->set('file_dimensions', $image[0] . 'x' . $image[1]);
            }
        }

        $file->save();
        $fileId = $file->get('id');

        // Save categories
        $categories = $data['categories'];
        if (!is_array($categories)) {
            $categories = explode(',', $categories);
        }

        foreach ($categories as $categoryId) {
            $category = $this->mediaManager->modx->newObject('MediamanagerFilesCategories');
            $category->set('mediamanager_files_id', $fileId);
            $category->set('mediamanager_categories_id', $categoryId);
            $category->save();
        }

        // Save tags
        $tags = $data['tags'];
        if (!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        foreach ($tags as $tagId) {
            $tag = $this->mediaManager->modx->newObject('MediamanagerFilesTags');
            $tag->set('mediamanager_files_id', $fileId);
            $tag->set('mediamanager_tags_id', $tagId);
            $tag->save();
        }

        return $fileId;
    }

    /**
     * Save file.
     *
     * @param int $fileId
     * @param array $data
     *
     * @return array
     */
    public function saveFile($fileId, $data)
    {
        $file = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $fileId));

        $version                = $this->createVersionNumber($file->get('id'));
        $data['version']        = $version;

        $file->set('name',      $this->sanitizeFileName($data['name']));
        $file->set('version',   $data['version']);
        $file->set('edited_on', time());
        $file->set('edited_by', $this->mediaManager->modx->getUser()->get('id'));
        $file->save();

        $pathInfo               = pathinfo($file->get('path'));
        $data                   = array_merge($file->toArray(), $data);
        $fileInformation        = pathinfo($data['path']);
        $filename               = $this->sanitizeFileName($fileInformation['filename']) . '.' . $fileInformation['extension'];

        $data['unique_name']    = $filename;
        $data['extension']      = $fileInformation['extension'];
        $data['upload_dir']     = MODX_BASE_PATH . ltrim($pathInfo['dirname'], '/') . DIRECTORY_SEPARATOR;

        $this->saveFileVersion($file->get('id'), $data, 'rename');

        return [];
    }

    /**
     * Save file version.
     *
     * @param int $fileId
     * @param array $file
     * @param string $action
     * @param int $replacedId
     *
     * @return bool
     */
    public function saveFileVersion($fileId, $file, $action = '', $replacedId = 0) {
        if(!isset($fileId) || empty($fileId) || $fileId === 0){
            return false;
        }

        if(!$this->versionDirectory) {
            $this->createUploadDirectory();
        }

        $version = $this->mediaManager->modx->newObject('MediamanagerFilesVersions');

        $file['file_id']        = $fileId;
        $file['version_path']   = $this->versionDirectory . $file['file_id'];

        // Add unique id to file name if needed
        $fileInformation = pathinfo($file['unique_name']);
        $versionFileName = $this->sanitizeFileName($fileInformation['filename']) . '-v' . $file['version'] . '.' . $fileInformation['extension'];

        if(!$file['extension']) {
            $file['extension'] = $fileInformation['extension'];
        }

        $file['version_name']   = $versionFileName;

        if($this->uploadVersionFile($file) === false) {
            return false;
        }

        $path = $this->versionUrl . $file['file_id'] . DIRECTORY_SEPARATOR . $versionFileName;

        $version->set('mediamanager_files_id',  $fileId);
        $version->set('version',                $file['version']);
        $version->set('path',                   $path);
        $version->set('file_name',              $file['name']);
        $version->set('file_size',              !empty($file['size']) ? $file['size'] : $file['file_size']);
        $version->set('created_by',             $this->mediaManager->modx->getUser()->get('id'));
        $version->set('action',                 $action);

        if($replacedId != 0){
            $version->set('replaced_file_id', $replacedId);
        }

        // If file type is image set dimensions
        if ($this->isImage($file['extension'])) {
            $image = getimagesize($file['upload_dir'] . $file['unique_name']);
            if ($image) {
                $version->set('file_dimensions', $image[0] . 'x' . $image[1]);
            }
        }

        $version->set('file_hash',              !empty($file['hash']) ? $file['hash'] : $file['file_hash']);

        if($version->save()){
            return true;
        }
    }

    /**
     * Revert to a specified version of a file.
     *
     * @param $versionId
     *
     * @return array $response
     */
    public function revertFile($versionId){
        $response = [
            'status'  => self::STATUS_SUCCESS,
            'message' => ''
        ];

        $version = $this->mediaManager->modx->getObject('MediamanagerFilesVersions', $versionId);
        if($version){
            $file    = $this->mediaManager->modx->getObject('MediamanagerFiles', $version->get('mediamanager_files_id'));

            if(!$file) {
                $response['status']     = self::STATUS_ERROR;
                $message                = $this->mediaManager->modx->lexicon('mediamanager.files.error.file_not_found');
                $response['message']    = $this->alertMessageHtml($message, 'danger');
            }
        }
        else {
            $response['status']     = self::STATUS_ERROR;
            $message                = $this->mediaManager->modx->lexicon('mediamanager.files.error.version_not_found', array('version' => $versionId));
            $response['message']    = $this->alertMessageHtml($message, 'danger');
        }

        if($version && $file) {
            $file->set('name',               $version->get('file_name'));
            $file->set('file_size',          $version->get('file_size'));
            $file->set('file_dimensions',    $version->get('file_dimensions'));
            $file->set('file_hash',          $version->get('file_hash'));
            $file->set('version',            $version->get('version'));
            $file->set('edited_on',          time());
            $file->set('edited_by',          $this->mediaManager->modx->getUser()->get('id'));

            //Get old file and replace current file
            $versionFile = $this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($version->get('path'));
            $currentFile = $this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file->get('path'));

            $replacedFile = copy($versionFile, $currentFile);
            if($replacedFile){
                if(!$file->save()) {
                    $response['status']     = self::STATUS_ERROR;
                    $message                = $this->mediaManager->modx->lexicon('mediamanager.files.error.revert_failed', array('file' => $file->get('name')));
                    $response['message']    = $this->alertMessageHtml($message, 'danger');
                }
            }
            else {
                $response['status']     = self::STATUS_ERROR;
                $message                = $this->mediaManager->modx->lexicon('mediamanager.files.error.revertfile_failed', array('file' => $file->get('name')));
                $response['message']    = $this->alertMessageHtml($message, 'danger');
            }
        }

        return $response;
    }

    /*
     * Retrieve versionnumber for creating a new version of a file.
     *
     * @param int $fileId
     *
     * @return int $versionNumber
     */
    private function createVersionNumber($fileId = 0) {
        $versionNumber = 1;
        if($fileId != 0){
            $sql = "SELECT MAX(version) as highestVersionNumber FROM " . $this->mediaManager->modx->getTableName('MediamanagerFilesVersions') . " WHERE mediamanager_files_id = '$fileId'";
            $query = $this->mediaManager->modx->query($sql);
            if($query){
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    if($row['highestVersionNumber']){
                        $versionNumber = ++$row['highestVersionNumber'];
                    }
                }
            }
        }

        return $versionNumber;
    }

    /**
     * Delete file.
     *
     * @param int $fileId
     * @return bool
     */
    public function deleteFile($fileId)
    {
        if (!$this->mediaManager->permissions->delete()) {
            return false;
        }

        $file = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $fileId));
        if (!$file) {
            return false;
        }

        $path = $file->get('path');
        if ($file->get('is_archived')) {
            $path = $file->get('archive_path');
        }

        // Delete file from server
        unlink($this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($path));

        // Delete file
        $this->mediaManager->modx->removeObject('MediamanagerFiles', array('id' => $fileId));

        // Delete file categories
        $this->mediaManager->modx->removeCollection('MediamanagerFilesCategories', array('mediamanager_files_id' => $fileId));

        // Delete file tags
        $this->mediaManager->modx->removeCollection('MediamanagerFilesTags', array('mediamanager_files_id' => $fileId));

        // Delete file relations
        $this->mediaManager->modx->removeCollection('MediamanagerFilesRelations', array('mediamanager_files_id' => $fileId));
        $this->mediaManager->modx->removeCollection('MediamanagerFilesRelations', array('mediamanager_files_id_relation' => $fileId));

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

        if (!is_array($selectedFiles)) {
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
     * Archive and replace file.
     *
     * @param int $fileId
     * @param int $newFileId
     *
     * @return array
     */
    public function archiveReplaceFile($fileId, $newFileId)
    {
        // New file
        $newFile = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $newFileId));

        // Get linked content
        $q = $this->mediaManager->modx->newQuery('MediamanagerFilesContent');
        $q->select('
            MediamanagerFilesContent.*,
            Files.path
        ');
        $q->innerJoin('MediamanagerFiles', 'Files');
        $q->where(array('MediamanagerFilesContent.mediamanager_files_id' => $fileId));

        $linkedContent = $this->mediaManager->modx->getCollection('MediamanagerFilesContent', $q);

        // Replace all content and template variables with new file
        foreach ($linkedContent as $fileContent) {
            if ($fileContent->get('is_tmplvar')) {
                // Replace template variable
                $templateVariable = $this->mediaManager->modx->getObject('modTemplateVarResource',
                    array(
                        'tmplvarid' => $fileContent->get('site_tmplvars_id'),
                        'contentid' => $fileContent->get('site_content_id')
                    )
                );
                $templateVariable->set('value', $newFile->get('path'));
                $templateVariable->save();
            } else {
                // Replace resource content
                $resource = $this->mediaManager->modx->getObject('modResource', array('id' => $fileContent->get('site_content_id')));
                $content  = str_replace($newFile->get('path'), $fileContent->get('path'), $resource->get('content'));
                $resource->set('content', $content);
                $resource->save();
            }
        }

        //save File version
        $data                   = $newFile->toArray();
        $version                = $this->createVersionNumber($newFileId);
        $data['version']        = $version;
        $fileInformation        = pathinfo($data['path']);
        $data['upload_dir']     = $this->addTrailingSlash(MODX_BASE_PATH) . ltrim($this->addTrailingSlash($fileInformation['dirname']), '/');
        $data['unique_name']    = $fileInformation['filename'] . '.' . $fileInformation['extension'];
        $data['size']           = $data['file_size'];

        $this->saveFileVersion($newFileId, $data, 'replace', $fileId);

        // Replace old file id with new file id
        $this->mediaManager->modx->updateCollection('MediamanagerFilesContent',
            array('mediamanager_files_id' => $newFileId),
            array('mediamanager_files_id' => $fileId)
        );

        // Archive old file
        $this->archiveFiles($fileId);

        return [];
    }

    /**
     * Unarchive files.
     *
     * @param array|int $selectedFiles
     * @return array
     */
    public function unArchiveFiles($selectedFiles)
    {
        $response = [
            'status'        => self::STATUS_SUCCESS,
            'message'       => '',
            'archivedFiles' => []
        ];

        $fileIds = [];

        if (!is_array($selectedFiles)) {
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

            $old = $this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file->get('archive_path'));
            // $new = $this->uploadDirectoryMonth . $file->get('archive_path');
            $new = $this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file->get('path'));

            // echo $new;

            if (!$this->renameFile($old, $new)) {
                $response['status'] = self::STATUS_ERROR;
                $response['message'] .= $this->mediaManager->modx->lexicon('mediamanager.files.error.file_archive', array('id' => $id)) . '<br />';
                continue;
            }

            $file->set('is_archived', 0);
            $file->set('archive_date', 0);
            $file->set('archive_path', '');
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
     * @param bool $isDownload
     *
     * @return array
     */
    public function shareFiles($selectedFiles, $isDownload = false)
    {
        $response = [
            'status'  => self::STATUS_SUCCESS,
            'message' => ''
        ];

        $fileIds = [];

        if (!is_array($selectedFiles)) {
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

        if ($isDownload) {
            // Return download link
            $response['message'] = $this->removeSlashes($this->mediaManager->modx->getOption('site_url')) . $zipUrl;
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
     * Download files.
     *
     * @param array $files
     * @return array
     */
    public function downloadFiles($files)
    {
        return $this->shareFiles($files, true);
    }

    /**
     * Crop file.
     *
     * @param int $fileId
     * @param string $cropData
     * @param bool $isNewImage
     *
     * @return array
     */
    public function cropFile($fileId, $cropData, $isNewImage = false)
    {
        $response = [
            'status'  => self::STATUS_SUCCESS,
            'message' => ''
        ];

        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $cropData));

        $file = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $fileId));

        if (!$file) {
            $response['status'] = self::STATUS_ERROR;
            $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_not_found'), 'danger');
            return $response;
        }

        if ($isNewImage) {
            // Save as new file
            $response = $this->duplicateFile($file, $imageData);
        } else {
            $filePath = $this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file->get('path'));

            // Replace current file
            $fileCreated = file_put_contents($filePath, $imageData);

            if ($fileCreated === false) {
                $response['status'] = self::STATUS_ERROR;
                $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.image_not_saved'), 'danger');
                return $response;
            }

            $hash           = $this->getFileHashByPath($filePath);
            $image          = getimagesize($filePath);
            $size           = filesize($filePath);
            $versionNumber  = $this->createVersionNumber($file->get('id'));

            $file->set('file_dimensions', $image[0] . 'x' . $image[1]);
            $file->set('file_hash', $hash);
            $file->set('file_size', $size);
            $file->set('version', $versionNumber);
            $file->save();

            $data                   = $file->toArray();
            $fileInformation        = pathinfo($data['path']);
            $data['version']        = $versionNumber;
            $data['upload_dir']     = $this->addTrailingSlash(MODX_BASE_PATH) . ltrim($this->addTrailingSlash($fileInformation['dirname']), '/');
            $data['unique_name']    = $fileInformation['filename'] . '.' . $fileInformation['extension'];

            // Save file version.
            $this->saveFileVersion($file->get('id'), $data, 'crop');

            $response['message'] = $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.success.image_saved'), 'success');
        }

        return $response;
    }

    /**
     * Duplicate file.
     *
     * @param object $file
     * @param string $imageData
     *
     * @return array
     */
    public function duplicateFile($file, $imageData)
    {
        $file = $file->toArray();
        $data = array();

        // Create upload directory
        if (!$this->createUploadDirectory()) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_directory'), 'danger')
            ];
        }

        // Add unique id to file name if needed
        $fileName = explode('.', $file['name']);
        $fileName = $this->createUniqueFile($this->uploadDirectoryMonth, $fileName[0], $file['file_type']);

        // Create new file
        $fileCreated = file_put_contents($this->uploadDirectoryMonth . $fileName, $imageData);
        if ($fileCreated === false) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_upload', array('file' => $file['name'])), 'danger')
            ];
        }

        $file['size']        = filesize($this->uploadDirectoryMonth . $fileName);
        $file['hash']        = $this->getFileHashByPath($this->uploadDirectoryMonth . $fileName);
        $file['extension']   = $file['file_type'];
        $file['unique_name'] = $fileName;

        // Get file categories
        $categories = $this->mediaManager->modx->getIterator('MediamanagerFilesCategories', array('mediamanager_files_id' => $file['id']));

        foreach ($categories as $category) {
            $data['categories'][] = $category->get('mediamanager_categories_id');
        }

        // Get file tags
        $tags = $this->mediaManager->modx->getIterator('MediamanagerFilesTags', array('mediamanager_files_id' => $file['id']));

        foreach ($tags as $tag) {
            $data['tags'][] = $tag->get('mediamanager_tags_id');
        }

        $file['version']        = $this->createVersionNumber();
        $file['upload_dir']     = $this->uploadDirectoryMonth;
        // Add file to database
        $fileId = $this->insertFile($file, $data);
        $versionCreated = $this->saveFileVersion($fileId, $file, 'create');
        if (!$fileId || !$versionCreated) {
            // Remove file from server if saving failed
            $this->removeFile($file);

            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_save', array('file' => $fileName)), 'danger')
            ];
        }

        $this->addFileRelation($file['id'], $fileId);

        return [
            'status'  => self::STATUS_SUCCESS,
            'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.success.file_upload', array('file' => $fileName)), 'success')
        ];
    }

    /**
     * Copy file to source.
     *
     * @param int $fileId
     * @param int $sourceId
     *
     * @return array
     */
    public function copyToSource($fileId, $sourceId = 0)
    {
        $file = $this->mediaManager->modx->getObject('MediamanagerFiles', array('id' => $fileId));
        $file = $file->toArray();
        $data = array();

        if ($sourceId === 0) {
            $sourceId = $this->mediaManager->sources->getUserSource();
        }

        // Create upload directory
        if (!$this->createUploadDirectory()) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.create_directory'), 'danger')
            ];
        }

        // Add unique id to file name if needed
        $fileName = explode('.', $file['name']);
        $fileName = $this->createUniqueFile($this->uploadDirectoryMonth, $fileName[0], $file['file_type']);

        // Copy file
        $fileCreated = $this->copyFile($this->addTrailingSlash(MODX_BASE_PATH) . $this->removeSlashes($file['path']), $this->uploadDirectoryMonth . $fileName);
        if ($fileCreated === false) {
            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_copy', array('file' => $file['name'])), 'danger')
            ];
        }

        $file['source']      = $sourceId;
        $file['extension']   = $file['file_type'];
        $file['size']        = $file['file_size'];
        $file['hash']        = $file['file_hash'];
        $file['unique_name'] = $fileName;

        // Get file categories
        $categories = $this->mediaManager->modx->getIterator('MediamanagerFilesCategories', array('mediamanager_files_id' => $file['id']));

        foreach ($categories as $category) {
            $data['categories'][] = $category->get('mediamanager_categories_id');
        }

        // Get file tags
        $tags = $this->mediaManager->modx->getIterator('MediamanagerFilesTags', array('mediamanager_files_id' => $file['id']));

        foreach ($tags as $tag) {
            $data['tags'][] = $tag->get('mediamanager_tags_id');
        }

        // Add file to database
        if (!$this->insertFile($file, $data)) {
            // Remove file from server if saving failed
            $this->removeFile($file);

            return [
                'status'  => self::STATUS_ERROR,
                'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.error.file_save', array('file' => $fileName)), 'danger')
            ];
        }

        return [
            'status'  => self::STATUS_SUCCESS,
            'message' => $this->alertMessageHtml($this->mediaManager->modx->lexicon('mediamanager.files.success.file_copy', array('file' => $fileName)), 'success')
        ];
    }

    /**
     * Copy file.
     *
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    private function copyFile($source, $destination)
    {
        return copy($source, $destination);
    }

    /**
     * Create file relation.
     *
     * @param int $id
     * @param int $relationId
     */
    private function addFileRelation($id, $relationId)
    {
        $file = $this->mediaManager->modx->newObject('MediamanagerFilesRelations');
        $file->set('mediamanager_files_id', $id);
        $file->set('mediamanager_files_id_relation', $relationId);
        $file->save();
    }

    /**
     * Add category to file.
     *
     * @param int $fileId
     * @param int $categoryId
     *
     * @return array
     */
    public function addCategory($fileId, $categoryId)
    {
        $category = $this->mediaManager->modx->newObject('MediamanagerFilesCategories');
        $category->set('mediamanager_files_id', $fileId);
        $category->set('mediamanager_categories_id', $categoryId);
        $category->save();

        return [];
    }

    /**
     * Remove category from file.
     *
     * @param int $fileId
     * @param int $categoryId
     *
     * @return array
     */
    public function removeCategory($fileId, $categoryId)
    {
        $this->mediaManager->modx->removeObject('MediamanagerFilesCategories', array(
            'mediamanager_files_id' => $fileId,
            'mediamanager_categories_id' => $categoryId
        ));

        return [];
    }

    /**
     * Add tag to file.
     *
     * @param int $fileId
     * @param int $tagId
     * @param mixed $name
     *
     * @return array
     */
    public function addTag($fileId, $tagId, $name = false)
    {
        if ($tagId === 0 && $name !== false) {
            $tag = $this->mediaManager->modx->getObject('MediamanagerTags', array('name:=' => $name));
            if (!$tag) {
                $newTag = $this->mediaManager->modx->newObject('MediamanagerTags');
                $newTag->set('media_sources_id', $this->mediaManager->sources->getUserSource());
                $newTag->set('name', $name);
                $newTag->save();

                $tagId = $newTag->get('id');
            }
        }

        if ($tagId === 0) {
            return [
                'status' => self::STATUS_ERROR
            ];
        }

        $tag = $this->mediaManager->modx->newObject('MediamanagerFilesTags');
        $tag->set('mediamanager_files_id', $fileId);
        $tag->set('mediamanager_tags_id', $tagId);
        $tag->save();

        return [
            'status' => self::STATUS_SUCCESS,
            'html'   => '<option value="' . $tagId . '">' . $newTag->get('name') . '</option>',
            'tagId'  => $tagId
        ];
    }

    /**
     * Remove tag from file.
     *
     * @param int $fileId
     * @param int $tagId
     *
     * @return array
     */
    public function removeTag($fileId, $tagId)
    {
        $this->mediaManager->modx->removeCollection('MediamanagerFilesTags', array(
            'mediamanager_files_id' => $fileId,
            'mediamanager_tags_id' => $tagId
        ));

        return [];
    }

    /**
     * Cleanup archive.
     * Automatically remove archived files.
     */
    private function cleanupArchive()
    {
        $time        = time();
        $cleanupTime = (int) $this->mediaManager->modx->getOption('mediamanager.cleanup_time');
        $cleanupMax  = (int) $this->mediaManager->modx->getOption('mediamanager.cleanup_max_age');

        $this->mediaManager->modx->setOption('mediamanager.cleanup_time', $time);

        // Stop if cleanup was less than 24 hours ago
        if ($time < ($cleanupTime + 86400)) {
            return false;
        }

        // Get files that need to be removed
        $files = $this->mediaManager->modx->getIterator('MediamanagerFiles', array(
            'is_archived'    => 1,
            'archive_date:<' => date('Y-m-d H:i:s', $time - ($cleanupMax * 86400))
        ));

        $fileIds = array();
        foreach ($files as $file) {
            $fileIds[] = $file->get('id');
        }

        if (empty($fileIds)) {
            return false;
        }

        // Remove files
        $this->mediaManager->modx->removeCollection('MediamanagerFiles', array(
            'is_archived'    => 1,
            'archive_date:<' => date('Y-m-d H:i:s', $time - ($cleanupMax * 86400))
        ));

        // Remove file categories
        $this->mediaManager->modx->removeCollection('MediamanagerFilesCategories', array(
            'mediamanager_files_id:IN' => $fileIds
        ));

        // Remove file tags
        $this->mediaManager->modx->removeCollection('MediamanagerFilesTags', array(
            'mediamanager_files_id:IN' => $fileIds
        ));

        // Remove file relations
        $this->mediaManager->modx->removeCollection('MediamanagerFilesRelations', array(
            'mediamanager_files_id:IN' => $fileIds,
            'OR:mediamanager_files_id_relation:IN' => $fileIds
        ));
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
        // Set upload directory, year and month
        $year = date('Y');
        $month = date('m');

        // Upload paths
        $this->uploadUrl            = $this->addSlashes(self::UPLOAD_DIRECTORY) . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
        $this->uploadDirectory      = $this->addTrailingSlash(MODX_BASE_PATH) . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR;
        $this->uploadDirectoryYear  = $this->uploadDirectory . $year . DIRECTORY_SEPARATOR;
        $this->uploadDirectoryMonth = $this->uploadDirectoryYear . $month . DIRECTORY_SEPARATOR;

        // Archive paths
        $this->archiveUrl           = $this->addSlashes(self::UPLOAD_DIRECTORY) . self::ARCHIVE_DIRECTORY . DIRECTORY_SEPARATOR;
        $this->archiveDirectory     = $this->uploadDirectory . self::ARCHIVE_DIRECTORY . DIRECTORY_SEPARATOR;

        // Download paths
        $this->downloadUrl          = $this->addSlashes(self::UPLOAD_DIRECTORY) . self::DOWNLOAD_DIRECTORY . DIRECTORY_SEPARATOR;
        $this->downloadDirectory    = $this->uploadDirectory . self::DOWNLOAD_DIRECTORY . DIRECTORY_SEPARATOR;


        // Version paths
        $this->versionUrl           = $this->addSlashes(self::UPLOAD_DIRECTORY) . self::VERSION_DIRECTORY . DIRECTORY_SEPARATOR;
        $this->versionDirectory     = $this->addTrailingSlash(MODX_BASE_PATH) . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . self::VERSION_DIRECTORY . DIRECTORY_SEPARATOR;

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

        if (!file_exists($this->versionDirectory)) {
            if (!$this->createDirectory($this->versionDirectory)) return false;
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
     *
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
     * Upload version file.
     *
     * @param array $file
     *
     * @return bool
     */
    private function uploadVersionFile($file)
    {
        $path   = $file['version_path'];
        $target = $path . '/' . $file['version_name'];

        if (!file_exists($path)) {
            $this->createDirectory($path);
        }

        $uploadedFile = $file['upload_dir'] . $file['unique_name'];
        $this->mediaManager->modx->log(xPDO::LOG_LEVEL_ERROR,'UPLOADED: ' . $uploadedFile);
//        var_dump($path);
//        var_dump($uploadedFile);
//        var_dump($target);
        if(is_file($uploadedFile)) {
            $uploadFile = copy($uploadedFile, $target);
            $this->mediaManager->modx->log(xPDO::LOG_LEVEL_ERROR, $target);
            $this->mediaManager->modx->log(xPDO::LOG_LEVEL_ERROR,'target: ' . $target);
            if ($uploadFile) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compress image.
     *
     * @param string $file
     * @return bool
     */
    private function tinify($file)
    {
        if (!$this->tinifyEnabled) return false;

        try {
            // Set and validate tinify api key
            \Tinify\setKey($this->tinifyApiKey);
            \Tinify\validate();

            // Check if tinify limit is reached
            if (\Tinify\compressionCount() === $this->tinifyLimit) return false;

            // Compress file
            $sourceData = file_get_contents($file);
            $resultData = \Tinify\fromBuffer($sourceData)->toBuffer();
            file_put_contents($file, $resultData);
        } catch(\Tinify\Exception $e) {
            return false;
        }

        return true;
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
        $fileName = preg_replace('/[^a-z0-9_.-]+/', '', $fileName);

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
     * @return mixed Return file id or false if no file found.
     */
    public function fileHashExists($fileHash)
    {
        $file = $this->mediaManager->modx->getObject('MediamanagerFiles',
            array(
                'file_hash' => $fileHash,
                'media_sources_id' => $this->mediaManager->sources->getCurrentSource()
            )
        );

        if ($file === null) {
            return false;
        }

        return $file->get('id');
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