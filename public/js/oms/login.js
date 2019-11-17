var refreshCaptcha = function () {

            $.ajax({
                url: config.routes.refreshCaptcha,
                type: 'get',
                dataType: 'json',
                beforeSend:function(){
                },
                success: function (e) {
                    $(".captcha").attr('src', e.source);
                    $(".captcha").on('load',function(){
                        $("#loaderCaptcha").hide();
                    });
                }
            });

        };
        
        
        $(".captcha").on('click', function (e) {
       refreshCaptcha();
    });
    
$(document).ready(function(){
    refreshCaptcha();
});

