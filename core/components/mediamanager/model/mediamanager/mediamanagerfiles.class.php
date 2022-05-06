<?php
class MediamanagerFiles extends xPDOSimpleObject
{
    /**
     * Remove file object.
     *
     * @param array $ancestors
     * @return void
     */
    public function remove(array $ancestors = array ())
    {
        $license = $this->getLicense();
        $result  = parent::remove($ancestors);

        /* Remove related license if it has no connections with other files. */
        if ($result && $license && count($license->getMany('LicenseFiles')) === 0) {
            $mediamanager = $this->xpdo->getService('mediamanager', 'MediaManager', $this->xpdo->getOption('mediamanager.core_path', null, $this->xpdo->getOption('core_path') . 'components/mediamanager/') . 'model/mediamanager/');
            $mediamanager->files->removeLicenseFile($license);

            $license->remove();
        }

        return $result;
    }

    /**
     * Retrieve license attached to this file.
     *
     * @return void
     */
    public function getLicense()
    {
        if (($licenseRelation = $this->getOne('FileLicense')) && $license = $licenseRelation->getOne('License')) {
            return $license;
        }

        return null;
    }
}
