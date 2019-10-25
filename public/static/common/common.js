/**
 * Created by static7<static7@qq.com> on 2017-07-13.
 */
var loading,table_reload,pop_up;
layui.use(['layer', 'form', 'table'], function () {
    layui.$(document).ajaxSend(function () {
        loading = layui.layer.load(2);
    }).ajaxStop(function () {
        layui.layer.close(loading);
    });
    //全选
    layui.form.on('checkbox(all_checkbox)', function (data) {
        var child = layui.jquery(data.elem).parents('table').find('tbody input[type="checkbox"]');
        child.each(function (index, item) {
            item.checked = data.elem.checked;
        });
        layui.form.render('checkbox');
    });
    //ajax post submit请求
    layui.$('.ajax-post').on('click', function () {
        var field = layui.$(this).data('field'),
            value= layui.$(this).data('value') ? layui.$(this).data('value') : 0,
            params = multiples('ids', field),
            Url = layui.$(this).attr('href') || layui.$(this).attr('url'),json={};
        if (!params) {
            layui.layer.msg('请选择数据!');
            return false;
        }
        json[field]=params;
        json['value']=value;
        ajaxSubmit(Url, json,'post');
        return false;
    });

    //ajax get submit请求
    layui.$('.ajax-get').on('click', function () {
        var field = layui.$(this).data('field'),
            value=layui.$(this).data('value') ? layui.$(this).data('value') : 0,
            params = multiples('ids', field),
            Url = layui.$(this).attr('href') || layui.$(this).attr('url'),json={};
        if (!params){
            layui.layer.msg('请选择数据!');
            return false;
        }
        json[field]=params;
        json['value']=value;
        ajaxSubmit(Url, json,'get');
        return false;
    });
    //特殊 ajax提交
    layui.$('.special').on('click', function () {
        var target=layui.$(this).attr('href') ||  layui.$(this).attr('url');
        if (!target){
            layui.layer.msg('链接不能为空');
            return false;
        }
        layui.layer.confirm('确定执行该操作吗?', function (index) {
            layui.layer.close(index);
            layui.$.get(target).success(function (data) {
                status_load(data);
            });
        });
        return false;
    });

    //加载特效
    layui.$('.a_load').on('click',function () {
        layui.layer.load(2);
        window.location.href = layui.$(this).attr('href');
    })

    // 通用返回
    layui.$('.retreat').on('click', function () {
        layui.layer.load(2);
        history.back(-1);
        return false;
    });
});

//状态加载
function status_load(data, that) {
    layui.use(['jquery'], function () {
        if (data.code == 1) {
            (data.url !== null && data.url !== undefined) ? alert_msg(data.msg + ' 页面即将自动跳转~', 1) : alert_msg(data.msg, 1);
            setTimeout(function () {
                layui.$(that).prop('disabled', false);
                if (data.url !== null && data.url !== undefined) {
                    location.href = data.url;
                }
            }, 1500);
        } else {
            alert_msg(data.msg, 0);
            setTimeout(function () {
                layui.$(that).prop('disabled', false);
            }, 1500);
        }
    })
}

//layer通用提示框
function alert_msg(text, icon) {
    layui.use(['layer'], function () {
        text = text || '提交成功，系统未返回信息';
        icon = icon || 0;
        layui.layer.msg(text, {
            icon: icon,
            offset: 70,
            shift: 0
        });
    });
}

//导航高亮
function UrlHighlight(url) {
    layui.use('jquery', function () {
        layui.$('.highlight').find('a[href="' + url + '"]').parent().addClass('layui-this');
    });
}


//基础对象检测
function setChoose(name, value) {
    layui.use(['jquery','form'], function () {
        var first = name.substr(0, 1), input, i = 0, val;
        if (value === "")
            return '';
        if ("#" === first || "." === first) {
            input = layui.$(name);
        } else {
            input = layui.$("[name='" + name + "']");
        }

        if (input.eq(0).is(":radio")) { //单选按钮
            input.filter("[value='" + value + "']").each(function () {
                this.checked = true;
            });
        } else if (input.eq(0).is(":checkbox")) { //复选框
            if (!layui.$.isArray(value)) {
                val = new Array();
                val[0] = value;
            } else {
                val = value;
            }
            for (i = 0, len = val.length; i < len; i++) {
                input.filter("[value='" + val[i] + "']").each(function () {
                    this.checked = true;
                });
            }
        } else {  //其他表单选项直接设置值
            input.val(value);
        }
        layui.form.render();
    });
};

/**
 *拼接url
 */
function createURL(url, param) {//链接和参数
    if (!param){
        return url;
    }
    var link=url + "?";
    layui.use('jquery', function () {
        layui.$.each(param, function (item, key) {
             link +=item + "=" +key+'&';
        })
    });
    return link.substr(0,(link.length-1));
}

/**
 * 选择数据
 * @param container
 * @param key
 * @returns {*}
 */
function multiples(container, key) {
    if (!container) {
        return {};
    }
    var value;
    layui.use(['table', 'jquery','layer'], function () {
        var checkStatus, keys = key ? key : 'ids', ids ='';
        checkStatus = layui.table.checkStatus(container);
        if (checkStatus.data.length === 0) {
            return null;
        }
        layui.$.each(checkStatus.data, function (i, e) {
            ids += e[keys] + ',';
        });
        value = ids.substr(0, ids.length - 1);
    });
    return value;
}

/**
 * 通用异步提交
 * @param url
 * @param param
 */
function ajaxSubmit(url,param,method){
    layui.use(['jquery','layer'], function () {
        layui.layer.confirm('确定执行该操作吗?', function (index) {
            layui.layer.close(index);
            layui.$.ajax({
                url:url,
                type: method == 'get' ?'get':'post',
                async:true,
                data:param,
                dataType:'json',
                timeout:5000,
                cache: false,
                error:function (xhr) {
                    console.log(xhr.responseText);
                    layui.layer.msg(xhr.status+':'+xhr.statusText);
                },
                success:function (result,status,xhr) {
                    layui.layer.msg(result.msg,{offset: 70});
                    if (result.code >= 1) {
                        setTimeout(function () {
                            table_reload.reload();
                        }, 1300);
                    }
                }
            });
        });
    });
}