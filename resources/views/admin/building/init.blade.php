@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">宿舍楼管理</a></li>
        <li class="active">宿舍楼初始化</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12" >

                <form class="form-horizontal" action="/building/{{ $id }}/init" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" value="">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>宿舍楼初始化</strong></h3>
                        </div>
                        <div class="panel-body ">
                            <p><span class="fa fa-exclamation-circle"></span>操作不可逆，请谨慎操作！</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">大部分宿舍为：</label>
                                        <div class="col-md-6 col-xs-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="roon_num"  value="4"> 4人间
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="roon_num" value="6"> 6人间
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="roon_num" value="8"> 8人间
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3" style="text-align: center;color: #ff2222;font-size: 18px;">
                                            <label>----以下填写特例的宿舍----</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">4人间宿舍：</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-building-o"></span></span>
                                                <input type="text" name="four_roon" class="form-control"/>
                                            </div>
                                            <span class="help-block">填写宿舍号，如：<code>101</code>。多个宿舍以<code>；</code>(分号)分隔。</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">6人间宿舍：</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-home"></span></span>
                                                <input type="text" name="six_roon" class="form-control"/>
                                            </div>
                                            <span class="help-block">填写宿舍号，如：<code>101</code>。多个宿舍以<code>；</code>(分号)分隔。</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">8人间宿舍：</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-tag"></span></span>
                                                <input type="text" name="eight_roon" class="form-control"/>
                                            </div>
                                            <span class="help-block">填写宿舍号，如：<code>101</code>。多个宿舍以<code>；</code>(分号)分隔。</span>
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
                                            <img src="#" alt="验证码">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <blockquote style="margin-top: 50px;">
                                        <h3>填写帮助：</h3>
                                        <p>例如：东1栋，绝大部分为6人间宿舍，但存在4间8人间宿舍(105，206，306，611），无4人间宿舍。则该填写如下：</p>
                                        <p>1.<code>大部分宿舍为:</code>应选择<code>6人间</code></p>
                                        <p>2.<code>4人间宿舍：</code>应该<code>不填写</code></p>
                                        <p>3.<code>6人间宿舍：</code>应该<code>不填写</code></p>
                                        <p>3.<code>8人间宿舍：</code>应该填写如下内容：<code>105;206;306;611</code></p>
                                    </blockquote>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary pull-right">初始化宿舍</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection