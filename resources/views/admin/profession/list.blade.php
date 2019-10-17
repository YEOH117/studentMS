@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">专业代码管理</a></li>
        <li class="active">专业代码列表</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="height: 720px">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>专业代码列表</strong></h3>
                    </div>
                    <div class="panel-body ">
                        <p><span class="fa fa-exclamation-circle"></span>操作不可逆，请谨慎操作！</p>
                    </div>
                    <div class="panel-body" style="padding:0 300px;font-size: 15px;">
                        <table class="table table-hover table-bordered">
                            <tr class="info">
                                <th>学院</th>
                                <th>专业</th>
                                <th>代码</th>
                                <th class="col-md-1">操作</th>
                            </tr>
                            @foreach($info as $value)
                            <tr>
                                <td>{{ $value->college }}</td>
                                <td>{{ $value->profession }}</td>
                                <td>{{ $value->code }}</td>
                                @if(Auth::user()->grade <5)
                                <td><a class="btn btn-info btn-block" disabled="disabled">修改</a></td>
                                @else
                                <td><a class="btn btn-info btn-block" href="/profession/{{ $value->id }}/edit">修改</a></td>
                                @endif
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="" style="display: table;width: auto;margin-left: auto;margin-right: auto" >
                        {{ $info->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection