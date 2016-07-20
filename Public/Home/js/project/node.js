$(function() {
    $("div[id='save']").click(function() {
        addNode();
    });

    $("div[id='saveModify']").click(function() {
        modifyNode();
    });

    $("div[id='cancel']").click(function() {
        window.location = "node.html";
    });
});

/* 添加权限 */
function addNode() {
    if ($("#name").val() == "") {
        $.showTips("权限名称不能为空，请输入！", "提示");
        $("#name").focus();
        return;
    }
/*    if ($("#sort").val() == "") {
        $.showTips("权重不能为空，请输入！", "提示");
        $("#sort").focus();
        return;
    }*/
    if ($("#name").val() == "" && $("#pid").val() != 0) {
        $.showTips("链接地址不能为空，请输入！", "提示");
        $("#name").focus();
        return;
    }
    var url = 'add_node.html';
    var pars = {
        name : $("#name").val(),
        pid : $("#pid").val(),
        status : $("#status").val(),
        sort : $("#sort").val(),
        title : $("#title").val()
    };
    $.post(url, pars, function(data) {
        if (data.code != 200) {
            $.showTips(data.msg, "提示");
        } else {
            $.showTips("添加权限完成", "提示");
            setTimeout(function() {
                window.location = "node.html";
            }, 1000);
        }
    });
}

/* 修改权限 */
function modifyNode() {
    if ($("#title").val() == "") {
        $.showTips("权限名称不能为空，请输入！", "提示");
        $("#title").focus();
        return;
    }
    if ($("#sort").val() == "") {
        $.showTips("权重不能为空，请输入！", "提示");
        $("#sort").focus();
        return;
    }
    if ($("#name").val() == "" && $("#pid").val() != 0) {
        $.showTips("链接地址不能为空，请输入！", "提示");
        $("#name").focus();
        return;
    }
    var url = 'edit_node.html';
    var pars = {
        id : $("#id").val(),
        title : $("#title").val(),
        pid : $("#pid").val(),
        status : $("#status").val(),
        sort : $("#sort").val(),
        name : $("#name").val()
    };
    $.post(url, pars, function(data) {
        if ( data.code != 200 ) {
            $.showTips(data.msg, "提示");
        } else {
            $.showTips("修改权限完成", "提示");
            setTimeout(function() {
                window.location = "node.html";
            }, 1000);
        }
    });
}

function delNode(id) {
    $.showDialog({
        "msgContent":"请确认是否删除此权限？",
        "btns":[
            {text:"确认",className:"",act: function(){
                // 隐藏提示框
                $(".dialog-frm").css("display", "none");
                $(".dialog-mask").css("display", "none");
                var url = 'delete_node.html';
                var pars = {
                    id: id
                };
                $.post(url, pars, function(data) {
                    if (data.code != 200) {
                        $.showTips(data.msg,"提示");
                    } else {
                        $.showTips("删除权限完成","提示");
                       window.location = "node.html";
                    }
                });
            }},
            {text:"取消",className:"",act:"_hide"}
        ]
    });
};

/**/
function modify_node(id) {
    window.location = 'edit_node.html?id='+id;
}

function add_node() {
    window.location = 'add_node.html';
}
