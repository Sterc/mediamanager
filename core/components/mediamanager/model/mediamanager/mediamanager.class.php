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
            'chunks_path'     => $basePath . 'templates/chunks/',
            'chunk_suffix'    => '.chunk.tpl',
        ), $config);

        $this->modx->addPackage('mediamanager', $this->config['model_path']);
        $this->modx->lexicon->load('mediamanager:default');

        $this->permissions  = new MediaManagerPermissionsHelper($this);
        $this->categories   = new MediaManagerCategoriesHelper($this);
        $this->contexts     = new MediaManagerContextsHelper($this);
        $this->files        = new MediaManagerFilesHelper($this);
        $this->tags         = new MediaManagerTagsHelper($this);
    }

    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name,array $properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name,$this->config['chunk_suffix']);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl by default.
     * @param string $suffix The suffix to add to the chunk filename.
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name, $suffix = '.chunk.tpl') {
        $chunk = false;
        $f = $this->config['chunks_path'].strtolower($name).$suffix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    /**
     * Render supporting javascript for the custom TVs
     */
    public function includeScriptAssets() {

        // $this->modx->regClientCSS($this->config['assets_url'] . 'libs/bootstrap/3.3.6/css/bootstrap.min.css');
        $this->modx->regClientCSS($this->config['assets_url'] . 'libs/jquery-ui/1.11.4/css/jquery-ui.min.css');
        $this->modx->regClientCSS($this->config['assets_url'] . 'css/mgr/mediamanager-tv-input.css');
        $this->modx->regClientStartupScript($this->config['assets_url'] . 'libs/jquery/1.12.1/js/jquery.min.js');
        $this->modx->regClientStartupScript($this->config['assets_url'] . 'libs/jquery-ui/1.11.4/js/jquery-ui.min.js');
        // $this->modx->regClientStartupScript($this->config['assets_url'] . 'libs/bootstrap/3.3.6/js/bootstrap.min.js');
        $this->modx->regClientStartupScript($this->config['assets_url'] . 'js/mgr/mediamanager-tv-input.js');

        // $vers = $this->modx->getVersionData();
        // if ($this->getOption('debug')) {
        //     if ($vers['major_version'] >= 3) {
        //         $this->modx->regClientCSS($this->options['assetsUrl'] . 'mgr/css/imageplus.css');
        //     } else {
        //         $this->modx->regClientCSS($this->options['assetsUrl'] . 'mgr/css/imageplus-22.css');
        //     }
        //     $this->modx->regClientCSS($this->options['assetsUrl'] . 'mgr/css/jquery/jquery.jcrop.min.css');
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/imageplus.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/imageplus.panel.input.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/imageplus.window.editor.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/imageplus.migx_renderer.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/tools/JSON2.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/jquery/jquery.min.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/jquery/jquery.jcrop.min.js?v=v' . $this->version);
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/imageplus.jquery.imagecrop.js?v=v' . $this->version);
        // } else {
        //     if ($vers['major_version'] >= 3) {
        //         $this->modx->regClientCSS($this->options['assetsUrl'] . 'mgr/css/imageplus.min.css');
        //     } else {
        //         $this->modx->regClientCSS($this->options['assetsUrl'] . 'mgr/css/imageplus-22.min.css');
        //     }
        //     $this->modx->regClientStartupScript($this->options['assetsUrl'] . 'mgr/js/imageplus.min.js?v=v' . $this->version);
        // }
        // $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">'
        //     . ' ImagePlus.config = ' . json_encode($this->options) . ';'
        //     . ' var $jIP = jQuery.noConflict();'
        //     . '</script>');
    }
}
