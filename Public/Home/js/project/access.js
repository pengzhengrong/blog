/*select改变选项时调用函数*/
function optionsChange(_this) {
	var id = $(_this).val();
	var form = $('#form');
	form.submit();
}

$(function(){
	$('#save').click(function(){
		save();
	});
})


function save() {
	var ids = [];
	$(':checked').each(function(){
		ids.push($(this).val());
	});
	// console.log(ids);
	var url = '/Home/Rbac/access_node.html';
	var pars = {
		node_id:ids,
		role_id:$('#role_id').val()
	};
	$.post(url,pars,function(){
		$.post(url, pars, function(data) {
			if (data.code != 200) {
				$.showTips(data.msg, "提示");
			} else {
				$.showTips("添加完成", "提示");
				setTimeout(function() {
					window.location = "/Home/Rbac/access.html?id="+pars.role_id;
				}, 1000);
			}
		});
	});

}