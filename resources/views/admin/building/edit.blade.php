@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍楼管理</a></li>
        <li class="active">宿舍楼修改</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="/building/{{ $building->id }}/edit" method="post">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>宿舍楼信息修改</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">区域</label>
                                        <div class="col-md-6 col-xs-12">
                                            <select  class="form-control" name="area">
                                                @switch($building->area)
                                                    @case(0)
                                                        <option value="0">东区</option>
                                                        <option value="1">西区</option>
                                                        @break
                                                    @case(1)
                                                        <option value="1">西区</option>
                                                        <option value="0" >东区</option>
                                                        @break
                                                    @default
                                                        <option value="0">东区</option>
                                                        <option value="1">西区</option>
                                                        @break
                                                @endswitch
                                            </select>
                                            <span class="help-block">必选！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">男/女宿舍楼</label>
                                        <div class="col-md-6 col-xs-12">
                                            <select  class="form-control" name="sex">
                                                @switch($building->sex)
                                                    @case(0)
                                                    <option value="0">男生宿舍楼</option>
                                                    <option value="1">女生宿舍楼</option>
                                                    @break
                                                    @case(1)
                                                    <option value="1">女生宿舍楼</option>
                                                    <option value="0" >男生宿舍楼</option>
                                                    @break
                                                    @default
                                                    <option value="0">男生宿舍楼</option>
                                                    <option value="1">女生宿舍楼</option>
                                                    @break
                                                @endswitch
                                            </select>
                                            <span class="help-block">必选！</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">楼号</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-building-o"></span></span>
                                                <input type="text" name="building" class="form-control" value="{{ $building->building }}"/>
                                            </div>
                                            <span class="help-block">必填！</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">专业偏好</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-tag"></span></span>
                                                <input type="text" name="preference" class="form-control" value="{{ $building->preference }}"/>
                                            </div>
                                            <span class="help-block">填写专业代码！可以填写多个，每个之间以 <code>;</code>（分号）分隔。</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">验证码</label>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-picture-o"></span></span>
                                                <input type="text" name="verification" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <img src="{{ captcha_src() }}" onclick="this.src='{{captcha_src()}}'+Math.random()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">修改</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection