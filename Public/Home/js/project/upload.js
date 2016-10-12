var id = '';
var pics = [];
$(function() {

	/*$("div[class='upload_div']").click(function() {
		$("#file").click();
	});*/
	$(".upload_div").click(function() {
		$("#file").click();
	});
	// $('#picsUrl').on('click', '.removePic', 'callback');
});

function removePic(_this) {
	var url = $(_this).prev().val();
	$(_this).prev().remove();
	$(_this).next().remove();
	$(_this).remove();
	pics.splice( pics.indexOf(url), 1);
}

/* 提交表单，上传文件 */
function fileUpLoad(id='') {
	id = id;
	// console.log(window.location.host);
	var host = window.location.host;
	$("#needHide").ajaxSubmit({
		type : 'post',
		url : '/Home/Upload/index.html',
		success : function(data) {
			if (data.code == 200) {
				var url = "http://"+host+"/uploadfile/"+data.data.savepath
				+ data.data.savename;
				if (id != '') {
					if (pics.indexOf(url) > -1) {
						return;
					}
					var removeBtn = '<button onclick="removePic(this)" type="button" class="removePic"><i class="fa  fa-times-circle">移除图片</i></button>';
					var input = "<input name='pics' readonly size='50' value="+url+">"+removeBtn+"<br>";
					$('#'+id).append(input);
					pics.push(url);
					return;
				}
				// 所点击div对象
				var divPic = $("#uploadPic");
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
