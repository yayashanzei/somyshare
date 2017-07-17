<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"D:\myfiles\www\somy_disk\app\admin\view\public\login.html";i:1464309903;}*/ ?>
﻿<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>索镁 :=: disk后台管理</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if((!empty($static_base))): echo $static_base; endif; ?>

</head>
<body>
<div class="admin-login-login-admin"></div>
<div class="login x12">

    <div class="fadein-top login_box  x4 x4-move  box-shadow-big bg-white border-main border-big border-bottom ">

        <div class="logo border-main border-small border-bottom">s<span>o</span>my</div>

        <div class="x8 x2-move margin-large-top">

            <form method="post" class="form form-block">

                <div class="form-group margin-bottom">
                    <div class="label">
                        <label for="username">用户名</label>
                    </div>
                    <div class="field">
                        <input type="text" class="input" id="username" name="username" size="50" data-validate="required:必填"
                               placeholder="账号" tabindex="1"/>
                    </div>
                </div>
                <div class="form-group margin-bottom">
                    <div class="label">
                        <label for="password">密　码</label>
                    </div>
                    <div class="field">
                        <input type="password" class="input" id="password" name="password" size="50" data-validate="required:必填"
                               placeholder="请输入密码" tabindex="2"/>
                    </div>
                </div>
                <div class="form-group margin-big-bottom">
                    <div class="label">
                        <label for="verify">验证码</label>
                    </div>
                    <div class="field clearfix">
                        <input type="text" class="input" style="width:60%;float: left;" id="verify" name="verify"
                               placeholder="请填入验证码" tabindex="3"/>
                        <img class="verifyimg reloadverify x4" src="<?php echo U('admin/Verify/index'); ?>" alt="切换验证码" title="切换验证码"
                             style="cursor: pointer;width:100px;height:34px;display:block;float:left;margin-left:15px;vertical-align: middle;"/>
                    </div>
                </div>
                <div class="form-button margin-big-bottom text-center">
                    <a class="button login_button x12 bg-green margin-large-bottom"  href="<?php echo U('index'); ?>" data-id="login" data-group="3" tabindex="4"
                       onclick="adminAjaxAsk(this,{type:'post',form:'form-block'});return false;">登　　录</a>
                </div>
            </form>
        </div>


    </div>

</div>

<script>

    $(function () {

        $("body").keydown(function (event) {
            if (event.keyCode == "13") {
                $('.login_button').click();
            }
        });
        if ($('.adi_h_nav').length > 0) {
            window.location.reload();
        }
        var verifyimg = $(".verifyimg").attr("src");
        $(".reloadverify").click(function () {
            if (verifyimg.indexOf('?') > 0) {
                $(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
            } else {
                $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
            }
        });
    });
</script>
</body>
</html>