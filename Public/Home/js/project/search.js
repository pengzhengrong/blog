$(function() {
	/*同步博客*/
	$("#syncBlog").click(function() {
		syncBlog();
	});
});

/*校验表单输入*/
var fields = {
	title:'栏目名称',
	click:'点击量'
	// content:'正文'
};
function validate() {
	for( v in fields  ) {
		if ($("#"+v).val().trim()== "") {
			$.showTips( fields[v]+"不能为空，请输入！", "提示");
			$("#"+v).focus();
			return 0;
		}
	}
	return 1;
}
/* 同步博客 */
function syncBlog() {

	$.showDialog({
		"msgContent":"即将覆盖原有的博客，请确认是否同步？",
		"btns":[
		{text:"确认",className:"",act: function(){
				// 隐藏提示框
				$(".dialog-frm").css("display", "none");
				$(".dialog-mask").css("display", "none");
				var url = '/Home/Search/create.html';
				$.post(url, '', function(data) {
					if (data.code != 200) {
						$.showTips(data.msg,"提示");
					} else {
						$.showTips("同步完成","提示");
						window.location.reload() ;
					}
				});
			}},
			{text:"取消",className:"",act:"_hide"}
			]
		});
}
