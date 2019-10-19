@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">信息查询</a></li>
        <li class="active">学生信息详情页</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>学生信息详情</strong></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>姓名</th>
                                    <th>学号</th>
                                    <th>学院</th>
                                    <th>专业</th>
                                    <th>宿舍</th>
                                </tr>
                                @foreach($student as $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->the_student_id }}</td>
                                    <td>{{ $value->college }}</td>
                                    <td>{{ $value->profession }}</td>
                                    <td>
                                        @if($building[$loop->index]->area > 0)
                                            西
                                        @else
                                            东
                                        @endif
                                        {{ $building[$loop->index]->building }}栋 {{ $dormitory[$loop->index]->house_num }}宿舍
                                    </td>
                                </tr>
                                    @endforeach
                            </table>
                        </div>
                        <div class="panel-footer">

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection