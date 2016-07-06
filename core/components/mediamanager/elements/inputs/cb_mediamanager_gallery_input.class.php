<?php
/**
 * Class CodeInput
 *
 * Displays a fancy Ace-powered syntax highlighter.
 *
 * @author Mark Hamstra
 * @package contentblocks
 */
class cbMediaManagerGalleryInput extends cbBaseInput {
    public $defaultIcon = 'gallery';
    public $defaultTpl = '<li><a href="[[+url]]" title="[[+title]]"><img src="[[+url]]" width="[[+width]]" height="[[+height]]" alt="[[+title]]"></a></li>';
    public $defaultWrapperTpl = '<ul class="gallery">[[+images]]</ul>';

    public function getName()
    {
        return 'Media Manager - Gallery';
    }

    public function getDescription()
    {
        return 'Input for the new Media Manager gallery ContentBlock.';
    }

    /**
     * @return array
     */
    public function getJavaScripts() {
        $assetsUrl = $this->modx->getOption('mediamanager.assets_url', null, MODX_ASSETS_URL . 'components/mediamanager/');

        return array(
            $assetsUrl . 'js/inputs/cb_mediamanager_gallery_input.js',
        );

    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        $tpls = array();

        // Grab the template from a .tpl file
        $corePath = $this->modx->getOption('mediamanager.core_path', null, MODX_CORE_PATH . 'components/mediamanager/');
        $template = file_get_contents($corePath . 'elements/templates/cb_mediamanager_gallery_input.tpl');
        $template_item = file_get_contents($corePath . 'elements/templates/cb_mediamanager_gallery_item.tpl');

        $tpls[] = $this->contentBlocks->wrapInputTpl('cb_mediamanager_gallery_input', $template);
        $tpls[] = $this->contentBlocks->wrapTpl('contentblocks-field-gallery-item', $template_item);

        // Add the connector url to the manager page
        $assetsUrl = $this->modx->getOption('mediamanager.assets_url', null, MODX_ASSETS_URL . 'components/mediamanager/');

        if ($this->modx->controller) {
//            $this->modx->controller->addHtml('<script type="text/javascript" src="' . $assetsUrl . 'libs/jquery/1.12.1/js/jquery.min.js"></script>');
//            $this->modx->controller->addHtml('<script type="text/javascript" src="' . $assetsUrl . 'libs/jquery-ui/1.11.4/js/jquery-ui.min.js"></script>');
//            $this->modx->controller->addHtml('<script type="text/javascript" src="' . $assetsUrl . 'js/mgr/mediamanager-modal.js"></script>');
        }

        return $tpls;
    }

    /**
     * @return array
     */
    public function getFieldProperties()
    {
        return array(
            array(
                'key' => 'wrapper_template',
                'fieldLabel' => $this->modx->lexicon('contentblocks.wrapper_template'),
                'xtype' => 'code',
                'default' => $this->defaultWrapperTpl,
                'description' => $this->modx->lexicon('contentblocks.gallery_wrapper_template.description')
            ),
            array(
                'key' => 'max_images',
                'fieldLabel' => $this->modx->lexicon('contentblocks.max_images'),
                'xtype' => 'numberfield',
                'default' => 12,
                'description' => $this->modx->lexicon('contentblocks.gallery_max_images.description')
            ),
            array(
                'key' => 'thumb_size',
                'fieldLabel' => $this->modx->lexicon('contentblocks.gallery.thumb_size'),
                'xtype' => 'contentblocks-combo-gallery-thumbsize',
                'default' => 'medium',
                'description' => $this->modx->lexicon('contentblocks.gallery.thumb_size.description'),
            ),
            array(
                'key' => 'thumbnail_size',
                'fieldLabel' => $this->modx->lexicon('contentblocks.image.thumbnail_size'),
                'xtype' => 'textfield',
                'default' => '0',
                'description' => $this->modx->lexicon('contentblocks.image.thumbnail_size.description'),
            ),
            array(
                'key' => 'source',
                'fieldLabel' => $this->modx->lexicon('contentblocks.image.source'),
                'xtype' => 'contentblocks-combo-mediasource',
                'default' => 0,
                'description' => $this->modx->lexicon('contentblocks.image.source.description')
            ),
            array(
                'key' => 'show_description',
                'fieldLabel' => $this->modx->lexicon('contentblocks.gallery.show_description'),
                'xtype' => 'contentblocks-combo-boolean',
                'default' => 0,
                'description' => $this->modx->lexicon('contentblocks.gallery.show_description.description')
            ),
            array(
                'key' => 'show_link_field',
                'fieldLabel' => $this->modx->lexicon('contentblocks.gallery.show_link_field'),
                'xtype' => 'contentblocks-combo-boolean',
                'default' => 0,
                'description' => $this->modx->lexicon('contentblocks.gallery.show_link_field.description')
            ),
            array(
                'key' => 'limit_to_current_context',
                'fieldLabel' => $this->modx->lexicon('contentblocks.link.limit_to_current_context'),
                'xtype' => 'contentblocks-combo-boolean',
                'default' => '1',
                'description' => $this->modx->lexicon('contentblocks.link.limit_to_current_context.description')
            ),
            array(
                'key' => 'directory',
                'fieldLabel' => $this->modx->lexicon('contentblocks.directory'),
                'xtype' => 'textfield',
                'default' => $this->contentBlocks->getOption('contentblocks.image.upload_path', null, 'assets/uploads/images/'),
                'description' => $this->modx->lexicon('contentblocks.directory.description')
            ),
            array(
                'key' => 'file_types',
                'fieldLabel' => $this->modx->lexicon('contentblocks.file_types'),
                'xtype' => 'textfield',
                'default' => 'png,gif,jpg,jpeg',
                'description' => $this->modx->lexicon('contentblocks.file_types.description')
            ),
        );
    }

    /**
     * Process this field based on a row and a wrapper tpl
     *
     * @param cbField $field
     * @param array $data
     * @return mixed
     */
    public function process(cbField $field, array $data = array())
    {
        $settings = $data;
        unset($settings['images']);

        $rowTpl = $field->get('template');
        $wrapperTpl = $field->get('wrapper_template');

        $output = array();
        $idx = 1;
        foreach ($data['images'] as $img) {
            $img = array_merge($settings, $img);
            $img['idx'] = $idx;
            if(isset($img['link']) && !empty($img['link'])) {
                $img['link_raw'] = $img['link'];

                if($img['linkType'] === 'email') {
                    $img['link'] = 'mailto:' . $img['link'];
                }
                if($img['linkType'] === 'resource') {
                    $img['link'] = '[[~' . $img['link'] . ']]';
                }
            }

            // grab image sizes
            if (!isset($img['width']) || $img['width'] < 1) {
                $size = false;
                if (file_exists($img['url'])) {
                    $size = getimagesize($img['url']);
                }

                if (!$size) {
                    $normalisedPath = str_replace(MODX_BASE_URL.MODX_BASE_URL, MODX_BASE_URL, MODX_BASE_PATH . $img['url']);
                    if (file_exists($normalisedPath)) {
                        $size = getimagesize($normalisedPath);
                    }
                }
                if (!empty($size)) {
                    $img['width'] = $size[0];
                    $img['height'] = $size[1];
                }
            }
            $output[] = $this->contentBlocks->parse($rowTpl, $img);
            $idx++;
        }
        $output = implode('', $output);
        $settings['images'] = $output;
        $settings['total'] = count($data['images']);
        return $this->contentBlocks->parse($wrapperTpl, $settings);
    }

}
