<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"/home/wwwroot/somyshare.local.com/app/admin/view/user/group.html";i:1461219504;}*/ ?>
<div class="margin-big-bottom">
    <h2>权限管理 </h2>
</div>

<div class="cf">
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('groupAdd'); ?>" onclick="adminAjaxAsk(this);return false;">新 增</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U( 'groupEnable'); ?>"
       onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定启用吗？' } });return false;">启 用</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('groupDisable'); ?>"
       onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定禁用吗？' } });return false;">禁 用</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('groupDel'); ?>"
       onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定禁用吗？' } });return false;">删 除</a>
    <!-- 高级搜索 -->
    <div class="float-right">
        <div class="sleft">
            <input type="text" name="title" class="search-input" value="<?php echo I('title'); ?>" placeholder="请输入菜单名称">
            <a class="sch-btn" href="javascript:;" id="search" url="__SELF__"><i class="btn-search"></i></a>
        </div>
    </div>
</div>

<div class="data-table table-striped">
    <form class="infoForm">
        <table class="table table-striped table-hover">
            <thead>
            <tr class="bg-blackblue bg-inverse">
                <th>
                    <input class="check-head" type="checkbox">
                </th>
                <th>用户组</th>
                <th>描述</th>
                <th>授权</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($groups != ''): if(is_array($groups)): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><input class="check-item" type="checkbox" name="id[]" value="<?php echo $group['id']; ?>"></td>
                <td><?php echo $group['group_name']; ?></td>
                <td><?php echo $group['group_remark']; ?></td>
                <td>
                    <?php if(is_array($authGroup)): $i = 0; $__LIST__ = $authGroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agp): $mod = ($i % 2 );++$i;?>
                        <a href="<?php echo U('authorize?authId='.$agp['id'].'&groupId='.$group['id'] ); ?>" onclick="adminAjaxAsk(this);return false;"><?php echo $agp['title']; ?></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
                <td><?php echo $group['status']; ?></td>
                <td>
                    <a title="编辑" href="<?php echo U('groupEdit?id='.$group['id'] ); ?>" onclick="adminAjaxAsk(this);return false;">编辑</a>
                    <a title="删除" href="<?php echo U('groupDel?id='.$group['id']); ?>"
                       onclick="adminAjaxAsk(this,{ask:0,layer:{title:'操作提示',content:'确定要执行该操作吗？',btn: ['确定', '取消']}});return false;">删除</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <td colspan="10" class="text-center"> aOh! 暂时还没有内容!</td>
            <?php endif; ?>

            </tbody>
        </table>
    </form>
    <!-- 分页 -->
    <div class="page">

    </div>
</div>

