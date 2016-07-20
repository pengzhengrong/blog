$(function() {
	/*添加页面*/
	$("#add").click(function() {
		addPage();
	});
	/*保存添加内容*/
	$("#save").click(function() {
		save();
	});
	/*保存口令内容*/
	$("#saveCard").click(function() {
		saveCard();
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
	username:'用户名称',
	name:'真实姓名'
};
function validate(fields) {
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
	var url = '/Home/User/add.html';

	var pars = {
		username : $("#username").val(),
		status : $("#status").val(),
		role : $("#role option:selected").text(),
		role_id : $('#role').val(),
		extra:{
			name : $("#name").val(),
			email : $("#email").val(),
		}
	};
	console.log(pars);
	$.post(url, pars, function(data) {
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("添加完成", "提示");
			setTimeout(function() {
				window.location = "/Home/User/index.html";
			}, 1000);
		}
	});
}

/* 保存修改用户 */
function saveModify() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	var url = '/Home/User/edit.html';
	var pars = {
		id : $("#id").val(),
		username : $("#username").val(),
		status : $("#status").val(),
		role : $("#role option:selected").text(),
		role_id : $('#role').val(),
		extra:{
			name : $("#name").val(),
			email : $("#email").val(),
		}
	};
	// console.log(pars);
	$.post(url, pars, function(data) {
		if ( data.code != 200 ) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("修改完成", "提示");
			setTimeout(function() {
				window.location = "/Home/User/index.html";
			}, 1000);
		}
	});
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
                var url = '/Home/User/delete.html';
                var pars = {
                	id: id
                };
                $.post(url, pars, function(data) {
                	if (data.code != 200) {
                		$.showTips(data.msg,"提示");
                	} else {
                		$.showTips("删除完成","提示");
                		window.location = "/Home/User/index.html";
                	}
                });
            }},
            {text:"取消",className:"",act:"_hide"}
            ]
        });
};


/*校验表单输入*/
var fields_card = {
	old_password:'原始密码',
	new_password:'新密码',
	re_password:'确认密码'
};
/*
/*修改操作*/
function saveCard() {
	var flag = validate(fields_card);
	if( !flag ) {
		return;
	}
	var newPassword = $("#new_password").val();
	var rePassword =  $('#re_password').val();
	if( newPassword !=  rePassword ) {
		$.showTips('确认密码不匹配！', "提示");
		return;
	}
	var url = '/Home/User/card.html';
	var pars = {
		id : $("#id").val(),
		old_password : $("#old_password").val(),
		new_password : $("#new_password").val(),
		re_password : $('#re_password').val()
	};
	// console.log(pars);
	$.post(url, pars, function(data) {
		if ( data.code != 200 ) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("修改完成", "提示");
			setTimeout(function() {
				window.location = "/Home/User/index.html";
			}, 1000);
		}
	});

};

/*添加页面*/
function addPage() {
	window.location = '/Home/User/add.html';
}

/*修改页面*/
function modify(id) {
	window.location = '/Home/User/edit.html?id='+id;
}

/*修改口令页面*/
function card(id) {
	window.location = '/Home/User/card.html?id='+id;
}