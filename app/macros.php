<?php

/**
 * Begin boostrap form group.
 * 
 * Checks whether field has errors.
 * 
 * @param string $name
 * 
 * @return string
 */
Form::macro('beginGroup', function ($name)
{
    $errors = View::shared('errors');

    $class = 'form-group';

    if ($errors->has($name)) $class .= ' has-error';

    return '<div class="'.$class.'">';
});

/**
 * End bootstrap form group.
 * 
 * Displays last error for a field if any.
 * 
 * @param string $name
 * 
 * @return string
 */
Form::macro('endGroup', function ($name)
{
    $html = '</div>';

    $errors = View::shared('errors');

    if ($errors->has($name))
    {
        $html = '<div class="col-lg-10 col-lg-offset-2"><span class="help-block">'.$errors->first($name).'</span></div>'.$html;
    }

    return $html;
});

/**
 * Simple macro for generating bootstrap icons.
 * 
 * @param string $icon
 */
HTML::macro('glyphicon', function ($icon)
{
    return '<span class="glyphicon glyphicon-'.$icon.'"></span>';
});

/**
 * Render multi-level navigation.
 *
 * @param  array  $data
 *
 * @return string
 */
HTML::macro('nav', function($data)
{
    if (empty($data)) return '';

    $html = '<ul class="nav">';

    foreach ($data as $item)
    {
        $html .= '<li';

        if (isset($item['active']) && $item['active']) $html .= ' class="active"';

        $html .= '><a href="'.$item['url'].'">';
        $html .= e($item['label']).'</a>';
        if (isset($item['items'])) $html .= HTML::nav($item['items']);
        $html .= '</li>';
    }   

    return $html.'</ul>';
});