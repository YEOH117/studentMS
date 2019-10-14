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
    <link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
    @section('css')

    @show
    <!-- EOF CSS INCLUDE -->
</head>
<body>
<!-- START PAGE CONTAINER -->
<div class="page-container">

    <!-- START PAGE SIDEBAR -->
    <div class="page-sidebar">
        <!-- START X-NAVIGATION -->
        <ul class="x-navigation">
            <li class="xn-logo">
                <a href="index.html">北部湾大学</a>
                <a href="#" class="x-navigation-control"></a>
            </li>
            <li class="xn-profile">
                <a href="#" class="profile-mini">
                    <!--小头像 -->
                    <img src="assets/images/users/avatar.jpg" alt="小头像"/>
                </a>
                <div class="profile">
                    <div class="profile-image">
                        <!--大头像 -->
                        <img src="assets/images/users/avatar.jpg" alt="大头像"/>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">用户名</div>
                        <div class="profile-data-title">身份</div>
                    </div>
                    <div class="profile-controls">
                        <a href="pages-profile.html" class="profile-control-left"><span class="fa fa-info"></span></a>
                        <a href="pages-messages.html" class="profile-control-right"><span class="fa fa-envelope"></span></a>
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
                    <li><a href="#"><span class="fa fa-user"></span> 学生信息查询</a></li>
                    <li><a href="#"><span class="fa fa-users"></span> 宿舍信息查询</a></li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-clock-o"></span> 预留部分</a>
                        <ul>
                            <li><a href="pages-timeline.html"><span class="fa fa-align-center"></span> Default</a></li>
                            <li><a href="pages-timeline-simple.html"><span class="fa fa-align-justify"></span> Full Width</a></li>
                        </ul>
                    </li>




                </ul>
            </li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-calendar"></span> <span class="xn-text">宿舍调换</span></a>
                <ul>
                    <li><a href="#"><span class="fa fa-pencil-square-o"></span> 调宿申请</a></li>
                    <li><a href="#"><span class="fa fa-list-ul"></span> 我的调宿申请</a></li>
                </ul>
            </li>
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
                    <li><a href="#"><span class="fa fa-random"></span> 新生智能排宿</a></li>
                    <li>
                        <a href="#"><span class="fa fa-refresh"></span> 宿舍调换</a>
                        <div class="informer informer-danger">新</div>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-align-justify"></span> 学生调换申请</a>
                                <div class="informer informer-danger">4</div>
                            </li>
                            <li><a href="#"><span class="fa fa-expand"></span> 调宿操作</a></li>
                            <li><a href="#"><span class="fa fa-table"></span> 宿舍迁移</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><span class="fa fa-arrow-right"></span> 退宿操作</a></li>
                    <li>
                        <a href="#"><span class="fa fa-tasks"></span> 登记备案</a>
                        <ul>
                            <li><a href="#"><span class="fa fa-align-justify"></span> 查询/查看备案</a></li>
                            <li><a href="#"><span class="fa fa-th-large"></span> 离校填写表</a></li>
                            <li><a href="#"><span class="fa fa-table"></span> 住宿填写表</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="xn-openable">
                <a href="tables.html"><span class="fa fa-gears"></span> <span class="xn-text">管理员账号管理</span></a>
                <ul>
                    <li><a href="#"><span class="fa fa-align-justify"></span> 账号列表</a></li>
                    <li><a href="#"><span class="fa fa-eraser"></span> 创建新账号</a></li>
                </ul>
            </li>
            <li class="xn-openable">
                <a href="#"><span class="fa fa-building-o"></span> <span class="xn-text">宿舍楼管理</span></a>
                <ul>
                    <li><a href="#"><span class="fa fa-home"></span><span class="xn-text">宿舍楼属性设置</span></a></li>
                </ul>
            </li>
            @if(Auth::check())
                @if(Auth::user()->grade >= 5)
            <li class="xn-openable">
                <a href="#"><span class="fa fa-tags"></span> <span class="xn-text">专业代码管理</span></a>
                <ul>
                    <li><a href="#"><span class="fa fa-pencil-square"></span><span class="xn-text">专业代码添加</span></a></li>
                    <li><a href="#"><span class="fa fa-bars"></span><span class="xn-text">专业代码列表</span></a></li>
                </ul>
            </li>
                @endif
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
            <li style="padding-left:600px;color:#ffffff;font-size: 25px;height: 50px;line-height: 50px">学生宿舍管理系统</li>
            <!-- END 侧边栏缩小按钮-->


            <!-- 注销按钮 -->
            <li class="xn-icon-button pull-right">
                <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
            </li>
            <!-- 注销按钮END -->
            <!-- 消息按钮 -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-comments"></span></a>
                <div class="informer informer-danger">4</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="fa fa-comments"></span> Messages</h3>
                        <div class="pull-right">
                            <span class="label label-danger">4 new</span>
                        </div>
                    </div>
                    <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                        <a href="#" class="list-group-item">
                            <div class="list-group-status status-online"></div>
                            <img src="assets/images/users/user2.jpg" class="pull-left" alt="John Doe"/>
                            <span class="contacts-title">John Doe</span>
                            <p>Praesent placerat tellus id augue condimentum</p>
                        </a>
                        <a href="#" class="list-group-item">
                            <div class="list-group-status status-away"></div>
                            <img src="assets/images/users/user.jpg" class="pull-left" alt="Dmitry Ivaniuk"/>
                            <span class="contacts-title">Dmitry Ivaniuk</span>
                            <p>Donec risus sapien, sagittis et magna quis</p>
                        </a>
                        <a href="#" class="list-group-item">
                            <div class="list-group-status status-away"></div>
                            <img src="assets/images/users/user3.jpg" class="pull-left" alt="Nadia Ali"/>
                            <span class="contacts-title">Nadia Ali</span>
                            <p>Mauris vel eros ut nunc rhoncus cursus sed</p>
                        </a>
                        <a href="#" class="list-group-item">
                            <div class="list-group-status status-offline"></div>
                            <img src="assets/images/users/user6.jpg" class="pull-left" alt="Darth Vader"/>
                            <span class="contacts-title">Darth Vader</span>
                            <p>I want my money back!</p>
                        </a>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="pages-messages.html">Show all messages</a>
                    </div>
                </div>
            </li>
            <!-- END 消息按钮 -->
            <!-- 通知按钮 -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-tasks"></span></a>
                <div class="informer informer-warning">3</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="fa fa-tasks"></span> Tasks</h3>
                        <div class="pull-right">
                            <span class="label label-warning">3 active</span>
                        </div>
                    </div>
                    <div class="panel-body list-group scroll" style="height: 200px;">
                        <a class="list-group-item" href="#">
                            <strong>Phasellus augue arcu, elementum</strong>
                            <div class="progress progress-small progress-striped active">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">50%</div>
                            </div>
                            <small class="text-muted">John Doe, 25 Sep 2014 / 50%</small>
                        </a>
                        <a class="list-group-item" href="#">
                            <strong>Aenean ac cursus</strong>
                            <div class="progress progress-small progress-striped active">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">80%</div>
                            </div>
                            <small class="text-muted">Dmitry Ivaniuk, 24 Sep 2014 / 80%</small>
                        </a>
                        <a class="list-group-item" href="#">
                            <strong>Lorem ipsum dolor</strong>
                            <div class="progress progress-small progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%;">95%</div>
                            </div>
                            <small class="text-muted">John Doe, 23 Sep 2014 / 95%</small>
                        </a>
                        <a class="list-group-item" href="#">
                            <strong>Cras suscipit ac quam at tincidunt.</strong>
                            <div class="progress progress-small">
                                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                            </div>
                            <small class="text-muted">John Doe, 21 Sep 2014 /</small><small class="text-success"> Done</small>
                        </a>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="pages-tasks.html">Show all tasks</a>
                    </div>
                </div>
            </li>
            <!-- END 通知按钮 -->
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

        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->

        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="js/plugins/fileinput/fileinput.min.js"></script>











        <!-- END THIS PAGE PLUGINS-->

        <!-- START TEMPLATE -->


        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>


        <!-- END TEMPLATE -->
        <!-- END 脚本 -->
    </div>
</div>
</body>
</html>






