<?php
/**
 * Files processor
 */
class MediaManagerFilesProcessor extends modProcessor
{

    private $mediaManager = null;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    public function process()
    {
        $this->mediaManager = $this->modx->getService('mediamanager', 'MediaManager', $this->modx->getOption('mediamanager.core_path', null, $this->modx->getOption('core_path') . 'components/mediamanager/') . 'model/mediamanager/');

        $method = $this->getProperty('method');
        $data   = array();

        switch ($method) {
            case 'add':
                $data = $this->add();

                break;
            case 'move':
                $data = $this->move();

                break;
            case 'archive':
                $data = $this->archive();

                break;
            case 'unarchive':
                $data = $this->unarchive();

                break;
            case 'archiveReplace':
                $data = $this->archiveReplace();

                break;
            case 'share':
                $data = $this->share();

                break;
            case 'download':
                $data = $this->download();

                break;
            case 'delete':
                $data = $this->delete();

                break;
            case 'crop':
                $data = $this->crop();

                break;
            case 'copyToSource':
                $data = $this->copyToSource();

                break;
            case 'save':
                $data = $this->save();

                break;
            case 'list':
                $data = $this->getList();

                break;
            case 'file':
                $data = $this->getFile();

                break;
            case 'addCategory':
                $data = $this->addCategory();

                break;
            case 'removeCategory':
                $data = $this->removeCategory();

                break;
            case 'addTag':
                $data = $this->addTag();

                break;
            case 'removeTag':
                $data = $this->removeTag();

                break;
            case 'revert':
                $data = $this->revertFile();
                break;
        }

        return $data;
    }

    private function add()
    {
        $response = $this->mediaManager->files->addFile();
        if ($response['status'] === 'error') {
            header('HTTP/1.1 400 Bad Request');
        }

        return $this->toJSON($response);
    }

    private function move()
    {
        return $this->outputArray(
            $this->mediaManager->files->moveFiles(
                (array) $this->getProperty('files'),
                (int)   $this->getProperty('category')
            )
        );
    }

    private function archive()
    {
        return $this->outputArray(
            $this->mediaManager->files->archiveFiles(
                $this->getProperty('files')
            )
        );
    }

    private function unarchive()
    {
        return $this->outputArray(
            $this->mediaManager->files->unArchiveFiles(
                $this->getProperty('files')
            )
        );
    }

    private function archiveReplace()
    {
        return $this->outputArray(
            $this->mediaManager->files->archiveReplaceFile(
                (int) $this->getProperty('fileId'),
                (int) $this->getProperty('newFileId')
            )
        );
    }

    private function share()
    {
        return $this->outputArray(
            $this->mediaManager->files->shareFiles(
                $this->getProperty('files')
            )
        );
    }

    private function download()
    {
        return $this->outputArray(
            $this->mediaManager->files->downloadFiles(
                (array) $this->getProperty('files')
            )
        );
    }

    private function delete()
    {
        return $this->outputArray(
            $this->mediaManager->files->deleteFiles(
                $this->getProperty('files')
            )
        );
    }

    private function crop()
    {
        return $this->outputArray(
            $this->mediaManager->files->cropFile(
                (int)    $this->getProperty('fileId'),
                (string) $this->getProperty('cropData'),
                (bool)   $this->getProperty('isNewImage')
            )
        );
    }

    private function copyToSource()
    {
        return $this->outputArray(
            $this->mediaManager->files->copyToSource(
                (int) $this->getProperty('fileId'),
                (int) $this->getProperty('sourceId')
            )
        );
    }

    private function save()
    {
        return $this->outputArray(
            $this->mediaManager->files->saveFile(
                (int)   $this->getProperty('fileId'),
                (array) json_decode($this->getProperty('data'), true)
            )
        );
    }

    private function getList()
    {
        $response = $this->mediaManager->files->getListHtml(
            (int)    $this->getProperty('category'),
            (string) $this->getProperty('search'),
            (array)  $this->getProperty('filters'),
            (array)  $this->getProperty('sorting'),
            (string) $this->getProperty('viewMode'),
            (array)  $this->getProperty('selectedFiles'),
            (int)    $this->getProperty('limit'),
            (int)    $this->getProperty('offset')
        );

        if ($response['type'] === 'html') {
            return $response['html'];
        }

        return $this->outputArray((array) $response['html']);
    }

    private function getFile()
    {
        return $this->outputArray(
            (array) $this->mediaManager->files->getFileHtml(
                (int)    $this->getProperty('id'),
                (string) $this->getProperty('template'),
                (string) $this->getProperty('successMessage')
            )
        );
    }

    private function addCategory()
    {
        return $this->outputArray(
            $this->mediaManager->files->addCategory(
                (int) $this->getProperty('fileId'),
                (int) $this->getProperty('categoryId')
            )
        );
    }

    private function removeCategory()
    {
        return $this->outputArray(
            $this->mediaManager->files->removeCategory(
                (int) $this->getProperty('fileId'),
                (int) $this->getProperty('categoryId')
            )
        );
    }

    private function addTag()
    {
        return $this->outputArray(
            $this->mediaManager->files->addTag(
                (int) $this->getProperty('fileId'),
                (int) $this->getProperty('tagId'),
                      $this->getProperty('name')
            )
        );
    }

    private function removeTag()
    {
        return $this->outputArray(
            $this->mediaManager->files->removeTag(
                (int) $this->getProperty('fileId'),
                (int) $this->getProperty('tagId')
            )
        );
    }

    private function revertFile()
    {
        $response = $this->mediaManager->files->revertFile(
            (int) $this->getProperty('version')
        );
        if ($response['status'] === 'error') {
            header('HTTP/1.1 400 Bad Request');
        }

        return $this->toJSON($response);
    }
}

return 'MediaManagerFilesProcessor';
