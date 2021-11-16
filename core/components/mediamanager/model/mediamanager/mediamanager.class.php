<?php

require_once __DIR__ . '/classes/categories.class.php';
require_once __DIR__ . '/classes/sources.class.php';
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
    public function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $this->namespace = $this->modx->getOption('namespace', $config, 'mediamanager');

        $basePath   = $this->modx->getOption('mediamanager.core_path', $config, $this->modx->getOption('core_path') . 'components/mediamanager/');
        $assetsUrl  = $this->modx->getOption('mediamanager.assets_url', $config, $this->modx->getOption('assets_url') . 'components/mediamanager/');
        $assetsPath = $this->modx->getOption('mediamanager.assets_path', $config, $this->modx->getOption('assets_path') . 'components/mediamanager/');

        $this->config = array_merge(array(
            'base_path'             => $basePath,
            'core_path'             => $basePath,
            'model_path'            => $basePath . 'model/',
            'processors_path'       => $basePath . 'processors/',
            'elements_path'         => $basePath . 'elements/',
            'templates_path'        => $basePath . 'templates/',
            'assets_path'           => $assetsPath,
            'js_url'                => $assetsUrl . 'js/',
            'css_url'               => $assetsUrl . 'css/',
            'assets_url'            => $assetsUrl,
            'connector_url'         => $assetsUrl . 'connector.php',
            'chunks_path'           => $basePath . 'templates/chunks/',
            'chunk_suffix'          => '.chunk.tpl',
            'max_file_size'         => $this->modx->getOption('mediamanager.max_file_size'),
            'max_file_size_images'  => $this->modx->getOption('mediamanager.max_file_size_images')
        ), $config);

        $this->modx->addPackage('mediamanager', $this->config['model_path']);
        $this->modx->lexicon->load('mediamanager:default');

        $this->permissions = new MediaManagerPermissionsHelper($this);
        $this->categories  = new MediaManagerCategoriesHelper($this);
        $this->sources     = new MediaManagerSourcesHelper($this);
        $this->files       = new MediaManagerFilesHelper($this);
        $this->tags        = new MediaManagerTagsHelper($this);
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
    public function getChunk($name,array $properties = array())
    {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk', array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name, $this->config['chunk_suffix']);
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
    private function _getTplChunk($name, $suffix = '.chunk.tpl')
    {
        $chunk = false;
        $f = $this->config['chunks_path'] . strtolower($name) . $suffix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name', $name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    /**
     * Render supporting javascript for the custom TVs
     */
    public function includeScriptAssets()
    {
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
            Ext.onReady(function() {
                MediaManager.config =  ' . $this->modx->toJSON($this->config) . ';
            });
            </script>
        ');

        $this->modx->regClientCSS($this->config['assets_url'] . 'css/mgr/mediamanager-tv-input.css');
        $this->modx->regClientStartupScript($this->config['assets_url'] . 'js/mgr/mediamanager-tv-input.js');
        $this->modx->regClientStartupScript($this->config['assets_url'] . 'js/inputs/mediamanager_cmp.js');
        $this->modx->regClientStartupScript($this->config['assets_url'] . 'js/mgr/migx/renderer.js');
    }

    public function getCategoryChildIds(array $categories, $parent = 0)
    {
        $children = [];

        foreach ($categories as $item) {
            if ($item->get('parent_id') === $parent) {
                $children[] = $item->get('id');

                $children = array_merge($this->getCategoryChildIds($categories, $item->get('id')), $children);
            }
        }

        return $children;
    }

    /**
     * Get all categories, tags and context tags.
     *
     * @return array
     */
    public function getAllCategoriesAndTags()
    {
        return [
            'categories'  => $this->categories->getAllCategories(),
            'tags'        => $this->tags->getAllTags(),
            'contextTags' => $this->tags->getAllTags(true)
        ];
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->config)) {
                $option = $this->config[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    /**
     * @access public
     * @param int $fileId
     * @param int $resourceId
     * @param int $tvId
     * @return boolean.
     */
    public function saveFileContent($fileId, $resourceId, $tvId = 0)
    {
        if ((int) $tvId !== 0) {
            $object = $this->modx->getObject('MediamanagerFilesContent', [
                'mediamanager_files_id' => $fileId,
                'site_content_id'       => $resourceId,
                'site_tmplvars_id'      => $tvId,
                'is_tmplvar'            => 1
            ]);
        }

        if (!$object) {
            $object = $this->modx->newObject('MediamanagerFilesContent', [
                'mediamanager_files_id' => $fileId,
                'site_content_id'       => $resourceId
            ]);
        }

        if ($object) {
            if ((int) $tvId === 0) {
                $object->fromArray([
                    'is_tmplvar'        => 0
                ]);
            } else {
                $object->fromArray([
                    'site_tmplvars_id'  => $tvId,
                    'is_tmplvar'        => 1
                ]);
            }

            return $object->save();
        }

        return false;
    }

    /**
     * @access public
     * @param array $cbValues
     * @param array $files
     * @return array.
     */
    public function cbCheckMMField(array $cbValues = [], array &$files = [])
    {
        foreach ($cbValues as $cbValue) {
            if (isset($cbValue['field'])) {
                $cbField = $this->modx->getObject('cbField', [
                    'id' => $cbValue['field']
                ]);

                if ($cbField) {
                    if ($cbField->get('input') === 'cb_mediamanager_image_input') {
                        if (isset($cbValue['file_id'])) {
                            $files[] = $cbValue['file_id'];
                        }
                    } else if ($cbField->get('input') === 'cb_mediamanager_input') {
                        if (isset($cbValue['file_id'])) {
                            $files[] = $cbValue['file_id'];
                        }
                    } else if ($cbField->get('input') === 'repeater') {
                        if (isset($cbValue['rows'])) {
                            foreach ($cbValue['rows'] as $row) {
                                $this->cbCheckMMField($row, $files);
                            }
                        }
                    }
                }
            } else if (isset($cbValue['file_id'])) {
                $files[] = $cbValue['file_id'];
            }
        }

        return $files;
    }
}