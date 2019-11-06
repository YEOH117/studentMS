@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍管理</a></li>
        <li><a href="#">宿舍调换</a></li>
        <li class="active">调宿申请列表</li>
    </ul>
@endsection

@section('body')
    <div class="content-frame-body" style="padding: 0px 10px;">

        <div class="panel panel-default">
            <div class="panel-heading row">
                <label class="check mail-checkall">
                </label>
            </div>
                <div class="panel-body mail" style="font-size: 18px;line-height: 40px;">
                    <div class=" col-md-6 col-md-offset-3" style="background-color: #1caf9a;height: 40px;">
                        <div class="col-md-4 ">申请人</div>
                        <div class="col-md-4">被申请人</div>
                        <div class="col-md-4">操作</div>
                    </div>
                    @foreach($moveStudent as $value)
                    <div class=" col-md-6 col-md-offset-3" style="height: 40px;">
                        <div class="col-md-4 ">{{ $value['userName'] }}</div>
                        <div class="col-md-4">
                            @if(!empty($value['targetName']))
                            {{ $value['targetName'] }}
                            @endif
                        </div>
                        <div class="col-md-4">
                            <a href="@if(!empty($value['targetName'])){{ route('adjust_show',[$value->user_id,$value->token]) }}@else{{ route('adjust_show_none',[$value->user_id,$value->token]) }}@endif" class="btn btn-info">详请</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer">


                    <ul class="pagination pagination-sm ">

                    </ul>
                </div>
        </div>

    </div>
@endsection