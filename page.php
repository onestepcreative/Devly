<?php 

/* 

	YOUR DEFAULT PAGE TEMPLATE

	This is your default page template that
	will be used unless otherwise specified when
	creating a new page in wordpress.

*/


get_header(); 

?>

<div id="content-container" class="row">

	<div id="main-content" class="small-12 medium-8 large-8 column">

		<?php 
		
		if (have_posts()) : while (have_posts()) : the_post(); 
			
			$permalink = get_permalink(); ?>

			<article id="post-<?php the_ID(); ?>" class="article-container single" data-id="<?php the_ID(); ?>" role="article">
	
				<hgroup class="post-heading">
					
					<h2 class="post-title"><a href="<?php echo $permalink; ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					
					<!-- // UNCOMMENT TO SHOW AUTHOR & CATEGORY // -->
					<!-- <h5 class="post-meta"><?php //the_time('j M, Y'); ?>&nbsp;&nbsp;//&nbsp;&nbsp;Posted Under <?php //the_category(', '); ?></h5> -->
				
				</hgroup>
	
				<div class="post-content"><?php the_content(); ?></div>
				
				<!-- // UNCOMMENT TO SHOW TAGS IN POST LISTS // -->
				<!-- <div class="post-footer"><p class="tags"><?php the_tags('<span class="tags-title">Tags:</span> ', ', ', ''); ?></p></div> -->
		
			</article>

		<?php 
		
		endwhile; else :
			
			// FOUND IN HELPER FILE (assets/core/helper.php)
			devlyContentNotFound(); 

		endif; // END MAIN LOOP ?>
				
		</div>
		
	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>