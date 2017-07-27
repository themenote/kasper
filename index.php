<?php get_header();?>
<main id="content" class="content" role="main">
	    <div class="blog-posts">
	    <?php while (have_posts()) : the_post();
		    get_template_part( 'content',get_post_format() );
	    endwhile;?>
	    </div>
	<div id="ajax-load-posts" class="pagination">
        <?php echo lb_load_postlist_button();?>
    </div>
</main>
<?php get_footer();?>