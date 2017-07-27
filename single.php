<?php get_header();?>
<?php while (have_posts()) : the_post(); ?>
<header class="main-header post-head no-cover">
    <nav class="main-nav  clearfix">
        <a class="back-button" href="<?php echo home_url(); ?>">首页</a>
        <a class="subscribe-button" href="<?php echo home_url(); ?>/rss.xml">订阅</a>
    </nav>
</header>
<main class="content" role="main">
    <article class="post">
        <header class="post-header">
            <h1 class="post-title"><?php the_title(); ?></h1>
            <section class="post-meta">
                <time class="post-date" datetime="<?php echo lb_time_since(strtotime($post->post_date_gmt)); ?>"><?php echo lb_time_since(strtotime($post->post_date_gmt)); ?></time>
            </section>
        </header>
        <section class="post-content">
            <?php the_content(); ?>
        </section>
        <footer class="post-footer">
		        <?php 
					$author_id=get_the_author_meta('ID');
					$author_url=get_author_posts_url($author_id);	
					$user_email = get_the_author_meta( 'user_email' );
				?>
                <figure class="author-image">
					<?php echo get_avatar(get_the_author_meta( 'user_email' ),68);?>
                    <span class="hidden"><?php echo get_the_author() ?></span></a>
                </figure>
                <section class="author">
                    <h4><?php echo get_the_author();?></h4>
                    <p><?php if(get_the_author_meta('description')){ echo the_author_meta( 'description' );}else{echo'世界上有许多美妙的事情等着我们去做'; }?></p>
                </section>
            <section class="share">
                <h4>分享此篇文章</h4>
                    <a href="http://service.weibo.com/share/share.php?title=<?php the_title(); ?>&appkey=4221439169&url=<?php the_permalink() ?>" onclick="window.open(this.href, 'weibo-share', 'width=550,height=235');return false;">
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-weibo"></use>
                        </svg>
                    </a>
					<a class="icon-wechat" href="http://qr.liantu.com/api.php?text=<?php the_permalink();?>" onclick="window.open(this.href, 'wechat-share', 'width=550,height=235');return false;">
                       <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-wechat"></use>
                        </svg>
                    </a>
					<a class="icon-twitter" href="http://twitter.com/share?text=<?php the_title();?>&url=<?php the_permalink();?>" onclick="window.open(this.href, 'twitter-share', 'width=550,height=235');return false;">
                       <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-twitter"></use>
                        </svg>
                    </a>
            </section>
        </footer>
    </article>
</main>
<?php endwhile;?>
<?php get_footer();?>