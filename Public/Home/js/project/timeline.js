var article = '<article class="timeline-item #LEFTORRIGHT#"><div class="timeline-layer"><div class="panel"><div class="panel-heading"><h1> <div class="subTitle">双击修改标题</div>#BUTTON#</h1></div>'+
                            '<div class="panel-body">'+
                                '<span class="arrow-left"></span>'+
                                '<div class="timeline-icon"></i><i class="fa fa-arrow-down"></i> </div>'+
                               '<p class="content">双击修改内容</p>'+
                            '</div> </div></div></article>'; 
 var button = '<button style="float:#LEFTORRIGHT#" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#scrollingModal">查看更多</button>';                           

$(function(){
    $('#timeline').on('dblclick','.subTitle',callback);
    $('#timeline').on('dblclick','.content', callback2);
    $('#timeline').on('dblclick','.title', callback3);

    $('#addNode').click(function(event){
        var timeline = $('.timeline-item');
        var len = timeline.length;
        var beforeLast = $(timeline[len-2]);
        var className = beforeLast.attr('class');
        var float = className == 'timeline-item left'?'right':'left';
        var temp = article;
        temp = temp.replace('#BUTTON#', button);
        temp = temp.replace(/#LEFTORRIGHT#/g, float);
        beforeLast.after( temp );
    });

})
function callback(event) {
    var _this = event.currentTarget;
    var subTitle = $(_this).text();
    var input = '<input id="subTitle" value="'+subTitle+'">';
    $(_this).replaceWith( input );
    $('#subTitle').focus();
    $('#subTitle').blur(function(){
        subTitle = $(this).val();
        $(this).replaceWith('<div class="subTitle">'+subTitle+'</div>')
    });
}

function callback2(event) {
    var _this = event.currentTarget;
    var content = $(_this).text();
    var textarea = '<textarea style="width:430px;height:160px;overflow:auto;" id="content">'+content+'</textarea>';
    $(_this).replaceWith(textarea);
    $('#content').focus();
    $('#content').blur(function(){
        content = $(this).val();
        $(_this).text(content);
        $(this).replaceWith(_this);
    });
}

function callback3(event) {
    var _this = event.currentTarget;
    var title = $(_this).text();
    var input = '<input id="title" style="color:#1abc9c;" value="'+title+'">';
    $(_this).replaceWith( input );
    $('#title').focus();
    $('#title').blur(function(){
        title = $(this).val();
        $(this).replaceWith('<span class="title">'+title+'</span>')
    });
}


