<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/home/wwwroot/somyshare.local.com/app/admin/view/addons/index.html";i:1462761144;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2>插件列表 </h2>
    </div>

    <div class="cf">
        <a class="button bg-main radius-none margin-bottom" href="<?php echo U('create'); ?>" onclick="adminAjaxAsk(this);return false;">创建插件</a>
    </div>

    <div class="data-table table-striped">
        <form class="ids form-small">
            <input type="hidden" name="mod" id="module" value="<?php echo MODULE_NAME; ?>">
            <input type="hidden" name="con" id="controller" value="<?php echo CONTROLLER_NAME; ?>">
            <table class="table table-striped">
                <thead>
                <tr class="bg-blackblue bg-inverse">
                    <th width="10%">插件名称</th>
                    <th width="10%">插件标识</th>
                    <th width="50%">插件描述</th>
                    <th width="10%">状态</th>
                    <th width="20%">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$addons): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $addons['title']; ?></td>
                            <td><?php echo $addons['name']; ?></td>
                            <td><?php echo $addons['description']; ?></td>
                            <td><?php echo $addons['status']; ?></td>
                            <td>
                                <?php if($addons['status'] == '未安装'): ?>
                                    <a title="安装" href="<?php echo U('install?name='.$addons['name']); ?>" onclick="adminAjaxAsk(this);return false;">安装</a>
                                <?php else: if($addons['has_config'] == '1'): ?>
                                    <a title="设置" href="<?php echo U('config?name='.$addons['name']); ?>" onclick="adminAjaxAsk(this);return false;">设置</a>
                                    <?php endif; if($addons['status'] == '启用'): ?>
                                    <a title="禁用" href="<?php echo U('disable?name='.$addons['name']); ?>" onclick="adminAjaxAsk(this);return false;">禁用</a>
                                    <?php else: ?>
                                    <a title="启用" href="<?php echo U('enable?name='.$addons['name']); ?>" onclick="adminAjaxAsk(this);return false;">启用</a>
                                    <?php endif; ?>
                                    <a class="confirm ajax-get" title="卸载" href="<?php echo U('uninstall?name='.$addons['name']); ?>" onclick="adminAjaxAsk(this,{ask:0,layer:{title:'操作提示',content:'确定要执行该操作吗？',btn: ['确定', '取消']}});return false;">卸载</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
                    <td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td>
                <?php endif; ?>
                </tbody>
            </table>
        </form>
        <div class="page"></div>
    </div>
