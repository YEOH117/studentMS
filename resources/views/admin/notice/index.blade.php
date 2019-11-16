@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li class="active">发送通知</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="{{ route('send_notice') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>发送通知</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">选择发送人群</label>
                                        <div class="col-md-6 col-xs-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="people"value="1" onclick="entire()"> 全体学生
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="people" value="2" onclick="part()"> 部分学生
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="people" value="3" onclick="individual()"> 个别学生
                                            </label>
                                            <span class="help-block">必选！</span>
                                        </div>
                                    </div>
                                    <div id="content">

                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">通知内容</label>
                                        <div class="col-md-6 col-xs-12">
                                            <textarea name="notification" class="form-control" rows="4"></textarea>
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
                        <script type="text/javascript" src="/js/send_notice.js"></script>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">发送</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection