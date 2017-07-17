<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/home/wwwroot/somyshare.local.com/app/admin/view/article/channel.html";i:1463444880;}*/ ?>
﻿    <div class="margin-big-bottom">
        <h2><?php echo $category['title']; ?> </h2>
    </div>

    <div class="cf">
        <?php if(!empty($buttons['_'])): if(is_array($buttons['_'])): $i = 0; $__LIST__ = $buttons['_'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bt): $mod = ($i % 2 );++$i;if($bt['group'] ==3): ?>
        <a class="button bg-main radius-none margin-bottom" data-id="<?php echo $bt['id']; ?>" data-group="<?php echo $bt['group']; ?>" title="<?php echo $bt['title']; ?>" href="<?php echo U(getParam($bt['url'],$bt['get_param'],array('id'=>I('get.id',0),'tid'=>$category['id'],'model'=>$category['model']))); ?>" onclick="adminAjaxAsk(this<?php echo !empty($bt['ajax_param'])?','.$bt['ajax_param']:''; ?>);return false;"><?php echo $bt['title']; ?></a>
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
            <table class="table table-striped">
                <thead>
                <tr class="bg-blackblue bg-inverse">
                    <th class="row-selected">
                        <input class="check-head" type="checkbox">
                    </th>
                    <th>ID</th>
                    <th>标题</th>
                    <th>所属分类</th>
                    <th>最后更新</th>
                    <th>状态</th>
                    <th>浏览次数</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$article): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><input class="check-item" type="checkbox" name="id[]" value="<?php echo $article['id']; ?>"></td>
                            <td><?php echo $article['id']; ?></td>
                            <td><?php echo $article['title']; ?></td>
                            <td><?php echo $catInfo[$article['category_id']]['title']; ?></td>
                            <td><?php echo timeToDate($article['create_time']); ?></td>
                            <td><?php if($article['status'] == '1'): ?>正常<?php else: ?>禁用<?php endif; ?></td>
                            <td><?php echo $article['views']; ?></td>
                            <td>
                                <?php if(!empty($buttons['_'])): if(is_array($buttons['_'])): $i = 0; $__LIST__ = $buttons['_'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bt): $mod = ($i % 2 );++$i;if($bt['group'] ==4): ?>
                                <a title="<?php echo $bt['title']; ?>" data-id="<?php echo $bt['id']; ?>" data-group="<?php echo $bt['group']; ?>" href="<?php echo U(getParam($bt['url'],$bt['get_param'],array('model'=>$category['model'],'tid'=>$category['id'],'id'=>$article['id']))); ?>" onclick="adminAjaxAsk(this<?php echo !empty($bt['ajax_param'])?','.$bt['ajax_param']:''; ?>);return false;"><?php echo $bt['title']; ?></a>
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