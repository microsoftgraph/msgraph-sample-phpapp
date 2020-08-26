<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@welcome');
Route::get('/signin', 'AuthController@signin');
Route::get('/callback', 'AuthController@callback');
Route::get('/signout', 'AuthController@signout');
Route::get('/calendar', 'CalendarController@calendar');

Route::get('/messages', 'MessageController@index')->name('message_index');
Route::get('/messages/{id}', 'MessageController@show')->name('message_show');
Route::get('/messages/{id}/attachments/{attachmentId}', 'MessageController@getAttachment')->name('message_attachment');
