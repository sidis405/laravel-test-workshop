<?php

Route::get('/', '\WS\Http\Controllers\PostsController@index')->name('posts.index');
Route::get('posts/create', '\WS\Http\Controllers\PostsController@create')->name('posts.create');
Route::get('posts/{post}', '\WS\Http\Controllers\PostsController@show')->name('posts.show');
Route::post('posts', '\WS\Http\Controllers\PostsController@store')->name('posts.store');

//

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
