<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>欢迎您登录影视系统</title>
    <link rel="stylesheet" href="/Public/Admin/css/pintuer.css">
    <link rel="stylesheet" href="/Public/Admin/css/admin.css">
    <script src="/Public/Admin/js/jquery.js"></script>
    <script src="/Public/Admin/js/pintuer.js"></script>
    <script src="/Public/Admin/js/respond.js"></script>
    <script src="/Public/Admin/js/admin.js"></script>
    
</head>
<body>
<div class="lefter">
    <div class="logo"><a href="#" target="_blank"><img src="/Public/Admin/images/logo.png" alt="后台管理系统" /></a></div>
</div>
<div class="righter nav-navicon" id="admin-nav">
    <div class="mainer">
        <div class="admin-navbar">
            <span class="float-right">
            	<a class="button button-little bg-main icon-home" href="/index.php" target="_blank">前台首页</a>
                <a class="button button-little bg-red icon-trash-o" href="<?php echo U('Index/cache');?>">清除缓存</a>
                <a class="button button-little bg-yellow icon-user" href="<?php echo U('Public/logout');?>">注销登录</a>
            </span>
            <ul class="nav nav-inline admin-nav">
            	<?php if(!empty($_extra_menu)): echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>" class="<?php echo ((isset($menu["icon"]) && ($menu["icon"] !== ""))?($menu["icon"]):''); ?>"> <?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
                <ul class="nav nav-inline sub-nav">
                    <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i; if(!empty($sub_menu)): if(!empty($key)): ?><h4><span class="icon-minus-square-o"></span><?php echo ($key); ?></h4><?php endif; ?>
                        <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
		</div>
        <div class="admin-bread">
            <span>您好<?php echo session('user_auth.username');?>，欢迎您的光临。</span>
            <ul class="bread">
                <li><a href="index.html" class="icon-home"> 开始</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="admin">
    <div id="top-alert" class="alert fixed hidden alert-red"></div>
	
    <div class="panel admin-panel">
    	<div class="panel-head"><strong>资源列表管理</strong></div>
        <div class="padding border-bottom">
            <div class="button-group button-group-small">
                 <a class="button" href="<?php echo U('add');?>"><span class="icon-plus text-green"></span> 新 增</a>
            </div>
        </div>
        <table class="table table-hover">
        	<tr>
                <th width="70%">资源名称</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($clist)): $i = 0; $__LIST__ = $clist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                <td><a href="<?php echo U('lists?url='.str_replace('?','*',$list['url']).'&type='.$list['type'].'&fid='.$list['fid']);?>"><?php echo ($list["title"]); ?></a></td>
                <td>
                <a class="cron" href="<?php echo U('cron',array('key'=>$list['fid']));?>">定时采集</a>
                <a href="javascript:ajaxjson('<?php echo U('collect?h=24&fid='.$list['fid'].'&url='.str_replace('?','*',$list['url']).'&type='.$list['type']);?>',1)">采集当日</a>
                <a href="javascript:ajaxjson('<?php echo U('collect?fid='.$list['fid'].'&url='.str_replace('?','*',$list['url']).'&type='.$list['type']);?>',1)">采集所有</a>
                <a href="<?php echo U('lists?url='.str_replace('?','*',$list['url']).'&type='.$list['type'].'&fid='.$list['fid']);?>">绑定分类</a>
                <?php if(($list["zy"]) == "1"): ?><a href="<?php echo U('edit?id='.$list['fid']);?>">编辑</a>
                    <a href="<?php echo U('del?id='.$list['fid']);?>" class="confirm ajax-get">删除</a><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </div>
     <div id="mydialog">
        <div class="dialog">
            <div class="dialog-head">
                <span class="dialog-close close rotate-hover"></span>
                <strong>采集进度</strong>
            </div>
            <div class="dialog-body">
                <p class="text-center">已采集 <span class="badge bg-green num">0</span> 条 共采集<span class="badge bg-green count">0</span> 条</p>
                <div class="progress progress-striped active progress-big">
                	<div class="progress-bar bg-sub" style="width:0%;">进度：0%</div>
                </div>
                <div style="width:100%;height:150px;OVERFLOW-Y: auto; OVERFLOW-X:hidden; margin-top:10px">
                    <ul class="list-group">
                    </ul>
                </div>
            </div>
            <div class="dialog-foot">
            	<button class="dialog-close button">取消</button>
            </div>
        </div>
    </div>

</div>
 <script type="text/javascript">
+function(){
	/* 左边菜单高亮 */
	var $window = $(window), $subnav = $(".sub-nav"), url;
	url = window.location.pathname + window.location.search;
	url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
	var bread=$subnav.find("a[href='" + url + "']").parent().prop('outerHTML');
	$('.bread').append(bread);
	$subnav.find("a[href='" + url + "']").parent().addClass("active");
	/* 左边菜单显示收起 */
	$(".sub-nav").on("click", "h4", function(){
		var $this = $(this);
		$this.find("span").toggleClass("icon-minus-square-o");
		$this.nextUntil("h4").slideToggle("fast").prev("h4").find("span").toggleClass("icon-plus-square-o");
	});
}();
</script>			

<script type="text/javascript" src="/Public/static/layer/layer.js"></script>
<script type="text/javascript">
$(function(){
	var collect=<?php echo json_encode(S('collect'));?>;
	if(collect){
		layer.confirm('上次未采集完成是否要继续采集？', {
		btn: ['继续采集','重新采集'],
		shade: 0.3 
		}, function(index){
            dialog.show();
			ajaxjson(collect.url,collect.page);
			layer.close(index);
		}, function(){
            $.get("<?php echo U('delCollect');?>");
		});
	}

    $(".cron").click(function(){
        layer.open({
            type: 2,
            area: ['400px', '260px'],
            fix: false, //不固定
            maxmin: true,
            content: $(this).attr('href')
        });
        return false;
    });
})
</script>

</body>
</html>