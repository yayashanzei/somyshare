<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/home/wwwroot/somyshare.local.com/app/admin/view/user/authorize.html";i:1461914560;}*/ ?>
<div class="margin-big-bottom">
    <h2>授权管理</h2>
</div>

<form class="form-horizontal">

    <?php if(!empty($menu)): if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pr): $mod = ($i % 2 );++$i;if($pr['pid'] == $auth_group and $pr['pid'] != 0): ?>
    <div class="form-group margin-large-bottom border border-mix radius ">

        <div class=" label bg padding-small margin-bottom text-blackblue border-bottom radius border-mix">
            <input name="rules[]" type="checkbox" value="<?php echo $pr['id']; ?>"  <?php echo isset($groupAuth[$pr['id']]) ? 'checked="checked"' : ''; ?> class="pr margin-small-right">
            <span class="h5"><strong><?php echo $pr['title']; ?></strong></span>
        </div>

        <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ch): $mod = ($i % 2 );++$i;if($ch['pid'] == $pr['id']): ?>
        <div class="margin-big-left padding-small-top">

            <div class="label text-green ch-box">
                <input name="rules[]" type="checkbox" value="<?php echo $ch['id']; ?>" <?php echo isset($groupAuth[$ch['id']]) ? 'checked="checked"' : ''; ?> class="ch margin-small-right">
                <span class="h5"><strong><?php echo $ch['title']; ?></strong></span>
            </div>

            <div class="margin-big-left gr-box">
            <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gr): $mod = ($i % 2 );++$i;if($gr['pid'] == $ch['id']): ?>
                <label class=" padding-small-right">
                    <input name="rules[]" type="checkbox" value="<?php echo $gr['id']; ?>" <?php echo isset($groupAuth[$gr['id']]) ? 'checked="checked"' : ''; ?> class="gr margin-small-right">
                    <span class="text-justify"><?php echo $gr['title']; ?><span class="text-gray">(<?php echo $gr['group']; ?>)</span></span>
                </label>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>

        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>

    </div>
    <?php endif; endforeach; endif; else: echo "" ;endif; if(!empty($cate)): ?>
    <div class="form-group margin-large-bottom border border-mix radius ">

        <div class=" label bg padding-small margin-bottom text-blackblue border-bottom radius border-mix">
             <span class="h5"><strong>模块（顶级分类）管理</strong></span>
        </div>

        <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ct): $mod = ($i % 2 );++$i;?>

        <div class="margin-big-left padding-small-top">

            <div class="label text-green ch-box">
                <input name="rules[]" type="checkbox" value="<?php echo $ct['id']; ?>" <?php echo isset($groupAuth[$ct['id']]) ? 'checked="checked"' : ''; ?> class="ch margin-small-right">
                <span class="h5"><strong><?php echo $ct['title']; ?></strong></span>
            </div>

        </div>

        <?php endforeach; endif; else: echo "" ;endif; ?>

    </div>
    <?php endif; ?>

    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
    <input type="hidden" name="auth_group" value="<?php echo $auth_group; ?>">
    <?php else: ?>
    <div class="alert alert-yellow margin-big-bottom">
        <span class="close rotate-hover"></span>aO,没有要管理的权限！</div>
    <?php endif; ?>

    <div class="form-group">
        <!--<input type="hidden" name="uid" value="<?php echo (isset($info['uid']) && ($info['uid'] !== '')?$info['uid']:''); ?>">-->
        <a class="button bg-green padding-large-left padding-large-right" id="submit" href="<?php echo U(); ?>"
           onclick="adminAjaxAsk(this,{type:'post',form:'form-horizontal'});return false;">确 定</a>
        <a class="button bg-yellow margin-left margin-large-bottom margin-small-top padding-large-left padding-large-right"
           href="<?php echo U(); ?>" onclick="adminAjaxAsk(this,{history:true});return false;">返 回
        </a>
    </div>
</form>
<script type="text/javascript">
    $('.pr').click(function(){
        $(this).closest('.form-group').find('input').prop('checked',this.checked);
    });

    $('.ch').click(function(){
        $(this).closest('.ch-box').siblings('.gr-box').find('input').prop('checked',this.checked);
    });
</script>