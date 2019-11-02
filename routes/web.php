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
Route::get('/admin/list','Home\AdminController@list')->name('admin_list');
//管理员账号管理_账号创建
Route::get('/admin/create','Home\AdminController@create')->name('admin_create');
Route::post('/admin/create','Home\AdminController@stroe')->name('admin_create');
//管理员账号管理_账号删除
Route::get('/admin/{user}/del','Home\AdminController@delete');

//专业代码管理_列表
Route::get('/profession/list','Home\ProfessionController@list')->name('profession_list');
//专业代码管理_创建
Route::get('/profession/create','Home\ProfessionController@create')->name('profession_create');
Route::post('/profession/create','Home\ProfessionController@stroe')->name('profession_create');
//专业代码管理_修改
Route::get('/profession/{profession_code}/edit','Home\ProfessionController@edit')->name('profession_edit');
Route::post('/profession/{profession_code}/edit','Home\ProfessionController@update')->name('profession_edit');

//宿舍楼管理_列表
Route::get('/building/list','Home\BuildingController@list')->name('building_list');
//宿舍楼管理_创建
Route::get('/building/create','Home\BuildingController@create')->name('building_create');
Route::post('/building/create','Home\BuildingController@stroe')->name('building_create');
//宿舍楼管理_详情
Route::get('/building/show/{building}','Home\BuildingController@show')->name('building_show');
//宿舍楼管理_修改
Route::get('/building/{building}/edit','Home\BuildingController@edit')->name('building_edit');
Route::post('/building/{building}/edit','Home\BuildingController@update');
//宿舍楼管理_删除
Route::get('/building/{building}/del','Home\BuildingController@delete');
//宿舍楼管理_宿舍初始化
Route::get('/building/{id}/init','Home\BuildingController@init')->name('building_init');
Route::post('/building/{building}/init','Home\BuildingController@initing');

//宿舍管理_新生排宿—全部
Route::get('/Student/Sort','Home\DormitoryController@NewStudent')->name('student_sort');
Route::post('/Student/Sort','Home\DormitoryController@sort')->name('student_sort');

//查询_学生住宿与个人信息查询
Route::get('/student/search','Home\StudentController@search')->name('student_show');
Route::post('/student/search','Home\StudentController@show')->name('student_show');
//查询_宿舍学生信息查询
Route::get('student/dormitorySearch','Home\StudentController@dormitorySearch')->name('student_dormitory_show');
Route::post('student/dormitorySearch','Home\StudentController@dormitoryShow')->name('student_dormitory_show');
//查询_宿舍楼学生信息查询
Route::get('student/buildingSearch','Home\StudentController@buildingSearch')->name('student_building_show');
Route::post('student/buildingSearch','Home\StudentController@buildingShow')->name('student_building_show');
//查询_班级学生信息查询
Route::get('student/classSearch','Home\StudentController@classesSearch')->name('student_class_show');
Route::post('student/classSearch','Home\StudentController@classesShow')->name('student_class_show');
//查询_专业学生信息查询
Route::get('student/collegeSearch','Home\StudentController@collegeSearch')->name('student_college_show');
Route::post('student/collegeShow','Home\StudentController@collegeShow')->name('student_college_show');

//学生信息导出
Route::post('/student/export','Home\StudentController@export')->name('student_export');


