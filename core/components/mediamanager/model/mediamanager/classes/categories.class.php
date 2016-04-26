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

    public function createCategory($name, $parent = 0, $excludes = [], $rank = 9999)
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

        if (!empty($excludes) && $category) {
            foreach ($excludes as $exclude) {
                $excludeObject = $this->mediaManager->modx->newObject('MediamanagerCategoriesExcludes');
                $excludeObject->set('mediamanager_contexts_id',   $exclude);
                $excludeObject->set('mediamanager_categories_id', $category->get('id'));
                $excludeObject->save();
            }
        }

        return [
            'error'   => false,
            'message' => $this->mediaManager->modx->lexicon('mediamanager.categories.success', ['name' => $name]),
            'html'    => $this->getList(),
            'select'  => $this->getParentOptions()
        ];
    }

<<<<<<< HEAD
    public function deleteCategory($id, $newId)
    {
        /*
         * First move all files to the new category.
         */

        /*
         * 
         */
=======
    public function editCategory($id, $name, $excludes = [])
    {
        $category = $this->mediaManager->modx->getObject('MediamanagerCategories', (int) $id);

        if ($category) {
            $category->set('name', $name);
            $category->save();

            $this->mediaManager->modx->removeCollection('MediamanagerCategoriesExcludes', array(
                'mediamanager_categories_id' => $category->get('id')
            ));

            if (!empty($excludes) && $category) {
                foreach ($excludes as $exclude) {
                    $excludeObject = $this->mediaManager->modx->newObject('MediamanagerCategoriesExcludes');
                    $excludeObject->set('mediamanager_contexts_id',   $exclude);
                    $excludeObject->set('mediamanager_categories_id', $category->get('id'));
                    $excludeObject->save();
                }
            }
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
        $q->where(array('mediamanager_categories_id:IN' => $categoryIds));

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
        $this->mediaManager->modx->removeCollection('MediamanagerCategories', array(
            'id:IN' => $categoryIds
        ));

        $this->mediaManager->modx->removeCollection('MediamanagerCategoriesExcludes', array(
            'mediamanager_categories_id:IN' => $categoryIds
        ));

        return [
            'error'   => false,
            'html'    => $this->getList(),
            'select'  => $this->getParentOptions()
        ];
>>>>>>> cb9568be106b245f03341d68031da70b113bbfdd
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
        $options = $this->mediaManager->getChunk('categories/option', [
            'value'    => 0,
            'name'     => $this->mediaManager->modx->lexicon('mediamanager.categories.root'),
            'selected' => 'selected',
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
                    'selected' => '',
                ]);

                $options .= $this->buildParentOptions($list, $item->get('id'), $level + 1);
            }
        }

        return $options;
    }

    public function getList()
    {
        $listHtml = $this->buildList($this->getCategories());

        if(!empty($listHtml)) {
            $listHtml = $this->mediaManager->getChunk('categories/list_sortable', [
                'html' => $listHtml
            ]);
        }

        return $listHtml;
    }

    private function buildList(array $list, $parent = 0)
    {
        $listHtml = '';

        foreach($list as $item) {
            if ($item->get('parent_id') === $parent) {
<<<<<<< HEAD
                $listHtml .= '<li id="items_' . $item->get('id') . '"><div>' . $item->get('name') . '<span class="pull-right">
                <a href="javascript:void(0)" data-delete-category="' . $item->get('id') . '" data-delete-message="' . $this->mediaManager->modx->lexicon('mediamanager.categories.delete_confirm_message', array('name' => $item->get('name'))) . '" data-delete-title="' . $this->mediaManager->modx->lexicon('mediamanager.categories.delete_confirm_title') . '" data-delete-confirm="' . $this->mediaManager->modx->lexicon('mediamanager.categories.delete') . '" data-delete-cancel="' . $this->mediaManager->modx->lexicon('mediamanager.categories.cancel') . '">
                    ' . $this->mediaManager->modx->lexicon('mediamanager.categories.delete') . '
                </a>
                </span></div><ol>';
                $listHtml .= $this->buildList($list, $item->get('id'));
                $listHtml .= '</ol></li>';
=======
                $contexts = 0;

                $itemContexts = $item->get('contexts');
                if(!empty($itemContexts)) {
                    $contexts = $itemContexts;
                }

                $listHtml .= $this->mediaManager->getChunk('categories/list_item', [
                    'id'            => $item->get('id'),
                    'name'          => $item->get('name'),
                    'contexts'      => $contexts,
                    'deleteMessage' => $this->mediaManager->modx->lexicon('mediamanager.categories.delete_confirm_message', array('name' => $item->get('name'))),
                    'deleteTitle'   => $this->mediaManager->modx->lexicon('mediamanager.categories.delete_confirm_title'),
                    'deleteConfirm' => $this->mediaManager->modx->lexicon('mediamanager.categories.delete'),
                    'deleteCancel'  => $this->mediaManager->modx->lexicon('mediamanager.categories.cancel'),
                    'delete'        => $this->mediaManager->modx->lexicon('mediamanager.categories.delete'),
                    'editMessage'   => $this->mediaManager->modx->lexicon('mediamanager.categories.edit_confirm_message', array('name' => $item->get('name'))),
                    'editTitle'     => $this->mediaManager->modx->lexicon('mediamanager.categories.edit_confirm_title'),
                    'editConfirm'   => $this->mediaManager->modx->lexicon('mediamanager.categories.edit'),
                    'editCancel'    => $this->mediaManager->modx->lexicon('mediamanager.categories.cancel'),
                    'edit'          => $this->mediaManager->modx->lexicon('mediamanager.categories.edit'),
                    'children'      => $this->buildList($list, $item->get('id'))
                ]);
>>>>>>> cb9568be106b245f03341d68031da70b113bbfdd
            }
        }

        return $listHtml;
    }

    /**
     * Get categories.
     *
     * @return array
     */
    public function getCategories()
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $q->select(array(
            'MediamanagerCategories.*',
            'contexts' => 'GROUP_CONCAT(CategoriesExcludes.mediamanager_contexts_id SEPARATOR ",")'
        ));
        $q->leftJoin('MediamanagerCategoriesExcludes', 'CategoriesExcludes');
        $q->sortby('parent_id', 'ASC');
        $q->sortby('rank', 'ASC');
        $q->groupby('MediamanagerCategories.id');

        return $this->mediaManager->modx->getCollection('MediamanagerCategories', $q);
    }

    public function getMediaContexts($includeAll = false, $includeMain = false)
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerContexts');
        $q->where(array('is_all' => (int) $includeAll));
        $q->where(array('is_main' => (int) $includeMain));
        $q->sortby('name', 'ASC');

        return $this->mediaManager->modx->getCollection('MediamanagerContexts', $q);
    }

    public function getMediaContextsCheckboxes()
    {
        $checkboxes = '';

        $mediaContexts = $this->getMediaContexts();
        foreach($mediaContexts as $mediaContext)
        {
            $checkboxes .= $this->mediaManager->getChunk('categories/checkbox',  $mediaContext->toArray());
        }

        return $checkboxes;
    }

    public function getCategoryTree($selected = 0)
    {
        $q = $this->mediaManager->modx->newQuery('MediamanagerCategories');
        $q->leftJoin('MediamanagerCategoriesExcludes', 'CategoriesExcludes');
        $q->sortby('parent_id', 'ASC');
        $q->sortby('rank', 'ASC');

        $categories = $this->mediaManager->modx->getCollection('MediamanagerCategories', $q);

        $list = $this->buildCategoryTree($categories, 0, $selected);
        $list = array_values($list);

        $root = array(
            array(
                'text'       => $this->mediaManager->modx->lexicon('mediamanager.global.root'),
                'categoryId' => 0,
                'state'      => array(
                    'selected' => ($selected == 0 ? true : false)
                )
            )
        );

        $archive = array(
            array(
                'text'       => $this->mediaManager->modx->lexicon('mediamanager.global.archive'),
                'categoryId' => -1,
                'state'      => array(
                    'selected' => ($selected == -1 ? true : false)
                )
            )
        );

        $list = array_merge($root, $list, $archive);

        $select = $this->getParentOptions();

        return [
            'list'   => $list,
            'select' => $select
        ];
    }

    private function buildCategoryTree(array $list, $parent = 0, $selected)
    {
        $data = array();

        foreach ($list as $item) {
            if ($item->get('parent_id') === $parent) {

                $data[$item->get('id')] = array(
                    'text'       => $item->get('name'),
                    'categoryId' => $item->get('id'),
                    'nodes'      => $this->buildCategoryTree($list, $item->get('id'), $selected),
                    'state'      => array(
                        'selected' => ($item->get('id') == $selected ? true : false),
                    )
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