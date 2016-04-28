<?php

class MediaManagerPermissionsHelper
{
    CONST MODX_ADMIN_GROUP = 'Administrator';
    CONST ADMIN_GROUP = 'Media Manager Admin';
    const USER_GROUP = 'Media Manager User';

    private $mediaManager = null;

    private $user = null;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
        $this->user = $this->mediaManager->modx->user;
    }

    /**
     * Check if user is in admin group.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user->isMember(array(
            self::ADMIN_GROUP,
            self::MODX_ADMIN_GROUP
        ));
    }

    /**
     * Check if user is in user group.
     *
     * @return bool
     */
    public function isUser()
    {
        return $this->user->isMember(self::USER_GROUP);
    }

    /**
     * Check if user is media manager user.
     *
     * @param modUser $user
     * @return bool
     */
    public function isMediaManagerUser($user)
    {
        return $user->isMember(array(
            self::USER_GROUP,
            self::ADMIN_GROUP
        ));
    }

    /**
     * Check if user has upload permission.
     *
     * @return bool
     */
    public function upload()
    {
        if ($this->isAdmin()) return true;

        if (
            $this->mediaManager->modx->hasPermission('file_upload')
            && $this->mediaManager->sources->getCurrentSource() === $this->mediaManager->sources->getUserSource()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has edit permission.
     *
     * @return bool
     */
    public function edit()
    {
        if ($this->isAdmin()) return true;

        if (
            $this->mediaManager->modx->hasPermission('file_update')
            && $this->mediaManager->sources->getCurrentSource() === $this->mediaManager->sources->getUserSource()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has delete permission.
     *
     * @return bool
     */
    public function delete()
    {
        if ($this->isAdmin()) return true;

        if (
            $this->mediaManager->modx->hasPermission('file_remove')
            && $this->mediaManager->sources->getCurrentSource() === $this->mediaManager->sources->getUserSource()
        ) {
            return true;
        }

        return false;
    }
}