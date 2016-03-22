<?php

class MediaManagerFilesHelper
{
    /**
     * The modX object.
     */
    public $modx = null;

    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

    /**
     * The mediaSource object.
     */
    private $mediaSource = null;

    private $uploadUrl = null;
    private $uploadDirectory = null;
    private $uploadDirectoryYear = null;
    private $uploadDirectoryMonth = null;

    /**
     * MediaManagerFilesHelper constructor.
     *
     * @param modX $modx
     * @param MediaManager $mediaManager
     */
    public function __construct(modX &$modx, MediaManager $mediaManager)
    {
        $this->modx =& $modx;
        $this->mediaManager = $mediaManager;
    }

    /**
     * Process files.
     *
     * @return bool
     */
    public function processFiles()
    {
        $result = array(
            'status' => 'success'
        );

//        var_dump($_REQUEST);
//        var_dump($_FILES);

        // Get media source settings
        $source = $this->modx->getObject('modMediaSource', $this->modx->getOption('mediamanager.media_source'));
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
            $result['status'] = 'error';
            $result['message'] = 'Could not create upload directory.';
            return $result;
        }

        // Process files
        $files = $_FILES;
        foreach ($files as $file) {
            // Check if file hash exists
            $file['hash'] = $this->getFileHashByPath($file['tmp_name']);
            if ($this->fileHashExists($file['hash'])) {
                $result['status'] = 'error';
                $result['files'][$file['name']] = 'File already exists.';
                continue;
            }

            // Add unique id to file name if needed
            $fileInformation = pathinfo($file['name']);
            $fileName = $this->createUniqueFile($this->sanitizeFileName($fileInformation['filename']), $fileInformation['extension']);

            $file['extension'] = $fileInformation['extension'];
            $file['unique_name'] = $fileName;

            // Upload file
            if (!$this->uploadFile($file)) {
                $result['status'] = 'error';
                $result['files'][$file['name']] = 'File could not be uploaded.';
                continue;
            }

            // Add file to database
            $this->addFile($file);
        }

        return $result;
    }

    public function addFile($file) {
        $newFile = $this->modx->newObject('MediamanagerFiles');

        $newFile->set('name', $file['unique_name']);
        $newFile->set('path', $this->uploadUrl . $file['unique_name']);
        $newFile->set('file_type', $file['extension']);
        $newFile->set('file_size', $file['size']);
        $newFile->set('file_hash', $file['hash']);
        $newFile->set('uploaded_by', $this->modx->getUser()->get('id'));

        // If image set dimensions
        if (false) {
            $dimensions = '';
            $newFile->set('file_dimensions', $dimensions);
        }

        $newFile->save();

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
    public function createUniqueFile($name, $extension, $uniqueId = '') {
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
    public function createUploadDirectory()
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
    public function createDirectory($directoryPath, $mode = 0755)
    {
        return mkdir($directoryPath, $mode);
    }

    /**
     * Upload file.
     *
     * @param array $file
     * @return bool
     */
    public function uploadFile($file)
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
        $fileObject = $this->modx->getObject('MediamanagerFiles',
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