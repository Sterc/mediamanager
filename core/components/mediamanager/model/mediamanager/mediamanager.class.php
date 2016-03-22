<?php

require_once __DIR__ . '/classes/categories.class.php';
require_once __DIR__ . '/classes/contexts.class.php';
require_once __DIR__ . '/classes/files.class.php';
require_once __DIR__ . '/classes/permissions.class.php';
require_once __DIR__ . '/classes/tags.class.php';

/**
 * Media Manager Main Class
 *
 * @package mediamanager
 */
class MediaManager
{
    /**
     * The modX object.
     */
    public $modx = null;

    /**
     * The namespace for this package.
     */
    public $namespace = 'mediamanager';

    /**
     * Holds all configs values.
     */
    public $config = array();

    public $chunks = array();

    public $categories = null;
    public $contexts = null;
    public $files = null;
    public $permissions = null;
    public $tags = null;

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     * @param    modX    $modx    The modX object.
     * @param    array   $config  Array with config values.
     */
    public function __construct(modX &$modx, array $config = array()) {
        $this->modx =& $modx;
        $this->namespace = $this->modx->getOption('namespace', $config, 'mediamanager');

        $basePath   = $this->modx->getOption('mediamanager.core_path', $config, $this->modx->getOption('core_path') . 'components/mediamanager/');
        $assetsUrl  = $this->modx->getOption('mediamanager.assets_url', $config, $this->modx->getOption('assets_url') . 'components/mediamanager/');
        $assetsPath = $this->modx->getOption('mediamanager.assets_path', $config, $this->modx->getOption('assets_path') . 'components/mediamanager/');

        $this->config = array_merge(array(
            'base_path'       => $basePath,
            'core_path'       => $basePath,
            'model_path'      => $basePath . 'model/',
            'processors_path' => $basePath . 'processors/',
            'elements_path'   => $basePath . 'elements/',
            'templates_path'  => $basePath . 'templates/',
            'assets_path'     => $assetsPath,
            'js_url'          => $assetsUrl . 'js/',
            'css_url'         => $assetsUrl . 'css/',
            'assets_url'      => $assetsUrl,
            'connector_url'   => $assetsUrl . 'connector.php',
        ), $config);

        $this->modx->addPackage('mediamanager', $this->config['model_path']);
        $this->modx->lexicon->load('mediamanager:default');

        $this->categories   = new MediaManagerCategoriesHelper($this);
        $this->contexts     = new MediaManagerContextsHelper($this);
        $this->files        = new MediaManagerFilesHelper($modx, $this);
        $this->permissions  = new MediaManagerPermissionsHelper($this);
        $this->tags         = new MediaManagerTagsHelper($this);
    }
}
