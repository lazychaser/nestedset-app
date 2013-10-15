<?php

use Illuminate\Support\Collection;

function render_contents(array $data)
{
    if (empty($data)) return '';

    $html = '<ul class="nav">';

    foreach ($data as $item) {
        $html .= '<li';

        if (isset($item['active']) && $item['active']) $html .= ' class="active"';

        $html .= '><a href="'.$item['url'].'">';
        $html .= e($item['label']).'</a>';
        if (isset($item['items'])) $html .= render_contents($item['items']);
        $html .= '</li>';
    }   

    return $html.'</ul>';
}

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