<?php

	get_header();

	$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

?>

<div id="contentContainer" class="wrap clearfix">

	<div id="mainContent" class="eightcol clearfix">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article class="articleContainer clearfix" id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>">
	
				<hgroup class="postHeading pageHeading">
					<h2 class="postTitle"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
					<h5 class="postMeta"><?php the_time('j M, Y'); ?>&nbsp;&nbsp;//&nbsp;&nbsp;Posted Under <?php the_category(', '); ?></h5>
				</hgroup>
	
				<div class="postExcerpt">
					<?php devlyTruncateExcerpt(300); ?>
				</div>
				
				<a href="<?php the_permalink(); ?>" class="readMore">Read More &rarr;</a>
		
			</article>

		<?php endwhile;

		if (function_exists('devlyPageNavigation')) {

			devlyPageNavigation();

		} else { ?>

			<nav class="pageNav">
				<ul class="clearfix">
					<li class="next-link"><?php next_posts_link(__('&laquo; Older Entries', "devlytheme")) ?></li>
					<li class="prev-link"><?php previous_posts_link(__('Newer Entries &raquo;', "devlytheme")) ?></li>
				</ul>
			</nav>

		<?php } else : ?>

			<article id="fourOhFour" class="clearfix">

				<hgroup class="errorContainer">
					<h1>Epic 404 - Page Not Found</h1>
					<h6>The content you were looking for was not found!</h6>
				</hgroup>

			</article>

		<?php endif; ?>
				
		</div>
		
	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>