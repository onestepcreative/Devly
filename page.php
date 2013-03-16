<?php 

/* 

	YOUR DEFAULT PAGE TEMPLATE

	This is your default page template that
	will be used unless otherwise specified when
	creating a new page in wordpress.

*/


get_header(); 

?>

<div id="contentContainer" class="wrap clearfix">
	
	<div id="mainContent" class="eightcol clearfix">

		<?php 
		
		if (have_posts()) : while (have_posts()) : the_post(); 
			
			$permalink = get_permalink(); ?>

			<article class="articleContainer clearfix" id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" role="article">
	
				<hgroup class="postHeading">
					<h2 class="postTitle">
						<a href="<?php echo $permalink; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h2>
					<h5 class="postMeta"><?php the_time('j M, Y'); ?>&nbsp;&nbsp;//&nbsp;&nbsp;Posted Under <?php the_category(', '); ?></h5>
				</hgroup>
	
				<div class="postContent"><?php the_content(); ?></div>
				
				<div class="postFooter"><p class="tags"><?php the_tags('<span class="tagsTitle">Tags:</span> ', ', ', ''); ?></p></div>
		
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