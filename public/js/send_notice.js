function entire(){
    $('#content').html(' ');
}
function part(){
    $('#content').html('<div class="form-group">' +
        '<label class="col-md-3 col-xs-12 control-label">区域</label>' +
        '<div class="col-md-6 col-xs-12">' +
        '<select  class="form-control" name="area">' +
        '<option value="0">东区</option>' +
        '<option value="1">西区</option>' +
        '</select>' +
        '<span class="help-block">必选！</span>' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label class="col-md-3 col-xs-12 control-label">宿舍楼</label>' +
        '<div class="col-md-6 col-xs-12">' +
        '<div class="input-group">' +
        '<span class="input-group-addon"><span class="fa fa-building-o"></span></span>' +
        '<input type="text" name="building" class="form-control"/>' +
        '</div>' +
        '<span class="help-block">必填！可填写多座宿舍大楼，<code>填写数字即可,多个请以;（分号）分隔开</code></span>' +
        '</div>' +
        '</div>');
}
function individual(){
    $('#content').html('<div class="form-group">' +
        '<label class="col-md-3 col-xs-12 control-label">学号</label>' +
        '<div class="col-md-6 col-xs-12">' +
        '<div class="input-group">' +
        '<span class="input-group-addon"><span class="fa fa-building-o"></span></span>' +
        '<input type="text" name="studentId" class="form-control"/>' +
        '</div>' +
        '<span class="help-block">必填！可填写多个，多个之间以<code>；</code>(分号)分隔。</span>' +
        '</div>' +
        '</div>');
}









