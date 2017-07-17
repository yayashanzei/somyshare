<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"/home/wwwroot/somyshare.local.com/app/admin/view/menu/index.html";i:1461641720;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2>菜单管理 </h2>
    </div>

    <div class="cf">
        <?php if(!empty($buttons['_'])): if(is_array($buttons['_'])): $i = 0; $__LIST__ = $buttons['_'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bt): $mod = ($i % 2 );++$i;if($bt['group'] ==3): ?>
        <a class="button bg-main radius-none margin-bottom" data-id="<?php echo $bt['id']; ?>" data-group="<?php echo $bt['group']; ?>" title="<?php echo $bt['title']; ?>" href="<?php echo U(getParam($bt['url'],$bt['get_param'],array('pid'=>I('get.pid',0)))); ?>" onclick="adminAjaxAsk(this<?php echo !empty($bt['ajax_param'])?','.$bt['ajax_param']:''; ?>);return false;"><?php echo $bt['title']; ?></a>
        <?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
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
            <input type="hidden" name="mod" id="module" value="<?php echo MODULE_NAME; ?>">
            <input type="hidden" name="con" id="controller" value="<?php echo CONTROLLER_NAME; ?>">
            <table class="table table-striped">
                <thead>
                <tr class="bg-blackblue bg-inverse">
                    <th class="row-selected">
                        <input class="check-head" type="checkbox">
                    </th>
                    <th>ID</th>
                    <th>名称</th>
                    <th>上级菜单</th>
                    <th>分组</th>
                    <th>URL</th>
                    <th>排序</th>
                    <th>仅开发者模式显示</th>
                    <th>隐藏</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><input class="check-item" type="checkbox" name="id[]" value="<?php echo $menu['id']; ?>"></td>
                            <td><?php echo $menu['id']; ?></td>
                            <td>
                                <a href="<?php echo U('index?pid='.$menu['id']); ?>" data-id="r-l" data-group="" onclick="adminAjaxAsk(this);return false;"><?php echo $menu['title']; ?></a>
                            </td>
                            <td><?php echo (isset($menu['up_title']) && ($menu['up_title'] !== '')?$menu['up_title']:'无'); ?></td>
                            <td><?php echo $menu['group']; ?></td>
                            <td><?php echo $menu['url']; ?></td>
                            <td width="160"><input class="input input-auto" alt="Menu" type="text" size="3" name="sort_<?php echo $menu['id']; ?>" value="<?php echo (isset($menu['sort']) && ($menu['sort'] !== '')?$menu['sort']:0); ?>" onchange="fieldUpdate(this);return false;"><span class="edit_msg"></span></td>
                            <td width="130">
                                <a href="<?php echo U('toogleDev',array('id'=>$menu['id'],'value'=>abs($menu['is_dev']-1))); ?>" class="ajax-get">
                                    <?php echo $menu['is_dev_text']; ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo U('toogleHide',array('id'=>$menu['id'],'value'=>abs($menu['hide']-1))); ?>" class="ajax-get">
                                    <?php echo $menu['hide_text']; ?>
                                </a>
                            </td>
                            <td>
                                <?php if(!empty($buttons['_'])): if(is_array($buttons['_'])): $i = 0; $__LIST__ = $buttons['_'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bt): $mod = ($i % 2 );++$i;if($bt['group'] ==4): ?>
                                        <a title="<?php echo $bt['title']; ?>" data-id="<?php echo $bt['id']; ?>" data-group="<?php echo $bt['group']; ?>" href="<?php echo U(getParam($bt['url'],$bt['get_param'],$menu)); ?>" onclick="adminAjaxAsk(this<?php echo !empty($bt['ajax_param'])?','.$bt['ajax_param']:''; ?>);return false;"><?php echo $bt['title']; ?></a>
                                    <?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
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