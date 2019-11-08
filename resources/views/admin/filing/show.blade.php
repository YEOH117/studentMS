@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">登记备案</a></li>
        <li class="active">登记备案记录详情</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="col-md-12" style="padding:10px;font-size: 10px;margin: 10px;background-color: #e6e6e6;border-radius: 10px;">
                                <div class="col-md-6">
                                    <span class="fa fa-user"></span><strong>{{ $filing->student->name }}</strong>
                                </div>
                                <div class="pull-right">
                                    <span class="fa fa-clock-o"></span><strong>{{ $filing->created_at }}</strong>
                                </div>

                            </div>
                            <div class="col-md-12" style="word-wrap:break-word;padding: 20px 10px;font-size: 20px;margin: 10px;background-color: #B0E0E6;width: 800px;height: 400px;border-radius: 10px;">
                                &emsp;&emsp;备注：{{ $filing->info }}
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection