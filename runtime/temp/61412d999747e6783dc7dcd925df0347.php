<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:60:"D:\myfiles\www\somy_disk\app\admin\view\public\getsider.html";i:1461639482;}*/ ?>
<?php if(!empty($sider['_'])): ?>
    <div class="info">
        <?php if(is_array($sider['_'])): $i = 0; $__LIST__ = $sider['_'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sids): $mod = ($i % 2 );++$i;?>

            <div class="info_lst_t pie"><?php echo $sids['title']; ?>
                <div class="info_lst_t_ico <?php echo $sids['icon']; ?>"></div>
                <div class="info_lst_t_ico2 icon-angle-down"></div>
            </div>
            <?php if(!empty($sids['_'])): if(is_array($sids['_'])): $i = 0; $__LIST__ = $sids['_'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sid): $mod = ($i % 2 );++$i;?>
                        <div class="info_lst">
                            <a href='<?php echo U($sid['url']); ?>' data-id="<?php echo $sid['id']; ?>" data-group="<?php echo $sid['group']; ?>" onclick="adminAjaxAsk(this);return false;"><?php echo $sid['title']; ?></a>
                        </div>
                <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
<?php endif; ?>