@extends('common.father')

@section('css')

@endsection

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍楼管理</a></li>
        <li class="active">宿舍楼</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="height: 720px">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>宿舍楼</strong></h3>
                    </div>
                    <div class="panel-body ">
                        <p><span class="fa fa-exclamation-circle"></span>操作不可逆，请谨慎操作！</p>
                    </div>
                    <div class="panel-body" style="padding:0 400px;font-size: 15px;">
                        <table class="table  table-bordered ta">
                            <tr class="info">
                                <th style="text-align: center;" colspan="8">东区</th>
                            </tr>
                            <tr>
                            @foreach($area_0 as $key => $value)
                                <td  style="text-align: center;"><a class="btn" href="/building/show/{{ $value->id }}">东{{ $value->building }}栋</a></td>
                            @if((intval($key)+1) % 5 == 0 && $key > 0)
                            </tr>
                            <tr>
                            @endif
                            @endforeach
                            </tr>
                        </table>

                        <table class="table  table-bordered ta">
                            <tr class="info">
                                <th style="text-align: center;" colspan="8">西区</th>
                            </tr>
                            <tr>
                            @foreach($area_1 as $key => $value)
                                <td  style="text-align: center;"><a class="btn" href="/building/show/{{ $value->id }}">西{{ $value->building }}栋</a></td>
                            @if((intval($key)+1) % 5 == 0 && $key > 0)
                            </tr>
                            <tr>
                            @endif
                            @endforeach
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection