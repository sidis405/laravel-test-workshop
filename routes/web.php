<?php

Route::get('/', 'PostsController@index')->name('posts.index');
Route::get('posts/create', 'PostsController@create')->name('posts.create');
Route::get('posts/{post}', 'PostsController@show')->name('posts.show');
Route::post('posts', 'PostsController@store')->name('posts.store');

//

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
