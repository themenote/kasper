<?php
define( 'THEME_VER', '0.1' );
/**
功能说明: 加载css&js
更新时间：2017-3-15
**/
function lb_scripts_styles() {
	global $wp_styles;
	$dir = get_template_directory_uri();
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	
	wp_enqueue_script( 'jquery', $dir . '/public/js/jquery.min.js', array('jquery'), THEME_VER, true );
	wp_enqueue_script( 'main', $dir . '/public/js/main.js', array('jquery'), THEME_VER, true );
	//iconfont是主题使用的字体代码链接，你也可以使用自己的链接替换
	wp_enqueue_script( 'iconfont', '//at.alicdn.com/t/font_6sm5yb947dcgnwmi.js', true );
	//iconfont载入结束
	wp_enqueue_script( 'fitvids', $dir . '/public/js/jquery.fitvids.js', array('jquery'), THEME_VER, true );
	wp_enqueue_script( 'script', $dir . '/public/js/script.js', array('jquery'), THEME_VER, true );
	wp_localize_script( 'script', 'barley', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'wp_url' => get_bloginfo('url'), 'templateurl' => get_template_directory_uri(), 'posts_per_page' => get_option('posts_per_page'),'order' => get_option('comment_order') ) );
	// Loads our main stylesheet.
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'main', $dir . '/public/css/main.css', THEME_VER, true );
	wp_enqueue_style( 'screent', $dir . '/public/css/screen.css', THEME_VER, true );
}
add_action( 'wp_enqueue_scripts', 'lb_scripts_styles' );
/**
功能说明: 移除wp比必要的功能
更新时间：2017-3-15
**/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
add_filter('show_admin_bar', '__return_false');
/**
功能说明: 禁用emojis
更新时间：2017-3-15
**/
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'widget_text', 'do_shortcode' );
}
add_action( 'init', 'disable_emojis' );
function disable_emojis_tinymce( $plugins ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
}
/**
功能说明: 时间以ago显示
更新时间：2017-3-15
**/
function lb_time_since( $older_date, $comment_date = false ) {
	$chunks = array(
		array( 24 * 60 * 60,' 天前' ),
		array( 60 * 60, ' 小时前'),
		array( 60, ' 分钟前' ),
		array( 1,' 秒前')
	);
	$newer_date = time();
	$since      = abs( $newer_date - $older_date );
	if ( $since < 30 * 24 * 60 * 60 ) {
		for ( $i = 0, $j = count( $chunks ); $i < $j; $i ++ ) {
			$seconds = $chunks[ $i ][0];
			$name    = $chunks[ $i ][1];
			if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
				break;
			}
		}
		$output = $count . $name;
	} else {
		$output = $comment_date ? date( 'y-m-d', $older_date ) : date( 'Y-m-d', $older_date );
	}

	return $output;
}
/**
功能说明: theme_title
更新时间：2017-3-15
**/
function lb_theme_title( $title, $sep ) {
    global $paged, $page, $wp_query;;
    if ( is_feed() )
        return $title;
    $title .= get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";
    if ( $paged >= 2 || $page >= 2 )
        $title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );
    return $title;
}
add_filter( 'wp_title', 'lb_theme_title', 10, 2 );
/**
功能说明: 替换avatar头像
更新时间：2017-3-15
**/
function lb_get_ssl_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'lb_get_ssl_avatar');
/**
功能说明: 无限加载列表
更新时间：2016-11-11
**/
function lb_make_post_section(){
     global $post;
	 $args=array('orderby'=>'name','order'=>'ASC');$categories=get_categories($args);foreach($categories as $category)
	 $post_section = '<article class="single-article post">
        <header class="post-header">
            <h2 class="post-title"><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h2>
        </header>
        <section class="post-excerpt">
            <p>'.mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 240,"…").'</p>
            <a class="read-more" href="' . get_the_title() . '">»</a>
        </section>
        <footer class="post-meta">
                '. get_avatar(get_the_author_meta( 'user_email' ),24).'
                '. get_the_author() .'
            <time class="post-date" datetime="'. date('Y-m-d').'">
                '. lb_time_since(strtotime($post->post_date_gmt)).'
            </time>
        </footer>
    </article>';
    return $post_section;
}
add_action('wp_ajax_nopriv_lb_load_postlist', 'lb_load_postlist_callback');
add_action('wp_ajax_lb_load_postlist', 'lb_load_postlist_callback');
function lb_load_postlist_callback(){
    $postlist = '';
    $paged = !empty($_POST["paged"]) ? $_POST["paged"] : null;
    $total = !empty($_POST["total"]) ? $_POST["total"] : null;
    $category = !empty($_POST["category"]) ? $_POST["category"] : null;
    $author = !empty($_POST["author"]) ? $_POST["author"] : null;
    $tag = !empty($_POST["tag"]) ? $_POST["tag"] : null;
    $search = !empty($_POST["search"]) ? $_POST["search"] : null;
    $year = !empty($_POST["year"]) ? $_POST["year"] : null;
    $month = !empty($_POST["month"]) ? $_POST["month"] : null;
    $day = !empty($_POST["day"]) ? $_POST["day"] : null;
    $query_args = array(
        "posts_per_page" => get_option('posts_per_page'),
        "cat" => $category,
        "tag" => $tag,
        "author" => $author,
        "post_status" => "publish",
        "post_type" => "post",
        "paged" => $paged,
        "s" => $search,
        "year" => $year,
        "monthnum" => $month,
        "day" => $day,
		"ignore_sticky_posts" => 1
    );
    $the_query = new WP_Query( $query_args );
    while ( $the_query->have_posts() ){
        $the_query->the_post();
        $postlist .= lb_make_post_section();
    }
    $code = $postlist ? 200 : 500;
    wp_reset_postdata();
    $next = ( $total > $paged )  ? ( $paged + 1 ) : '' ;
    echo json_encode(array('code'=>$code,'postlist'=>$postlist,'next'=> $next));
    die;
}
function lb_load_postlist_button(){
    global $wp_query;
    if (2 > $GLOBALS["wp_query"]->max_num_pages) {
        return;
    } else {
        $button = '<a id="lb-loadmore" class="button button--small"';
        if (is_category()) $button .= ' data-category="' . get_query_var('cat') . '"';

        if (is_author()) $button .=  ' data-author="' . get_query_var('author') . '"';

        if (is_tag()) $button .=  ' data-tag="' . get_query_var('tag') . '"';

        if (is_search()) $button .=  ' data-search="' . get_query_var('s') . '"';

        if (is_date() ) $button .=  ' data-year="' . get_query_var('year') . '" data-month="' . get_query_var('monthnum') . '" data-day="' . get_query_var('day') . '"';

        $button .= ' data-paged="2" data-action="lb_load_postlist" data-total="' . $GLOBALS["wp_query"]->max_num_pages . '">加载更多</a>';

        return $button;
    }
}
