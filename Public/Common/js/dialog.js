/*
白色弹窗
$.showDialog({
    "msgTitle":"弹窗标题",
	"msgContent":"单行内容~~~~~",
	"btns":[
			{text:"取消",className:"",act:"_hide"},
			{text:"确定",className:"",act: function(){alert("确定")}}
	]
});

黑色弹窗
$.showTips("hehe",[毫秒]);

下拉框
 <div class="row clearfix row_right row_zjlx">
	 <div class="fl"><span class="name">证件类型</span></div>
	 <div class="fr"><i class="iconfont icon-jiantou"></i></div>
	 <div class="inputBox" id="row_zjlx"><input type="hidden" value="身份证" name="zjlx"><span>居民身份证</span></div>
 </div>
$("#btn").click(function(){
	 var _this= $(this);
	 $.showSlideMenu({
		title:"证件类型",
		 list: [
			 {value:"A",content:"身份证"},
			 {value:"b",content:"军官证"}
		],
		callback: function(data){
			 _this.find("span").text(data.content);
			 _this.find('input[type="hidden"]').val(data.value);
		 }
	 });
});

省份下拉框
  html代码结构
 <div class="row clearfix row_carNo">
	 <div class="fl"><span class="name">号牌号码</span></div>
	 <div class="carNo fl"><input type="hidden" value=""><span class="province">京</span><i class="iconfont icon-xiangxia"></i></div>
	 <div class="inputBox"><input class="input_txt" type="text" name="hphm" placeholder="请输入车牌号码"></div>
 </div>
  调用
 $(".userKind .carNo").click(function(){
	 var _this= $(this);
	 $.showProviceMenu({
	 list: [
		 {value:"A",content:"京"},
		 {value:"b",content:"沪"},
	 ],
	 callback: function(data){
		 _this.find("span").text(data.content);
		 _this.find("input").val(data.value);
	  }
	 });
 });

*/

!(function($){
$.showTips =  function(msg,title){
	var ms= 3000;
	var obj =  null;
	var msg = msg || "Message";
	var title = title || "Message";
	if($(".dialog-tips").length==0){
		obj= $('<style>.dialog-tips{position:fixed;width:200px;top:30%;left:20%;z-index:101;background-color:rgba(0,0,0,0.7);color:#fff;font-size:17px;text-align:center;border-radius:10px;padding:10px 0}</style><div class="dialog-tips"><div class="dialog-title" style="font-size: 17px;margin-bottom: 13px;color: white">'+title+'</div><div class="dialog-msg">'+ msg +'</div></div>').appendTo("body");
	}else{
		$(".dialog-tips .dialog-title").text(title).show();
		$('.dialog-tips .dialog-msg').text(msg);

	}
	if(arguments.length == 1){
		$(".dialog-tips .dialog-title").hide();
	}
	obj= $('.dialog-tips');
	obj.show(0,function(){
		 setTimeout(function(){obj.fadeOut()},ms)
	});
}

$.showDialog =  function(settings){
	var opts = {};
	opts.msgTitle= settings.msgTitle || "Message";
	opts.msgContent= settings.msgContent || "Message";
	opts.btnsHTML =[] ;
	opts.btnCallbacks= [];
	for(var i=0; i<settings.btns.length;i++){
	    opts.btnCallbacks.push( settings.btns[i].act);
		opts.btnsHTML.push("<span>"+settings.btns[i].text+"</span>")
	}

	if($(".dialog-frm").length == 0){
			$('<style>'+
			'*{margin:0;padding:0}'+
			'.dialog-mask{display:none;background-color:rgba(0,0,0,0.3);position:fixed;z-index:99;width:100%;height:100%;top:0;left:0  }'+
			'.dialog-frm{display:none;width:300px;position:fixed;top:130px;left:50%;margin-left:-150px;z-index:100;background-color:#fff;border-radius:10px;overflow:hidden;padding-top:28px}'+
			'.dialog-title{text-align:center;font-size:20px;margin-bottom:13px;}'+
			'.dialog-content{text-align:center;font-size:15px;margin-bottom:25px;padding:0 30px;line-height:1.2}'+
			'.dialog-buttons{background-color:#cecece;height:50px;line-height:50px;padding-top:1px}'+
			'.dialog-buttons span{float:left;width:50%;text-align:center;background-color:#fff;font-size:20px;color:#70cee7;border-left:1px #cecece solid;-webkit-box-sizing:border-box;box-sizing:border-box;}'+
			'.dialog-buttons span.large{width:100%;margin-left:0;}'+
			'.dialog-buttons span:first-child{border-left:0}'+
			'.dialog-tips{display:none;position:absolute;width:50%;position:absolute;top:10%;left:25%;z-index:101;background-color:rgba(0,0,0,0.7);color:#fff;font-size:17px;text-align:center;border-radius:10px;padding:10px 0}'+
			'</style>'+
			'   <div class="dialog-mask"></div>'+
			'	<div class="dialog_wrap">'+
			'   <div class="dialog-frm">'+
			'        <div class="dialog-title"></div>'+
			'		<div class="dialog-content"></div>'+
			'		<div class="dialog-buttons"></div>'+
			'  </div>'+
			'</div>').appendTo("body");
   }

    $(".dialog-frm .dialog-title").html(opts.msgTitle);
	$(".dialog-frm .dialog-content").html(opts.msgContent);
	$(".dialog-frm .dialog-buttons").html(opts.btnsHTML.join(""));
	if(	$(".dialog-frm .dialog-buttons span").length == 1  ){
           $(".dialog-frm .dialog-buttons span").addClass("large");
	}

 
    $(".dialog-frm").show();
    $(".dialog-mask").show();

	$(".dialog-frm .dialog-buttons span").each(function(index,span){
		   var fn= opts.btnCallbacks[index] == "_hide" ? _hide : opts.btnCallbacks[index]
		   $(span).click(fn)
	})

	function _hide(){
		  console.log("hide");
			$(".dialog-frm").remove();
			$(".dialog-mask").remove();
	}

	if(opts.msgContent.length>16){
		$(".dialog-frm .dialog-content").css('text-align','left');
	}
	if(opts.msgTitle=="Message"){
		$(".dialog-frm .dialog-title").remove();
	}

		}

$.showSlideMenu= function(data){
	var title= data.title || "";
	var list= data.list || [];
	var fn= data.callback || function(val){alert(val)};
	var curH= 0;
	var first;
	if($(".slidemenu-frm").length == 0){
	 $('<style>'+
		'*{margin:0;padding:0} ul,ol{list-style:none}'+
		'.slidemenu-mask{display:none;background-color:rgba(0,0,0,0.3);position:fixed;z-index:99;width:100%;height:100%;top:0;left:0}'+
		'.slidemenu-frm{width:100%;position:fixed;bottom:-999px;left:0;z-index:100;background-color:#fff;overflow:scroll;line-height:50px}'+
		'.slidemenu-frm .slidemenu-title{border-bottom:1px #d9d9d9 solid;text-align:center;font-size:18px;}'+
		'.slidemenu-frm .slidemenu-list{text-align:center;font-size:16px;overflow:scroll}'+
		'.slidemenu-frm .slidemenu-list li{border-bottom:1px #d9d9d9 solid;}'+
		'</style>'+
		'<div class="slidemenu-mask"></div>'+
		'<div class="slidemenu-frm">'+
		'<div class="slidemenu-title"></div>'+
		'	<ul class="slidemenu-list">'+
		'	</ul>'+
		'</div>').appendTo("body");
		first= 1;
	}
    
    $(".slidemenu-frm .slidemenu-title").text(title);
	$(".slidemenu-frm .slidemenu-list").html( function(){
		var arr= [];
		$.each(data.list,function(index,el){
		      arr.push("<li data-value="+el.value+">"+el.content+"</li>")
		})
		return arr.join("");
	});
    curH= $(".slidemenu-frm").height();
	$(".slidemenu-frm").css("bottom", -curH);

	$(".slidemenu-mask").fadeIn(150);
	if(curH<=251){
		$(".slidemenu-frm").animate({bottom:0},300);
	}else{
		$(".slidemenu-frm").height(250);
		$(".slidemenu-frm").animate({bottom: 0},300);
	}

	$(".slidemenu-frm .slidemenu-list li").click(function(){
		 $(".slidemenu-frm").animate({bottom: -curH},200,function(){ $(".slidemenu-mask").fadeOut(150).remove();}).remove();
		 fn( {value:$(this).data("value"),content:$(this).text()} );
		$(".slidemenu-frm ").remove();
	 });



	if(first== 1){
		$(".slidemenu-mask").click(function(){
			 $(".slidemenu-frm").animate({bottom: -curH},200,function(){$(".slidemenu-mask").fadeOut(150).remove();}).remove();
	  });
	}
}

$.showProviceMenu= function(data){
		var list= data.list || [];
		var fn= data.callback || function(val){alert(val)};
		var curH= 0;
		var first;
		if($(".slidemenu-frm").length == 0){
			$('<style>'+
				'*{margin:0;padding:0} ul,ol{list-style:none}'+
				'.slidemenu-mask{display:none;background-color:rgba(0,0,0,0.3);position:fixed;z-index:99;width:100%;height:100%;top:0;left:0}'+
				'.slidemenu-frm{width:100%;position:fixed;bottom:-999px;left:0;z-index:100;background-color:#d9d9d9;overflow:hidden;line-height:50px}'+
				'.slidemenu-frm .slidemenu-list{text-align:center;font-size:16px;}'+
				'.slidemenu-frm .slidemenu-list li{float:left;width:14.28%;text-align:center;margin:5px 0;}'+
				'.slidemenu-frm .slidemenu-list li span{display:inline-block;width:70%;background:#fff;text-align:center;height:80%;border-radius:10px;}'+
				'</style>'+
				'<div class="slidemenu-mask"></div>'+
				'<div class="slidemenu-frm">'+
				'	<ul class="slidemenu-list">'+
				'	</ul>'+
				'</div>').appendTo("body");
			first= 1;
		}

		/*$(".slidemenu-frm .slidemenu-title").text(title);*/
		$(".slidemenu-frm .slidemenu-list").html( function(){
			var arr= [];
			$.each(data.list,function(index,el){
				arr.push("<li data-value="+el.value+"><span>"+el.content+"</span></li>")
			})
			return arr.join("");
		});
		curH= $(".slidemenu-frm").height();
		$(".slidemenu-frm").css("bottom", -curH);

		$(".slidemenu-mask").fadeIn(150);
		$(".slidemenu-frm").animate({bottom:0},300);

		$(".slidemenu-frm .slidemenu-list li").click(function(){
			$(".slidemenu-frm").animate({bottom: -curH},200,function(){ $(".slidemenu-mask").fadeOut(150);});
			fn( {value:$(this).data("value"),content:$(this).text()} );
		});

		if(first== 1){
			$(".slidemenu-mask").click(function(){
				$(".slidemenu-frm").animate({bottom: -curH},200,function(){$(".slidemenu-mask").fadeOut(150);});
			});
		}
	}
})(jQuery);
