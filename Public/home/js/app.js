/**
 * Created by sunguide on 2015-04-06 00:49:11
 */
$("#header-menu .login.user-info").mouseover(function(){
    $("#header-menu .login.user-info").addClass("open");
});
$("#header-menu .login.user-info").mouseout(function(){
    $("#header-menu .login.user-info").removeClass("open");
});

$("#btn_vote").click(function(){
    var $span = $(this).find("span");
    if($span.hasClass("voted")){
        $span.html(parseInt($span.html())-1);
        $span.removeClass("voted");
    }else{
        $span.html(parseInt($span.html())+1);
        $span.addClass("voted");
    }
});