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

    public function createCategory($name, $parent = 0, $sourceId = 0, $rank = 9999)
    {
        $name = trim($name);

        if (empty($name)) {
            return [
                'error'   => true,
                'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.error.empty')
            ];
        }

        $category = $this->mediaManager->modx->getObject('MediamanagerCategories', [
            'name'             => $name,
            'parent_id'        => $parent,
            'media_sources_id' => $sourceId
        ]);

        if ($category) {
            return [
                'error'   => true,
                'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.error.exists')
            ];
        }

        $category = $this->mediaManager->modx->newObject('MediamanagerCategories');
        $category->set('media_sources_id', $sourceId);
        $category->set('name',             $name);
        $category->set('parent_id',        $parent);
        $category->set('rank',             $rank);
        $category->save();

        return [
            'error'   => false,
            'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.success', ['name' => $name]),
            'html'    => $this->getList(),
            'select'  => $this->getParentOptions()
        ];
    }

    public function editCategory($id, $name)
    {
        $category = $this->mediaManager->modx->getObject('MediamanagerCategories', (int) $id);

        if ($category) {
            $category->set('name', $name);
            $category->save();
        }

        return [
            'error'   => false,
            'message' => '',
            'html'    => $this->getList(),
            'select'  => $this->getParentOptions()
        ];
    }

    public function deleteCategory($id, $newId)
    {
        $id    = (int) $id;
        $newId = (int) $newId;

        $categoryIds = array_merge($this->mediaManager->getCategoryChildIds($this->getCategories(), $id), [$id]);

        /**
         * Move files to the new category.
         */
        $q = $this->mediaManager->modx->newQuery('MediamanagerFilesCategories');
        $q->where([
            'mediamanager_categories_id:IN' => $categoryIds
        ]);

        $filesCategories = $this->mediaManager->modx->getCollection('MediamanagerFilesCategories', $q);
        $filesIds = [];

        /**
         * Remove current connections.
         */
        foreach ($filesCategories as $filesCategory) {
            $filesIds[$filesCategory->get('mediamanager_files_id')] = $filesCategory->get('mediamanager_files_id');

            $filesCategory->remove();
        }

        /**
         * Create new connections.
         */
        foreach ($filesIds as $fileId) {
            $fileConnection = $this->mediaManager->modx->newObject('MediamanagerFilesCategories');
            $fileConnection->set('mediamanager_files_id',      $fileId);
            $fileConnection->set('mediamanager_categories_id', $newId);
            $fileConnection->save();
        }

        /**
         * Delete the category and child categories.
         */
        $this->mediaManager->modx->removeCollection('MediamanagerCategories', [
            'id:IN' => $categoryIds
        ]);

        return [
            'error'   => false,
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
                $category->set('parent_id', (int) $value);
                $category->set('rank', $i);
                $category->save();

                ++$i;
            }
        }

        return [
            'error' => false
        ];
    }

    /**
     * Get source options.
     *
     * @return string
     */
    public function getSourceOptions()
    {
        $options = '';
        foreach ($this->mediaManager->sources->getList() as $item) {
            $options .= $this->mediaManager->getChunk('categories/option', [
                'value'    => $item['id'],
                'name'     => $item['name'],
                'selected' => ($this->mediaManager->sources->getCurrentSource() === $item['id'] ? 'selected' : '')
            ]);
        }

        return $options;
    }

    /**
     * Get parent options.
     *
     * @return string
     */
    public function getParentOptions()
    {
        $options = $this->mediaManager->getChunk('categories/option', [
            'value'    => 0,
            'name'     => $this->mediaManager->modx->lexicon('mediamanager.categories.root'),
            'selected' => 'selected'
        ]);

        $options .= $this->buildParentOptions($this->getCategories());

        return $options;
    }

    private function buildParentOptions(array $list, $parent = 0, $level = 0)
    {
        $options = '';

        foreach ($list as $item) {
            if ($item->get('parent_id') === $parent) {
                $prefix = str_repeat('-', $level);

                $options .= $this->mediaManager->getChunk('categories/option', [
                    'value'    => $item->get('id'),
                    'name'     => $prefix . $item->get('name'),
                    'selected' => ''
                ]);

                $options .= $this->buildParentOptions($list, $item->get('id'), $level + 1);
            }
        }

        return $options;
    }

    public function getList()
    {
        $listHtml = $this->buildList($this->getCategories());

        if (!empty($listHtml)) {
            $listHtml = $this->mediaManager->getChunk('categories/list_sortable', [
                'html' => $listHtml
            ]);
        }

        return $listHtml;
    }

    private function buildList(array $list, $parent = 0)
    {
        $listHtml = '';

        foreach ($list as $item) {
            if ($item->get('parent_id') === $parent) {
                $sources = 0;

                $itemSources = $item->get('sources');
                if (!empty($itemSources)) {
                    $sources = $itemSources;
                }

                $listHtml .= $this->mediaManager->getChunk('categories/list_item', [
                    'id'            => $item->get('id'),
                    'name'          => $item->get('name'),
                    'sources'       => $sources,
                    'deleteMessage' => $this->mediaManager->modx->lexicon('mediamanager.categories.delete_confirm_message', ['name' => $item->get('name')]),
                    'deleteTitle'   => $this->mediaManager->modx->lexicon('mediamanager.categories.delete_confirm_title'),
                    'deleteConfirm' => $this->mediaManager->modx->lexicon('mediamanager.categories.delete'),
                    'deleteCancel'  => $this->mediaManager->modx->lexicon('mediamanager.categories.cancel'),
                    'delete'        => $this->mediaManager->modx->lexicon('mediamanager.categories.delete'),
                    'editMessage'   => $this->mediaManager->modx->lexicon('mediamanager.categories.edit_confirm_message', ['name' => $item->get('name')]),
                    'editTitle'     => $this->mediaManager->modx->lexicon('mediamanager.categories.edit_confirm_title'),
                    'editConfirm'   => $this->mediaManager->modx->lexicon('mediamanager.categories.edit'),
                    'editCancel'    => $this->mediaManager->modx->lexicon('mediamanager.categories.cancel'),
                    'edit'          => $this->mediaManager->modx->lexicon('mediamanager.categories.edit'),
                    'children'      => $this->buildList($list, $item->get('id'))
                ]);
            }
        }

        return $listHtml;
    }

    /**
     * Get categories.
     *
     * @param int $source
     * @return array
     */
    public function getCategories($source = null)
    {
        if ($source === null) {
            $source = $this->mediaManager->sources->getCurrentSource();
        }

        $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $q->where([
            'media_sources_id' => $source
        ]);
        $q->sortby('parent_id', 'ASC');
        $q->sortby($this->mediaManager->modx->escape('rank'), 'ASC');

        return $this->mediaManager->modx->getCollection('MediamanagerCategories', $q);
    }

    /**
     * Get category tree.
     *
     * @param int $selected
     * @return array
     */
    public function getCategoryTree($selected = 0)
    {
        $list = $this->buildCategoryTree($this->getCategories(), 0, $selected);
        $list = array_values($list);

        $root = [
            [
                'text'       => $this->mediaManager->modx->lexicon('mediamanager.global.root'),
                'categoryId' => 0,
                'state'      => [
                    'selected' => ($selected == 0 ? true : false)
                ]
            ]
        ];

        $archive = [
            [
                'text'       => $this->mediaManager->modx->lexicon('mediamanager.global.archive'),
                'categoryId' => -1,
                'state'      => [
                    'selected' => ($selected == -1 ? true : false)
                ]
            ]
        ];

        $list = array_merge($root, $list, $archive);
        $select = $this->getParentOptions();

        return [
            'list'   => $list,
            'select' => $select
        ];
    }

    /**
     * Build category tree.
     *
     * @param array $list
     * @param int $parent
     * @param int $selected
     *
     * @return array
     */
    private function buildCategoryTree(array $list, $parent = 0, $selected = 0)
    {
        $data = [];

        foreach ($list as $item) {
            if ($item->get('parent_id') === $parent) {

                $data[$item->get('id')] = [
                    'text'       => $item->get('name'),
                    'categoryId' => $item->get('id'),
                    'nodes'      => $this->buildCategoryTree($list, $item->get('id'), $selected),
                    'state'      => [
                        'selected' => ($item->get('id') == $selected ? true : false),
                    ]
                ];

                // Remove nodes if empty
                if (empty($data[$item->get('id')]['nodes'])) {
                    unset($data[$item->get('id')]['nodes']);
                }
            }
        }

        return $data;
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
            'media_sources_id' => $this->mediaManager->sources->getCurrentSource(),
            'name:LIKE' => '%' . $search . '%'
        ]);

        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id'   => $category->get('id'),
                'text' => $category->get('name')
            ];
        }

        return $result;
    }

    /**
     * Get all categories.
     *
     * @return array
     */
    public function getAllCategories()
    {
        $query = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $query->where(['media_sources_id' => $this->mediaManager->sources->getCurrentSource()]);

        $query->sortby('parent_id', 'asc');

        $categories = $this->mediaManager->modx->getIterator('MediamanagerCategories', $query);

        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id'        => $category->get('id'),
                'text'      => $this->getParents($category->get('parent_id')) . $category->get('name'),
                'parents'   => $this->getParents($category->get('parent_id')) // TODO
            ];
        }

        return $result;
    }

    /**
     * Get list of parent category names as string.
     *
     * @return string
     */
    private function getParents($categoryId, $list = [], $separator = ' > ') {
        if ($object = $this->mediaManager->modx->getObject('MediamanagerCategories', $categoryId)) {
            $list[] = $object->get('name');
            return $this->getParents($object->get('parent_id'), $list);
        }

        return count($list) ? implode($separator, array_reverse($list)) . $separator : '';
    }
}
