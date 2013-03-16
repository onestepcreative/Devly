<?php

/*
	Author:   Josh McDonald
	Twitter:  @onestepcreative
	Website:  http://onestepcreative.com
	
	This file handles creates all of the
	"Theme Settings" you see in the devly
	admin. This is here for you to customize
	and make your own settings, if needbe.
	
	This was written a while ago, so I'm
	sure there is a more elegant way to do
	this now.
	
	Initialized in core.php:
	add_action( 'admin_init', 'devlyAdminInit' );
	add_action( 'admin_menu', 'devlySettingsPageInit' );
	
*/



// =========================================================================
// ====== SETUP DEFAULTS FOR NEW DEVLY SETTINGS PAGE 
// =========================================================================


function devlyAdminStartup() {

	$settings = get_option( "devlyThemeSettings" );
	
	if ( empty( $settings ) ) {
	
		$settings = array(
			'devly_intro' => 'Some intro text for the home page',
			'devly_tag_class' => false,
			'devly_ga' => false
		);
		
		add_option( "devlyThemeSettings", $settings, '', 'yes' );
	
	}	

}


// =========================================================================
// ====== REGISTER NEW SETTINGS PAGE WITH WORPDRESS ADMIN 
// =========================================================================


function devlyCreateSettingsPage() {

	$theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
	$settings_page = add_menu_page( 'Devly Settings', 'Devly Settings', 'edit_theme_options', 'theme-settings', 'devlyBuildSettingsPage' );
	
	add_action( "load-{$settings_page}", 'devlyLoadSettingsPage' );

}


// =========================================================================
// ====== REDIRECT USERS TO SETTINGS PAGE AFTER SAVE 
// =========================================================================


function devlyLoadSettingsPage() {

	if ( $_POST["devly-submit-settings"] == 'Y' ) {
	
		check_admin_referer( "devly-settings-page" );
		devlySaveThemeSettings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('admin.php?page=theme-settings&'.$url_parameters));
		exit;
	
	}

}


// =========================================================================
// ====== SAVE ALL NEW DATA ENTERED ON SETTINGS PAGE 
// =========================================================================


function devlySaveThemeSettings() {

	global $pagenow;
	
	$settings = get_option( "devlyThemeSettings" );
	
	if ( $pagenow == 'admin.php' && $_GET['page'] == 'theme-settings' ){ 
	
		if (isset($_GET['tab'])) $tab = $_GET['tab']; else $tab = 'general'; 

	    switch ( $tab ){ 
	        
	        case 'general' :
				$settings['devly_color_scheme']		= $_POST['devly_color_scheme'];
				$settings["devly_favicon"]			= $_POST['devly_favicon'];
				$settings["devly_feedburner"]		= $_POST['devly_feedburner'];
				$settings["devly_ga_code"]			= $_POST['devly_ga_code'];
			break; 
			
			case 'social' : 
				$settings['devly_twitter']			= $_POST['devly_twitter'];
				$settings['devly_facebook']			= $_POST['devly_facebook'];
				$settings['devly_google']			= $_POST['devly_google'];
				$settings['devly_pin']				= $_POST['devly_pin'];
			break;
			
			case 'api' : 
				$settings['devly_twitter_key']		= $_POST['devly_twitter_key'];
				$settings['devly_fb_app']			= $_POST['devly_fb_app'];
				$settings['devly_google_key']		= $_POST['devly_google_key'];
				$settings['devly_buffer_key']		= $_POST['devly_buffer_key'];
			break;

	    }
	
	}

	$updated = update_option( "devlyThemeSettings", $settings );

}


// =========================================================================
// ====== CREATE DEFAULT TABS IN SETTINGS PAGE 
// =========================================================================


function devlyAdminTabs($current = 'general') { 
	
	$tabs	= array( 'general' => 'General Settings', 'social' => 'Social Networking', 'api' => 'API Integration'); 
	$links	= array();
	
	echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	
	foreach($tabs as $tab => $name) {
        
		$class = ($tab == $current) ? ' nav-tab-active' : '';
        
        echo "<a class='nav-tab$class' href='?page=theme-settings&tab=$tab'>$name</a>";
        
    }
    
    echo '</h2>';

}


// =========================================================================
// ====== FUNCTION TO HANDLE THE OUTPUT OF THE SUCCESS MESSAGE
// =========================================================================


function devlyThemeSubmitButton() {
	
	$saveSuccess = '<h4 class="devlySavedSuccess"><img src="/wp-content/themes/devly/assets/img/ui/success.png" /> ';
	$saveSuccess .= 'Your Theme Settings Have Been Successfully Updated.</h4>'; ?>
	
	<div class="devlyButtonContainer">
		
		<input type="submit" name="Submit"  class="devlySaveSettings" value="Update Settings" />
		<input type="hidden" name="devly-submit-settings" value="Y" />  
		<?php if('true' == esc_attr($_GET['updated'])) { echo $saveSuccess; } ?>

	</div>
	
<?php }


// =========================================================================
// ====== BUILD DEVLY SETTINGS PAGE IN ADMIN
// =========================================================================


function devlyBuildSettingsPage() {
	
	global $pagenow;
	
	$settings	= get_option( "devlyThemeSettings" );
	$file_dir	= get_bloginfo('template_directory'); 
	
	// THESE STYLES SHOULD ALREADY BE LOADED FROM THE CORE
	//wp_enqueue_style("functions", $file_dir."/assets/core/admin.css", false, "1.0", "all"); ?>
	
	<div class="wrap">
	
		<h2>Devly Theme Settings</h2>
		
		<?php 
		
		// SET ACTIVE TAB
		if (isset($_GET['tab'])) { 
		
			devlyAdminTabs($_GET['tab']); 
			
		} else { 
		
			devlyAdminTabs('general'); 
			
		} ?>

		<form method="post" action="<?php admin_url('admin.php?page=theme-settings'); ?>">
		
			<div id="devlySettingsContainer"> 

			<?php 
				
				wp_nonce_field( "devly-settings-page" ); 
				
				if ( $pagenow == 'admin.php' && $_GET['page'] == 'theme-settings' ) { 
						
					if (isset($_GET['tab'])) { 
						
						$tab = $_GET['tab']; 
						
					} else { 
					
						$tab = 'general'; 
						
					}
					
				switch ($tab) {

					case 'general' : ?>
				
					<h3 class="devlyTypeTitle">General Settings</h3>
						
						<?php devlyThemeSubmitButton(); ?>
									
						<!-- COLOR SCHEME -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Color Scheme</h3>
							<div class="devlyFeature">
				    			<select id="devly_color_scheme" name="devly_color_scheme">
				    				<option value="disabled">Disabled</option>
				    			</select>
				    		</div>
					    	<div class="devlyFeatureDesc">
					    		This feature is currently disabled for lack of relevance. I originally
					    		built this option to offer a theme variation for a client to run 
					    		takeover ads on their wordpress site.
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
					
						<!-- CUSTOM FAVICON -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Custom Favicon</h3>
					    	<div class="devlyFeature">
					    		<input type="text" id="devly_favicon" name="devly_favicon" value="<?php echo $settings["devly_favicon"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		A favicon is the 16x16 pixel icon that appears in the address bar of 
					    		most browsers and represents your site; Upload a favicon to Wordpress, 
					    		then paste the URL to the image that you want to use. (Note: Image 
					    		should be in .ico format.) To use any of these values while developing,
					    		just reference the "key" at the end of each description.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_favicon']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- FEEDBURNER URL -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Feedburner URL</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_feedburner" name="devly_feedburner" value="<?php echo $settings["devly_feedburner"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		Feedburner is a Google service that takes care of your RSS feed. It's
					    		highly recommended to use a serverice like feedburner, as RSS feeds can
					    		be troublesome at times. Paste your Feedburner URL here to let readers 
					    		see it in your website.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_feedburner']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- GOOGLE ANALYTICS -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Google Analytics Code</h3></h3>
					    	<div class="devlyFeature">
					    		<textarea id="devly_ga_code" name="devly_ga_code"><?php echo esc_html(stripslashes($settings["devly_ga_code"] ) ); ?>
					    		</textarea>
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		You can put your Google Analytics code or code from another 
					    		tracking service here if you'd like. It will be added 
					    		to the footer if you wish. Just open your footer.php file
					    		and uncomment the relevant code. Just paste your code here.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_ga_code']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
				
					<?php break; 

					case 'social' : ?>
					
					<h3 class="devlyTypeTitle">Social Networking</h3>
						
						<!-- TWITTER HANDLE -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Twitter Handle</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_twitter" name="devly_twitter" value="<?php echo $settings["devly_twitter"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		Having your Twitter Handle defined in one spot, not 20, is 
					    		always a good thing. Just define your handle here, and
					    		get this or other data with get_option() when developing.
					    		Dynamic is "gooder..."<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_twitter']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- FACEBOOK PAGE -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Facebook Page</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_facebook" name="devly_facebook" value="<?php echo $settings["devly_facebook"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		Here you can provide the link to your facebook page
					    		if you'd like. You can access the value in the code just
					    		like any other values here.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_facebook']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- GOOGLE PLUS -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Google +</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_google" name="devly_google" value="<?php echo $settings["devly_google"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		Believe it or not, people do user Google + quite a bit. If 
					    		you'd like to have another venue to promote your brand, 
					    		paste your Google+ page here.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_google']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- PINTEREST PROFILE -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Pinterest Profile</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_pin" name="devly_pin" value="<?php echo $settings["devly_pin"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		I still haven't found a way that I would want to
					    		use Pinterest on my site. Nevertheless, you may be
					    		more creative, and find an awesome implementation
					    		for your site.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_pinterest']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>

							
					<?php break; 
								
					case 'api' : ?>
					
					<h3 class="devlyTypeTitle">Social Networks API Integration</h3>
					
						<?php devlyThemeSubmitButton(); ?>
					
						<!-- TWITTER APP ID -->
						<div class="devlyOptionContainer" style="border-top:1px solid #DFDFDF;">  
							<h3 class="devlyFeatureTitle">Twitter API Key</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_twitter_key" name="devly_twitter_key" value="<?php echo $settings["devly_twitter_key"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		The decision to add this here was made after I realized
					    		the convenience of keeping track of these in one spot, that
					    		way in the backend you can reference it as variable and
					    		be sure to always have the updated value.
					    		<a href="https://dev.twitter.com/apps/new" target="_blank">Click Here</a>
					    		to create a Twitter Application and get your Key.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_twitter_key']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- FACEBOOK APP ID -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Facebook App ID</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_fb_app" name="devly_fb_app" value="<?php echo $settings["devly_fb_app"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		The Facebook App ID can be used for any Facebook integration
					    		throughout the site. <a href="https://developers.facebook.com/docs/opengraph/tutorial/" target="_blank">Click Here</a>
					    		and follow the instructions. You will need to register an app
					    		with Facebook to get an app ID.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_fb_key']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- GOOGLE API KEY -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Google API Key</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_google_key" name="devly_google_key" value="<?php echo $settings["devly_google_key"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		The Google API key is one that I haven't had a whole lot 
					    		of use for. If you've got an idea or way to implement with
					    		Google, I'd like to hear about it.
					    		<a href="https://code.google.com/apis/console/" target="_blank">Click Here</a>
					    		to register with Google and get an API Key.<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_google_key']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div>
						
						<!-- BUFFER APP API KEY -->
						<div class="devlyOptionContainer">  
							<h3 class="devlyFeatureTitle">Buffer API Key</h3>
					    	<div class="devlyFeature">
					    	   <input type="text" id="devly_buffer_key" name="devly_buffer_key" value="<?php echo $settings["devly_buffer_key"]; ?>" />
					    	</div>
					    	<div class="devlyFeatureDesc">
					    		Buffer is an app that I didn't expect to like, but after seeing
					    		what a service it provides, I was all for adding the Buffer App
					    		to theme. I have built a few tools with it, and enjoy their API.
					    		<a href="http://bufferapp.com/" target="_blank">Click Here</a>
					    		to sign up for an account, and then register an App to receive
					    		your API key. Key:<br> 
					    		<br>
					    		<i>Usage: <b>$devlyOptions['devly_buffer_key']</b></i>
					    	</div>
					    	<div style="clear:both;"></div>
						</div> <?php 
					break; 
					
				} 
					
			} 
			
			devlyThemeSubmitButton(); ?>
		
		</form>
	
	</div><!-- // Settings Container -->
	
<?php } ?>