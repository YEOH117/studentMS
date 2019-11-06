@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍调换</a></li>
        <li class="active">我的调宿申请</li>
    </ul>
@endsection

@section('body')
    <div class="content-frame-body" style="padding: 0px 10px;">

        <div class="panel panel-default">
            <div class="panel-heading row">
            </div>
                <div class="panel-body" >
                    <div class="col-md-6 col-md-offset-3 col-xs-12"  style="background-color: #e8e8e8;margin-top:20px;border-radius:15px;">
                        <table class="table table-hover" >
                            <tr>
                                <th class="col-md-1">申请人</th>
                                <th class="col-md-2">被申请人<div class="pull-right">|对方:</div></th>
                                <th class="col-md-2">学号</th>
                                <th class="col-md-2">专业</th>
                                <th class="col-md-2">联系手机号</th>
                                <th class="col-md-2">状态</th>
                                <th class="col-md-1">操作</th>
                            </tr>
                            @if(empty($myInfo) == false)
                                <tr>
                                    <td class="col-md-1">{{ $myInfo['user'] }}</td>
                                    <td class="col-md-2">{{ $myInfo['target']->name }}</td>
                                    <td class="col-md-2">{{ $myInfo['target']->the_student_id }}</td>
                                    <td class="col-md-2">{{ $myInfo['target']->profession }}</td>
                                    <td class="col-md-2">{{ $myInfo['target']->phone }}</td>
                                    <td class="col-md-2"><label style="color: #CD5C5C;">{{ $myInfo['state'] }}</label></td>
                                    <td class="col-md-1"><a class="btn btn-danger" onclick="del_confirm()">删除此请求</a></td>
                                </tr>
                                <script>
                                    function del_confirm()
                                    {
                                        var r=confirm("确定删除调宿请求？是否继续？")
                                        if (r==true)
                                        {
                                            window.location.href="{{ route('adjust_del',$myInfo['movestudent']) }}";
                                        }
                                    }
                                </script>
                            @endif
                            @if(empty($otherInfo) == false)
                                @foreach($otherInfo as $value)
                                    <tr>
                                        <td class="col-md-1">{{ $value['target']->name }}</td>
                                        <td class="col-md-2">{{ $value['user'] }}</td>
                                        <td class="col-md-2">{{ $value['target']->the_student_id }}</td>
                                        <td class="col-md-2">{{ $value['target']->profession }}</td>
                                        <td class="col-md-2">{{ $value['target']->phone }}</td>
                                        <td class="col-md-2"><label style="color: #CD5C5C;">{{ $value['state'] }}</label></td>
                                        <td class="col-md-1"><a href="{{ route('adjust_answer',[$value['user_id'],$value['token']]) }}" class="btn btn-primary">详情</a></td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                <div class="panel-footer">


                    <ul class="pagination pagination-sm ">

                    </ul>
                </div>
        </div>

    </div>
@endsection