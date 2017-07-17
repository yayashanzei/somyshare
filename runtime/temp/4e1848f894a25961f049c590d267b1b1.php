<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/home/wwwroot/somyshare.local.com/addons/systeminfo/system_info.html";i:1464070166;}*/ ?>
<?php if(!empty($config['data_display']['value'])): ?>
<div class="x<?php echo $config['data_width']['value']; ?> x<?php echo $config['data_move']['value']; ?>-move">

    <div class="bg pdding-top-bottom padding text-big text-main  border-mix border-bottom border-small">
        <strong><?php echo $config['data_title']['value']; ?></strong></div>

    <div class="sys-info margin-big-bottom">
        <table class="table table-bordered">
            <tr>
                <td>文章</td>
                <?php 
                $system_info_article = M()->query("select count(id) as v from disk_article");
                 ?>
                <td><?php echo $system_info_article[0]['v']; ?></td>
            </tr>
            <tr>
                <td>视频</td>
                <?php 
                $system_info_video = M()->query("select count(id) as v from disk_video");
                 ?>
                <td><?php echo $system_info_video[0]['v']; ?></td>
            </tr>
            <tr>
                <td>用户</td>
                <?php 
                $system_info_user = M()->query("select count(uid) as v from disk_user");
                 ?>
                <td><?php echo $system_info_user[0]['v']; ?></td>
            </tr>
        </table>
    </div>

</div>
<?php endif; if(!empty($config['sys_display']['value'])): ?>
<div class="x<?php echo $config['sys_width']['value']; ?> x<?php echo $config['sys_move']['value']; ?>-move">

    <div class="bg pdding-top-bottom padding text-big text-main  border-mix border-bottom border-small">
        <strong><?php echo $config['sys_title']['value']; ?></strong></div>

    <div class="sys-info">
        <table class="table table-bordered">
            <tr>
                <td>服务器操作系统</td>
                <td><?php echo PHP_OS; ?></td>
            </tr>
            <tr>
                <td>ThinkPHP版本</td>
                <td><?php echo THINK_VERSION; ?></td>
            </tr>
            <tr>
                <td>运行环境</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
            </tr>
            <tr>
                <td>MYSQL版本</td>
                <?php 
                $system_info_mysql = M()->query("select version() as v");
                 ?>
                <td><?php echo $system_info_mysql[0]['v']; ?></td>
            </tr>
            <tr>
                <td>上传限制</td>
                <td><?php echo ini_get('upload_max_filesize'); ?></td>
            </tr>
        </table>
    </div>

</div>
<?php endif; ?>


