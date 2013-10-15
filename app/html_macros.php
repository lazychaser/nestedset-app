<?php

Form::macro('beginGroup', function ($name) {
    $errors = View::shared('errors');

    $class = 'form-group';

    if ($errors->has($name)) $class .= ' has-error';

    return '<div class="'.$class.'">';
});

Form::macro('endGroup', function ($name) {
    $html = '</div>';

    $errors = View::shared('errors');

    if ($errors->has($name))
    {
        $html = '<div class="col-lg-10 col-lg-offset-2"><span class="help-block">'.$errors->first($name).'</span></div>'.$html;
    }

    return $html;
});