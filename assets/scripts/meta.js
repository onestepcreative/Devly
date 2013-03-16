/*

	METABOXES JAVASCRIPT
	
	This file contains the code and functionality
	needed to properly run Devly's built-in metabox
	helper. Needs a re-write.

*/


jQuery(document).ready(function ($) {
	
	'use strict';

	var formfield;
	 
	// INITIALIZE THE JQUERY TIMEPICKER
	$('.devlyTimePicker').each(function () {
		
		$('#' + jQuery(this).attr('id')).timePicker({
		
			startTime: "07:00",
			endTime: "22:00",
			show24Hours: false,
			separator: ':',
			step: 30
		
		});
	
	});

	// INITIALIZE WP'S JQUERY UI DATEPICKER
	$('.devlyDatePicker').each(function () {

		$('#' + jQuery(this).attr('id')).datepicker();

	});

	// WRAP DATEPICKER IN CLASS, TO PREVENT SCOPE CONFLICT
	$("#ui-datepicker-div").wrap('<div class="devlyElement" />');

	// INITIALIZE COLOR PICKER
    $('input:text.devlyColorPicker').each(function (i) {
        
        $(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
        $('#picker-' + i).hide().farbtastic($(this));
    
    }).focus(function() {
        
        $(this).next().show();
    
    }).blur(function() {
        
        $(this).next().hide();
    
    });

	// FILE AND IMAGE UPLOAD HANDLING
	$('.devlyFileUpload').change(function () {
		
		formfield = $(this).attr('name');
	
		$('#' + formfield + '_id').val("");
	
	});

	// HANDLE UPLOAD BUTTON CLICK HANDLER
	$('.devlyUploadButton').live('click', function () {
		var buttonLabel;
			formfield = $(this).prev('input').attr('name');
			buttonLabel = 'Use as ' + $('label[for=' + formfield + ']').text();
		
		tb_show('', 'media-upload.php?post_id=' + $('#post_ID').val() + '&type=file&devlyForceSend=true&devlySendLabel=' + buttonLabel + '&TB_iframe=true');
		
		return false;
	
	});

	// REMOVE FILE BUTTON CLICK HANDLER
	$('.devlyRemoveFileButton').live('click', function () {
		
		formfield = $(this).attr('rel');
		
		$('input#' + formfield).val('');
		$('input#' + formfield + '_id').val('');
		$(this).parent().remove();
		
		return false;
	
	});

	window.original_send_to_editor = window.send_to_editor;
    
    window.send_to_editor = function (html) {
		
		var itemurl, itemclass, itemClassBits, itemid, htmlBits, itemtitle, image, uploadStatus = true;

		if (formfield) {

	        if ($(html).html(html).find('img').length > 0) {
		
				itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
				itemclass = $(html).html(html).find('img').attr('class'); // Extract the ID from the returned class name.
				itemClassBits = itemclass.split(" ");
				itemid = itemClassBits[itemClassBits.length - 1];
				itemid = itemid.replace('wp-image-', '');
	    
	        } else {

				// IF NOT AN IMAGE, GET URL INSTEAD
				htmlBits 	= html.split("'");
				itemurl 	= htmlBits[1];
				itemtitle 	= htmlBits[2];
				itemtitle 	= itemtitle.replace('>', '');
				itemtitle 	= itemtitle.replace('</a>', '');
				itemid 		= "";
			
			}

			image = /(jpe?g|png|gif|ico)$/gi;

			if (itemurl.match(image)) {
			
				uploadStatus = '<div class="img_status"><img src="' + itemurl + '" alt="" /><a href="#" class="devlyRemoveFileButton" rel="' + formfield + '">Remove Image</a></div>';
			
			} else {

				html = '<a href="' + itemurl + '" target="_blank" rel="external">View File</a>';
				uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="devlyRemoveFileButton" rel="' + formfield + '">Remove</a></div>';

			}

			$('#' + formfield).val(itemurl);
			$('#' + formfield + '_id').val(itemid);
			$('#' + formfield).siblings('.devlyMediaStatus').slideDown().html(uploadStatus);

			tb_remove();

		} else {

			window.original_send_to_editor(html);

		}

		formfield = '';

	};


	// AJAX ON PASTE
	$('.devlyOembed').bind('paste', function (e) {
		
		var pasteitem = $(this);

		setTimeout(function () {

			devlyExecuteAjax(pasteitem, 'paste');

		}, 100);

	}).blur(function() {

		setTimeout(function () {

			$('.postbox table.devlyMetabox .devlySpinner').hide();

		}, 2000);

	});

	// AJAX WHEN TYPING
	$('.devlyMetabox').on('keyup', '.devlyOembed', function (event) {

		devlyExecuteAjax($(this), event);

	});

	// FUNCTION TO EXECUTE AJAX
	function devlyExecuteAjax(obj, e) {

		// GET TYPED VALUE
		var oembed_url = obj.val();

		// PROCEED ONLY IF 6 CHARACTER OR MORE
		if (oembed_url.length < 6) { return; } 

		// PROCEED IF USER HAS PASTED, OR TYPED NUMBERS OR LETTERS
		if (e === 'paste' || e.which <= 90 && e.which >= 48 || e.which >= 96 && e.which <= 111 || e.which == 8 || e.which == 9 || e.which == 187 || e.which == 190) {

			// GET FIELD ID
			var field_id = obj.attr('id');

			// GET INPUT CONTEXT
			var context = obj.parents('.devlyMetabox tr td');

			// SHOW SPINNER
			$('.devlySpinner', context).show();

			// CLEAR PREVIOUS RESULTS
			$('.embed_wrap', context).html('');

			// RUN AJAX
			setTimeout(function () {

				if ($('.devlyOembed:focus').val() == oembed_url) {

					$.ajax({

						type : 'post',
						dataType : 'json',
						url : window.ajaxurl,
						data : {
							'action': 'devlyOembed_handler',
							'oembed_url': oembed_url,
							'field_id': field_id,
							'post_id': window.devly_ajax_data.post_id,
							'devlyAjaxNonce': window.devly_ajax_data.ajax_nonce
						},

						success: function (response) {

							if (typeof response.id !== 'undefined') {

								$('.devlySpinner', context).hide();
								$('.embed_wrap', context).html(response.result);

							}
						
						}
					
					});
				
				}
			
			}, 500);
		
		}
	
	}

});