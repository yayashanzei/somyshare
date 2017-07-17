<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"/home/wwwroot/somyshare.local.com/app/index/view/index/index.html";i:1463710316;s:67:"/home/wwwroot/somyshare.local.com/app/index/view/public/header.html";i:1464840160;s:67:"/home/wwwroot/somyshare.local.com/app/index/view/public/footer.html";i:1464832938;}*/ ?>
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


<div class="banner layout" data-pointer="0">
    <div class="carousel layout">
        <div class="item img-responsive"><img src="<?php echo _IMG; ?>/banner1.jpg" class="img-responsive"/></div>
        <div class="item  img-responsive"><img src="<?php echo _IMG; ?>/banner1.jpg" class="img-responsive"/></div>
        <div class="item  img-responsive"><img src="<?php echo _IMG; ?>/banner1.jpg" class="img-responsive"/></div>
    </div>
</div>
<script type="text/javascript">

    $(window).resize(function () {
        var resize = $('.carousel');
        var windowsize = parseInt($(window).width());
        resize.find('img').width(windowsize);
        resize.find('.item').width(windowsize);
        resize.width(windowsize * 3);
    });
</script>
<div class="layout">
    <div class="container margin-large-top margin-large-bottom">
        <div class="x4">
            <img class="img-responsive" src="<?php echo _IMG; ?>/p1.jpg"/>
        </div>
        <div class="x4">
            <img class="img-responsive zoom_img_2" src="<?php echo _IMG; ?>/p2.jpg"/>
        </div>
        <div class="x4">
            <img class="img-responsive float-right" src="<?php echo _IMG; ?>/p3.jpg"/>
        </div>
    </div>
</div>

<div class="layout">
    <div class="container">
        <img src="<?php echo _IMG; ?>/p2c.jpg" class="img-responsive"/>
    </div>
</div>


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



