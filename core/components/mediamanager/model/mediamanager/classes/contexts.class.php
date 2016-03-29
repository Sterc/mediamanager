<?php

class MediaManagerContextsHelper
{
    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

    /**
     * @var int
     */
    private $defaultContext = 1;

    /**
     * @var int
     */
    private $currentContext = 1;

    /**
     * MediaManagerContextsHelper constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;

        $this->setDefaultContext();
        $this->setCurrentContext();
    }

    /**
     * Set default context.
     *
     * @return int
     */
    private function setDefaultContext()
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerContexts');
        $q->where(array(
            'is_all' => 1,
            'is_deleted' => 0
        ));

        $context = $this->mediaManager->modx->getObject('MediamanagerContexts', $q);

        if ($context) {
            $this->defaultContext = $context->get('id');
        }

        return $this->defaultContext;
    }

    /**
     * Set current context.
     *
     * @return int
     */
    private function setCurrentContext()
    {
        $contextId = (int) $_REQUEST['context'];

        if ($contextId) {
            $q = $this->mediaManager->modx->newQuery('MediamanagerContexts');
            $q->where(array(
                'id' => $contextId,
                'is_deleted' => 0
            ));

            if ($this->mediaManager->modx->getObject('MediamanagerContexts', $q)) {
                return $this->currentContext = $contextId;
            }
        }

        return $this->currentContext = $this->defaultContext;
    }

    /**
     * Get default context.
     *
     * @return int
     */
    public function getDefaultContext()
    {
        return $this->defaultContext;
    }

    /**
     * Get current context
     *
     * @return int
     */
    public function getCurrentContext()
    {
        return $this->currentContext;
    }

    /**
     * Get contexts.
     *
     * @return array
     */
    public function getList()
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerContexts');
        $q->where(array(
            'is_deleted' => 0
        ));

        return $this->mediaManager->modx->getIterator('MediamanagerContexts', $q);
    }

    /**
     * Get contexts html.
     *
     * @return array
     */
    public function getListHtml()
    {
        $contexts = $this->getList();
        $html = '';

        foreach ($contexts as $context) {
            $context = $context->toArray();
            $context['selected'] = 0;

            if ($this->currentContext === $context['id']) {
                $context['selected'] = 1;
            }

            $html .= $this->mediaManager->getChunk('contexts/context', $context);
        }

        if (empty($html)) {
            $html = $this->mediaManager->modx->lexicon('mediamanager.contexts.error.no_contexts_found');
        }

        return [
            'error' => false,
            'html'  => $html
        ];
    }
}
