@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍调换</a></li>
        <li class="active">调宿申请</li>
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
                            <h3 class="panel-title"><strong>调宿申请</strong></h3>
                        </div>
                        <div class="panel-body ">
                            <p><span class="fa fa-exclamation-circle"></span>先查询目标同学，才能进行调宿申请！</p>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">目标同学的学号</label>
                                <div class="col-md-4 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                        <input type="text" id="studentId" class="form-control"/>
                                    </div>
                                    <span class="help-block">必填！</span>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <button class="btn bg-info" onclick="search()">查询</button>
                                </div>
                                <script>
                                    var hre = "#";
                                    function search(){
                                        var studentId = $('#studentId').val();
                                        $('#student').html('<p><label>我的信息</label></p>');
                                        $.get("/ajax/"+studentId,function(data){
                                            var info = JSON.parse(data);
                                            $('#student').append(info);
                                            hre = "/dormitory/adjustApplication/" + studentId;
                                        });
                                    }
                                    function del_confirm()
                                    {
                                        var r=confirm("确定申请调宿？是否继续？")
                                        if (r==true)
                                        {
                                            window.location.href=hre;
                                        }
                                    }
                                </script>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-md-offset-3" style="border:1px dashed #E9967A;height: 230px;">
                                    <div id="student">
                                        <p><label>目标同学信息</label></p>
                                    </div>
                                </div>
                                <div class="col-md-2" style="text-align: center;padding-top: 100px;">
                                    <a  class="btn bg-primary" onclick="del_confirm()">申请调宿</a>
                                </div>
                                <div class="col-md-2" style="border:1px solid #E5E5E5;height: 230px;">
                                        <p><label>我的信息</label></p>
                                        <p>姓名：<code>{{ $info->name }}</code></p>
                                        <p>学院：<code>{{ $info->college }}</code></p>
                                        <p>专业：<code>{{ $info->profession }}</code></p>
                                        <p>班级：<code>{{ $info->class }}班</code></p>
                                        <p>联系邮箱：<code>{{ $info->email }}</code></p>
                                        <p>联系手机号：<code>{{ $info->phone }}</code></p>
                                        <p>所在宿舍：
                                            <code>
                                                @if($building->area)
                                                    西
                                                @else
                                                    东
                                                @endif
                                                {{ $building->building }}栋
                                                {{ $dormitory->house_num }}宿舍
                                            </code>
                                        </p>

                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection