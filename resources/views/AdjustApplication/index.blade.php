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
                            <p><span class="fa fa-exclamation-circle"></span>先查询宿舍，才能进行调宿申请！</p>
                        </div>
                        <div class="panel-body row ">
                            <div class="col-md-6 col-md-offset-3 col-xs-12 form-inline">
                                <div class="col-md-2 col-xs-12">
                                    <div class="form-group">
                                        <label>区域</label>
                                        <select class="form-control" id="area">
                                            <option value="0">东区</option>
                                            <option value="1">西区</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label>宿舍楼</label>
                                        <input type="text" class="form-control" id="building" placeholder="请输入宿舍楼。">
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label>宿舍号</label>
                                        <input type="text" class="form-control" id="houseNum" placeholder="请输入宿舍号">
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 ">
                                    <a class="btn bg-primary pull-right" onclick="inquire()">查询</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3 col-xs-12"  style="background-color: #e8e8e8;margin-top:20px;border-radius:15px;">
                                <table class="table table-hover" id="info">
                                    <tr>
                                        <th class="col-md-3">姓名</th>
                                        <th class="col-md-3">学号</th>
                                        <th class="col-md-3">联系手机号</th>
                                        <th class="col-md-1">操作</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <script>
                            function inquire(){
                                var area = $('#area').val();
                                var building = $('#building').val();
                                var houseNum = $('#houseNum').val();
                                $('#info').html('<tr><th class="col-md-3">姓名</th><th class="col-md-3">学号</th><th class="col-md-3">联系手机号</th><th class="col-md-1">操作</th></tr>');
                                $.get("/ajax/"+area+'/'+building+'/'+houseNum,function(data){
                                    var info = JSON.parse(data);
                                    $('#info').append(info);
                                });
                            }
                            function one_confirm(hre)
                            {
                                var r=confirm("确定申请调宿？是否继续？")
                                if (r==true)
                                {
                                    window.location.href=hre;
                                }
                            }
                        </script>
                        <div class="panel-footer">

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection