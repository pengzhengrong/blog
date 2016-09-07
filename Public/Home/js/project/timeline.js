var article = $('#model1').html();
var ScrollingModal = $('#model2').html();

$(function(){

	$('#timeline').on('dblclick','.subTitle',callback);
	$('#timeline').on('dblclick','.content', callback);
	$('#timeline').on('dblclick','.title', callback);
	$('#timeline').on('dblclick','.desc', callback);

	$('#timeline').on('dblclick','.addNode', addNode);
	$('#save').on('click',addTimeline);
	$('#add').on('click',addPage);

	/*$('#addNode').click(function(event){
		var timeline = $('.timeline-item');
		var len = timeline.length;
		var beforeLast = $(timeline[len-3]);
		var className = beforeLast.attr('class');
		var float = className == 'timeline-item left'?'right':'left';
		var temp = article;
		var uuid = getUuid();
		temp = temp.replace(/#LEFTORRIGHT#/g, float);
		temp = temp.replace('#SCROLLING_MODAL_ID#', uuid);
		beforeLast.after( temp );

		addScrollingModal(uuid);
	});*/

})

function init(rest) {
	$('#init').remove();
	$('#scrollingModal').remove();
	var title = rest.title;
	var extra = rest.extra;
	var extra = eval( '('+extra+')' );
	$('.title').html(title);
	// console.log(extra.length);
	for( var i = 0,len = extra.length; i < len; i++ ) {
		var desc = extra[len-1-i].desc;
		var subTitle = extra[len-1-i].subTitle;
		var content = extra[len-1-i].content;
		var float = i%2==0?'':'left';
		// console.log(i+' '+float);
		var uuid = getUuid();
		var temp = addArticleModel(float, uuid, desc, subTitle);
		$('#firstArticle').after(temp);
		// console.log(content);
		addScrollingModal(uuid, subTitle, content);
	}
}

function addNode(event) {
	var _this = $(event.currentTarget);
	var item = $(_this.parents('.timeline-item')[0]);
	var className = item.attr('class')
	var float = className == 'timeline-item left'?'':'left';
	var uuid = getUuid();
	var temp = addArticleModel(float, uuid);
	item.after( temp );

	addScrollingModal(uuid);
}

function addArticleModel(float, uuid, desc='', subTitle='') {
	var temp = article;
	temp = temp.replace(/#LEFTORRIGHT#/g, float);
	temp = temp.replace('#SCROLLING_MODAL_ID#', uuid);
	if( desc != '' ) {
		temp = temp.replace('100字内...', desc);
	}
	if ( subTitle != '' ) {
		temp = temp.replace('双击文字', subTitle);
	}
	return temp;
}

function addScrollingModal(uuid, title='', content='') {
	var model2 = ScrollingModal.replace('#SCROLLING_MODAL_ID#', uuid);
	model2 = model2.replace('#SCROLLING_MODEL_TITLE_ID#', uuid+'Title');
	if (content != '') {
		model2 = model2.replace('500字内...',content);
	}
	if ( title != '' ) {
		model2 = model2.replace('#SCROLLING_MODEL_TITLE#', title);
	}
	$('#modal-contain').after(model2);
}

function callback(event) {
	var _this = event.currentTarget;
	var temp = $(_this).text();
	var className = $(_this).attr('class');
	var htmlTag = '';
	switch(className) {
		case 'subTitle':
		htmlTag = '<input id="'+className+'" value="'+temp+'">';
		break;
		case 'title':
		htmlTag = '<input id="'+className+'" style="color:#1abc9c;" value="'+temp+'">';
		break;
		case 'desc':
		htmlTag = '<textarea maxlength="150" style="width:430px;height:160px;overflow:auto;" id="'+className+'">'+temp+'</textarea>';
		break;
		default:
		className = 'content';
		htmlTag = '<textarea maxlength="500" style="width:598px;height:240px;overflow:auto;" id="'+className+'">'+temp+'</textarea>';
		break;
	}
	$(_this).replaceWith( htmlTag );
	$('#'+className).focus();
	$('#'+className).blur(function(){
		var innerHtml = $(this).val();
		$(_this).text(innerHtml);
		$(this).replaceWith(_this);
		if ( className == 'subTitle' ) {
			// 编辑subTitle时，同时修改内容区域的title
			var ScrollingModalId = $(_this).parent().find('.btn-lg').attr('data-target');
			$(ScrollingModalId+'Title').text(innerHtml);
		}
		

	});
}

function getUuid() {
	var s = [];
	var hexDigits = "0123456789abcdefghijklmnopqrstuvwxyz";
	for (var i = 0; i < 36; i++) {
		s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
	}
	s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
	s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
	s[8] = s[13] = s[18] = s[23] = "-";

	var uuid = s.join("");
	return uuid;
}

function addTimeline() {
	var info = {
		'subTitle':[],
		'title':'',
		'desc':[],
		'content':[]
	};
	var fields = [
	'subTitle','desc','content'
	];
	var title = $('.title').text();
	for( var v in fields ) {
		var item = fields[v];
		var temp = document.getElementsByClassName(item);
		for( var i=0, len = temp.length; i < len-1; i++) {
			if ( item == 'content' ) {
				info[item].push( $(temp[i]).val() );
			} else {
				info[item].push( $(temp[i]).text() );
			}
		}
	}
	info.title = title;
	if( $('#id').val() > 0 ) {
		info.id = $('#id').val();
	}
	// console.log(info);
	$.post('/Home/Timeline/detail.html',{info:info},function(data){
		if ( data.code != 200 ) {
			$.showTips(data.msg, "提示");
		} else {
			$.showTips("操作完成", "提示");
			setTimeout(function() {
				window.location = "/Home/Timeline/index.html";
			}, 1000);
		}
	});
}

function detail(id) {
	window.location = '/Home/Timeline/detail.html?id='+id;
}

function addPage() {
	window.open('/Home/Timeline/detail.html','_blank');
}

function del(id) {
	$.showDialog({
		"msgContent":"确认删除该条记录吗？",
		"btns":[
		{text:"确认",className:"",act: function(){
                // 隐藏提示框
                $(".dialog-frm").css("display", "none");
                $(".dialog-mask").css("display", "none");
                var _this = event.currentTarget;
                var pars = {id: id};
                $.post("/Home/Timeline/delete.html",pars,
                	function(data) {
                		if( data.code == 200 ) {
                			$.showTips("删除完成", "提示");
                			window.location.reload();
                		} else {
                			$.showTips( data.msg , "提示");
                		}
                	});
            }},
            {text:"取消",className:"",act:"_hide"}
            ]
        });
}


