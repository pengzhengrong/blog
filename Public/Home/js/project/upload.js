$(function() {

	$("div[class='upload_div']").click(function() {
		$("#file").click();
	});
});

/* 提交表单，上传文件 */
function fileUpLoad() {
	$("#needHide").ajaxSubmit({
		type : 'post',
		url : '/Home/Upload/index.html',
		success : function(data) {
			if (data.code == 200) {
				var url = "http://blog.com:8890/uploadfile/"+data.data.savepath
				+ data.data.savename;
						var divPic = $("#uploadPic");// 所点击div对象
						divPic.text("").append(
							'<img src="' + url + '" alt=""/>');
						divPic.find("img").css("width", divPic.css("width"))
						.css("height", divPic.css("height"));
						$("#pics").val(url);
					} else {
						$.showTips(data.msg, "提示");
					}
				}
			});
}
