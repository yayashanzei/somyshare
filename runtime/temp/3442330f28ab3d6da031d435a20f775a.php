<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/home/wwwroot/somyshare.local.com/app/admin/view/config/index.html";i:1461219504;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2>配置管理 </h2>
    </div>

    <div class="cf">
        <a class="button bg-main radius-none margin-bottom" href="<?php echo U('add',array('pid'=>I('get.pid',0))); ?>" onclick="adminAjaxAsk(this);return false;">新 增</a>
        <a class="button bg-main radius-none margin-bottom" href="<?php echo U('del'); ?>" onclick="adminAjaxAsk(this,{ask:0,form:'ids',type:'post',layer:{ btn:['确定','取消'],content:'确定删除吗？'}});return false;">删 除</a>
        <a class="button bg-main margin-bottom" href="<?php echo U('updateCache'); ?>" onfocus="this.blur()" onclick="adminAjaxAsk(this);return false;">更新缓存</a>
        <!-- 高级搜索 -->
        <div class="float-right">
            <div class="sleft">
                <input type="text" name="title" class="input input-auto" size="36" value="<?php echo I('title'); ?>" placeholder="请输入菜单名称">
                <a class="sch-btn" href="javascript:;" id="search" url="__SELF__"><i class="btn-search"></i></a>
            </div>
        </div>
    </div>

    <div class="data-table table-striped">
        <form class="ids form-small">
            <table class="table table-striped">
                <thead>
                <tr class="bg-blackblue bg-inverse">
                    <th class="row-selected">
                        <input class="check-head" type="checkbox">
                    </th>
                    <th>ID</th>
                    <th>名称</th>
                    <th>标题</th>
                    <th>排序</th>
                    <th>分组</th>
                    <th>类型</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><input class="check-item" type="checkbox" name="id[]" value="<?php echo $config['id']; ?>"></td>
                            <td><?php echo $config['id']; ?></td>
                            <td><?php echo $config['ckey']; ?></td>
                            <td><?php echo $config['title']; ?></td>
                            <td width="160"><input class="input input-auto" type="text" size="3" name="sort_<?php echo $config['id']; ?>" value="<?php echo (isset($config['sort']) && ($config['sort'] !== '')?$config['sort']:0); ?>" onchange="input_change_v(<?php echo $config['id']; ?>,this);return false;"><span class="edit_msg"></span></td>
                            <td><?php echo $config['group']; ?></td>
                            <td><?php echo $config['type']; ?></td>
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

    <script type="text/javascript">
        $(function() {
            //搜索功能
            $("#search").click(function() {
                var url = $(this).attr('url');
                var query = $('.search-form').find('input').serialize();
                query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
                query = query.replace(/^&/g, '');
                if (url.indexOf('?') > 0) {
                    url += '&' + query;
                } else {
                    url += '?' + query;
                }
                window.location.href = url;
            });
            //回车搜索
            $(".search-input").keyup(function(e) {
                if (e.keyCode === 13) {
                    $("#search").click();
                    return false;
                }
            });
        });
    </script>