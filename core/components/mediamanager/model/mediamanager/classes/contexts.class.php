<?php

class MediaManagerContextsHelper
{
    private $mediaManager = null;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }
}