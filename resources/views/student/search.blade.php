@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">信息查询</a></li>
        <li class="active">学生信息查询</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="{{ route('student_show') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>学生信息查询</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">学号</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-building-o"></span></span>
                                                <input type="text" name="studentId" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！可填写多个，多个之间以<code>；</code>(分号)分隔。</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">名字</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-home"></span></span>
                                                <input type="text" name="name" class="form-control"/>
                                            </div>
                                            <span class="help-block">选填！</span>
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
                                            <img src="#" alt="验证码">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">查询</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection