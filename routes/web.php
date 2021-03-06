<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::resource('/projects', 'ProjectsController');

    Route::get('/', 'ProjectsController@index');
    Route::patch('/projects/{project}/tasks/{task}', 'ProjectTasksController@update');
    Route::post('/projects/{project}/tasks', 'ProjectTasksController@store');

    Route::post('/projects/{project}/invitations', 'ProjectInvitationsController@store');
});

Auth::routes();
