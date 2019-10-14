@extends('common.father')

@section('navigation')
    <ul class="breadcrumb">
        <li><a href="{{ Route('/') }}">主页</a></li>
        <li><a href="#">学生录入</a></li>
        <li class="active">批量录入</li>
    </ul>
@endsection

@section('body')
    <div class="page-content-wrap">
        <div class="row"  >
            <div class="col-md-12" >
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>批量导入</strong></h3>
                    </div>
                    <div class="col-md-8 col-md-offset-2" style="height: 600px;" >
                        <div class="panel-body">
                            <p>批量录入时请务必使用 <code>.xlsx</code> 文件格式的Excel文件！注意！使用其他格式文件，很大几率会录入失败，请知悉！</p>
                            <form action="{{ route('batch_entry') }}" method="post" enctype="multipart/form-data" class="form-horizontal" >
                                <div class="form-group" style="padding-top: 50px;">
                                    {{ csrf_field() }}
                                    <div class="col-md-12">
                                        <input type="file" name="upfile" multiple class="file" data-preview-file-type="any"/>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection


