<?php

/*

	Author:   Josh McDonald
	Twitter:  @onestepcreative
	Website:  http://onestepcreative.com


	This file handles most of the custom admin
	functionality and cleanup. With this file we'll
	remove some default wordpress widgets, add
	custom styles for login, add new social
	newtworking fields for users, etc.
	
	Feel free to take a look around and make 
	any changes you need to! Heavily editting 
	the admin is not recommended, as things 
	might get weird during updates.

*/



// =========================================================================
// ====== DISABLE DEFAULT DASHBOARD WIDGETS  
// =========================================================================


function devlyDisableWidgets() {

	// RECENT COMMENTS, INCOMING LINKS & PLUGINS WIDGETS
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');

	// RECENT DRAFTS, PRIMARY & SECONDARY WIDGETS
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
	remove_meta_box('dashboard_primary', 'dashboard', 'core');
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');

	// YOASTS SEO PLUGIN WIDGET
	remove_meta_box('yoast_db_widget', 'dashboard', 'normal');

	// OTHERS YOU MAY WANT TO REMOVE
	// remove_meta_box('dashboard_right_now', 'dashboard', 'core');
	// remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	
}


// =========================================================================
// ====== CREATE NEW DASHBOARD WIDGETS: FOR YOUR VIEWING PLEASURE  
// =========================================================================


// RSS DASHBOARD WIDGET
function devlyDashboardRSS() {

	if(function_exists('fetch_feed')) {
		
		// MUST INCLUDE THIS FILE FOR SUCCESS
		include_once(ABSPATH . WPINC . '/feed.php');
		
		// DEFINE WHICH FEED YOU WANT TO GET
		$feed 	= fetch_feed('http://blog.onestepcreative.com/feed/rss/');
		
		// SETUP SOME VARIABLES TO WORK WITH
		$limit 	= $feed->get_item_quantity(7);
		$items 	= $feed->get_items(0, $limit);

	} if ($limit == 0) {
		
		// JUST A FRIENDLY FALLBACK MESSAGE
		echo '<div>The requested feed is empty or unavailable.</div>';

	} else { 
		
		// LOOP THRU RESULTS
		foreach ($items as $item) { 
			
			$postLink	= $item->get_permalink();
			$postTitle	= $item->get_title();
			
			echo '<h4 class="devlyFeedTitle"><a href="'.$postLink.'" target="_blank">'.$postTitle.'</a></h4>';
			echo '<p class="devlyFeedDesc">'.substr($item->get_description(), 0, 200).'</p>';
			
		} 

	}

}


// SIMPLE TWITTER WIDGET
function devlyTwitterFeed() {
	
	// USER TO GET TWEETS FROM
	$user	= 'onestepcreative';
	
	$count 	= 10;
	$string	= file_get_contents('http://api.twitter.com/1/statuses/user_timeline/'.$user.'.json?count=' . $count);
	$tweets	= json_decode($string); 
	
	// LOOP THRU RESULTS
	foreach ($tweets as $tweet) {
		
		$name		= $tweet->user->name;
		$handle		= $tweet->user->screen_name;
		$image		= $tweet->user->profile_image_url;
		$text 		= $tweet->text;
		$tweetID	= $tweet->id;

		?>
		
		<div class="tweetContainer clearfix">
		
			<div class="tweetone">
				<div class="tweetThumb">
					<img src="<?php echo $image; ?>" alt="twitter img" height="48" width="48" />
				</div>
			</div>
			
			<div class="tweettwo">
				<h3 class="tweetName clearfix">
					<a href="http://twitter.com/<?php echo $handle; ?>" class="twitterDirect" target="_blank">
						<?php echo $name; ?><span class="tweetHandle">@<?php echo $handle; ?></span>
					</a>
				</h3>
				
				<div style="clear:both;"></div>
				
				<div class="tweetText clearfix"><p><?php echo $text; ?></p></div>
			
				<div class="tweetConnect clearfix">
					<a href="" target="_blank">
						<span class="tweetReply">
							<img src="/wp-content/themes/devly/assets/img/admin/tweet-reply.png" height="10" width="13" alt="Twitter Reply" /> Reply
						</span>
					</a>
				</div>
			</div>
			
		</div>
	    
	    <?php
		
	}

}


// CALL ALL NEW WIDGETS
function devlyDashboard() {

	wp_add_dashboard_widget('devlyDashboardRSS', 'Recently on onenstepcreative', 'devlyDashboardRSS');
	wp_add_dashboard_widget('devlyTwitterFeed', 'Latest Tweets From @onestepcreative', 'devlyTwitterFeed');
	
	// ANY OTHER WIDGETS SHOULD BE ADDED HERE

}


// TELL WORDPRESS TO REMOVE WIDGETS
add_action('admin_menu', 'devlyDisableWidgets');

// TELL WORDPRESS TO ADD THE NEW WIDGETS
add_action('wp_dashboard_setup', 'devlyDashboard');


// =========================================================================
// ====== CUSTOM URL COLUMN FOR MEDIA LISTINGS  
// =========================================================================


function devlyMediaColumn($columns) {
	
	$columns["media_url"] = "URL";
	
	return $columns;

}


function devlyMediaColumnValue($columnName, $id) {

	if ($columnName == "media_url" ) {
		
		echo '<input type="text" width="100%" onclick="jQuery(this).select();" value="'. wp_get_attachment_url($id). '" />';
	
	}
	
}


// ADD NEW COLUMN TO MEDIA LISTINGS IN ADMIN
add_filter( 'manage_media_columns', 'devlyMediaColumn' );

// POPULATE MEDIA COLUMN WITH MEDIA URL, FOR QUICK COPY + PASTE
add_action( 'manage_media_custom_column', 'devlyMediaColumnValue', 10, 2 );


// =========================================================================
// ====== LOGIN PAGE: CUSTOM STYLES + SIGNIN BY EMAIL & USERNAME  
// =========================================================================


add_action('login_head', 'devlyLoginCSS');

// CUSTOM LOGIN STYLESHEET
function devlyLoginCSS() { 

	echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/assets/core/styles/login.css">'; 
	
}


// LOGIN WITH USERNAME OR EMAIL
function devlyEmailLogin($username) {
	
	$user = get_user_by('email', $username);
	
	if(!empty($user->user_login))
		
		$username = $user->user_login;
	
	return $username;

}


// CHANGE LOGIN TEXT
function devlyLoginText($text) {
	
	if(in_array($GLOBALS['pagenow'], array('wp-login.php'))) {
		
		if($text == 'Username') { $text == 'username or email'; }
		
	}
	
	return $text;
	
}


// ADD LOGIN STYLES ONLY TO LOGIN PAGE
add_action('login_enqueue_scripts', 'devlyLoginCSS', 10);

// TELL WP WE CAN LOGIN WITH USERNAME OR EMAIL
add_action('wp_authenticate','devlyEmailLogin');

// TELL WP TO CHANGE LOGIN TEXT
add_filter('gettext', 'devlyLoginText');


// =========================================================================
// ====== CUSTOMIZE ADMIN: FOOTER TEXT + USER CONTACT METHODS 
// =========================================================================


// ADD FACEBOOK, TWITTER & GOOGLE+ TO PROFILES
function devlyUserNetworkFields($contactmethods) {

	// ADD NEW FIELDS
    $contactmethods['user_tw'] = 'Twitter';
    $contactmethods['user_fb'] = 'Facebook';
    $contactmethods['user_gp'] = 'Google+';

    // REMOVE UNWANTED FIELDS
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);

    return $contactmethods;

}


// CHANGE ADMIN FOOTER TEXT
function devlyAdminFooter() {

	echo '<span id="footer-thankyou">Developed by <a href="https://github.com/onestepcreative" target="_blank">Josh McDonald</a></span>';

}


// ADD NEW SOCIAL FIELDS TO USER PROFILES
add_filter('user_contactmethods','devlyUserNetworkFields', 10, 1);

// TELL WORDPRESS ABOUT NEW FOOTER TEXT
add_filter('admin_footer_text', 'devlyAdminFooter');



// END OF DEVLY ADMIN.PHP FILE ?>