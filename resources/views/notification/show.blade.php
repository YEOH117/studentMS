@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">通知</a></li>
        <li class="active">通知详情</li>
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
                                <div class="col-md-12" style="text-align: center;margin: 10px;background-color: #e6e6e6">
                                    <h1><strong>{{ $notification->data->title }}</strong></h1>
                                </div>
                                <div class="col-md-12" style="font-size: 10px;margin: 10px;background-color: #e6e6e6">
                                    <span class="fa fa-clock-o"></span><strong><strong>{{ $notification->time }}</strong>
                                </div>
                                <div class="col-md-12" style="word-wrap:break-word;padding: 20px 10px;font-size: 20px;margin: 10px;background-color: #e6e6e6;width: 800px;height: 400px;">
                                    &emsp;&emsp;{!! $notification->data->content !!}
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