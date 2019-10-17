@extends('common.father')

@section('css')
    <style type="text/css">
        .title{
            font-size: 24px;
        }
        li{
            list-style-type:none;
            padding:0;
        }
        ul{
            padding: 0;
        }
    </style>
@endsection

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍楼管理</a></li>
        <li class="active">宿舍楼详情</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>宿舍楼详情</strong></h3>
                    </div>
                    <div class="panel-body ">
                        <p><span class="fa fa-exclamation-circle"></span>操作不可逆，请谨慎操作！</p>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div id="morris-donut" style="height: 300px;"></div>
                            </div>
                            <div class="col-md-4 title">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>大楼：</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <code>
                                            @if($building->area >= 1)
                                            西
                                            @else
                                            东
                                            @endif
                                            {{ $building->building }}栋
                                        </code>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>层数：</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <code>{{ $building->layers }}层</code>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>每层宿舍：</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <code>{{ $building->layer_roon_num }}间</code>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>男/女宿舍：</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <code>
                                            @if($building->sex >= 1)
                                                女生
                                            @else
                                                男生
                                            @endif
                                            宿舍
                                        </code>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>宿舍偏好：</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <ul>
                                            @foreach($code as $value)
                                            <li style="font-size: 18px;color: #8BAA4A;"><em>{{ $value }}</em></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <a class="btn btn-info" href="/building/{{ $building->id }}/edit">修改</a>
                        <a class="btn btn-danger pull-right" onclick="del_confirm({{ $building->id }})">删除</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function del_confirm(id)
        {
            var r=confirm("该操作会删除此此大楼所有信息！是否继续？")
            if (r==true)
            {
                window.location.href="/building/"+id+"/del";
            }
        }
    </script>
@endsection

@section('script')
    <script>
        var morrisCharts = function() {


            Morris.Donut({
                element: 'morris-donut',
                data: [
                    {label: "已入住的宿舍", value: {{ $num }} },
                    {label: "尚闲置的宿舍", value: {{ $building->empty_num }} },
                ],
                colors: ['#B22222', '#00FF00']
            });

        }();
    </script>

@endsection