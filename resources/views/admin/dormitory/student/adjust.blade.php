@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍调换</a></li>
        <li class="active">管理员初始化宿舍人员</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="{{ route('adjust_init') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>管理员初始化宿舍人员</strong></h3>
                        </div>
                        <div class="panel-body ">
                            <p><span class="fa fa-exclamation-circle"></span>此功能仅为了系统部署初期初始化学生住宿，后期不建议使用。使用请注意，操作不可逆！</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">区域</label>
                                        <div class="col-md-6 col-xs-12">
                                            <select  class="form-control" name="area">
                                                <option value="0">东区</option>
                                                <option value="1">西区</option>
                                            </select>
                                            <span class="help-block">必选！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">大楼</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-building-o"></span></span>
                                                <input type="text" name="building" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">宿舍</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-home"></span></span>
                                                <input type="text" name="dormitory" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">学生学号</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                <input type="text" name="student" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">验证码</label>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-picture-o"></span></span>
                                                <input type="text" name="verification" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <img src="{{ captcha_src() }}" onclick="this.src='{{captcha_src()}}'+Math.random()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">安排</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection