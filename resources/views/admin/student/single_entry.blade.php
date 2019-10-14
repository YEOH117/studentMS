@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">学生录入</a></li>
        <li class="active">单个录入</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <form class="form-horizontal" action="{{ route('single_entry') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>单个录入</strong></h3>
                        </div>
                        <div class="panel-body ">
                            <p>建议使用文件批量录入，此功能再用来补录</p>
                        </div>
                        <div class="panel-body">

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
                                <label class="col-md-3 col-xs-12 control-label">学号</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-bookmark"></span></span>
                                        <input type="text" name="the_student_id" class="form-control"/>
                                    </div>
                                    <span class="help-block">必填！</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">性别</label>
                                <div class="col-md-6 col-xs-12">
                                    <label class="radio-inline">
                                        <input type="radio" name="sex"  value="1"> 男
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="sex"  value="2"> 女
                                    </label>
                                    <span class="help-block">必填！</span>
                                </div>
                            </div>











                        </div>
                        <div class="panel-footer">
                            <a href="{{ route('single_entry') }}"><button class="btn btn-default">清除输入</button></a>
                            <button class="btn btn-primary pull-right">提交</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection