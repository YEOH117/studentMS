@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li class="active">个人资料详情</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >


                    <div class="panel panel-default">
                        <div class="panel-heading">

                        </div>
                        <div class="panel-body">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="col-md-7">
                                    <h3 class="panel-title"><span class="fa fa-edit"></span><strong>个人资料</strong></h3>
                                    <div class="col-md-12" style="padding: 50px;">
                                        <form class="form-horizontal" action="{{ route('user_avatar') }}" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label >头像上传：</label>
                                                <input type="file" name="upfile" multiple class="file" data-preview-file-type="any"/>
                                            </div>
                                        </form>
                                        @if(empty($student) == false)
                                        <form class="form-horizontal" action="{{ route('user_me') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label >姓名：</label>
                                                <input type="text" class="form-control"  placeholder="{{ $student->name }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label >学号：</label>
                                                <input type="text" class="form-control" placeholder="{{ $student->the_student_id }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label >性别：</label>
                                                <input type="text" class="form-control" placeholder="{{ $student->sex }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label >学院：</label>
                                                <input type="text" class="form-control" placeholder="{{ $student->college }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label >专业：</label>
                                                <input type="text" class="form-control" placeholder="{{ $student->profession }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label >班级：</label>
                                                <input type="text" class="form-control" placeholder="{{ $student->class }}班" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label >联系邮箱：</label>
                                                <input type="text" name="email" class="form-control" value="{{ $student->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label >联系手机：</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $student->phone }}">
                                            </div>
                                            <div class="col-md-12" style="text-align: center">
                                                <button type="submit" class="btn btn-info">保存修改</button>
                                            </div>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h3 class="panel-title"><span class="fa fa-shield"></span><strong>密保工具</strong></h3>
                                    <div class="col-md-12" style="padding: 50px 0px 50px 50px;font-size: 15px;">
                                        <div class="col-md-12" style="margin: 5px 0;">
                                            <label>密保邮箱：</label>{{ $user->email }}<a href="{{ route('user_email') }}">点击修改</a>
                                        </div>
                                        <div class="col-md-12" style="margin: 5px 0;">
                                            <label>密保手机：</label>{{ $user->phone }}<a href="#">点击修改</a>
                                        </div>
                                        <div class="col-md-12" style="margin: 5px 0;">
                                            <label>修改密码：</label><a href="{{ route('user_password') }}">点击修改</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                        </div>

                    </div>

            </div>
        </div>
    </div>
@endsection