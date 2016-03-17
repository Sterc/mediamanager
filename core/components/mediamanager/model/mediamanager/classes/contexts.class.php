<?php

class MediaManagerContexts
{
    private $mediaManager = null;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }
}