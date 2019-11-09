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
Route::get('/student/dormitorySearch','Home\StudentController@dormitorySearch')->name('student_dormitory_show');
Route::post('/student/dormitorySearch','Home\StudentController@dormitoryShow')->name('student_dormitory_show');
//查询_宿舍楼学生信息查询
Route::get('/student/buildingSearch','Home\StudentController@buildingSearch')->name('student_building_show');
Route::post('/student/buildingSearch','Home\StudentController@buildingShow')->name('student_building_show');
//查询_班级学生信息查询
Route::get('/student/classSearch','Home\StudentController@classesSearch')->name('student_class_show');
Route::post('/student/classSearch','Home\StudentController@classesShow')->name('student_class_show');
//查询_专业学生信息查询
Route::get('/student/collegeSearch','Home\StudentController@collegeSearch')->name('student_college_show');
Route::post('/student/collegeShow','Home\StudentController@collegeShow')->name('student_college_show');
//查询_ajax请求学生信息
Route::get('/ajax/{area}/{building}/{dormitory}','Home\StudentController@ajaxInquire');

//学生信息导出
Route::post('/student/export','Home\StudentController@export')->name('student_export');

//删除我的调宿请求
Route::get('/application/{movestudent}/del','Home\AdjustApplicationController@delete')->name('adjust_del');
//调宿_学生申请调宿页
Route::get('/dormitory/adjustApplication','Home\AdjustApplicationController@index')->name('adjust_application');
//调宿_学生申请调宿逻辑 人-人
Route::get('/adjust/application/{student}','Home\AdjustApplicationController@store');
//调宿_学生申请调宿逻辑 人-空位
Route::get('/adjust/application/{dormitory}/none','Home\AdjustApplicationController@noneStore');
//调宿_对方处理调宿请求页
Route::get('/application/{user}/{token}','Home\AdjustApplicationController@response')->name('adjust_answer');
//调宿_对方处理调宿请求逻辑
Route::get('/answer/{movestudent}/{token}/{judge}','Home\AdjustApplicationController@answer')->name('adjust_reply');
//调宿_学生我的调宿申请页
Route::get('/application/me','Home\AdjustApplicationController@myList')->name('adjust_my_list');

//宿舍调换-调宿申请列表 管理员
Route::get('/dormitory/adjust/list','Home\AdjustApplicationController@list')->name('adjust_list');
//宿舍调换-调宿申请处理页 管理员 人-人
Route::get('/dormitory/adjust/{userId}/{token}','Home\AdjustApplicationController@show')->name('adjust_show');
//宿舍调换-调宿申请处理页 管理员 人-空位
Route::get('/dormitory/adjust/{userId}/{token}/none','Home\AdjustApplicationController@noneShow')->name('adjust_show_none');
//宿舍调换-处理申请 管理员 人-人
Route::get('/dormitory/process/{movestudent}/{token}/{judge}','Home\AdjustApplicationController@process')->name('adjust_process');
//宿舍调换-处理申请 管理员 人-空位
Route::get('/dormitory/process/{movestudent}/{token}/{judge}/none','Home\AdjustApplicationController@noneProcess')->name('adjust_process_none');
//宿舍初始化-管理员初始化宿舍人员
Route::get('/dormitory/init','Home\AdjustApplicationController@init')->name('adjust_init');
Route::post('/dormitory/init','Home\AdjustApplicationController@initAdjust')->name('adjust_init');
//宿舍管理-退宿操作
Route::get('/dormitory/del','Home\DormitoryController@delete')->name('dormitory_student_del');
Route::post('/dormitory/del','Home\DormitoryController@del')->name('dormitory_student_del');

//通知页
Route::get('/notification/list','Home\NotificationController@index')->name('notification_list');
//查看通知
Route::get('/notification/{notification}','Home\NotificationController@show')->name('notification_show');
//删除全部通知
Route::get('/notification/all/del','Home\NotificationController@delete')->name('notification_del');

//登记备案列表
Route::get('filing/list','Home\FilingController@index')->name('filing_list');
//离校登记表页
Route::get('/filing/leaveSchool','Home\FilingController@leaveSchool')->name('filing_leave');
//住宿登记表页
Route::get('/filing/deferSchool','Home\FilingController@deferSchool')->name('filing_defer');
//备案提交逻辑
Route::post('/filing/store','Home\FilingController@store')->name('filing_store');
//ajax查询学生备案记录
Route::get('/filing/ajax/{student}','Home\FilingController@ajaxInquire')->name('filing_ajax');
//显示备案记录
Route::get('/filing/{filing}','Home\FilingController@show')->name('filing_show');

//个人资料页
Route::get('/user/me','Home\UserController@index')->name('user_me');
Route::post('/user/me','Home\UserController@store')->name('user_me');
//头像上传
Route::post('/user/avatar','Home\UserController@upload')->name('user_avatar');
//邮箱设置按钮
Route::get('/user/set/email','Home\UserController@setEmail')->name('user_email');
//ajax请求，给原邮箱发送验证码
Route::post('/user/email/code','Home\UserController@sendVerifyEmail')->name('user_email_code');
//给邮箱发送激活邮件
Route::post('/user/email/active','Home\UserController@sendActiveEmail')->name('user_email_active');
//给初始化邮箱发送激活邮件
Route::post('/user/email/init','Home\UserController@sendInitEmail')->name('user_emain_init');
//邮箱激活
Route::get('/user/email/active/{email}/{token}','Home\UserController@emailSeting')->name('user_mail_set');
//修改密码-通过原密码修改
Route::get('/user/password/edit','Home\UserController@password')->name('user_password');
Route::post('/user/password/edit','Home\UserController@passwordUpdata')->name('user_password');
//忘记密码
Route::get('/password/reset','Home\UserController@passwordReset')->name('user_password_reset');
Route::post('/password/reset','Home\UserController@resetPassWord')->name('user_password_reset');
Route::get('/password/reset/{user}/{token}','Home\UserController@passwordReseting')->name('user_password_reseting');
Route::post('/password/reseting','Home\UserController@reset')->name('user_password_reseting');

//注销
Route::get('/logout','Home\SessionController@logout')->name('logout');






