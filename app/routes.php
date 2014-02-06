<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'PageController@show');

Route::resource('pages', 'PagesController', array('except' => array('show')));

Route::group(array('prefix' => 'pages'), function () {

    foreach (array('up', 'down') as $key) 
    {    
        Route::post("{pages}/$key", array(
            'as' => "pages.$key",
            'uses' => "PagesController@$key",
        ));
    }

    Route::get('export', array(
        'as' => 'pages.export', 
        'uses' => 'PagesController@export',
    ));

    Route::get('{pages}/confirm', array(
        'as' => 'pages.confirm', 
        'uses' => 'PagesController@confirm',
    ));
});


// The slug route should be registered last since it will capture any slug-like
// route
Route::get('{slug}', array('as' => 'page', 'uses' => 'PageController@show'))
    ->where('slug', Page::$slugPattern);
