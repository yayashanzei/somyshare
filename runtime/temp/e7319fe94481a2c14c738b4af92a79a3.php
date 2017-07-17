<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"/home/wwwroot/somyshare.local.com/app/admin/view/user/info.html";i:1464148094;}*/ ?>
<div class="margin-big-bottom">
    <h2>用户信息 </h2>
</div>

<div class="cf">
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('infoAdd'); ?>" onclick="adminAjaxAsk(this);return false;">新 增</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U( 'infoEnable'); ?>" onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定启用吗？' } });return false;" >启 用</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('infoDisable'); ?>" onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定禁用吗？' } });return false;">禁 用</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('infoVerify'); ?>" onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定审核吗？' } });return false;">审 核</a>
    <a class="button bg-main radius-none margin-bottom" href="<?php echo U('infoDel'); ?>" onclick="adminAjaxAsk(this,{ask:0,form:'infoForm',type:'post',layer:{ btn:['确定','取消'],content:'确定删除吗？' } });return false;">删 除</a>
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
                    <th>UID</th>
                    <th>昵称</th>
                    <th>邮箱</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php if($users != ''): if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><input class="check-item" type="checkbox" name="uid[]" value="<?php echo $user['uid']; ?>"></td>
                        <td><?php echo $user['uid']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['status']; ?></td>
                        <td>
                            <a title="编辑" href="<?php echo U('infoEdit?uid='.$user['uid'] ); ?>"  onclick="adminAjaxAsk(this);return false;">编辑</a>
                            <a title="删除" href="<?php echo U('infoDel?uid='.$user['uid']); ?>" onclick="adminAjaxAsk(this,{ask:0,layer:{title:'操作提示',content:'确定要执行该操作吗？',btn: ['确定', '取消']}});return false;">删除</a>
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

