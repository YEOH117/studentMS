@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍调换</a></li>
        <li class="active">调宿申请详情</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <div class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>调宿申请详情</strong></h3>
                        </div>
                        <div class="panel-body" style="font-size: 18px;">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="col-md-5">
                                    <p><label>申请人信息</label></p>
                                    <p>姓名：{{ $userInfo['name'] }}</p>
                                    <p>学院：{{ $userInfo['college'] }}</p>
                                    <p>专业：{{ $userInfo['profession'] }}</p>
                                    <p>班级：{{ $userInfo['class'] }}班</p>
                                    <p>联系邮箱：{{ $userInfo['email'] }}</p>
                                    <p>联系手机号：{{ $userInfo['phone'] }}</p>
                                    <p>
                                        所在宿舍：

                                            @if($userInfo['area'])
                                                西
                                            @else
                                                东
                                            @endif
                                            {{ $userInfo['building'] }}栋
                                            {{ $userInfo['houseNum'] }}宿舍

                                    </p>
                                </div>
                                <div class="col-md-5 col-md-offset-2">
                                    <p><label>被申请人信息</label></p>
                                    <p>姓名：{{ $targetInfo['name'] }}</p>
                                    <p>学院：{{ $targetInfo['college'] }}</p>
                                    <p>专业：{{ $targetInfo['profession'] }}</p>
                                    <p>班级：{{ $targetInfo['class'] }}班</p>
                                    <p>联系邮箱：{{ $targetInfo['email'] }}</p>
                                    <p>联系手机号：{{ $targetInfo['phone'] }}</p>
                                    <p>
                                        所在宿舍：
                                            @if($targetInfo['area'])
                                                西
                                            @else
                                                东
                                            @endif
                                            {{ $targetInfo['building'] }}栋
                                            {{ $targetInfo['houseNum'] }}宿舍
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="col-md-4 col-md-offset-4">
                                <div class="col-md-6">
                                    <a class="btn bg-primary" onclick="yes_confirm()">同意</a>
                                </div>
                                <div class="col-md-6 ">
                                    <a class="btn bg-danger" onclick="no_confirm()">拒绝</a>
                                </div>
                            </div>
                        </div>
                        <script>
                            function yes_confirm() {
                                var r=confirm("确定同意申请？继续请按确定键，返回按取消。")
                                if (r==true)
                                {
                                    window.location.href="{{ route('adjust_process',[$moveStudent,$token,1]) }}";
                                }
                            }
                            function no_confirm() {
                                var r=confirm("确定拒绝申请？继续请按确定键，返回按取消。")
                                if (r==true)
                                {
                                    window.location.href="{{ route('adjust_process',[$moveStudent,$token,0]) }}";
                                }
                            }
                        </script>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection