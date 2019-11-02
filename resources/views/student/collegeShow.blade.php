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
                <label class="check mail-checkall">
                    <input type="checkbox" class="icheckbox col-md-1"/>
                </label>
                <label>全选</label>
            </div>
            <form action="{{ route('student_export') }}" method="post">
                {{ csrf_field() }}
                <div class="panel-body mail" >
                    <div class="mail-item mail-unread  row bg-success">
                        <div class="mail-checkbox "></div>
                        <div class="col-md-1 ">姓名</div>
                        <div class="col-md-1">学号</div>
                        <div class="col-md-1">性别</div>
                        <div class="col-md-2">学院</div>
                        <div class="col-md-1">专业</div>
                        <div class="col-md-1">班级</div>
                        <div class="col-md-1">宿舍</div>
                        <div class="col-md-1">手机号</div>
                        <div class="col-md-2">邮箱</div>
                    </div>
                    @foreach($student as $value)
                        <div class="mail-item mail-unread  row">

                            <div class="mail-checkbox ">
                                <input type="checkbox" name="single[]" value="{{ $value->id }}" class="icheckbox"/>
                            </div>

                            <div class="col-md-1 ">{{ $value->name }}</div>

                            <div class="col-md-1">{{ $value->the_student_id }}</div>

                            <div class="col-md-1">{{ $value->sex }}</div>

                            <div class="col-md-2">{{ $value->college }}</div>

                            <div class="col-md-1">{{ $value->profession }}</div>
                            <div class="col-md-1">{{ $value->class }}班</div>

                            <div class="col-md-1">
                                @if($value['area'] > 0)
                                    西
                                @else
                                    东
                                @endif
                                {{ $value['building'] }}栋 {{ $value['house_num'] }}宿舍
                            </div>
                            <div class="col-md-1">{{ $value->phone }}</div>
                            <div class="col-md-2">{{ $value->email }}</div>
                        </div>
                    @endforeach

                </div>
                <div class="panel-footer">


                    <ul class="pagination pagination-sm ">

                    </ul>
                    @if(Auth::user()->grade >= 2)
                        <button type="submit" class="btn btn-info pull-right">导出选中</button>
                    @endif
                </div>
            </form>
        </div>

    </div>
@endsection