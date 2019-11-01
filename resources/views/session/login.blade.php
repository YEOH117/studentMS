<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        
        <title>Joli Admin - Responsive Bootstrap Admin Template</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        
        
              
        <link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
                                          
    </head>
    <body>
        
        <div class="login-container">
        
            <div class="login-box animated fadeInDown">
                <div class="login-logo"></div>
                <div class="login-body">
                    <div class="login-title"><strong>欢迎登陆宿舍管理系统</strong></div>
                    <form action="{{ route('login') }}" class="form-horizontal" method="post">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" name="account" class="form-control" placeholder="请输入账号" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" name="password" class="form-control" placeholder="请输入密码"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <input type="text" name="verification" class="form-control" placeholder="验证码"/>
                        </div>
                        <div class="col-md-4" style="padding-left: 0;">
                            <img src="{{ captcha_src() }}" onclick="this.src='{{captcha_src()}}'+Math.random()">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" value="1">
                                    <font color="#fff">记住我</font>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-info btn-block">登陆</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="login-footer">
                    <div class="pull-left">
                        &copy; 2019 叶斌龙
                    </div>
                    <div class="pull-right">
                        <a href="#">忘记密码？点这</a>
                    </div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(session()->has($msg))
                        <div class="flash-message">
                            <div class="alert alert-{{ $msg }}">
                                <ul >
                                    <li>{{ session()->get($msg) }}</li>
                                </ul>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>

        </div>
        
    </body>
</html>






