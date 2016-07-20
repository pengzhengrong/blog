$(function() {
	/*添加页面*/
	$("#add").click(function() {
		addPage();
	});
	/*保存添加内容*/
	$("#save").click(function() {
		save();
	});
	/*保存添加内容并且继续添加*/
	$("#savemore").click(function() {
		saveMore();
	});
	/*保存修改内容*/
	$("#saveModify").click(function() {
		saveModify();
	});
	/*取消*/
	$("#cancle").click(function() {
		history.go(-1);
	});
});

/*校验表单输入*/
var fields = {
	title:'中文名称',
	m:'模块名',
	c:'文件名',
	action:'方法名'
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
	var url = '/Home/Nav/add.html';
	var pars = {
		title : $("#title").val(),
		pid : $("#pid").val(),
		status : $("#status").val(),
		sort : $("#sort").val(),
		m : $("#m").val(),
		c : $("#c").val(),
		action:$("#action").val()
		// level:$('#level').val()
	};
	$.post(url, pars, function(data) {
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("添加完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Nav/index.html";
			}, 1000);
		}
	});
}

/* 修改菜单 */
function saveModify() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	var url = '/Home/Nav/edit.html';
	var pars = {
		id : $("#id").val(),
		title : $("#title").val(),
		pid : $("#pid").val(),
		status : $("#status").val(),
		sort : $("#sort").val(),
		m : $("#m").val(),
		c : $("#c").val(),
		action:$("#action").val()
	};
	// console.log(pars);
	$.post(url, pars, function(data) {
		if ( data.code != 200 ) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("修改完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Nav/index.html";
			}, 1000);
		}
	});
}

/*添加一级菜单页面*/
function addPage() {
	window.location = '/Home/Nav/add.html';
}

/*添加子菜单页面*/
function add(id) {
	window.location = '/Home/Nav/add.html?id='+id;
}
/*修改页面*/
function modify(id) {
	window.location = '/Home/Nav/edit.html?id='+id;
}
/*删除操作*/
function del(id) {
	$.showDialog({
		"msgContent":"请确认是否删除该记录？",
		"btns":[
		{text:"确认",className:"",act: function(){
                // 隐藏提示框
                $(".dialog-frm").css("display", "none");
                $(".dialog-mask").css("display", "none");
                var url = '/Home/Nav/delete.html';
                var pars = {
                	id: id
                };
                $.post(url, pars, function(data) {
                	if (data.code != 200) {
                		$.showTips(data.msg,"提示");
                	} else {
                		$.showTips("删除完成","提示");
                		window.location = "/Home/Nav/index.html";
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

/*继续添加*/
function saveMore() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	var url = '/Home/Nav/add.html';
	var pars = {
		title : $("#title").val(),
		pid : $("#pid").val(),
		status : $("input[name=status]").val(),
		sort : $("#sort").val(),
		m : $("#m").val(),
		c : $("#c").val(),
		action:$("#action").val(),
		saveMore:1
	};
	$.post(url, pars, function(data) {
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("添加完成,继续添加!", "提示");
		}
	});
}