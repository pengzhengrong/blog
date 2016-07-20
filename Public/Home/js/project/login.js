$(function(){
	//跳转登入页面
	$('#login').click(function(){
		login();
	});
	//跳转登出页面
	$('#logout').click(function(){
		logout();
	});
	//跳转个人设置页面
	$('#profile').click(function(){
		profile();
	});
})

/*校验表单输入*/
var fields = {
	username:'用户名',
	password:'密码'
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

function profile() {
	window.location = 'Home/User/profile.html';
}

function login() {
	var flag = validate();
	if( !flag ) {
		return;
	}
	var url = '/Home/Login/handle.html';
	var pars = {
		username:$('#username').val(),
		password:$('#password').val()
	};
	$.post(url, pars, function(data){
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("登入成功,等待跳转", "提示");
			setTimeout(function() {
				window.location = "/admin.html";
			}, 1000);
		}
	});
}

function logout() {
	var url = '/Home/Login/logout.html';
	$.post(url, '', function(data){
		if (data.code != 200) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("退出登入成功,等待跳转", "提示");
			setTimeout(function() {
				window.location = "/login.html";
			}, 1000);
		}
	});
}