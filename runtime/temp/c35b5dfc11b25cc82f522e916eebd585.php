<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:66:"/home/wwwroot/somyshare.local.com/app/index/view/public/login.html";i:1464839218;s:67:"/home/wwwroot/somyshare.local.com/app/index/view/public/header.html";i:1464840160;s:67:"/home/wwwroot/somyshare.local.com/app/index/view/public/footer.html";i:1464832938;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="索镁"/>
<meta name="description" content="SOMYSHARE外贸营销分享平台。"/>
<title>SOMYSHARE外贸营销分享平台</title>
<?php if((!empty($static_base))): echo $static_base; endif; ?>
<!--[if lt IE 10]>
<script src="<?php echo _COMMON; ?>/js/PIE.js"></script>
<![endif]-->
<script src="<?php echo _JS; ?>/index.js"></script>
<link type="image/x-icon" href="/favicon.ico" rel="shortcut icon"/>
<link href="/favicon.ico" rel="bookmark icon"/>
</head>
<body>

<div class="layout margin-big-top margin-big-bottom">
    <div class="container">
        <div class="x12">
            <div class="x3">
                <a href="/"><img src="<?php echo _IMG; ?>/logo.jpg"/></a>
            </div>
            <div class="x6">

            </div>
            <div class="x3">
                <div class="float-right hidden-l">
                    <?php if(empty($userInfo)): ?>
                        <a href="/login" class="text-big margin-large-top">登录</a>
                        <span class="text-default text-gray padding-left padding-right"> | </span>
                        <a href="/register" class="text-big">注册</a>
                    <?php else: ?>
                        <div class="button-group">
                            <ul class="nav nav-menu nav-inline nav-navicon">
                                <li class="active">
                                    <a href="#">
                                        <?php if(!empty($userInfo['avatarPic'])): ?>
                                        <img src="<?php echo $userInfo['avatarPic']; ?>" onerror="this.src='<?php echo _IMG; ?>/nologin.gif'" class="radius-circle avatar border" />
                                        <?php else: ?>
                                        <img src="<?php echo _IMG; ?>/nologin.gif" class="radius-circle avatar border" />
                                        <?php endif; ?>
                                    </a>
                                    <div class="user_name text-center"><?php if(empty($userInfo['name'])): echo $userInfo['email']; else: echo $userInfo['name']; endif; ?></div>
                                    <ul class="drop-menu">
                                        <li><a href="/member.html">个人中心</a></li>
                                        <li><a href="/logout.html" onclick="indexAjaxAsk(this);return false;">退出</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
                <button class="button icon-navicon float-right" data-target="#nav-link"></button>
            </div>
        </div>
    </div>
</div>

<div class="layout bg-sred bg-inverse padding-top-bottom padding-small">
    <div class="container">
        <div class="x12">
            <div class="x9">
                <ul class="nav nav-inline nav-navicon padding-small-top nav-menu" id="nav-link">
                    <li class="show-l" style="display: none;">
                        <?php if(empty($userInfo)): ?>
                        <a href="/login" class="text-big margin-large-top">登录</a>
                        <a href="/register" class="text-big">注册</a>
                        <?php else: ?>

                                    <a href="#">
                                        <?php if(!empty($userInfo['avatarPic'])): ?>
                                        <img src="<?php echo $userInfo['avatarPic']; ?>" onerror="this.src='<?php echo _IMG; ?>/nologin.gif'" class="radius-circle avatar border" />
                                        <?php else: ?>
                                        <img src="<?php echo _IMG; ?>/nologin.gif" class="radius-circle avatar border" />
                                        <?php endif; ?>
                                    </a>
                                        <a href="/member.html">个人中心</a>
                                        <a href="/logout.html" onclick="indexAjaxAsk(this);return false;">退出</a>


                        <?php endif; ?>
                    </li>
                    <?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>
                    <li><a href="/<?php echo $nav['url']; ?>" class="text-big"><?php echo $nav['title']; ?></a></li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    <li class="show-l" style="display: none;">
                        <input type="text" class="input margin-little-top" placeholder="请输入关键字"/>
                    </li>
                </ul>
            </div>
            <div class="x3">
                <input type="text" class="input margin-little-top hidden-l hidden-s" placeholder="请输入关键字"/>
            </div>
        </div>
    </div>
</div>


<div class="login x12">
    <div class="login_bg"><img src="<?php echo _IMG; ?>/reg_bg.jpg" class="bg_img"/></div>

    <div class="fadein-top login_box x4 x4-move bg-white radius-big ">

        <div class="x10 x1-move margin-large-top padding-top padding-bottom">

            <form method="post" class="form form-block">

                <div class="form-group margin-bottom">
                    <div class="label">
                        <label for="name">用户名 </label>
                    </div>
                    <div class="field clearfix">
                        <input type="text" class="input" id="name" name="name" size="50" data-validate="required:用户名不能为空！"
                               placeholder="邮箱/昵称" tabindex="1"/>
                    </div>
                </div>
                <div class="form-group margin-bottom">
                    <div class="label">
                        <label for="password">密　码 </label>
                    </div>
                    <div class="field clearfix">
                        <input type="password" class="input" id="password" name="password" size="50"
                               data-validate="required:密码不能为空!"
                               placeholder="请设置密码" tabindex="2"/>
                    </div>
                </div>

                <div class="form-group margin-big-bottom">
                    <div class="label">
                        <label for="verify">验证码</label>
                    </div>
                    <div class="field clearfix">
                        <div class="x12">
                            <input type="text" class="input" style="width:60%;float: left;" id="verify" name="verify"
                                   placeholder="请填入验证码" tabindex="3"
                                   data-validate="required:　@_@regexp#(?!^\\S+$).{4}:验证码必须4位"/>
                            <img class="verifyimg reloadverify x4" src="<?php echo U('index/Verify/index'); ?>" alt="切换验证码" title="切换验证码"
                                 style="cursor: pointer;width:100px;height:34px;display:block;float:left;margin-left:15px;vertical-align: middle;"/>
                        </div>
                    </div>
                </div>
                <div class="form-button margin-big-bottom margin-big-top text-center">
                    <a class="button button-big login_button x6 bg-red margin-large-bottom" href="<?php echo U('login'); ?>" tabindex="4"
                       onclick="return false;">登　陆</a>

                    <div class="x6 text-center height-large text-gray">
                        还没有帐号 立即
                        <a class="text-big text-green" href="<?php echo U('/register'); ?>" tabindex="4">注册</a>
                    </div>

                </div>
            </form>
        </div>


    </div>

</div>
<div class="clear"></div>
<script type="text/javascript">

    $(function () {


        $('.login').animate({'height': $('.login_bg').height()}, 500);

        var interval = setInterval(function () {
            if (parseInt($('.login').height()) < 250) {
                $('.login').stop(true).animate({'height': $('.login_bg').height()}, 500);
            } else {
                clearInterval(interval);
            }
        }, 500);

        $('.login_box').animate({'opacity': '.9'}, 500,function(){
            var _offset = $('.login_box').height()+$('.login_box').offset().top;
            if($('.login_bg').height()<_offset){
                $('.login_bg').find('img').height(_offset);
                $('.login').height(_offset);
            }
        });

        $('.login_box').hover(
                function () {
                    $(this).stop(true).animate({'opacity': '1'}, 300);
                },
                function () {
                    $(this).stop(true).animate({'opacity': '.9'}, 300);
                }
        );

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

        $(".login_button").click(function () {
            var login = $(this)[0];
            $(".form-block").ajaxSubmit(function () {
                indexAjaxAsk(login, {type: 'post', form: 'form-block'});
            });
        });


    });
</script>

<div class="layout bg-littleblack footer padding-top padding-bottom border-little border-top border-red">
    <div class="x12 show-l" style="display:none;">
	<p class="text-center">CopyRight 2010-2016 www.somyshare.com All Rights Reserved 陕ICP备14012129号-2</p>
    </div>
    <div class="container table-responsive hidden-l">
        <div class="x12">
            <div class="x2 margin-top">
                <a href="#"><strong>关于索镁</strong></a>

                <div class="margin-top">
                    <div class="height"><a href="/about/us">关于索镁</a></div>
                    <div class="height"><a href="/about/faq">常见问题</a></div>
                </div>
            </div>
            <div class="x2 x1-move margin-top">
                <a href="#"><strong>联系我们</strong></a>

                <div class="margin-top">
                    <div class="height">电话：029-68686809</div>
                    <div class="height">传真：029-68686809</div>
                    <div class="height">地址：西安市雁塔区丈八一路绿地SOHO A座1909室</div>
                </div>
            </div>
            <div class="x3 x1-move margin-top">
                <a href="#"><strong>索镁宗旨</strong></a>

                <div class="margin-top">
                    <p>我们的目标是让想真正做好外贸电子商务的公司和个人能系统和科学的学会最新最实用的方法和技巧。我们坚信要做好网络营销只能靠自己。</p>
                </div>
            </div>
            <div class="x2 x1-move margin-top">
                <img src="<?php echo _IMG; ?>/somyshare.jpg" class="img-responsive ewm"/>
                <p>扫我获取外贸干货</p>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>
</body>
</html>



