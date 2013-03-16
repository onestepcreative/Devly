<?php

/*

	Author:   Josh McDonald
	Twitter:  @onestepcreative
	Website:  http://onestepcreative.com

	This file is here for you to add any custom
	functionalities you may need. The scripts that
	are "require_once" below include some of the
	themes core functionality. It is recommended
	that the scripts below are not removed.

*/


// =========================================================================
// ====== GET THE DEVLY CORE UP AND RUNNING (DO NOT REMOVE)
// =========================================================================


// LOAD UP THE DEVLY CORE
require_once('assets/core/core.php');

// LOAD THE CUSTOM ADMIN FUNCTIONALITY
require_once('assets/core/admin.php');

// HOOK IN THE DEVLY THEME SETTINGS PAGE
require_once('assets/core/settings.php');

// LOAD UP THE DEVLY THEME HELPERS
require_once('assets/core/helpers.php');

// LOAD & BUILD OUR SAMPLE METABOXES
require_once('meta-example.php');


// =========================================================================
// ====== REGISTER CUSTOM IMAGES SIZE HERE (TRUE = HARD CROP)
// =========================================================================


add_image_size( 'devly-large', 720, 300, true );
add_image_size( 'devly-medium', 400, 150, true );
add_image_size( 'devly-thumb', 210, 100, true );

// SET THUMBNAIL SIZE
set_post_thumbnail_size( 125, 125, true );


// =========================================================================
// ====== SEARCHBAR CALLED FROM THE "GET_SEARCH_FORM()" FUNCTION
// =========================================================================


function devlySearch($form) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <input type="text" value="' . get_search_query() . '" name="search" id="search" class="text" placeholder="Search the Site..." />
    <button type="submit" id="search-submit">Search</button>
    </form>';
    return $form;
}


// =========================================================================
// ====== ALL YOUR CUSTOM FUNCTIONS SHOULD START HERE
// =========================================================================













 ?>