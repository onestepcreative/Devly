<!DOCTYPE html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?> class="no-js iem7"> <![endif]-->
<!--[if lt IE 7]> 	  <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    	  <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    	  <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 8)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html class="no-js" dir="ltr" lang="en-US"><!--<![endif]-->

<head>

	<meta charset="UTF-8">
	
	<title><?php wp_title(''); ?></title>
		
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<meta name="keywords" content="devly, developer theme, wordpress, theme, responsive, grid system, 1140" />
	<meta name="description" content="Devly is a modern theme, built to give developers a solid starting point for wordpress dev." />
	<meta name="author" content="Josh McDonald" />
	
	<meta name="application-name" content="Devly Theme" />
	<meta name="viewport" content="width=device-width">
	
	<link href="/wp-content/themes/devly/assets/img/ui/favicon.ico" rel="shortcut icon">
	<link href="http://fonts.googleapis.com/css?family=Volkhov" rel="stylesheet" type="text/css">
	
	<!-- // UNCOMMENT TO USE THEME SETTINGS FAVICON // -->
	<!-- <link href="<?php echo $devlyOptions['devly_favicon']; ?>" rel="shortcut icon"> -->
	
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="page-container">

	<header class="header-container">
		<div class="row">

			<hgroup class="logo-wrap small-12 medium-4 large-4 column">
				
				<h3 class="logo"><a href="/" rel="nofollow">Devly</a></h3>
				<h5 class="slogan">a theme for developers</h5>
			
			</hgroup>
			
			<nav class="main-nav menu small-12 medium-8 large-8 column">
				
				<?php devlyMainMenu(); // THIS MENU IS REGISTERED IN /assets/core/core.php ?>
			
			</nav>
			
		</div>
	</header>
	





