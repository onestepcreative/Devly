<?php

/* 

	AUTHOR ARCHIVES PAGE TEMPATE
	---
	This page serves as the template for all 
	author archives. We use get_query_var to get
	the current author to make sure the proper
	posts are displayed 

*/

get_header();

$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

?>

<div id="contentContainer" class="wrap clearfix">

	<div id="mainContent" class="eightcol clearfix">

		<?php 
		
		// FOUND IN HELPER FILE (assets/core/helper.php)
		devlyArchiveTitles(); 
		
		if (have_posts()) : while (have_posts()) : the_post(); 
			
			$permalink = get_permalink(); ?>

			<article class="articleContainer archive clearfix" id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" role="article">
	
				<hgroup class="postHeading">
					<h2 class="postTitle">
						<a href="<?php echo $permalink; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h2>
					<h5 class="postMeta"><?php the_time('j M, Y'); ?>&nbsp;&nbsp;//&nbsp;&nbsp;Posted Under <?php the_category(', '); ?></h5>
				</hgroup>
	
				<div class="postExcerpt"><?php devlyTruncateExcerpt(250); // FOUND IN HELPER FILE (assets/core/helper.php) ?></div>
				
				<a href="<?php echo $permalink; ?>" class="readMore">Read More &rarr;</a>
		
			</article>

		<?php 
		
		endwhile;

			if(function_exists('devlyPageNavigation')) {
			
				 // PART OF DEVLY'S CORE (assets/core/core.php)
				devlyPageNavigation();
	
			} else { ?>

				<nav class="defaultPageNav">
					<ul class="clearfix">
						<li class="nextSingle"><?php next_posts_link(__('&laquo; Older Entries', 'devlytheme')) ?></li>
						<li class="prevSingle"><?php previous_posts_link(__('Newer Entries &raquo;', 'devlytheme')) ?></li>
					</ul>
				</nav> <?php 
			
			}
			
		else :
			
			// FOUND IN HELPER FILE (assets/core/helper.php)
			devlyContentNotFound(); 

		endif; // END MAIN LOOP ?>
				
		</div>
		
	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>