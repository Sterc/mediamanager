<?php

class MediaManagerCategoriesHelper
{
    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

    /**
     * MediaManagerCategoriesHelper constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * Get categories.
     *
     * @return array
     */
    public function getList()
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $q->where(array(
            'is_deleted' => 0
        ));
        $q->sortby('parent_id', 'ASC');
        $q->sortby('rank', 'DESC');

        return $this->mediaManager->modx->getIterator('MediamanagerCategories', $q);
    }

    /**
     * Get categories by name.
     *
     * @param string $search
     * @return array
     */
    public function getCategoriesByName($search)
    {
        $categories = $this->mediaManager->modx->getIterator('MediamanagerCategories', [
            'is_deleted' => 0,
            'name:LIKE' => '%' . $search . '%'
        ]);

        $result = array();
        foreach ($categories as $category) {
            $result[] = array(
                'id' => $category->get('id'),
                'text' => $category->get('name')
            );
        }

        return $result;
    }

}