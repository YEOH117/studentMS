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

//首页
Route::get('/','Home\IndexController@index')->name('/');

//登陆
Route::get('/login','Home\SessionController@index')->name('login');
Route::post('/login','Home\SessionController@store')->name('login');

//学生录入_单人录入
Route::get('/student/single_entry','Home\StudentController@single_entry')->name('single_entry');
Route::post('/student/single_entry','Home\StudentController@stroe')->name('single_entry');

//学生录入_批量录入
Route::get('/student/batch_entry','Home\StudentController@batch_entry')->name('batch_entry');
Route::post('/student/batch_entry','Home\StudentController@batch_stroe')->name('batch_entry');

//管理员账号管理_账号列表
Route::get('/admin/account_list','Home\AdminController@list')->name('admin_list');
//管理员账号管理_账号创建
Route::get('/admin/create','Home\AdminController@create')->name('admin_create');
Route::post('/admin/create','Home\AdminController@stroe')->name('admin_create');
//管理员账号管理_账号删除
Route::get('/admin/{user}/del','Home\AdminController@delete');
