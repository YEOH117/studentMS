<!DOCTYPE html>
<html lang="en" class="body-full-height">
<head>

    <title>北部湾大学-学生宿舍管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" id="theme" href="/css/theme-default.css"/>

</head>
<body>

<div class="login-container">

    <div class="login-box  ">
        <div class="login-logo"></div>
        <div class="login-body">
            <div class="login-title"><strong>重置密码</strong></div>
            <form action="{{ route('user_password_reseting') }}" class="form-horizontal" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="user" value="{{ $user->id }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" name="password" class="form-control" placeholder="请输入新密码"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="请再次输入新密码"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12" style="text-align: center">
                        <button class="btn btn-info btn-block">提交</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="login-footer">
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






