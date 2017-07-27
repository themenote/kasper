    <article id="post-<?php the_ID(); ?>" class="single-article post">
        <header class="post-header">
            <h2 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        </header>
        <section class="post-excerpt">
            <p><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 240,"…"); ?></p>
            <a class="read-more" href="<?php the_title(); ?>">»</a>
        </section>
        <footer class="post-meta">
                <?php echo get_avatar(get_the_author_meta( 'user_email' ),24);?>
                <?php echo get_the_author() ?>
            <time class="post-date" datetime="2016-07-21">
                <?php echo lb_time_since(strtotime($post->post_date_gmt)); ?>
            </time>
        </footer>
    </article>