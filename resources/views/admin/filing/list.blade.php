@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍管理</a></li>
        <li><a href="#">登记备案</a></li>
        <li class="active">登记备案列表</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <div class="form-horizontal" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>登记备案列表</strong></h3>
                        </div>
                        <div class="panel-body row ">
                            <div class="col-md-6 col-md-offset-3 col-xs-12 form-inline">
                                <div class="col-md-10 col-xs-12">
                                    <div class="form-group">
                                        <label>快速查找</label>
                                        <input type="text" class="form-control" id="student" placeholder="请输入要查询的学生学号。">
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 ">
                                    <a class="btn bg-primary pull-right" onclick="inquire()">查询</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3 col-xs-12"  style="background-color: #e8e8e8;margin-top:20px;border-radius:15px;">
                                <table class="table table-hover" id="info">
                                    <tr>
                                        <th class="col-md-3">学生</th>
                                        <th class="col-md-3">类型</th>
                                        <th class="col-md-3">日期</th>
                                        <th class="col-md-1">操作</th>
                                    </tr>
                                    @foreach($filing as $value)
                                        <tr>
                                            <td class="col-md-3">{{ $value->student->name }}</td>
                                            <td class="col-md-3">
                                                @if($value->sort)
                                                    住宿登记记录
                                                @else
                                                    离校登记记录
                                                @endif
                                            </td>
                                            <td class="col-md-3">{{ $value->created_at }}</td>
                                            <td class="col-md-1"><a href="{{ route('filing_show',$value->id) }}" class="btn btn-info">查看</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <script>
                            function inquire(){
                                var student = $('#student').val();
                                $('#info').html('<tr><th class="col-md-3">学生</th><th class="col-md-3">类型</th><th class="col-md-3">日期</th><th class="col-md-1">操作</th></tr>');
                                $.get("/filing/ajax/"+student,function(data){
                                    var info = JSON.parse(data);
                                    $('#info').append(info);
                                });
                            }
                        </script>
                        <div class="panel-footer">
                            {{ $filing->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection