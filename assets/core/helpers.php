<?php

/*

	Author:   Josh McDonald
	Twitter:  @onestepcreative
	Website:  http://onestepcreative.com


	This file contains a few extra functions
	I've written over time that I tend to use in
	most Wordpress sites. You'll find things 
	like title & excerpt truncation functions
	and a related posts generator. 
	
	Theres some good stuff here. Feel free
	to have some fun with it. You don't need
	to use it, unless you need it.

*/


// =========================================================================
// ====== GLOBAL VARIABLE ASSIGNMENT TO HANDLE THEME SETTINGS
// =========================================================================

/*

	This variable assignment is to make the use
	of Theme Option simple. Theme Options can be set
	in the admin by clicking the "Devly Settings"
	tab in the bottom of the sidebar.
	
	To retreive a single value where 'bg_color'
	is the value being retreived. USE:
	
	echo $devlyOptions['bg_color']
	
	Or to get all options, USE:
	
	foreach($devlyOptions as $option) echo $option;
	
*/



// DEFINE GLOBAL VARIABLE TO HOLD OPTIONS
global $devlyOptions;

// ASSIGN THE 'DEVLYTHEMESETTINGS' ARRAY OF OPTIONS TO VARIABLE
$devlyOptions = get_option('devlyThemeSettings', $devlyOptions);



// =========================================================================
// ====== FUNCTION TO TRUNCATE POST TITLES 
// =========================================================================

/*

	This function is pretty stright forward.
	It accepts one parameter ($limit), which is
	the character count you'd like to display
	before you start truncating your title.
	
	Unlike truncating an excerpt, we are
	using mb_strlen() so that titles aren't
	cut off in the middle of words. This
	will find the nearest space and 
	then truncates.
	
	Usage: Use in place of the_title()
	
	This would begin to truncate after
	the 25th character: 
	
	devlyTruncateTitle(25);

*/

function devlyTruncateTitle($limit) {

	global $post;

	$title = get_the_title($post->ID);
	
	if (mb_strlen($title, 'utf8') > $limit) {
	
		$truncHere 	= strrpos(substr($title, 0, $limit), ' ');	   
		$title 		= substr($title, 0, $truncHere) . '';	
		
	}
	
	echo $title;

}


// =========================================================================
// ====== FUNCTION TO TRUNCATE POST TITLES 
// =========================================================================

/*

	Unlike truncating the title, this
	function will begin truncating in the
	middle words, which means the exact
	character count that is passed, is
	when you'll begin truncating.
	
	Usage: Use in place of the_excerpt()
	
	Example: calling devlyTruncateExcerpt(5)
	on this string:
	
	"Truncating things is awesome"
	
	Would output this:
	
	"Trunc..."

*/

function devlyTruncateExcerpt($limit) {

	global $post;

	$excerpt	 	= strip_tags(get_the_excerpt($post->ID));

	$devlyExcerpt 	= substr($excerpt, 0, $limit);
	$truncExcerpt 	= substr($excerpt, 0, $limit) . '...';

	if($excerpt > $devlyExcerpt) {

		echo $truncExcerpt;

	} else {

		echo $devlyExcerpt;

	}


}


// =========================================================================
// ====== FUNCTION TO GET RELATED POSTS FOR CURRENT DISPLAYED POST
// =========================================================================

/*

	This function returns a given amount
	of related posts based on tags that
	are assigned to the post you are
	currently viewing.

	This function is meant to be used on
	the Single post page, and shouldn't
	be used otherwise without alteration. 
	It accepts one parameter, which is 
	the number of related posts you'd 
	like to display. To call 3 posts, 
	just use it like this:
	
	devlyRelatedPosts(3);

*/


function devlyRelatedPosts($number) {

	global $post;

	$tags = wp_get_post_tags($post->ID);

	echo '<ul id="devlyRelatedPosts">';

	if($tags) {

		// LOOP THRU TAGS & GET SLUGS
		foreach($tags as $tag) { $tag_arr .= $tag->slug . ','; }

		// SETUP QUERY PARAMETERS
        $args = array('tag' => $tag_arr, 'numberposts' => $number, 'post__not_in' => array($post->ID));

     	// QUERY THE POSTS
        $related_posts = get_posts($args);

        // LOOP THRU AND DISPLAY RELATED POSTS
        if($related_posts) {

        	foreach ($related_posts as $post) { 
        	
        		setup_postdata($post); ?>
	           	
	           	<li class="relatedPost">
	           		<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
	           			<?php the_post_thumbnail('related-thumb'); ?>
	           		</a>
	           	</li><?php
		    
		    }

		} else {

			// FALLBACK MESSAGE IF NO RELATED POSTS
        	echo '<li class="relatedEmpty">There are no related posts to display!</li>';

        }

	}

	wp_reset_query();

	echo '</ul>';

}


// =========================================================================
// ====== DISPLAY SIMPLE AUTHOR INFORMATION INSIDE LOOP
// =========================================================================

/*

	This function returns minimal info
	about the author for any given post.
	This is typically used on the single
	post page, and will return the authors
	name, gravatar and bio.
	
	Usage: devlySimpleAuthor();

*/


function devlySimpleAuthor() { 

	$authID = get_the_author_meta('ID'); 
	$size	= '78';
	
	?>

	<div id="authorBioContainer">
		
		<div class="gravatar"><?php echo get_avatar($authID, $size); ?></div>
		
		<div class="aboutAuthor">
			<h3 class="authorName"><?php the_author(); ?></h3>
			<p><?php echo get_the_author_meta('description') ?></p>
		</div>
		
	</div>
	
	<?php

}


// =========================================================================
// ====== DISPLAY THE MOST VIEWED POSTS WITH A CUSTOM QUERY
// =========================================================================

/*

	This function assumes that you haven't turned
	off the view counter functionality that is built
	into the devly core. Double check to make sure
	these functions exist in the specified files. 
	If these functions and actions appear in the
	files listed, you should be good to go.
	
	DEVLY CORE FILE (assets/core/core.php):
		- add_action('wp_head', 'devlyCountPostViews');
		- function devlyCountPostViews($post_ID){...}
		
	IN YOUR SINGLE.PHP FILE:
		- devlyCountPostViews(get_the_ID());
	
	This function queries the database by a meta
	key called "post_view_count", which is attached
	to each post in the database. We then tell the
	database that we want to order the results by 
	the value of "post_view_count", which gives us
	the most viewed posts of all time.
	
	Simply pass the number of posts you want to
	display, to the function and you're done. This
	function is best used in the sidebar or footer.
	
	Example: devlyPopularPosts(10);
	
		- this would return the 10 most viewed
		  posts on your site.

*/

function devlyPopularPosts($number) {

	$trending = array(
		'posts_per_page' => $number,
		'meta_key' 		 => 'post_view_count',
		'orderby' 		 => 'meta_value_num'
	);

	$trendingPosts = new WP_Query($trending); ?>

	<h2 class="sideHeading">Trending Posts</h2>

	<ul id="trendingPosts" class="sideList">

		<?php while($trendingPosts->have_posts()) : $trendingPosts->the_post(); ?>

			<li class="sideListItem clearfix">
				<a href="<?php the_permalink(); ?>">

					<span class="listItemPhoto"><?php the_post_thumbnail('devly-thumb'); ?></span>

					<div class="listItemInfo">
						<p class="listItemDate"><?php the_time('j M, Y'); ?></p>
						<h4 class="listItemTitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					</div>

				</a>
			</li>

		<?php endwhile; ?>

	</ul><?php 
	
	// DO NOT REMOVE THIS
	wp_reset_postdata();

}


// =========================================================================
// ====== DEFAULT ARCHIVE TEMPLATE TITLING LOGIC 
// =========================================================================

/*

	In an attempt to clean up, and abstract
	as much logic as possible from the page
	templates on the front end, this code is
	the default you see in most themes to 
	display the proper title on the archive
	pages. Nothing special.
	
	Usage: devlySimpleAuthor();

*/


function devlyArchiveTitles() {
	
	if (is_category()) {
		echo '<h1 class="archiveTitle"><span>' . _e("Posts Categorized:") . '</span> ' . single_cat_title() . '</h1>';
	} elseif (is_tag()) {
		echo '<h1 class="archiveTitle"><span>' . _e("Posts Tagged:") . '</span> ' . single_tag_title() . '</h1>';
	} elseif (is_author()) {
		echo '<h1 class="archiveTitle"><span>' . _e("Posts By:") . '</span> ' . get_the_author_meta('display_name') . '</h1>';
	} elseif (is_day()) {
		echo '<h1 class="archiveTitle"><span>' . _e("Daily Archives:") . '</span> ' . the_time('l, F j, Y') . '</h1>';
	} elseif (is_month()) {
		echo '<h1 class="archiveTitle"><span>' . _e("Monthly Archives:") . '</span> ' . the_time('F Y') . '</h1>';
	} elseif (is_year()) {
		echo '<h1 class="archiveTitle"><span>' . _e("Yearly Archives:") . '</span> ' . the_time('Y') . '</h1>';
	}
	
}


// =========================================================================
// ====== MAIN LOOP "CONTENT NOT FOUND" FALLBACK 
// =========================================================================

/*

	I find it better to wrap this 'no content
	found' fallback into its own so you're able
	to change it one place, and it'll change
	wherever it's used. I also think the end of
	loop can be pain sometimes, this helps
	out a little bit.
	
	Usage: devlyContentNotFound();

*/

function devlyContentNotFound() {
	
	?>
	
	<article class=" articleContainer clearfix">
		<hgroup class="contentNotFound">
			<h3>You appear to be lost...</h3>
			<h5>It's probably on us, so you can bet we're working on it.</h5>
		</hgroup>
	</article>
	
	<?php
	
}


// =========================================================================
// ====== A SIMPLE QUERY TESTER FOR DEVELOPMENT
// =========================================================================

/*

	The devlyQueries function returns a nice 
	string to the page that lets you know how 
	many queries were ran on page load. To
	get the best results, run this function
	in footer.php
	
	Usage: devlyQueries();
	
	- Returns: devly ran 14 queries in 0.025 seconds

*/

function devlyQueries() {
	
	echo 'ran ' . get_num_queries() . ' queries in ' . timer_stop(1) . ' seconds';
	
}









// END OF THE DEVLY HELPER FILE ?>