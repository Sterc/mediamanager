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

    public function createCategory($name, $parent = 0, $rank = 9999)
    {
        $name = trim($name);

        if (empty($name)) {
            return [
                'error'   => true,
                'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.error.empty')
            ];
        }

        $category = $this->mediaManager->modx->getObject('MediamanagerCategories', [
            'name'      => $name,
            'parent_id' => $parent
        ]);

        if ($category) {
            return [
                'error'   => true,
                'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.error.exists')
            ];
        }

        $category = $this->mediaManager->modx->newObject('MediamanagerCategories');
        $category->set('name',      $name);
        $category->set('parent_id', $parent);
        $category->set('rank',      $rank);
        $category->save();

        return [
            'error'   => false,
            'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.success', ['name' => $name]),
            'html'    => $this->getList(),
            'select'  => $this->getParentOptions()
        ];
    }

    public function sortCategories($items)
    {
        parse_str($items);

        $i = 1;
        foreach ($items as $key => $value) {
            $category = $this->mediaManager->modx->getObject('MediamanagerCategories', $key);

            if ($category) {
                $category->set('parent_id', $value);
                $category->set('rank', $i);
                $category->save();

                ++$i;
            }
        }

        return [
            'error' => false
        ];
    }

    public function getParentOptions()
    {
        $options = '<option value="0" selected>' .  $this->mediaManager->modx->lexicon('mediamanager.categories.root') . '</option>';
        $options .= $this->buildParentOptions($this->getCategories());

        return $options;
    }

    private function buildParentOptions(array $list, $parent = 0, $level = 0)
    {
        $options = '';

        foreach ($list as $item) {
            if ($item->get('parent_id') === $parent) {
                $prefix = str_repeat('-', $level);

                $options .= '<option value="' . $item->get('id') . '">' . $prefix . $item->get('name') . '</option>';

                $options .= $this->buildParentOptions($list, $item->get('id'), $level + 1);
            }
        }

        return $options;
    }

    public function getList()
    {
        $listHtml = $this->buildList($this->getCategories());

        if(!empty($listHtml)) {
            $listHtml = '<ol class="sortable">' . $listHtml . '</ol>';
        }

        return $listHtml;
    }

    private function buildList(array $list, $parent = 0)
    {
        $listHtml = '';

        foreach($list as $item) {
            if ($item->get('parent_id') === $parent) {
                $listHtml .= '<li id="items_' . $item->get('id') . '"><div>' . $item->get('name') . '<span class="pull-right">Edit - Delete</span></div><ol>';
                $listHtml .= $this->buildList($list, $item->get('id'));
                $listHtml .= '</ol></li>';
            }
        }

        return $listHtml;
    }

    public function getCategoryTree()
    {
        $list = $this->buildCategoryTree($this->getCategories());
        $list = array_values($list);

        $root = array(
            array(
                'text'       => $this->mediaManager->modx->lexicon('mediamanager.global.root'),
                'categoryId' => 0,
                'state'      => array(
                    'selected' => true
                )
            )
        );

        $archive = array(
            array(
                'text'       => $this->mediaManager->modx->lexicon('mediamanager.global.archive'),
                'categoryId' => -1
            )
        );

        $list = array_merge($root, $list, $archive);

        $select = $this->getParentOptions();

        return [
            'list'   => $list,
            'select' => $select
        ];
    }

    private function buildCategoryTree(array $list, $parent = 0)
    {
        $data = array();

        foreach ($list as $item) {
            if ($item->get('parent_id') === $parent) {
                $data[$item->get('id')] = array(
                    'categoryId' => $item->get('id'),
                    'text'       => $item->get('name'),
                    'nodes'      => $this->buildCategoryTree($list, $item->get('id'))
                );

                // Remove nodes if empty
                if (empty($data[$item->get('id')]['nodes'])) {
                    unset($data[$item->get('id')]['nodes']);
                }
            }
        }

        return $data;
    }

    /**
     * Get categories.
     *
     * @return array
     */
    public function getCategories()
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $q->sortby('parent_id', 'ASC');
        $q->sortby('rank', 'ASC');

        return $this->mediaManager->modx->getCollection('MediamanagerCategories', $q);
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