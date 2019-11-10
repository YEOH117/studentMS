@extends('common.father')

@section('container')
    page-navigation-toggled
@endsection

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
    </ul>
@endsection

@section('body')
    <div style="height: 100%;width: 100%;background-image: url('img/bgimg.png');">
        <div style="height: 100%;width: 100%;background:rgba(173, 216, 230,0.6);text-align: center;">
            <div id="clock" style="font-size: 90px;line-height: 100px;font-weight: 300;color: #1b1e24;padding-top: 200px;"></div>
            <div id="time" style="font-size: 30px;line-height: 35px;font-weight: 300;color: #1b1e24;"></div>
            <script type="text/javascript">
                //获取当天日期
                function date()
                {
                    var date=new Date();
                    var year=date.getFullYear();
                    var month=date.getMonth()+1;
                    var day=date.getDate();
                    var hour="00"+date.getHours();
                    hour=hour.substr(hour.length-2);
                    var minute="00"+date.getMinutes();
                    minute=minute.substr(minute.length-2);
                    var second="00"+date.getSeconds();
                    second=second.substr(second.length-2);
                    var week=date.getDay();
                    switch(week)
                    {
                        case 1:week="星期一 ";break;
                        case 2:week="星期二 ";break;
                        case 3:week="星期三 ";break;
                        case 4:week="星期四 ";break;
                        case 5:week="星期五 ";break;
                        case 6:week="星期六 ";break;
                        case 0:week="星期日 ";break;
                        default:week="";break;
                    }
                    document.getElementById("clock").innerHTML=hour+":"+minute+":"+second;
                    document.getElementById("time").innerHTML=year+"年"+month+"月"+day+"日"+week;
                }
                setInterval("date()",1000);
            </script>



        </div>
    </div>

@endsection