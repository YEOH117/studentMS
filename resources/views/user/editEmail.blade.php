@extends('common.father')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">用户密保设置</a></li>
        <li class="active">修改密保邮箱</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="{{ route('user_email_active') }}" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>修改密保邮箱</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label  class="col-md-3 col-xs-12 control-label">验证码</label>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="verify" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                        <a id="verify" class="btn btn-primary" onclick="getVerify()">获取验证码</a>
                                    </div>
                                    <script>
                                        function getVerify(){
                                            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                                            $.post("{{ route('user_email_code') }}");
                                            $('#verify').text('验证码已发送');
                                        }
                                    </script>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">现邮箱</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                                                <input type="email" name="email" class="form-control"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">设置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection