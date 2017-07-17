<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"/home/wwwroot/somyshare.local.com/app/admin/view/config/set.html";i:1461219504;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2>网站设置 </h2>
    </div>

    <div class="tab">

        <div class="tab-head">
            <ul class="tab-nav">
                <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?>
                <li <?php if($g['id']== $id): ?>class="active"<?php endif; ?>><a href="<?php echo U('config/set?id='.$g['id']); ?>" onfocus="this.blur()" onclick="adminAjaxAsk(this);return false;"><?php echo $g['title']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="tab-body">
            <form action="<?php echo U(); ?>" method="post" class="form-horizontal">
                <?php if(is_array($config)): $i = 0; $__LIST__ = $config;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;switch($c['type']): case "43": ?>
                            <div class="form-group">
                                <div class="label">
                                    <label><?php echo $c['title']; ?> </label><span class="text-gray">（<?php echo $c['remark']; ?>）</span>
                                </div>
                                <div class="field">
                                    <input type="text" class="input input-auto" name="<?php echo $c['ckey']; ?>" size="60" value="<?php echo (isset($c['cvalue']) && ($c['cvalue'] !== '')?$c['cvalue']:''); ?>">
                                </div>
                            </div>
                        <?php break; case "44": ?>
                            <div class="form-group">
                                <div class="label">
                                    <label><?php echo $c['title']; ?> </label><span class="text-gray">（<?php echo $c['remark']; ?>）</span>
                                </div>
                                <div class="field">
                                    <input type="text" class="input input-auto" name="<?php echo $c['ckey']; ?>" size="60" value="<?php echo (isset($c['cvalue']) && ($c['cvalue'] !== '')?$c['cvalue']:''); ?>">
                                </div>
                            </div>
                        <?php break; case "47": ?>
                            <div class="form-group">
                                <div class="label">
                                    <label><?php echo $c['title']; ?> </label><span class="text-gray">（<?php echo $c['remark']; ?>）</span>
                                </div>
                                <div class="field">
                                    <textarea rows="5" class="input" name="<?php echo $c['ckey']; ?>" placeholder="" style="width:600px;"><?php echo (isset($c['cvalue']) && ($c['cvalue'] !== '')?$c['cvalue']:''); ?></textarea>
                                </div>
                            </div>
                        <?php break; case "48": ?>
                            <div class="form-group">
                                <div class="label">
                                    <label><?php echo $c['title']; ?> </label><span class="text-gray">（<?php echo $c['remark']; ?>）</span>
                                </div>
                                <div class="field">
                                    <input type="text" class="input input-auto" name="<?php echo $c['ckey']; ?>" size="60" value="<?php echo (isset($config['ckey']) && ($config['ckey'] !== '')?$config['ckey']:''); ?>">
                                </div>
                            </div>
                        <?php break; case "49": ?>
                            <div class="form-group">
                                <div class="label">
                                    <label><?php echo $c['title']; ?> </label><span class="text-gray">（<?php echo $c['remark']; ?>）</span>
                                </div>
                                <div class="field">
                                    <select name="<?php echo $c['ckey']; ?>" class="input input-auto" style="width:433px;">
                                        <?php if(is_array($c['enum'])): $i = 0; $__LIST__ = $c['enum'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$e): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $e['id']; ?>" <?php if($c['cvalue'] == $e['id']): ?>selected="selected"<?php endif; ?>><?php echo $e['title']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php break; default: endswitch; endforeach; endif; else: echo "" ;endif; ?>
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo (isset($id) && ($id !== '')?$id:'0'); ?>">
                    <a class="button bg-green padding-large-left padding-large-right" id="submit" type="submit" target-form="form-horizontal" href="<?php echo U(); ?>" onclick="adminAjaxAsk(this,{type:'post',form:'form-horizontal'});return false;">确 定</a>
                    <a class="button bg-yellow margin-left margin-large-bottom margin-small-top padding-large-left padding-large-right" href="<?php echo U(); ?>" onclick="adminAjaxAsk(this,{history:true});return false;">返 回</a>
                </div>
            </form>
        </div>
    </div>
