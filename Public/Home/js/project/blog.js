$(function() {
	/*添加页面*/
	$("#add").click(function() {
		addPage();
	});
	/*添加页面 MarkDown*/
	$("#add2").click(function() {
		addPage2();
	});
	/*保存添加内容*/
	$("#save").click(function() {
		save();
	});
	/*Markdown 编辑器*/
	$("#save2").click(function() {
		save2();
	});
	/*保存添加内容并且继续添加*/
	$("#savemore").click(function() {
		saveMore();
	});
	/*保存修改内容*/
	$("#saveModify").click(function() {
		saveModify();
	});
	/*保存修改内容 Markdown*/
	$("#saveModify2").click(function() {
		saveModify2();
	});
	/*取消*/
	$("#cancle").click(function() {
		history.go(-1);
	});
});

/*校验表单输入*/
var fields = {
	title:'栏目名称',
	// click:'点击量'
	// contentSource:'正文'
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
/* 保存添加内容 */
function save() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	var content=CKEDITOR.instances.content.getData(); //取得html文本
	var url = '/Home/Blog/add.html';
	var pars = {
		title : $("#title").val(),
		cat_id : $("#cat_id").val(),
		isdisplay : $("#isdisplay").val(),
		click : $("#click").val(),
		content :content
	};
	$.post(url, pars, function(data) {
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("添加完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Blog/index.html";
			}, 1000);
		}
	});
}

/* 保存添加内容 MarkDown */
function save2() {
	// var flag = validate();
	// if( !flag ) {
	// 	return;
	// }
	var content=$('#content').html(); //取得html文本
	var contentSource = $('#contentSource').val();
	var from = $('#from').val();
	var url = '/Home/Blog/add.html';
	var inputPics = $('input[name=pics]');
	var pics = [];
	for(var i=0; i<inputPics.length; i++){
		// console.log($(pics[i]).val());
		pics.push($(inputPics[i]).val());
	}
	console.log(pics);
	// return;
	var pars = {
		title : $("#title").val(),
		cat_id : $("#cat_id").val(),
		isdisplay : $("#isdisplay").val(),
		click : $("#click").val(),
		content :content,
		extra:{
			source: contentSource,
			from:from,
			pics:pics
		}
	};
	// console.log(pars);
	$.post(url, pars, function(data) {
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("添加完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Blog/index.html";
			}, 1000);
		}
	});
}

/* 保存修改栏目 */
function saveModify() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	//var stemTxt=CKEDITOR.instances.content.document.getBody().getText(); //取得纯文本
	var content=CKEDITOR.instances.content.getData(); //取得html文本
	var url = '/Home/Blog/edit.html';
	var pars = {
		id : $("#id").val(),
		title : $("#title").val(),
		cat_id : $("#cat_id").val(),
		isdisplay : $("#isdisplay").val(),
		click : $("#click").val(),
		content : content
	};
	// console.log(pars);
	$.post(url, pars, function(data) {
		if ( data.code != 200 ) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("修改完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Blog/index.html";
			}, 1000);
		}
	});
}
/* 保存修改栏目 Markdown */
function saveModify2() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	var content=$('#content').html(); //取得html文本
	var contentSource = $('#contentSource').val();
	var from = $('#from').val();
	var url = '/Home/Blog/edit.html';
	var pars = {
		id : $("#id").val(),
		title : $("#title").val(),
		cat_id : $("#cat_id").val(),
		isdisplay : $("#isdisplay").val(),
		click : $("#click").val(),
		content : content,
		extra:{
			source: contentSource,
			from:from
		}
	};
	// console.log(pars);
	$.post(url, pars, function(data) {
		if ( data.code != 200 ) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("修改完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Blog/index.html";
			}, 1000);
		}
	});
}

/*添加一级菜单页面*/
function addPage() {
	window.location = '/Home/Blog/add.html';
}

/*添加一级菜单页面 Markdown*/
function addPage2() {
	window.location = '/Home/Blog/add.html?editor=Markdown';
}

/*添加子菜单页面*/
function add(id) {
	window.location = '/Home/Blog/add.html?id='+id;
}
/*修改页面*/
function modify(id) {
	window.location = '/Home/Blog/edit.html?id='+id;
}
/*删除操作*/
function handle(id,type) {
	var msg = '删除';
	if( type=='delete' ) {
		msg = '彻底删除';
	}
	if( type=='reback' ) {
		msg = '恢复删除';
	}
	$.showDialog({
		"msgContent":"请确认是否"+msg+"该记录？",
		"btns":[
		{text:"确认",className:"",act: function(){
				// 隐藏提示框
				$(".dialog-frm").css("display", "none");
				$(".dialog-mask").css("display", "none");
				var url = '/Home/Blog/delete.html';
				var pars = {
					id: id,
					type:type
				};
				$.post(url, pars, function(data) {
					if (data.code != 200) {
						$.showTips(data.msg,"提示");
					} else {
						$.showTips(msg+"完成","提示");
						window.location.reload() ;
					}
				});
			}},
			{text:"取消",className:"",act:"_hide"}
			]
		});
};

/*select改变选项时调用函数*/
function optionsChange(_this) {
	var id = $(_this).val();
	var form = $('#form');
	form.submit();
}
//详情页
function detailContent(id) {
	window.location = '/Home/Blog/detail.html?id='+id;
}

function display(id, isdisplay) {
	var pars = {id: id,isdisplay:1-isdisplay};
                $.post("/Home/Blog/show.html",pars,
                	function(data) {
                		if( data.code == 200 ) {
                			$.showTips("操作完成", "提示");
                			window.location.reload();
                		} else {
                			$.showTips( data.msg , "提示");
                		}
                	});
}