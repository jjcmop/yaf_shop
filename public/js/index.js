$(function(){
    //导航栏tap
    $(document).on("click",'.sub_tab',function(){
        $(this).addClass('active').siblings().removeClass("active");
    });


});