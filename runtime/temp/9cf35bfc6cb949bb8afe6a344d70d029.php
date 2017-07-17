<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"/home/wwwroot/somyshare.local.com/app/admin/view/index/index.html";i:1463035920;}*/ ?>
﻿<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>索镁 :=: disk后台管理</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if((!empty($static_base))): echo $static_base; endif; if((!empty($static_nicescroll))): echo $static_nicescroll; endif; ?>


</head>
<body>

<div class="main">

    <div class="left">
        <div class="l_top pie">s<span>o</span>my</div>
        <div class="l_con">

            <div class="left_menu">

                <div class="info">
                    <div class="info_lst_t pie">登陆信息
                        <div class="info_lst_t_ico icon-laptop"></div>
                        <div class="info_lst_t_ico2 icon-angle-down"></div>
                    </div>

                    <div class="info_lst">帐号:&nbsp;<span><?php echo (isset($userInfo['name']) && ($userInfo['name'] !== '')?$userInfo['name']:'暂无'); ?></span> <a class="tag bg-main text-white" style="text-decoration: none;" href="<?php echo U('logout'); ?>" data-id="logout" data-group="2" onclick="adminAjaxAsk(this);return false;">注销</a></div>
                    <div class="info_lst">级别:&nbsp;<?php echo (isset($userGroup['group_name']) && ($userGroup['group_name'] !== '')?$userGroup['group_name']:'暂无'); ?></div>

                </div>

                <div class="left_menu_c">

                </div>

                <div class="info">
                    <div class="info_lst_t pie">版权所有
                        <div class="info_lst_t_ico icon-flag"></div>
                        <div class="info_lst_t_ico2 icon-angle-down"></div>
                    </div>

                    <div class="info_lst">系统设计:&nbsp;sam,icebr</div>
                    <div class="info_lst">特别鸣谢:&nbsp;somy</div>
                    <div class="info_lst">版　　本:&nbsp;1.0.0</div>

                </div>
                <div style="clear: both;"></div>
            </div>

        </div>
    </div>

    <div class="right">
        <div class="r_top pie bg-whitesmoke">

                <div class="adi_h_nav">
                    <?php if(is_array($topSider)): $i = 0; $__LIST__ = $topSider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tps): $mod = ($i % 2 );++$i;?>
                        <div class="item pie">
                            <a href="<?php echo U($tps['url']); ?>" data-id="<?php echo $tps['id']; ?>" data-group="<?php echo $tps['group']; ?>" onfocus="this.blur()" onclick="adminAjaxAsk(this);return false;" class="a">
                                <span class="<?php echo $tps['icon']; ?>"></span> <?php echo $tps['title']; ?>
                            </a>
                        </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
        </div>

        <div class="r_con">

        </div>

    </div>


</div>

</body>
</html>


