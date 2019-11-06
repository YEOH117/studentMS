@extends('common.father')

@section('css')
    <style>
        a:hover {
            text-decoration:none;
        }
    </style>
@endsection

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">通知</a></li>
        <li class="active">通知列表</li>
    </ul>
@endsection

@section('body')
    <div class="content-frame-body" style="padding: 0px 10px;">

        <div class="panel panel-default">
            <div class="panel-body" style="height: 750px;">
            @foreach($notification as $notifi)
                <div class="col-md-6 col-md-offset-3" style="border: 1px dashed #bdbdbd;border-radius: 10px;padding: 20px;margin-top: 12px;
                @if(empty($notifi->read_at))background-color:#FFEFD5;@esle color:#636b6f; @endif">
                    <div class="media">
                        <div class="media-body" style="font-size: 20px;">
                            <a href="{{ route('notification_show',$notifi->id) }}"><strong>{{ $notifi->data->title}}</strong></a>
                        </div>
                    </div>
                    <div class="media">
                        <div class="media-body" style="font-size: 15px;">
                            <span class="fa fa-clock-o"></span><strong> {{ $notifi->created_at}}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
            <div class="panel-footer" >
                <div class="col-md-8">
                    {{ $notification->links() }}
                </div>
                <div class="col-md-4">
                    @if($notification->count() > 0)
                    <a class="btn btn-danger pull-right" onclick="yes_confirm()">删除全部通知</a>
                    @endif
                </div>
            </div>
            <script>
                function yes_confirm() {
                    var r=confirm("确定删除全部通知？继续请按确定键，返回按取消。")
                    if (r==true)
                    {
                        window.location.href="{{ route('notification_del') }}";
                    }
                }
            </script>
        </div>

    </div>
@endsection