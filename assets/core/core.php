<?php

/*

	Author:   Josh McDonald
	Twitter:  @onestepcreative
	Website:  http://onestepcreative.com
	Version:  2.5.0

	This file is the core of Devly, and contains 
	most of the functions and additional features.
	If you are looking for a place to add your
	custom functions, I'd recommend heading
	over to functions.php

*/



// INITIALIZE ALL OF THE DEVLY THEME AWESOMENESS
add_action('after_setup_theme','devlyCoreBlastOff', 15);


// =========================================================================
// ====== DEVLY CORE BLAST OFF: MOST FUNCTIONS + TOOLS  
// =========================================================================


function devlyCoreBlastOff() {
	
	// CLEANUP THE WORDPRESS HEAD
	add_action('init', 'devlyCleanHead');
	
	// HOOK UP OUR NEW METABOX HELPER CLASS
	add_action('init', 'devlyMetaboxSetup', 9999);
	
	// REMOVE WP VERSION FROM RSS
	add_filter('the_generator', 'devlyVersionRSS');
	
	// REMOVE INJECTED CSS FROM COMMENTS WIDGET
	add_filter( 'wp_head', 'devlyRemoveCommentWidgetStyles', 1 );
	
	// REMOVE ERRORS FROM LOGIN PAGE (SECURITY MEASURE)
	add_filter('login_errors', create_function('$a', "return null;"));
	
	// ENQUEUE DEVLY SCRIPTS & STYLES
	add_action('wp_enqueue_scripts', 'devlyLoadScriptsAndStyles', 999);
	
	// LOAD UP THE DEVLY ADMIN STYLESHEET
	add_action('admin_init', 'devlyLoadScriptsAndStyles');
	
	
	
	// LOAD THIS STUFF AFTER THEME SETUP
	add_action('after_setup_theme', 'devlyThemeSupport');
	
	// ADDING A COUPLE OF SIDEBARS TO WORDPRESS
	add_action('widgets_init', 'devlyRegisterSidebars');
	
	// DEVLY SEARCH BAR (LOCATED IN FUNCTIONS.PHP)
	add_filter('get_search_form', 'devlySearch' );
	
	// FIX THE EXCERPTS READ MORE LINK
	add_filter('excerpt_more', 'devlyExcerptFix');
	
	// REMOVE P TAGS FROM AROUND POST IMAGES
	add_filter('the_content', 'devlyRemoveIMGP');
	
	// REMOVE TRACKBACKS FROM COMMENT COUNT
	add_filter('get_comments_number', 'devlyFixCommentCount', 0);
	
	// ADD "TIME AGO" FUNCTIONALITY TO WORDPRESS TIME
	add_filter('the_time', 'devlyCalculateTimeAgo');
	
	
	
	// LOAD UP THEME SETTINGS FOR ADMIN (CREATED IN SETTINGS.PHP)
	add_action( 'admin_init', 'devlyAdminStartup' );
	
	// REGISTER NEW MENU ITEM FOR THEME SETTINGS (CREATED IN SETTINGS.PHP)
	add_action( 'admin_menu', 'devlyCreateSettingsPage' );
	
	
	
	// ADD A VIEW COUNTER THAT SAVES VIEWS TO DATABASE
	add_action('wp_head', 'devlyCountPostViews');
	
	// BUILD A "VIEWS" COLUMN FOR POSTS IN ADMIN
	add_filter('manage_posts_columns', 'devlyViewsColumn');
	
	// SHOW VIEW COUNT FOR POSTS IN NEW ADMIN COLUMN
	add_action('manage_posts_custom_column', 'devlyShowPostViews', 10, 2);
	
}


// =========================================================================
// ====== CLEANUP FUNCTIONS FOR THE MESSY PARTS OF WORDPRESS 
// =========================================================================


// CLEAN UP WORDPRESS HEAD
function devlyCleanHead() {

	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_generator' );

}


// REMOVE WP VERSION FROM RSS
function devlyVersionRSS() { return ''; }


// REMOVE CSS INJECTION FROM COMMENTS WIDGET
function devlyRemoveCommentWidgetStyles() {
	
	if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
		
		remove_filter('wp_head', 'wp_widget_recent_comments_style');

	}

}


// =========================================================================
// ====== REGISTER AND ENQUEUE: SCRIPTS & STYLES
// =========================================================================


function devlyLoadScriptsAndStyles() {
	
	$devlyPath = get_template_directory_uri();
	
	if (!is_admin()) {
	
		// REGISTER MODERNIZR (WILL LOAD IN HEAD)
		wp_register_script('modernizr', $devlyPath . '/assets/scripts/libs/jquery.modernizr.js', null, '2.5.1', false);
		
		// REGISTER THE DEVLY RESET
		wp_register_style( 'devly-reset', $devlyPath . '/assets/css/devly.css', null, '2.5.0', 'all' );
		
		// REGISTER THE DEVLY MAIN STYLESHEET
		wp_register_style( 'devly-style', $devlyPath . '/assets/css/global.css', null, '2.5.0', 'all' );
		
		// REGISTER THE DEVLY RESPONSIVE STYLESHEET
		wp_register_style( 'devly-modern', $devlyPath . '/assets/css/responsive.css', null, '2.5.0', 'screen' );

		// REGISTER DEVLY SCRIPT TO THE FOOTER
		wp_register_script('devly-script', $devlyPath . '/assets/scripts/main.js', array('jquery'), '2.5.0', true);


		// ENQUEUE SCRIPTS & STYLES
		wp_enqueue_script('modernizr');
		wp_enqueue_style('devly-reset');
		wp_enqueue_style('devly-style');
		wp_enqueue_style('devly-modern');
		
		// ENQUEUE JQUERY & DEVLY SCRIPT
		wp_enqueue_script('jquery');
		wp_enqueue_script('devly-script');

	} else {

		if (is_admin()) {
			
			// REGISTER CUSTOM STYLES FOR ADMIN
			wp_register_style('devly-admin', $devlyPath . '/assets/core/styles/admin.css', array(), '1.0', 'all');
		    
		    // LOAD ADMIN STYLES (ONLY IN ADMIN)
		    wp_enqueue_style('devly-admin');
		
		}
		
	}
	
}


// =========================================================================
// ====== ADD WP+ MENU SUPPORT & THEME FUNCTIONALITY
// =========================================================================


function devlyThemeSupport() {

	// FEATURE THUMBNAILS (ADD CUSTOM SIZES IN FUNCTIONS.PHP)
	add_theme_support('post-thumbnails');

	// RSS AWESOMENESS
	add_theme_support('automatic-feed-links');

	// ADD POST FORMAT SUPPORT - TURNED OFF BECAUSE I HAVEN'T FOUND A USE FOR THEM
	//add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

	// ADD MENU SUPPORT FOR DEVLY THEME
	add_theme_support( 'menus' );

	// REGISTER DEFAULT MENUS
	register_nav_menus(
		array(
			'header_nav'	=> 'Main Menu',
			'footer_nav' 	=> 'Footer Menu'
		)
	);

}


// =========================================================================
// ====== BUILD IN THE DEVLY METABOX HELPER
// =========================================================================


function devlyMetaboxSetup() {

	if (!class_exists('Devly_Meta_Box')) {
	
		require_once 'meta.php';

	}

}


// =========================================================================
// ====== REGISTER DEFAULT MENUS & DYNAMIC WIDGET AREAS
// =========================================================================


// DEVLY'S MAIN MENU
function devlyMainMenu() {

	$defaults = array(
		'theme_location'	=> 'header_nav',
		'container'			=> false,
		'echo'				=> false,
		'item_wrap'			=> '%3$s',
		'depth'				=> 0
	);
	
	echo strip_tags(wp_nav_menu($defaults), '<a>');

	// CALL IN THEME LIKE THIS: devlyMainMenu();
	
	// return <a> tags without containers for me control

}


// DEVLY'S FOOTER MENU
function devlyFooterMenu() {

	$defaults = array(
		'theme_location'	=> 'footer_nav',
		'container'			=> false,
		'echo'				=> false,
		'item_wrap'			=> '%3$s',
		'depth'				=> 0
	);
	
	$footerLinks = strip_tags(wp_nav_menu($defaults), '<a>');
	
	echo '<nav id="footerNav" class="menu">'.$footerLinks.'</nav>';
	
	// CALL IN THEME LIKE THIS: devlyFooterMenu();

}


// REGISTER DYNAMIC WIDGETS
function devlyRegisterSidebars() {

    register_sidebar(array(
    	'id' 				=> 'devly-sidebar',
    	'name' 				=> 'Devly Dynamic Sidebar',
    	'description' 		=> 'The default devly sidebar.',
    	'before_widget' 	=> '<div id="%1$s" class="widget %2$s">',
    	'after_widget' 		=> '</div>',
    	'before_title' 		=> '<h4 class="widgettitle">',
    	'after_title' 		=> '</h4>'
    ));

}


// =========================================================================
// ====== CUSTOM COMMENTS LAYOUT FOR SINGLE PAGE
// =========================================================================


function devlyComments($comment, $args, $depth) {

	// MORE INFO: http://codex.wordpress.org/Function_Reference/wp_list_comments

	$GLOBALS['comment'] = $comment; ?>
	
	<article <?php comment_class('commentContainer clearfix'); ?> id="<?php comment_ID(); ?>" data-id="<?php comment_ID(); ?>">
		
		<div class="commentAuthContainer clearfix">
			
			<div class="commentAuthPhoto"><?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?></div>
			
			<div class="authorMeta">
				<h5 class="commentAuthName clearfix">
					<a href="<?php get_comment_author_link(); ?>"><?php comment_author(); ?></a>
				</h5><div style="clear:both;"></div>
				<span class="datetime">Posted <?php printf(__('%1$s'), get_comment_date(),  get_comment_time()) ?></span>
				<?php comment_reply_link($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])); ?>
			</div>
		
		</div>
		
		<div class="commentInfo">
			
			<div class="commentContent"><?php
				if($comment->comment_approved == '0') {
					echo '<span class=""><p>' . _e('Your comment is awaiting approval!') . '</p></span>';
				}
				
				echo '<p>' . comment_text() . '</p>'; ?>
			</div>
			
			<div class="commentMeta">
				<?php comment_reply_link($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])); ?>
			</div>
		
		</div>
	
	</article><?php

}


// =========================================================================
// ====== COUNT POST VIEWS FROM THE SINGLE.PHP PAGE
// =========================================================================


function devlyCountPostViews($post_ID) {

	$count_key 	= 'post_view_count';
    $count 		= get_post_meta($post_ID, $count_key, true);

    if($count == '') {

        $count = 0;

        delete_post_meta($post_ID, $count_key);
        add_post_meta($post_ID, $count_key, $count);

        return $count . ' View';

    } else {

        $count++;

        if($count == '1') { return $count . ' View'; } else { return $count . ' Views'; }

        update_post_meta($post_ID, $count_key, $count);

    }

}


// =========================================================================
// ====== HANDLE AND DISPLAY THE POST VIEWS COUNT IN ADMIN
// =========================================================================


function devlyGetPostViews($post_ID){

    $count_key 	= 'post_view_count';
    $count 		= get_post_meta($post_ID, $count_key, true);

    if(!$count) { return '0'; } else { return $count; }

}


function devlyViewsColumn($newcolumn){
	
	// SET UP NEW COLUMN IN ADMIN
    $newcolumn['post_views'] = __('Views');
    return $newcolumn;

}


function devlyShowPostViews($column_name, $id){

	if($column_name === 'post_views'){ 
		
		// POPULATE VIEWS COLUMN WITH VIEW COUNT
		echo devlyGetPostViews(get_the_ID()); 
		
	}

}


// =========================================================================
// ====== NUMERIC PAGE NAVIGATION FUNCTIONALITY
// =========================================================================


function devlyPageNavigation($before = '', $after = '') {

	global $wpdb;
	global $wp_query;

	$request 		= $wp_query->request;
	$foundPosts 	= $wp_query->found_posts;
	$maxPages 		= $wp_query->max_num_pages;
	$postsPerPage 	= intval(get_query_var('posts_per_page'));
	$paged 			= intval(get_query_var('paged'));

	// DONT EXECUTE IF POSTS FOUND IS LESS THAN POSTS PER PAGE
	if ($foundPosts <= $postsPerPage) { return; }

	// SET PAGINATION FUNCTIONALITY TO TRUE
	if(empty($paged) || $paged == 0) { $paged = 1; }

	$pageLinksLimit = 7;
	$newLinksLimit 	= $pageLinksLimit - 1;
	$startPage 		= $paged - $halfPageStart;
	$endPage 		= $paged + $halfPageEnd;
	$halfPageStart 	= floor($newLinksLimit / 2);
	$halfPageEnd 	= ceil($newLinksLimit / 2);

	// SETUP START PAGE
	if($startPage <= 0) { $startPage = 1; }

	// SETUP END PAGE
	if(($endPage - $startPage) != $newLinksLimit) { $endPage = $startPage + $newLinksLimit; }

	// CALCULATE NEW END PAGE
	if($endPage > $maxPages) {
		$startPage 	= $maxPages - $newLinksLimit;
		$endPage 	= $maxPages;
	}

	// SETUP STARTING POINT
	if($startPage <= 0) { $startPage = 1; }

	echo $before . '<nav class="pageNavigation"><ol class="devlyPageNav clearfix">' . "";

	// SETUP BACK TO FIRST PAGE LINK
	if ($startPage >= 2 && $pageLinksLimit < $maxPages) {

		$firstPageText = "First";
		echo '<li class="devlyFirstLink"><a href="' . get_pagenum_link() . '" title="' . $firstPageText . '">' . $firstPageText . '</a></li>';

	}

	// SETUP PREVIOUS PAGE LINK
	echo '<li class="prevPage">' . previous_posts_link('<<') . '</li>';

	// SETUP NUMBERED LINKS
	for($i = $startPage; $i <= $endPage; $i++) {

		if($i == $paged) { echo '<li class="currentPage">' . $i . '</li>'; } else { echo '<li><a href="' . get_pagenum_link($i) . '">' . $i . '</a></li>'; }

	}

	// SETUP NEXT PAGE LINK
	echo '<li class="nextPage">' . next_posts_link('>>') . '</li>';

	// SETUP GO TO LAST PAGE LINK
	if ($endPage < $maxPages) {

		$lastPageText = "Last";
		echo '<li class="devlyLastLink"><a href="' . get_pagenum_link($maxPages) . '" title="' . $lastPageText . '">' . $lastPageText . '</a></li>';

	}

	echo '</ol></nav>' . $after . "";

}


// =========================================================================
// ====== TIME DISPLAY: CALCULATE "TIME AGO" SINCE POSTED
// =========================================================================


function devlyCalculateTimeAgo() {
 
	global $post;
 
	$date = get_post_time('G', true, $post);

	$chunks = array(
		array( 60 * 60 * 24 * 365, __('year', 'devlytheme'), __('years', 'devlytheme')),
		array( 60 * 60 * 24 * 30 , __('month', 'devlytheme'), __('months', 'devlytheme')),
		array( 60 * 60 * 24 * 7, __('week', 'devlytheme'), __('weeks', 'devlytheme')),
		array( 60 * 60 * 24 , __('day', 'devlytheme'), __('days', 'devlytheme')),
		array( 60 * 60 , __('hour', 'devlytheme'), __('hours', 'devlytheme')),
		array( 60 , __('minute', 'devlytheme'), __('minutes', 'devlytheme')),
		array( 1, __('second', 'devlytheme'), __('seconds', 'devlytheme'))
	);
 
	if (!is_numeric($date)) {
		
		$timeChunks 	= explode( ':', str_replace( ' ', ':', $date ) );
		$dateChunks 	= explode( '-', str_replace( ' ', '-', $date ) );
		$date 			= gmmktime((int)$timeChunks[1], (int)$timeChunks[2], (int)$timeChunks[3], (int)$dateChunks[1], (int)$dateChunks[2], (int)$dateChunks[0]);
	
	}
 
	$current_time 	= current_time('mysql', $gmt = 0);
	$newer_date 	= strtotime($current_time);
	$since 			= $newer_date - $date;

	if (0 > $since) { return 'sometime'; }

	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
		
		$seconds = $chunks[$i][0];

		if (($count = floor($since / $seconds)) != 0) { break; }

	}

	$output = (1 == $count) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
 
 
	if (!(int)trim($output) ){
		
		$output = '0 ' . __( 'seconds', 'devlytheme' );
	
	}
 
	$output .= __(' ago', 'devlytheme');
 
	return $output;

}


// =========================================================================
// ====== MISCELLANEOUS: QUIRKY LITTLE THEME IMPROVEMENTS
// =========================================================================


// FIX READ MORE ELIPSES (THE EXERPT)
function devlyExcerptFix($more) {

	global $post;
	return '...  <a href="'. get_permalink($post->ID) . '" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';

}


// REMOVE "P" TAGS FROM AROUND IMAGES
function devlyRemoveIMGP($content){

	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);

}


// REMOVES TRACKBACKS FROM THE COMMENT COUNT
function devlyFixCommentCount($count) {

	if (!is_admin()) {

		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		
		return count($comments_by_type['comment']);

	} else {

		return $count;

	}

}

















// END OF THE DEVLY CORE FILE ?>