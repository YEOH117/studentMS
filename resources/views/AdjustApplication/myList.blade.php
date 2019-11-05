@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">信息查询</a></li>
        <li class="active">班级学生信息详情页</li>
    </ul>
@endsection

@section('body')
    <div class="content-frame-body" style="padding: 0px 10px;">

        <div class="panel panel-default">
            <div class="panel-heading row">
            </div>
                <div class="panel-body mail" >
                    <div class="col-md-10 col-md-offset-1">
                        <div class="mail-item mail-unread  row bg-success">
                            <div class="mail-checkbox "></div>
                            <div class="col-md-1 ">申请人姓名</div>
                            <div class="col-md-1">申请人学号</div>
                            <div class="col-md-1">申请人专业</div>
                            <div class="col-md-1">申请人宿舍</div>
                            <div class="col-md-1">被申请人姓名</div>
                            <div class="col-md-1">被申请人学号</div>
                            <div class="col-md-1">被申请人专业</div>
                            <div class="col-md-1">被申请人宿舍</div>
                            <div class="col-md-2">申请进度</div>
                            <div class="col-md-1">操作</div>
                        </div>
                        @foreach($user as $key => $value)
                            <div class="mail-item mail-unread  row">
                                <div class="col-md-1 ">{{ $value['name'] }}</div>

                                <div class="col-md-1">{{ $value['the_student_id'] }}</div>

                                <div class="col-md-1">{{ $value['profession']}}</div>

                                <div class="col-md-1">
                                    @if($value['area'] > 0)
                                        西
                                    @else
                                        东
                                    @endif
                                        {{ $value['building'] }}栋 {{ $value['house_num'] }}宿舍
                                </div>

                                <div class="col-md-1">{{ $target[$key]['name'] }}</div>
                                <div class="col-md-1">{{ $target[$key]['the_student_id'] }}班</div>
                                <div class="col-md-1">{{ $target[$key]['profession'] }}班</div>
                                <div class="col-md-1">
                                    @if($target[$key]['area'] > 0)
                                        西
                                    @else
                                        东
                                    @endif
                                    {{ $target[$key]['building'] }}栋 {{ $target[$key]['house_num'] }}宿舍
                                </div>
                                <div class="col-md-2">{{ $info->state}}</div>
                                <div class="col-md-1"><a class="btn btn-danger">删除</a></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer">


                    <ul class="pagination pagination-sm ">

                    </ul>
                </div>
        </div>

    </div>
@endsection