@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">管理员账号管理</a></li>
        <li class="active">账号列表</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="height: 720px">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>管理员列表</strong></h3>
                    </div>
                    <div class="panel-body ">
                        <p><span class="fa fa-exclamation-circle"></span>操作不可逆，请谨慎操作！</p>
                    </div>
                    <div class="panel-body" style="padding:0 300px;font-size: 15px;">
                        <table class="table table-hover table-bordered">
                            <tr class="info">
                                <th>账号</th>
                                <th>名称</th>
                                <th>邮箱</th>
                                <th>电话</th>
                                <th>等级</th>
                                <th class="col-md-1">操作</th>
                            </tr>
                            @foreach($info as $value)
                            <tr>
                                <td>{{ $value->account }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->phone }}</td>
                                @switch($value->grade)
                                    @case(2)
                                        <td>宿舍管理员</td>
                                        @break
                                    @case(3)
                                        <td>大楼管理员</td>
                                        @break
                                    @case(4)
                                        <td>区域管理员</td>
                                        @break
                                    @case(5)
                                        <td>顶级管理员</td>
                                        @break
                                    @default
                                        <td></td>
                                @endswitch
                                <td>
                                    @if(Auth::user()->grade > $value->grade)
                                    <a class="btn btn-danger btn-block" onclick="del_confirm({{ $value->id }})">删除</a>
                                    @else
                                    <a class="btn btn-danger btn-block" disabled="disabled">删除</a>
                                    @endif
                                </td>
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
    <script type="text/javascript">
        function del_confirm(id)
        {
            var r=confirm("该操作会删除此管理员账号！是否继续？")
            if (r==true)
            {
                window.location.href="/admin/"+id+"/del";
            }
        }
    </script>
@endsection