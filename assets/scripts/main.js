/*

	Devly Main Script File

	Author:   Josh McDonald
	Twitter:  @onestepcreative
	Website:  http://onestepcreative.com

	This file is where you'll want to add most
	of you javascript functions and fallbacks. This
	file already contains an iOS zomm bug fix, 
	and a HTML5 Placeholder fallback. It is
	aimed to be written in an oop fashion.

*/

(function($) {
	
	var Devly = {
	
		setup: function() {
			
			Devly.fallbacks();
			
		},
		
		fallbacks: function() {
			
			if (!Modernizr.input.placeholder) {
		
				// SETUP PLACEHOLDER VALUES
				$(this).find('[placeholder]').each(function() {
					
					$(this).val( $(this).attr('placeholder') );
			
				});
			
				// FOCUS & BLUR HANDLING
				$('[placeholder]').focus(function() {
			
					if ($(this).val() === $(this).attr('placeholder')) {
						
						$(this).val('');
						$(this).removeClass('placeholder');
					
					}
			
				}).blur(function() {
					
					if ($(this).val() === '' || $(this).val() === $(this).attr('placeholder')) {
						
						$(this).val($(this).attr('placeholder'));
						$(this).addClass('placeholder');
					
					}
				
				});
			
				// REMOVE PLACEHOLDERS ON SUBMIT
				$('[placeholder]').closest('form').submit(function() {
			
					$(this).find('[placeholder]').each(function() {
						
						if ($(this).val() === $(this).attr('placeholder')) {
							
							$(this).val('');
						
						}
					
					});
			
				});
			
			}
			
		}
	
	};
	
	Devly.setup();
	
})(jQuery);





	




// FIX FOR iOS ZOOM BUG
(function(w){

	if(!( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( 'AppleWebKit' ) > -1 )) {
	
		return;
	
	}
	
    var doc = w.document;

    if(!doc.querySelector) { return; }

    var meta 			= doc.querySelector( 'meta[name=viewport]' ),
        initialContent 	= meta && meta.getAttribute( 'content' ),
        disabledZoom 	= initialContent + ',maximum-scale=1',
        enabledZoom 	= initialContent + ',maximum-scale=10',
        enabled 		= true,
		
		x, y, z, aig;

    if(!meta) { return; }

    function restoreZoom(){
    
        meta.setAttribute('content', enabledZoom);
        enabled = true;
    
    }

    function disableZoom(){
    
        meta.setAttribute('content', disabledZoom);
        enabled = false;
    
    }
	
    function checkTilt( e ){
		
		aig 	= e.accelerationIncludingGravity;
		x 		= Math.abs( aig.x );
		y 		= Math.abs( aig.y );
		z 		= Math.abs( aig.z );

        if(!w.orientation && (x > 7 || (( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ))) {
			
			if(enabled) {
			
				disableZoom();
		
			}
        
        } else if(!enabled){
			
			restoreZoom();
        
        }
    
    }
	
	w.addEventListener( 'orientationchange', restoreZoom, false );
	w.addEventListener( 'devicemotion', checkTilt, false );

})(this);
