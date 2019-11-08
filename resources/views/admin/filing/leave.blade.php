@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍管理</a></li>
        <li><a href="#">登记备案</a></li>
        <li class="active">学生离校登记</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form id="form" class="form-horizontal" action="{{ route('filing_store') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="sort" value="0">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>学生离校登记</strong></h3>
                        </div>
                        <div class="panel-body ">
                            <p><span class="fa fa-exclamation-circle"></span>备案记录后无法修改，请认真填写！</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
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
                                        <label class="col-md-3 col-xs-12 control-label">备注</label>
                                        <div class="col-md-6 col-xs-12">
                                            <textarea name="info" class="form-control" rows="4"></textarea>
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
                            <button class="btn btn-primary pull-right" type="submit">登记</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection