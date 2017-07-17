<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"/home/wwwroot/somyshare.local.com/app/admin/view/enum/index.html";i:1461219504;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2>枚举管理 </h2>
    </div>

    <div class="cf">
        <a class="button bg-main margin-bottom" href="<?php echo U('add',array('pid'=>I('get.pid',0))); ?>" onfocus="this.blur()"  onclick="adminAjaxAsk(this);return false;">新 增</a>
        <a class="button bg-main radius-none margin-bottom" href="<?php echo U('del'); ?>" onfocus="this.blur()" onclick="adminAjaxAsk(this,{ask:0,form:'ids',type:'post',layer:{ btn:['确定','取消'],content:'确定删除吗？'}});return false;">删 除</a>
        <a class="button bg-main margin-bottom" href="<?php echo U('updateCache'); ?>" onfocus="this.blur()" onclick="adminAjaxAsk(this);return false;">更新缓存</a>
        <!-- 当前位置 -->
        <div class="float-right">
            <div style="margin-top:15px">当前位置：<?php echo $pos; ?> </div>
        </div>
    </div>

    <div class="data-table table-striped">
        <form class="ids form-small">
            <input type="hidden" name="mod" id="module" value="<?php echo MODULE_NAME; ?>">
            <input type="hidden" name="con" id="controller" value="<?php echo CONTROLLER_NAME; ?>">
            <table class="table table-striped">
                <thead>
                <tr class="bg-blackblue bg-inverse">
                    <th class="row-selected">
                        <input class="check-head" type="checkbox">
                    </th>
                    <th>ID</th>
                    <th>枚举组名</th>
                    <th>缓存组名</th>
                    <th>排序</th>
                    <th>系统</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><input class="check-item" type="checkbox" name="id[]" value="<?php echo $config['id']; ?>"></td>
                            <td><?php echo $config['id']; ?></td>
                            <td>
                                <a href="<?php echo U('index?pid='.$config['id']); ?>" onclick="adminAjaxAsk(this);return false;"><?php echo $config['title']; ?></a>
                            </td>
                            <td><?php echo $config['name']; ?></td>
                            <td width="160"><input class="input input-auto" type="text" size="3" name="sort_<?php echo $config['id']; ?>" value="<?php echo (isset($config['sort']) && ($config['sort'] !== '')?$config['sort']:0); ?>" onchange="fieldUpdate(this);return false;"><span class="edit_msg"></span></td>
                            <td><?php echo ($config['issystem']==1)?'是' : '否'; ?></td>
                            <td>
                                <a title="编辑" href="<?php echo U('edit?id='.$config['id']); ?>" onclick="adminAjaxAsk(this);return false;">编辑</a>
                                <a class="confirm ajax-get" title="删除" href="<?php echo U('del?id='.$config['id']); ?>" onclick="adminAjaxAsk(this,{ask:0,layer:{title:'操作提示',content:'确定要执行该操作吗？',btn: ['确定', '取消']}});return false;">删除</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
                    <td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td>
                <?php endif; ?>
                </tbody>
            </table>
        </form>
        <!-- 分页 -->
        <div class="page">

        </div>
    </div>