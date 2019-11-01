@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">管理员账号管理</a></li>
        <li class="active">创建新账号</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <form class="form-horizontal" action="{{ route('admin_create') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>创建新账号</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">姓名</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                <input type="text" name="name" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">账号</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-bookmark"></span></span>
                                                <input type="text" name="account" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">邮箱</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                                <input type="email" name="email" class="form-control"/>
                                            </div>
                                            <span class="help-block">选填！</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">手机</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-mobile"></span></span>
                                                <input type="text" name="phone" class="form-control"/>
                                            </div>
                                            <span class="help-block">选填！</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">账号等级</label>
                                        <div class="col-md-6 col-xs-12">
                                            <select  class="form-control" name="grade">
                                                @if(Auth::check() && Auth::user()->grade >=3)
                                                @switch(Auth::user()->grade)
                                                    @case(3)
                                                        <option value="2">宿舍管理员</option>
                                                        @break
                                                    @case(4)
                                                        <option value="2">宿舍管理员</option>
                                                        <option value="3">大楼管理员</option>
                                                        @break
                                                    @case(5)
                                                        <option value="2">宿舍管理员</option>
                                                        <option value="3">大楼管理员</option>
                                                        <option value="4">区域管理员</option>
                                                        @break;
                                                    @default
                                                        @break
                                                @endswitch
                                                @endif
                                            </select>
                                            <span class="help-block">必选！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">密码</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                                <input type="password" name="password" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">重复密码</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                                <input type="password" name="password_confirmation" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">验证码</label>
                                        <div class="col-md-3 col-xs-6">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-picture-o"></span></span>
                                                <input type="text" name="verification" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                            <img src="{{ captcha_src() }}" onclick="this.src='{{captcha_src()}}'+Math.random()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">创建</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection