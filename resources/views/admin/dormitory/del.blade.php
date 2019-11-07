@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍管理</a></li>
        <li class="active">学生退宿</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form id="form" class="form-horizontal" action="{{ route('dormitory_student_del') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>学生退宿</strong></h3>
                        </div>
                        <div class="panel-body ">
                            <p><span class="fa fa-exclamation-circle"></span>操作不可逆，请谨慎操作！</p>
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
                            <a class="btn btn-primary pull-right" onclick="del_confirm()">退宿</a>
                        </div>
                        <script>
                            function del_confirm() {
                                var r=confirm("确定进行退宿操作？继续请按确定键，返回按取消。")
                                if (r==true)
                                {
                                    document.getElementById("form").submit();
                                }
                            }
                        </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection