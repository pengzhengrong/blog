/*
$('#title').blur( function(){
	var title = $('#title').val();
	if( $.trim(title) == '' ) {
		// $.showTips("事件不能为空！", "提示");
		$('#title').focus();
		return;
	}

	$.post("/Home/Schedule/event.html",{title: $('#title').val()},
		function(data) {
			if( data.code == 200 ) {
				$.showTips("添加完成", "提示");
				window.location.reload();
			} else {
				$.showTips( data.msg , "提示");
			}
		}
		);
});*/


$('#title').blur( function(){
	var title = $('#title').val();
	if( $.trim(title) == '' ) {
		// $.showTips("事件不能为空！", "提示");
		$('#title').focus();
		return;
	}
	$.showDialog({
		"msgContent":"确认添加该事件吗？",
		"btns":[
		{text:"确认",className:"",act: function(){
                // 隐藏提示框
                $(".dialog-frm").css("display", "none");
                $(".dialog-mask").css("display", "none");
                var url = 'delete_node.html';
                var pars = {title: $('#title').val()};
                $.post("/Home/Schedule/event.html",pars,
		function(data) {
			if( data.code == 200 ) {
				$.showTips("添加完成", "提示");
				window.location.reload();
			} else {
				$.showTips( data.msg , "提示");
			}
		});
            }},
            {text:"取消",className:"",act:"_hide"}
            ]
        });
} )
