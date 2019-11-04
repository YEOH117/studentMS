@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">调宿申请</a></li>
        <li class="active">申请处理</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>申请处理</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-offset-3 col-md-6">

                            <div class="col-md-12" style="word-wrap:break-word;padding: 20px 10px;font-size: 20px;margin: 10px;background-color: #F5F5F5;width: 800px;height: 400px;border-radius: 10px;">
                                <div class="col-md-12" style="text-align: center;">
                                    <h1><strong>{{ $info }}</strong></h1>
                                </div>
                                <div class="col-md-12" style="margin-top: 50px;">
                                    <div class="col-md-6" style="text-align: center;">
                                        <a class="btn btn-lg bg-primary" onclick="yes_confirm()">同意</a>
                                    </div>
                                    <div class="col-md-6" style="text-align: center;">
                                        <a href="{{ route('adjust_reply',[$moveStudent,$token,0]) }}" class="btn btn-lg bg-danger" onclick="no_confirm()">拒绝</a>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function yes_confirm() {
                                    var r=confirm("确定同意申请？继续请按确定键，返回按取消。")
                                    if (r==true)
                                    {
                                        window.location.href="{{ route('adjust_reply',[$moveStudent,$token,1]) }}";
                                    }
                                }
                                function no_confirm() {
                                    var r=confirm("确定拒绝申请？继续请按确定键，返回按取消。")
                                    if (r==true)
                                    {
                                        window.location.href="{{ route('adjust_reply',[$moveStudent,$token,0]) }}";
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection