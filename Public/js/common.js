
$(function(){
    'use strict';
    if(typeof Messenger != "undefined"){
        Messenger.options = {
            extraClasses: 'messenger-fixed messenger-on-top messenger-on-right',
            theme: 'flat'
        }
    }
});
//ajax get请求
$(document).on("click", ".ajax-get", function () {
    var target;
    var that = this;
    if ($(this).hasClass('confirm')) {
        if (!confirm('确认要执行该操作吗?')) {
            return false;
        }
    }
    if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
        var that = $(this);
        $.get(target).success(function (data) {
            console.log(data);
            var message = typeof data.result == "string" ? data.result : "操作成功";
            if (data.result == "OK") {
                var returnURL;
                if (data.result.redirect_url) {
                    remind(message + ' 页面即将自动跳转~', '系统提示');
                } else if (returnURL = that.attr("return-url")) {
                    remind(message + ' 页面即将自动跳转到上一页~', '系统提示');
                } else {
                    remind(message, '系统提示');
                }
                setTimeout(function () {
                    if (data.result.redirect_url) {
                        location.href = data.result.redirect_url;
                    } else if (returnURL = that.attr("return-url")) {
                        if (returnURL == "document.referrer") {
                            location.href = document.referrer;
                        } else {
                            location.href = returnURL;
                        }
                    } else if ($(that).hasClass('no-refresh')) {
                        $('#top-alert').find('button').click();
                    } else {
                        location.reload();
                    }
                }, 1500);
                if (that.attr("remove-class")) {
                    console.log(that.attr("remove-class"));
                    $("." + that.attr("remove-class")).remove();
                }
            } else {
                remind(data.errors[0]['error']?data.errors[0]['error']:"操作失败", "系统提示");
                setTimeout(function () {
                    if (data.result.redirect_url) {
                        location.href = data.result.redirect_url;
                    }
                }, 1500);
            }
        });

    }
    return false;
});


//ajax post submit请求
$('.ajax-post').click(function () {
    var target, query, form;
    var target_form = $(this).attr('target-form');
    var that = this;
    var nead_confirm = false;
    if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
        form = $('.' + target_form);

        if ($(this).attr('hide-data') === 'true') {//无数据时也可以使用的功能
            form = $('.hide-data');
            query = form.serialize();
        } else if (form.get(0) == undefined) {
            return false;
        } else if (form.get(0).nodeName == 'FORM') {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            if ($(this).attr('url') !== undefined) {
                target = $(this).attr('url');
            } else {
                target = form.get(0).action;
            }
            query = form.serialize();
        } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
            form.each(function (k, v) {
                if (v.type == 'checkbox' && v.checked == true) {
                    nead_confirm = true;
                }
            })
            if (nead_confirm && $(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = form.serialize();
        } else {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = form.find('input,select,textarea').serialize();
        }
        $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
        $.post(target, query).success(function (data) {
            console.log(data);
            if (typeof data == 'string' && !data.match("^\{(.+:.+,*){1,}\}$")) {
                remind(result, "系统提示");
            } else {
                var data = typeof data == 'string' ? JSON.parse(data) : data;
                if (data.status == "OK") {
                    var message = typeof data.result == "string" ? data.result : "操作成功";
                    if (data.result.redirect_url) {
                        remind(message + ' 页面即将自动跳转~', '系统提示');
                    } else {
                        remind(message, "系统提示");
                    }
                    setTimeout(function () {
                        if (data.result.redirect_url) {
                            location.href = data.result.redirect_url;
                        } else if ($(that).hasClass('no-refresh')) {

                        } else if ($(that).hasClass("reload")){
                            location.reload();
                        }
                    }, 1500);
                } else {
                    remind(data.errors[0]['error']?data.errors[0]['error']:"操作失败", "系统提示");
                    setTimeout(function () {
                        if (data.result.redirect_url) {
                            location.href = data.result.redirect_url;
                        }
                    }, 1500);
                }
            }
        });
        $(that).removeClass('disabled').prop('disabled', false);;
    }
    return false;
});
function remind(detail,title){
    Messenger().post(title?title+":"+detail:detail);
}