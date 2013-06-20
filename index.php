<?php 

/* 

	WELCOME TO THE INDEX.PHP FILE

	This is the main loop wordpress, by default,
	uses for the home page. Of course that can be
	changed a nummber of different ways, but I
	do recommend leavingthis loop as your 
	backup - you can always copy this file
	and edit it as your wish. 

*/


get_header(); 

?>

<div id="content-container" class="row">

	<div id="main-content" class="small-12 medium-8 large-8 column">

		<?php 
		
		if (have_posts()) : while (have_posts()) : the_post(); 
			
			$permalink = get_permalink(); ?>

			<article id="post-<?php the_ID(); ?>" class="article-container" data-id="<?php the_ID(); ?>" role="article">
	
				<hgroup class="post-heading">
					
					<h2 class="post-title"><a href="<?php echo $permalink; ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<h5 class="post-meta"><?php the_time('j M, Y'); ?>&nbsp;&nbsp;//&nbsp;&nbsp;Posted Under <?php the_category(', '); ?></h5>
				
				</hgroup>
	
				<div class="post-excerpt">
					
					<?php devlyTruncateExcerpt(330); // FOUND IN HELPER FILE (assets/core/helper.php) ?>
					
				</div>
				
				<a href="<?php echo $permalink; ?>" class="read-more">Read More &rarr;</a>
				
				<!-- // UNCOMMENT TO SHOW TAGS IN POST LISTS // -->
				<!-- <div class="postFooter"><p class="tags"><?php //the_tags('<span class="tagsTitle">Tags:</span> ', ', ', ''); ?></p></div> -->
		
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