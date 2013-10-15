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

Route::get('/', 'HomeController@showPage');

Route::resource('pages', 'PagesController');

// The slug route should be registered last since it will capture any slug-like
// route
Route::get('{slug}', array('as' => 'page', 'uses' => 'HomeController@showPage'))
    ->where('slug', '[a-z0-9\-\/]+');
