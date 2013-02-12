
<aside id="sidebarContainer" class="sidebar fourcol last clearfix">

	<?php 
	
	if (is_active_sidebar('devly-sidebar')) {

		dynamic_sidebar( 'devly-sidebar' );

	} else {

		echo '<div class="help"><p>Alert! There are no widgets activated.</p></div>';

	} 
	
	?>

</aside>

