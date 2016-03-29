<?php

class MediaManagerCategoriesHelper
{
    /**
     * The mediaManager object.
     */
    private $mediaManager = null;

    /**
     * @var int
     */
    private $currentCategory = 0;

    private $categories = array();

    private $tree = array();

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
     * Get categories JSON.
     *
     * @return array
     */
    public function getListJson()
    {
        $categories = $this->getList();
        $tree = array();

//        var tree = [
//            {
//                text: "Home"
//            },
//            {
//                text: "Documents",
//                nodes: [
//                    {
//                        text: "Brochures"
//                    }
//                ]
//            },
//            {
//                text: "Blog"
//            },
//            {
//                text: "Archive"
//            }
//        ];

        foreach ($categories as $category) {
            $this->categories[$category->get('id')] = $category->toArray();
        }

//        foreach ($this->categories as $category) {
//            $this->tree[$category['id']] = array(
//                'text' => $category['name']
//            );
//
//            $parentId = $category['parent_id'];
//
//            if ($parentId === 0) {
//                continue;
//            }
//
//            foreach ($this->categories as $childCategory) {
//                if ($childCategory['parent_id'] !== $parentId) {
//                    continue;
//                }
//                $this->tree[$parentId][] = array(
//                    'text' => $childCategory['name']
//                );
//            }
//        }

//        var_dump($this->buildTree(0));

//        foreach ($this->categories as $category) {
//            if ($category['parent_id'] !== 0) {
//                continue;
//            }
//            $c = $this->addChild(array(), 0, $category);
//            $this->tree[$category['id']] = $c;
//        }
//
//        var_dump($this->tree);

//            var_dump($category);
//            $category['selected'] = 0;
//
//            if ($this->currentCategory === $category['id']) {
//                $category['selected'] = 1;
//            }
//
//            $html .= $this->mediaManager->getChunk('categories/tree_item', $category);

//            $tree[] = array(
//                'text' => $category->get('name')
//            );

//        }

//        if (empty($tree)) {
//            $tree = $this->mediaManager->modx->lexicon('mediamanager.categories.error.no_categories_found');
//        }

        return [
            'error' => false,
            'tree'  => $tree
        ];
    }

    public function buildTree($parentId, $tree = array()) {
        foreach ($this->categories as $category) {
            if ($category['parent_id'] !== $parentId) {
                continue;
            }

            $tree[$parentId]['text'] = $category['name'];
            $tree[$parentId]['nodes'] = $this->buildTree($category['parent_id'], $tree[$parentId]['nodes']);
        }

        return $tree;
    }

//    public function addChild($child, $parentId, $category)
//    {
//        $child[$parentId]['text'] = $category['name'];
//
//        foreach ($this->categories[$parentId] as $childCategory) {
//            $child[$parentId]['nodes']['text'] = $category['name'];
//
//        }
//
//        return $child;
//    }
//
//
//    public function getTreeIndex($name, $category, $parents = array())
//    {
//        $parentId = $this->categories[$category['id']]['parent_id'];
//        if ($parentId !== 0) {
//            $parents[] = $parentId;
//            return $this->getTreeIndex($name, $this->categories[$category['parent_id']], $parents);
//        }
//
//        $parents[] = $parentId;
//
//        $parents = array_reverse($parents);
//        $row = $this->tree;
//        foreach ($parents as $parent) {
//            $row = $row[$parent];
//        }
//
//        $row[] = array(
//            'text' => $name
//        );
//
//        array_push($this->tree, $row);
//
//        return null;
//    }

}