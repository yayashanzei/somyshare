<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"/home/wwwroot/somyshare.local.com/app/admin/view/hooks/index.html";i:1461219504;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2>钩子列表 </h2>
    </div>

    <div class="cf">
        <a class="button bg-main radius-none margin-bottom" href="<?php echo U('add'); ?>" onclick="adminAjaxAsk(this);return false;">新 增</a>
        <a class="button bg-main radius-none margin-bottom" href="<?php echo U('del'); ?>" onclick="adminAjaxAsk(this,{ask:0,form:'ids',type:'post',layer:{ btn:['确定','取消'],content:'确定删除吗？'}});return false;">删 除</a>
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
                    <th>钩子名称</th>
                    <th>钩子描述</th>
                    <th>钩子类型</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><input class="check-item" type="checkbox" name="id[]" value="<?php echo $nav['id']; ?>"></td>
                            <td><?php echo $nav['id']; ?></td>
                            <td><?php echo $nav['name']; ?></td>
                            <td><?php echo $nav['description']; ?></td>
                            <td><?php echo $nav['type']; ?></td>
                            <td>
                                <a title="编辑" href="<?php echo U('edit?id='.$nav['id']); ?>" onclick="adminAjaxAsk(this);return false;">编辑</a>
                                <a class="confirm ajax-get" title="删除" href="<?php echo U('del?id='.$nav['id']); ?>" onclick="adminAjaxAsk(this,{ask:0,layer:{title:'操作提示',content:'确定要执行该操作吗？',btn: ['确定', '取消']}});return false;">删除</a>
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