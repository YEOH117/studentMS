@extends('common.father')

@section('css')

@endsection

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍管理</a></li>
        <li class="active">新生列表</li>
    </ul>
@endsection

@section('body')
    <div class="content-frame-body" style="padding: 0px 10px;">

        <div class="panel panel-default">

            <form action="{{ route('student_sort') }}" method="post">
                {{ csrf_field() }}
            <div class="panel-heading row">
                <label class="check mail-checkall">
                    <input type="checkbox" class="icheckbox col-md-1"/>
                </label>
                <label>全选</label>

                    <input type="hidden" name="all" value="1">
                    <button type="submit" class="btn btn-danger pull-right">全部智能排宿</button>

            </div>
            </form>
            <form action="{{ route('student_sort') }}" method="post">
                {{ csrf_field() }}
            <div class="panel-body mail" style="height: 400px;">
                @foreach($student as $value)
                <div class="mail-item mail-unread  row">
                    <div class="mail-checkbox col-md-2">
                        <input type="checkbox" name="single[]" value="{{ $value->id }}" class="icheckbox"/>
                    </div>
                    <div class="col-md-2">{{ $value->the_student_id }}</div>
                    <div class="col-md-2">{{ $value->name }}</div>
                    <div class="col-md-1">{{ $value->sex }}</div>
                    <div class="col-md-2">{{ $value->college }}</div>
                    <div class="col-md-2">{{ $value->profession }}</div>
                    <div class="col-md-1">{{ $value->class }}班</div>
                </div>

                @endforeach
            </div>
            <div class="panel-footer">


                <ul class="pagination pagination-sm ">
                    {{ $student->links() }}
                </ul>
                <button type="submit" class="btn btn-info pull-right">智能排宿选中学生</button>
            </div>
            </form>
        </div>

    </div>
@endsection