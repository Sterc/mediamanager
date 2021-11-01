<?php

class MediaManagerSourcesHelper
{
    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

    /**
     * @var int
     */
    private $defaultSource = 1;

    /**
     * @var int
     */
    private $currentSource = 1;

    /**
     * @var int
     */
    private $userSource = 1;

    /**
     * MediaManagerSourcesHelper constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;

        $this->setDefaultSource();
        $this->setCurrentSource();
        $this->setUserSource();
        $this->hasPermission();
    }

    /**
     * Set default media source.
     *
     * @return int
     */
    private function setDefaultSource()
    {
        $source = $this->mediaManager->modx->getObject('modMediaSource', [
            'id' => $this->mediaManager->modx->getOption('mediamanager.default_media_source')
        ]);

        if ($source) {
            $this->defaultSource = (int) $source->get('id');
        }

        return $this->defaultSource;
    }

    /**
     * Set current media source.
     *
     * @return int
     */
    private function setCurrentSource()
    {
        $sourceId = 0;

        if (isset($_REQUEST['source'])) {
            $sourceId = (int) $_REQUEST['source'];
        }

        if (!$sourceId && isset($_SESSION['mediamanager']['source'])) {
            return $this->currentSource = $_SESSION['mediamanager']['source'];
        }

        if ($sourceId) {
            $source = $this->mediaManager->modx->getObject('modMediaSource', [
                'id' => $sourceId
            ]);

            if ($source) {
                $_SESSION['mediamanager']['source'] = $sourceId;
                return $this->currentSource = $sourceId;
            }

            $this->mediaManager->modx->sendError('fatal');
        }

        return $this->currentSource = $this->defaultSource;
    }

    /**
     * Set user media source.
     *
     * @return int
     */
    private function setUserSource()
    {
        return $this->userSource = (int) $this->mediaManager->modx->user->getOption('media_sources_id');
    }

    /**
     * Check if user is allowed to view current source.
     */
    private function hasPermission()
    {
        if (
            !$this->mediaManager->permissions->isAdmin()
            && $this->userSource !== $this->currentSource
            && $this->defaultSource !== $this->currentSource
        ) {
            $this->mediaManager->modx->sendError('fatal');
        }
    }

    /**
     * Get default media source.
     *
     * @return int
     */
    public function getDefaultSource()
    {
        return $this->defaultSource;
    }

    /**
     * Get current media source.
     *
     * @return int
     */
    public function getCurrentSource()
    {
        return $this->currentSource;
    }

    /**
     * Get user media source.
     *
     * @return int
     */
    public function getUserSource()
    {
        return $this->userSource;
    }

    /**
     * Get media sources.
     *
     * @return array
     */
    public function getList()
    {
        $mediaSources = $this->mediaManager->modx->getIterator('modMediaSource');

        $sources = [];
        foreach ($mediaSources as $source) {
            $properties = $source->get('properties');
            if (isset($properties['mediamanagerSource']['value'])) {
                $rank = (float) (isset($properties['rank']['value']) ? $properties['rank']['value'] : 1) . '.' . $source->get('id');
                $sources[$rank] = [
                    'id'               => $source->get('id'),
                    'name'             => $source->get('name'),
                    'basePath'         => isset($properties['basePath']['value']) ? $properties['basePath']['value'] : '',
                    'basePathRelative' => isset($properties['basePathRelative']['value']) ? $properties['basePathRelative']['value'] : true,
                    'baseUrl'          => isset($properties['baseUrl']['value']) ? $properties['baseUrl']['value'] : '',
                    'baseUrlRelative'  => isset($properties['baseUrlRelative']['value']) ? $properties['baseUrlRelative']['value'] : true,
                    'allowedFileTypes' => isset($properties['allowedFileTypes']['value']) ? $properties['allowedFileTypes']['value'] : ''
                ];
            }
        }

        ksort($sources);

        return $sources;
    }

    /**
     * Get media source html.
     *
     * @return string
     */
    public function getListHtml()
    {
        $output = [];

        foreach ($this->getList() as $source) {
            if (
                !$this->mediaManager->permissions->isAdmin()
                && $source['id'] !== $this->getUserSource()
                && $source['id'] !== $this->getDefaultSource()
            ) {
                continue;
            }

            $source['selected'] = 0;

            if ($source['id'] === $this->getCurrentSource()) {
                $source['selected'] = 1;
            }

            $output[] = $this->mediaManager->getChunk('sources/source', $source);
        }

        if (empty($output)) {
            return $this->mediaManager->modx->lexicon('mediamanager.sources.error.no_sources_found');
        }

        return implode(PHP_EOL, $output);
    }

    /**
     * Get media source meta fields html.
     *
     * @param array $source
     * @return string
     */
    public function getMetaFieldsHtml(array $source = [])
    {
        $output = [];

        if (isset($source['meta']) && is_array($source['meta'])) {
            foreach ($source['meta'] as $meta) {
                $output[] = $this->mediaManager->getChunk('files/dropzone_file_meta', array_merge([
                    'value'     => '',
                    'required'  => false
                ], $meta));
            }
        }

        return implode(PHP_EOL, $output);
    }

    /**
     * Get media source by id.
     *
     * @param int $sourceId
     * @return bool|array
     */
    public function getSource($sourceId)
    {
        $source = $this->mediaManager->modx->getObject('modMediaSource', [
            'id' => $sourceId
        ]);

        if (!$source) {
            return false;
        }

        $properties = $source->get('properties');

        if (!$properties['mediamanagerSource']['value']) {
            return false;
        }

        $source = [
            'id'                => $source->get('id'),
            'name'              => $source->get('name'),
            'basePath'          => isset($properties['basePath']['value']) ? $properties['basePath']['value'] : '',
            'basePathRelative'  => isset($properties['basePathRelative']['value']) ? $properties['basePathRelative']['value'] : true,
            'baseUrl'           => isset($properties['baseUrl']['value']) ? $properties['baseUrl']['value'] : '',
            'baseUrlRelative'   => isset($properties['baseUrlRelative']['value']) ? $properties['baseUrlRelative']['value'] : true,
            'allowedFileTypes'  => isset($properties['allowedFileTypes']['value']) ? $properties['allowedFileTypes']['value'] : '',
            'meta'              => isset($properties['mediamanagerMeta']['value']) ? (array) json_decode($properties['mediamanagerMeta']['value'], true) : []
        ];

        return $source;
    }
}
