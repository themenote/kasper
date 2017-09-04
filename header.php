<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php wp_title( '-', true, 'right' ); ?></title>
<meta name="description" content="主题笔记自适应简介博客">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body class="home-template">
    <?php if (is_home()) { ?>
    <header class="main-header no-cover">
        <nav class="main-nav overlay clearfix">
            <a class="subscribe-button icon-feed" href="<?php echo wp_login_url(); ?>">登录</a>
        </nav>
        <div class="vertical">
            <div class="main-header-content inner">
                <h1 class="page-title"><?php bloginfo('name'); ?></h1>
                    <h2 class="page-description">
                        世界上有许多美妙的事情等着我们去做
                    </h2>
                <ul class="social-links">
                    <li>
					    <a title="微博主页" href="http://weibo.com/5080890941" target="_blank">
					    <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-weibo"></use>
                        </svg>
						</a>
					</li>
                    <li>
					    <a title="QQ 100041385" href="tencent://AddContact/?fromId=50&fromSubId=1&subcmd=all&uin=100041385">
					    <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-qq"></use>
                        </svg>
						</a>
					</li>
                    <li>
					    <a title="豆瓣小组" href="https://www.douban.com/" target="_blank">
					    <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-douban"></use>
                        </svg>
						</a>
					</li>
					<li>
					    <a title="twitter主页" href="https://twitter.com/themenote_1" target="_blank">
					    <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-twitter"></use>
                        </svg>
						</a>
					</li>
                </ul>
            </div>
        </div>
    </header>
	<?php } ?>
