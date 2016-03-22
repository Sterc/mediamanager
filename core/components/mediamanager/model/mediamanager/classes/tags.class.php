<?php

class MediaManagerTagsHelper
{
    private $mediaManager = null;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function createTag($name)
    {
        $name = trim($name);

        if(empty($name)) {
            return [
                'error'   => true,
                'message' => $this->mediaManager->modx->lexicon('mediamanager.tags.error.empty')
            ];
        }

        $tag = $this->mediaManager->modx->getObject('MediamanagerTags', [
            'name' => $name
        ]);

        if($tag) {
            return [
                'error'   => true,
                'message' => $this->mediaManager->modx->lexicon('mediamanager.tags.error.exists')
            ];
        }

        $tag = $this->mediaManager->modx->newObject('MediamanagerTags');
        $tag->set('name', $name);
        $tag->save();

        return [
            'error'   => false,
            'message' => $this->mediaManager->modx->lexicon('mediamanager.tags.success', ['name' => $name]),
            'html'    => $this->getList()
        ];
    }

    public function editTag($id, $name)
    {
        $tag = $this->mediaManager->modx->getObject('MediamanagerTags', $id);

        if($tag) {
            $tag->set('name', $name);
            $tag->save();
        }

        return ['error' => false, 'message' => ''];
    }

    public function deleteTag($id)
    {
        $tag = $this->mediaManager->modx->getObject('MediamanagerTags', $id);

        if($tag) {
            $tag->remove();
        }

        return [
            'error'   => false,
            'message' => $this->mediaManager->modx->lexicon('mediamanager.tags.delete_success'),
            'html'    => $this->getList()
        ];
    }

    public function getTags($contextId = 0)
    {
        $tags = $this->mediaManager->modx->getIterator('MediamanagerTags', [
            'mediamanager_contexts_id' => $contextId
        ]);

        return $tags;
    }

    public function getList()
    {
        $properties = $this->getListItems();

        return $this->mediaManager->getChunk('tags/list', $properties);
    }

    public function getListItems()
    {
        $range       = range('a', 'z');
        $tags        = $this->getTags();
        $groupedTags = [];

        foreach ($tags as $tag) {
            $name = strtolower($tag->get('name'));

            if (in_array($name[0], $range)) {
                $groupedTags[$name[0]][] = $tag->toArray();
            }
            else {
                $groupedTags['#'][] = $tag->toArray();
            }
        }
        ksort($groupedTags);

        $groupHtml = '';
        $navigationHtml = '';
        foreach ($groupedTags as $letter => $tags) {
            $tagsHtml = '';
            foreach($tags as $tag) {
                $tagsHtml .= $this->mediaManager->getChunk('tags/tag', $tag);
            }

            $groupHtml .= $this->mediaManager->getChunk('tags/group', [
                'letter' => strtoupper($letter),
                'tags'   => $tagsHtml
            ]);

            $navigationHtml .= $this->mediaManager->getChunk('tags/navigation_item', [
                'letter' => strtoupper($letter),
            ]);
        }

        if (!empty($navigationHtml)) {
            $navigationHtml = $this->mediaManager->getChunk('tags/navigation', [
                'items' => $navigationHtml,
            ]);
        }

        return [
            'items' => $groupHtml,
            'navigation' => $navigationHtml
        ];
    }
}