<?php

use Illuminate\Support\Collection;

/**
 * Convert tree of nodes in an array appropriate for HTML::nav().
 *
 * @param  \Illuminate\Support\Collection $tree
 * @param  int         $activeItemKey
 * @param  boolean     $active
 *
 * @return array
 */
function make_nav(Collection $tree, $activeItemKey = null, &$active = null)
{
    if (!$tree->count()) return null;

    return array_map(function ($item) use ($activeItemKey, &$active) {
        $data = array();

        $childActive = false;
        $data['items'] = make_nav($item->children, $activeItemKey, $childActive);

        if ($activeItemKey !== null) 
        {
            $childActive |= $activeItemKey == $item->getKey();
        }

        $active |= $childActive;

        $data['active'] = $childActive;

        foreach (array('url', 'label') as $key) {
            $getter = 'getNav'.ucfirst($key);

            $data[$key] = $item->$getter();
        }

        return $data;

    }, $tree->all());
}