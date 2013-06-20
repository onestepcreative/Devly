
<aside class="sidebar small-12 medium-4 large-4 columns">

	<?php 
	
	if (is_active_sidebar('devly-sidebar')) {

		dynamic_sidebar( 'devly-sidebar' );

	} else {

		echo '<div class="help"><p>Alert! There are no widgets activated.</p></div>';

	} 
	
	?>

</aside>

