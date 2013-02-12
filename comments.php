<?php

// TEMPLATE TO DISPLAY COMMENTS FORM
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');

	// BEGIN COMMENT CODE
	if (post_password_required() ) {
	
		echo '<div class="help"><p class="nocomments">This post is password protected.</p></div>';
		
		return; 
		
	}

	if (have_comments()) : ?>

		<nav id="commentsNav">
			<ul class="clearfix">
		  		<li><?php previous_comments_link() ?></li>
		  		<li><?php next_comments_link() ?></li>
		 	</ul>
		</nav>
	
		<ol class="commentlist">
			<?php wp_list_comments('type=comment&callback=devlyComments'); ?>
		</ol>
	
		<nav id="commentsNav">
			<ul class="clearfix">
		  		<li><?php previous_comments_link() ?></li>
		  		<li><?php next_comments_link() ?></li>
			</ul>
		</nav>

	<?php 
	
	else :
	
		if (comments_open()) {
			
			
			
		} else {
			
			echo '<p class="nocomments">Comments are closed.</p>';
			
		}

	endif;


	if (comments_open()) : ?>

	<section id="respond" class="respondForm">

		<div id="commentsCancel">
			<p class="small"><?php cancel_comment_reply_link(); ?></p>
		</div>

		<?php 
		
		if ( get_option('comment_registration') && !is_user_logged_in() ) :

	  		echo '<div class="help"><a href="<?php echo wp_login_url( get_permalink() ); ?>">Log In</a> to comment.</p></div>';

	  	else : ?>

			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

				<?php if ( is_user_logged_in() ) : ?>

					<p class="comments-logged-in-as">
						Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. 
						<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a>
					</p>

				<?php else : ?>

					<div id="commentElements" class="clearfix">
						
						<div class="replyInputContainer">
							<label for="author">Your Name</label>
							<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="Enter your name" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
						</div>
						
						<div class="replyInputContainer last">
							<label for="email">Your Email</label>
							<input type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="Enter your email" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
						</div>
										
					</div>

				<?php endif; ?>

				<p><textarea name="comment" id="comment" placeholder="Your comment here..." tabindex="4"></textarea></p>

				<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" /><?php comment_id_fields(); ?></p>

				<?php do_action('comment_form', $post->ID); ?>

			</form>

		<?php endif; ?>

	</section>
	
<?php endif; // DO NOT DELETE THIS ENDIF ?>
