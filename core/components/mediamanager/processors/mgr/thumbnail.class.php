<?php
/**
 * Thumbnail processor
 */
require_once MODX_CORE_PATH . 'model/phpthumb/modphpthumb.class.php';

class MediaManagerThumbnailProcessor extends modProcessor
{

    private $mediaManager = null;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    public function process()
    {
        /* Prevent path traversal and set all paths and filenames. */
        $path = preg_replace('/(\.+\/)+/', '', htmlspecialchars($this->getProperty('path')));
        $cache = preg_replace('/(\.+\/)+/', '', htmlspecialchars($this->getProperty('cache')));
        $thumbsDir = dirname($cache);
        $thumbName = $cache;

        if (empty($path) || empty($thumbsDir) || empty($thumbName)) {
            return '';
        }

        /* Build the image. */
        $phpThumb = new modPhpThumb($this->modx);
        $phpThumb->cache_filename = $thumbName;
        $phpThumb->setParameter('h', 180);
        $phpThumb->setParameter('w', 230);
        $phpThumb->setParameter('q', 80);
        $phpThumb->setParameter('far', 1);
        $phpThumb->setParameter('config_cache_directory', $thumbsDir);
        $phpThumb->setParameter('config_allow_src_above_docroot', true);
        $phpThumb->set($path);

        /* Check to see if there's a cached file of this already. */
        if ($phpThumb->checkForCachedFile()) {
            $phpThumb->loadCache();
            return '';
        }

        $phpThumb->generate();
        $phpThumb->cache();
        $phpThumb->output();

        return '';
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
}

return 'MediaManagerThumbnailProcessor';
