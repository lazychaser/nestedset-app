<?php

use Illuminate\Support\Collection;

/**
 * Convert tree of nodes in an array appropriate for HTML::nav().
 *
 * @param  \Illuminate\Support\Collection $tree
 * @param  Page       $activePage
 * @param  boolean     $active
 *
 * @return array
 */
function make_contents(Collection $tree, Page $activePage, &$active = null)
{
    if (!$tree->count()) return null;

    return array_map(function ($item) use($activePage, &$active) {
        $data = array();

        $data['items']  = make_contents($item->children, $activePage, $childActive);

        $childActive |= $activePage->id == $item->id;
        $active |= $childActive;

        $data['active'] = $childActive;
        $data['url']    = URL::route('page', array('slug' => $item->slug));
        $data['label']  = $item->title;

        return $data;

    }, $tree->all());
}