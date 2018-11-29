<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><?php echo ($list_webtitle); ?></title>
<meta name="copyright" content="<?php echo ($weburl); ?>" />
<meta name="keywords" content="<?php echo ($list_keywords); ?>">
<meta name="description" content="<?php echo ($list_description); ?>">
<meta name="ROBOTS" content="NOIMAGEINDEX">
<link rel="alternate" href="<?php echo ($rssurl); ?>" type="application/rss+xml" title="rss" />
<link rel="stylesheet" type="text/css" href="<?php echo ($tplpath); ?>/style/style.css" />
</head>
<body>

<div id="header">
	<div class="wrapper">
		<h1 class="logo"><a href="<?php echo ($weburl); ?>"><img src="<?php echo ($webpath); echo ($weblogo); ?>" /></a></h1>
		<div class="search">
            <form name="search" id="searchform" action="<?php echo ($webdir); echo U('Search/index');?>" method="post" target="_blank">
                <input type="text" placeholder="输入影片名称、演员" id="searchTxt" autocomplete="off" name="keyword">
                <button type="submit">搜索</button>
            </form>
            <div class="queryList clearfix" id="js-query-data" style="display: none;">
            	<ul id="js-calldata-list"></ul>
                <div class="prevList" id="js-calldata-preview"></div>
             </div>
		</div>
		<div class="operht">
			<div class="userlog">
            	<?php if(empty($user['id'])): ?><a href="<?php echo ($user['userlogin']); ?>">登录</a>
                <a href="<?php echo ($user['userreg']); ?>">注册</a>
                <?php else: ?>
                <p class="loged">
                    <a href="<?php echo ($user['userurl']); ?>" class="u"><?php echo ($user['username']); ?></a>
                	<a href="<?php echo ($user['userlogout']); ?>">退出</a>
                </p><?php endif; ?>
	    	</div>
			<div class="history">
				<span class="kick">播放记录</span>
		  		<dl class="hisCont">
					<dt>播放记录</dt>
					<dd id="hisStatu">
						<ul><li id="noHis">您还没有观看记录~</li></ul>
					</dd>
				</dl>
			</div>
		</div>
	</div>
</div>
<div id="navis">
    <ul>
    	<li><a href="<?php echo ($webpath); ?>" title="首页">首页</a></li>
    <?php $__NAV__ = D('Tag')->getNav(0,"",0,"id,title,name,pid,link"); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?><li <?php if(($nav['id'] == $cid) OR ($nav['id'] == $pid)): ?>class="active"<?php endif; ?>><a href="<?php echo ($nav['url']); ?>" title="<?php echo ($nav['title']); ?>"><?php echo ($nav['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		<li class="more" style="margin-right: 0px">
            <span><a href="<?php echo U('Home/Other/index/tpl/top');?>" title="排行榜">排行榜</a></span>
            <span><a href="javascript:;" title="收藏本站" id="collet">收藏本站</a></span>
        </li>
    </ul>
</div>
<div class="wrapper">
	<div class="comwrap clearfix">
		<div class="comAside fr">
            <div class="sideCont">
               <div class="inTitle">
                    <h6>影视排行榜</h6>
                </div>
                <ul class="foldlist">
                    <?php $__LIST__ = D('Tag')->lists(all, "hits desc","10", 1,"true"); if(is_array($__LIST__)): $i = 0; $__LIST__ = $__LIST__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li <?php if(($i) == "1"): ?>class="hover"<?php endif; ?>>
                        <div class="hisus">
                            <a href="<?php echo ($list['url']); ?>" class="img">
                                <img src="<?php echo ($tplpath); ?>/images/loading.gif" data-original="<?php echo ($list['pic']); ?>" alt="<?php echo ($list['title']); ?>" style="display: inline;">
                                <b><?php echo ($i); ?></b>
                            </a>
                            <div class="imr">
                                <h4><?php echo ($list['title']); ?></h4>
                                <p>类型：<?php echo ($list['ctitle']); ?></p>
                                <p>主演：<?php echo (substr($list['actors'],'0,10')); ?></p>
                                <a href="<?php echo ($list['url']); ?>" class="p">立即播放</a>
                            </div>
                        </div>
                        <p><em><?php echo ($i); ?></em><a href="<?php echo ($list['url']); ?>"><?php echo ($list['title']); ?></a><span><?php echo ($list['hits']); ?></span></p>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
			</div>
    	</div>
        <div class="leftSide fl">
			<div class="rowContr">
				<dl class="totabs">
					<dt>共<i><?php echo ($count); ?></i>篇<?php echo ($ctitle); ?></dt>
				</dl>
			</div>
			<div class="listad"><!-- 广告位：列表页通用668*34 --></div>
            <div class="news_channel">
            <ul>
            <?php $__NEWS__ = D('Tag')->newspage($cid, "`id` DESC","20", 1,"true"); if(is_array($__NEWS__)): $i = 0; $__LIST__ = $__NEWS__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                <h3><a href="<?php echo ($list['url']); ?>" target="_blank"><?php echo ($list['title']); ?></a></h3>
                <div class="timebox">
                    <span pubtime="<?php echo ($list['time']); ?>"><span><?php echo ($list['time']); ?></span></span>
                    <a href="javascript:void(0);" target="_blank" <?php echo ($list['digg']['up_js']); ?>><em class="icon_nbbs"></em><?php echo ($list['digg']['up']); ?></a>
                </div>
                <div class="news_spic">
                	<?php if(!empty($list['pic'])): ?><div class="news_imgctrl">
                        <a href="<?php echo ($list['url']); ?>" target="_blank"><img src="<?php echo ($tplpath); ?>/images/loading.gif" data-original="<?php echo ($list['pic']); ?>" alt="<?php echo ($list['title']); ?>"/></a>
                    </div><?php endif; ?>
                    <p><?php echo (msubstr(strip_tags($list['content']),0,250)); ?></p>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            </div>
			<div class="pages">
                <?php $__PAGE__ = new \Think\Page($count, 20,array("model"=>$model,"name"=>$cname,"id"=>$cid,"year"=>$cyear,"area"=>$carea,"language"=>$clanguage,"order"=>$order,"keyword"=>$keyword));echo $__PAGE__->show(); ?>
			</div>
		</div>
    </div>
    <div id="bde">
    <!-- 广告位：首页横幅960*90 -->
    </div>
</div>
<div id="footer">
	<div class="wrapper clearfix">
		<p class="bhr"></p>
		<ul class="fl">
			<li><a href="http://www.lfdycms.com">雷风官网</a> | </li>
			<li><a href="http://bbs.lfdycms.com">雷风论坛</a> | </li>
			<li><a href="http://www.lfdycms.com">网站开发</a></li>
		</ul>
		<p class="fr">(C)2005- 2013 <?php echo ($webname); ?> <?php echo ($weburl); ?></p>
        <p class="fr"><?php echo ($icp); ?></p>
	</div>
</div>
<div id="gotop" style="opacity: 0;"><a href="javascript:;" title="回顶部">回顶部</a></div>
<script type="text/javascript" src="<?php echo ($tplpath); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ($tplpath); ?>/js/jquery.lazyload.min.js"></script>
<script type="text/javascript" src="<?php echo ($tplpath); ?>/js/common.source.js"></script>
</body>
</html>