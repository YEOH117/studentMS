<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>北部湾大学-学生宿舍管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="/css/theme-default.css"/>
    @section('css')

    @show
    <!-- EOF CSS INCLUDE -->
</head>
<body style="background-image: url('img/bgimg.png')">
<!-- START PAGE CONTAINER -->
<div class="page-container @section('container')@show">

    <!-- START PAGE SIDEBAR -->
    <div class="page-sidebar">
        <!-- START X-NAVIGATION -->
        <ul class="x-navigation">
            <li class="xn-logo">
                <a href="/">北部湾大学</a>
            </li>
            <li class="xn-profile">
                <a href="#" class="profile-mini">
                    <!--小头像 -->
                    <img src="{{ Auth::user()->avatar }}" width="36px" height="36px" alt="小头像"/>
                </a>
                <div class="profile">
                    <div class="profile-image">
                        <!--大头像 -->
                        <a href="{{ route('user_me') }}">
                            <img src="{{ Auth::user()->avatar }}" height="100px" width="100px" alt="大头像"/>
                        </a>

                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">{{ Auth::user()->name }}</div>
                        <div class="profile-data-title">
                            @switch(Auth::user()->grade)
                                @case(1)
                                    学生
                                    @break
                                @case(2)
                                    宿舍管理员
                                    @break
                                @case(3)
                                    宿舍楼管理员
                                    @break
                                @case(4)
                                    区域管理员
                                    @break
                                @case(5)
                                    顶级管理员
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </li>
            <li class="xn-title">学生可操作</li>
            <li class="active">
                <a href="{{ Route('/') }}"><span class="fa fa-desktop"></span> <span class="xn-text">首页</span></a>
            </li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-search"></span> <span class="xn-text">信息查询</span></a>
                <ul>
                    <li><a href="{{ route('student_show') }}"><span class="fa fa-user"></span> 学生信息查询</a></li>
                    <li><a href="{{ route('student_dormitory_show') }}"><span class="fa fa-users"></span> 宿舍学生信息查询</a></li>
                @if(Auth::user()->grade >= 2)
                    <li><a href="{{ route('student_building_show') }}"><span class="fa fa-users"></span> 宿舍楼学生信息查询</a></li>
                    <li><a href="{{ route('student_class_show') }}"><span class="fa fa-users"></span> 班级学生信息查询</a></li>
                    <li><a href="/student/collegeSearch"><span class="fa fa-users"></span> 专业学生信息查询</a></li>
                @endif
                </ul>
            </li>
            @if(Auth::user()->grade == 1)
            <li class="xn-openable">
                <a href="#"><span class="fa fa-calendar"></span> <span class="xn-text">宿舍调换</span></a>
                <ul>
                    <li><a href="{{ route('adjust_application') }}"><span class="fa fa-pencil-square-o"></span> 调宿申请</a></li>
                    <li><a href="{{ route('adjust_my_list') }}"><span class="fa fa-list-ul"></span> 我的调宿申请</a></li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->grade >= 2)
            <li class="xn-title">管理员可操作</li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-tasks"></span> <span class="xn-text">学生录入</span></a>
                <ul>
                    <li><a href="{{ route('single_entry') }}"><span class="fa fa-pencil"></span> 单个录入</a></li>
                    <li><a href="{{ route('batch_entry') }}"><span class="fa fa-pencil-square-o"></span> 批量录入</a></li>
                </ul>
            </li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-calendar"></span> <span class="xn-text">宿舍管理</span></a>
                <ul>
                    <li><a href="{{ route('student_sort') }}"><span class="fa fa-random"></span> 新生智能排宿</a></li>
                    <li>
                        <a href="#"><span class="fa fa-refresh"></span> 宿舍调换</a>
                        @if($count = \App\Models\Movestudent::where('state',2)->count())
                        <div class="informer informer-danger">新</div>
                        @endif
                        <ul>
                            <li>
                                <a href="{{ route('adjust_list') }}"><span class="fa fa-align-justify"></span> 学生调换申请</a>
                                @if($count)
                                <div class="informer informer-danger">{{ $count }}</div>
                                @endif
                            </li>
                            <li><a href="{{ route('adjust_init') }}"><span class="fa fa-expand"></span> 调宿操作</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('dormitory_student_del') }}"><span class="fa fa-arrow-right"></span> 退宿操作</a></li>
                    <li>
                        <a href="#"><span class="fa fa-tasks"></span> 登记备案</a>
                        <ul>
                            <li><a href="{{ route('filing_list') }}"><span class="fa fa-align-justify"></span> 查询/查看备案</a></li>
                            <li><a href="{{ route('filing_leave') }}"><span class="fa fa-th-large"></span> 离校填写表</a></li>
                            <li><a href="{{ route('filing_defer') }}"><span class="fa fa-table"></span> 住宿填写表</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-gears"></span> <span class="xn-text">管理员账号管理</span></a>
                <ul>
                    <li><a href="{{ route('admin_list') }}"><span class="fa fa-align-justify"></span> 账号列表</a></li>
                    <li><a href="{{ route('admin_create') }}"><span class="fa fa-eraser"></span> 创建新账号</a></li>
                </ul>
            </li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-building-o"></span> <span class="xn-text">宿舍楼管理</span></a>
                <ul>
                    <li><a href="{{ route('building_list') }}"><span class="fa fa-bars"></span>宿舍楼列表</a></li>
                    <li><a href="{{ route('building_create') }}"><span class="fa fa-home"></span>添加宿舍楼</a></li>
                </ul>
            </li>
            @if(Auth::check())
                @if(Auth::user()->grade >= 5)
            <li class="xn-openable">
                <a href="#"><span class="fa fa-tags"></span> <span class="xn-text">专业代码管理</span></a>
                <ul>
                    <li><a href="{{ route('profession_create') }}"><span class="fa fa-pencil-square"></span>专业代码添加</a></li>
                    <li><a href="{{ route('profession_list') }}"><span class="fa fa-bars"></span>专业代码列表</a></li>
                </ul>
            </li>
                @endif
            @endif
                <li class="">
                    <a href="{{ route('send_notice') }}"><span class="fa fa-comment-o"></span> <span class="xn-text">发送通知</span></a>
                </li>
                @endif
        </ul>
        <!-- END X-NAVIGATION -->
    </div>
    <!-- END PAGE SIDEBAR -->

    <!-- 主体内容 -->
    <div class="page-content">

        <!-- 导航栏 -->
        <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
            <!-- 侧边栏缩小按钮 -->
            <li class="xn-icon-button">
                <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
            </li>
            <li class="center-block" style="padding-left:710px;color:#ffffff;font-size: 25px;height: 50px;line-height: 50px">学生宿舍管理系统</li>
            <!-- END 侧边栏缩小按钮-->


            <!-- 注销按钮 -->
            <li class="xn-icon-button pull-right">
                <a class="mb-control" data-box="#mb-signout" onclick="logout()"><span class="fa fa-sign-out"></span></a>
            </li>
            <script>
                function logout() {
                    var r=confirm("确定退出登陆？继续请按确定键，返回按取消。")
                    if (r==true)
                    {
                        window.location.href="{{ route('logout') }}";
                    }
                }
            </script>
            <!-- 注销按钮END -->
            <!-- 消息按钮 -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-comments"></span></a>
                @if(Auth::user()->unreadNotifications->count() > 0)
                <div class="informer informer-danger">{{ Auth::user()->unreadNotifications->count() }}</div>
                @endif
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="fa fa-comments"></span> 未读通知</h3>
                        <div class="pull-right">
                            <span class="label label-danger">{{ Auth::user()->unreadNotifications->count() }}条新通知</span>
                        </div>
                    </div>
                    <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                        @foreach(Auth::user()->unreadNotifications as $notification)
                        <a href="{{ route('notification_show',$notification->id) }}" class="list-group-item">
                            <div class="list-group-status status-online"></div>
                            <span class="contacts-title">{{ $notification->data['title'] }}</span>
                            <p>{{ str_limit($notification->data['content'],50).'...' }} </p>
                        </a>
                        @endforeach
                    </div>
                    <div class="panel-footer text-center">
                        <a href="{{ route('notification_list') }}">显示全部通知</a>
                    </div>
                </div>
            </li>
            <!-- END 消息按钮 -->

        </ul>
        <!-- END 导航栏 -->

        <!-- START 路径导航 -->
        @section('navigation')

        @show

        <!-- END 路径导航 -->
            @section('body')

            @show

        @if (count($errors) > 0)
            <div class="alert alert-danger" style="text-align: center;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(session()->has($msg))
                <div class="flash-message" style="text-align: center;">
                    <div class="alert alert-{{ $msg }}">
                        <ul >
                            <li>{{ session()->get($msg) }}</li>
                        </ul>
                    </div>

                </div>
        @endif
    @endforeach
    <!-- END 主体内容 -->



        <!-- START 脚本 -->

        <!-- START 框架 -->
        <script type="text/javascript" src="/js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/plugins/bootstrap/bootstrap.min.js"></script>

        <!-- END 框架 -->

        <!-- START THIS PAGE PLUGINS-->




        <script type="text/javascript" src="/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="/js/plugins/fileinput/fileinput.min.js"></script>

        <script type="text/javascript" src="/js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="/js/plugins/morris/morris.min.js"></script>

        <script type='text/javascript' src='/js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="/js/plugins/bootstrap/bootstrap-datepicker.js"></script>





        <!-- END THIS PAGE PLUGINS-->

        <!-- START TEMPLATE -->

        <script type="text/javascript" src="/js/plugins.js"></script>
        <script type="text/javascript" src="/js/actions.js"></script>

    @section('script')

    @show
        <!-- END TEMPLATE -->
        <!-- END 脚本 -->
    </div>
</div>
</body>
</html>






