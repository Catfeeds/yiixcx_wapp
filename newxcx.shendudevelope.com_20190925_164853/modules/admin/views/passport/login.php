<?php


defined('YII_ENV') or exit('Access Denied');

use app\models\Option;

$this->title = '管理员登录';
$logo = Option::get('logo', 0, 'admin', null);
$logo = $logo ? $logo : Yii::$app->request->baseUrl . '/statics/admin/images/logo.png';
$copyright = Option::get('copyright', 0, 'admin');
$copyright = $copyright ? $copyright : '云上科技';
$passport_bg = Option::get('passport_bg', 0, 'admin', Yii::$app->request->baseUrl . '/statics/admin/images/passport-bg.jpg');
$open_register = Option::get('open_register', 0, 'admin', false);
?>
<style>
    html {
        position: relative;
        min-height: 100%;
        height: 100%;
    }

    body {
        padding-bottom: 70px;
        height: 100%;
        overflow: hidden;
    }

    .main {
        background-image: url("<?=$passport_bg?>");
        background-size: cover;
        background-position: center;
        height: 100%;
    }

    form {
        max-width: 420px;
        margin: 0 auto;
    }


    form h1 {
        font-size: 20px;
        font-weight: normal;
        text-align: center;
        margin: 0 0 32px 0;
    }

    form .custom-checkbox .custom-control-indicator {
        border: 1px solid #ccc;
        background-color: #eee;
    }

    form .custom-control-input:checked ~ .custom-control-indicator {
        border-color: transparent;
    }

    .header {
        height: 50px;
        background: rgba(255, 255, 255, .5);
        margin-bottom: 120px;
    }

    .header a {
        display: inline-block;
        height: 50px;
        padding: 8px 30px;
    }

    .logo {
        display: inline-block;
        height: 100%;
    }

    .footer {
        position: absolute;
        height: 70px;
        background: #fff;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    .copyright {
        padding: 24px 0;
    }
</style>
<div class="top_div" id="app">
    <div class="top">
        <h5>云上科技<em></em></h5>
        <h2>系统管理中心</h2>

    </div>
</div>


<div style="width: 400px;height: 270px;margin: auto auto;background: #ffffff;text-align: center;margin-top: -100px;border: 1px solid #e7e7e7">
    <div style="width: 165px;height: 96px;position: absolute">
        <div class="tou"></div>
        <div id="left_hand" class="initial_left_hand"></div>
        <div id="right_hand" class="initial_right_hand"></div>
    </div>

    <p style="padding: 30px 0px 10px 0px;position: relative;">
        <span class="u_logo"></span>
        <input class="ipt" type="text" placeholder="帐号" id="username" name="username" autocomplete="off" required="">
    </p>
    <p style="position: relative;">
        <span class="p_logo"></span>
        <input id="password" class="ipt" type="password" placeholder="密码" name="password" autocomplete="off" required="" pattern="[\S]{6}[\S]*" title="密码不少于6个字符">
    </p>

    <p style="position: relative;height:50px;">
        <span class="p_logo"></span>
        <input class="ipt" name="captcha_code" placeholder="图片验证码" style="width: 150px;display: inline-block;float: left;margin-left: 30px;" id="captcha_code">
        <img class="refresh-captcha"
             data-refresh="<?= Yii::$app->urlManager->createUrl(['admin/passport/captcha', 'refresh' => 1,]) ?>"
             src="<?= Yii::$app->urlManager->createUrl(['admin/passport/captcha',]) ?>"
             style="height: 33px;width: 80px;float: left;cursor: pointer;margin-left:40px;margin-top:5px;" title="点击刷新验证码">
    </p>
    <div style="height: 50px;line-height: 50px;margin-top: 0px;border-top: 1px solid #e7e7e7;padding:0 35px;">

           <span style="float: left;position: relative;">

            </span>


        <span style="float: right;line-height:20px;margin-top:10px;">

               <a href="javascript:void(0)" style="background: #008ead;padding: 7px 10px;border-radius: 4px;border: 1px solid #1a7598;color: #FFF;font-weight: bold;font-size: 16px" class="btn-submit btn-block btn-primary submit-btn btn">登录</a>
           </span>

    </div>

    <div class="submit2"></div>

</div>



<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            admin_list: [],
        },
    });
    $(document).on('click', '.refresh-captcha', function () {
        var img = $(this);
        var refresh_url = img.attr('data-refresh');
        $.ajax({
            url: refresh_url,
            dataType: 'json',
            success: function (res) {
                img.attr('src', res.url);
            }
        });
    });
    function formcheck() {

        return true;
    }
    var h = document.documentElement.clientHeight;
    $(".system-login").css('height',h);

    /*
     * 为低版本IE添加placeholder效果
     *
     * 使用范例：
     * [html]
     * <input id="captcha" name="captcha" type="text" placeholder="验证码" value="" >
     * [javascrpt]
     * $("#captcha").nc_placeholder();
     *
     * 生效后提交表单时，placeholder的内容会被提交到服务器，提交前需要把值清空
     * 范例：
     * $('[data-placeholder="placeholder"]').val("");
     * $("#form").submit();
     *
     */
    (function($) {
        $.fn.nc_placeholder = function() {
            var isPlaceholder = 'placeholder' in document.createElement('input');
            return this.each(function() {
                if(!isPlaceholder) {
                    $el = $(this);
                    $el.focus(function() {
                        if($el.attr("placeholder") === $el.val()) {
                            $el.val("");
                            $el.attr("data-placeholder", "");
                        }
                    }).blur(function() {
                        if($el.val() === "") {
                            $el.val($el.attr("placeholder"));
                            $el.attr("data-placeholder", "placeholder");
                        }
                    }).blur();
                }
            });
        };
    })(jQuery);

    $(function(){
        //得到焦点
        $("#password").focus(function(){
            $("#left_hand").animate({
                left: "150",
                top: " -38"
            },{step: function(){
                    if(parseInt($("#left_hand").css("left"))>140){
                        $("#left_hand").attr("class","left_hand");
                    }
                }}, 2000);
            $("#right_hand").animate({
                right: "-64",
                top: "-38px"
            },{step: function(){
                    if(parseInt($("#right_hand").css("right"))> -70){
                        $("#right_hand").attr("class","right_hand");
                    }
                }}, 2000);
        });
        //失去焦点
        $("#password").blur(function(){
            $("#left_hand").attr("class","initial_left_hand");
            $("#left_hand").attr("style","left:100px;top:-12px;");
            $("#right_hand").attr("class","initial_right_hand");
            $("#right_hand").attr("style","right:-112px;top:-12px");
        });
        //显示隐藏验证码
        $("#hide").click(function(){
            $(".code").fadeOut("slow");
        });
        $("#captcha").focus(function(){
            $(".code").fadeIn("fast");
        });
        //跳出框架在主窗口登录
        if(top.location!=this.location)	top.location=this.location;
        $('#user_name').focus();
        if ($.browser.msie && ($.browser.version=="6.0" || $.browser.version=="7.0")){
            window.location.href='http://localhost/ynlmsc/adminmin/templates/default/ie6update.html';
        }
        $("#captcha").nc_placeholder();
        //动画登录
        $('.btn-submit').click(function(e){

            setTimeout(function () {

                    $('.submit2').html('<div class="progress"><div class="progress-bar progress-bar-success" aria-valuetransitiongoal="100"></div></div>');
                    $('.progress .progress-bar').progressbar({
                        done : function() {
                            var username = $('#username').val();
                            var password = $('#password').val();
                            var captcha_code = $('#captcha_code').val();
                            $.ajax({
                                url:'<?=Yii::$app->urlManager->createUrl('admin/passport/login')?>',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    'username': username,
                                    'password': password,
                                    'captcha_code': captcha_code,
                                    _csrf: _csrf,
                                },
                                success:function (res) {
                                    if (res.code === 1) {
                                        $.myAlert({
                                            content: res.msg
                                        });
                                    }else  {
                                        location.href = "<?= \Yii::$app->urlManager->createUrl('admin/user/me') ?>";
                                    }
                                }
                            })

                        }
                    });
                },
                300);

        });

        // 回车提交表单
        $('#form_login').keydown(function(event){
            if (event.keyCode == 13) {
                $('.btn-submit').click();
            }
        });
        $('#verifycode').focus(function(){
            $('a.change_img').trigger("click");
        });
    });

    $(document).on('click', '.send-sms-code', function () {
        var form = document.getElementById('send_sms_code_form');
        var mobile = form.mobile.value;
        var captcha_code = form.captcha_code.value;
        var btn = $(this);
        btn.btnLoading();
        $('.send-sms-code-error').html('').hide();
        $.ajax({
            url: form.action,
            type: 'post',
            dataType: 'json',
            data: {
                mobile: mobile,
                captcha_code: captcha_code,
                _csrf: _csrf,
            },
            complete: function () {
                btn.btnReset();
            },
            success: function (res) {
                if (res.code == 1) {
                    $('.send-sms-code-error').html(res.msg).show();
                }
                if (res.code == 0) {
                    $('#send_sms_code_form').hide();
                    $('#reset_password_form').show();
                    app.admin_list = res.data.admin_list;
                }
            },
        });
    });

    $(document).on('click', '.reset-password', function () {
        var form = document.getElementById('reset_password_form');
        var admin_id = form.admin_id.value;
        var sms_code = form.sms_code.value;
        var password = form.password.value;
        var password2 = form.password2.value;
        if (password.length < 6) {
            $('.reset-password-error').html('密码长度不能低于6位。').show();
            return false;
        }
        if (password != password2) {
            $('.reset-password-error').html('两次输入的密码不一致。').show();
            return false;
        }
        var btn = $(this);
        btn.btnLoading();
        $('.reset-password-error').html('').hide();
        $.ajax({
            url: form.action,
            type: 'post',
            dataType: 'json',
            data: {
                admin_id: admin_id,
                sms_code: sms_code,
                password: password,
                _csrf: _csrf,
            },
            complete: function () {
                btn.btnReset();
            },
            success: function (res) {
                if (res.code == 1) {
                    $('.reset-password-error').html(res.msg).show();
                }
                if (res.code == 0) {
                    $('#resetPassword').hide();
                    $.myAlert({
                        content: res.msg,
                        confirm: function () {
                            location.reload();
                        }
                    });
                }
            },
        });
    });
</script>