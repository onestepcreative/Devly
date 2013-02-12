/*

	DEVLY SOCIAL SCRIPT
	Version: 1.0

	Author:  JOSH MCDONALD
	Twitter: @onestepcreative
	Website: onestepcreative.com

*/


$(document).ready(function() {

	social.setup();

});



var social = {


		// SETUP UP GLOBALS AND INITIALIZE FUNCTIONS
		setup: function() {

			social.dropNav();
			social.setupTooltip();

			social.shareSetup();
			social.shareTrigger();
			social.initShareDrop();

		},

		// FUNCTION TO HANDLE GLOBAL DROPDOWN MENUS
		dropNav: function() {

			$('.main-menu li:has(ul)').addClass('nav-drop');
			$('sub-menu').css({ display: 'none' });

			$('ul.main-menu li').hover(function() {

				$(this).find('.sub-menu').stop(true, true).delay(50).animate({ 'height' : 'show', 'opacity' : 'show' }, 250);

			}, function() {

				$(this).find('.sub-menu').stop(true, true).delay(50).animate({ 'height' : 'hide', 'opacity' : 'hide' }, 250);

			});

		},

		// TOOLTIP FUNCTIONALITY FOR SIDEBAR AUTHORS
		setupTooltip: function() {

			$('#authors-container ul li').hover(function() {

				$('this').find('img').fadeTo(250, 1);

				var elem 		= $(this);
				var tipContain	= $('.tooltip-container');
				var tipContent	= elem.attr('tooltip');

				var elemTop		= elem.offset().top - elem.height();
				var elemLeft	= elem.offset().left + elem.width() / 2 - tipContain.width() / 2;

				if (tipContain.length === 0) { $('body').append('<div class="tooltip-container"><div class="toolTipInner">' + tipContent + '</div>'); }

				tipContain.css({ top: elemTop, left: elemLeft });

				tipContain.stop().fadeIn(200);

			}, function() {

				tipContain.stop(true, true).fadeIn(200).remove();

				$(this).find('img').fadeTo(250, 0.2);

			});

		},




		// =====================================================
		// ===== CUSTOM SOCIAL NETWORK "SHARE" INTEGRATION =====
		// =====================================================


		// SHOW SHARE DROP & SET TIMEOUT ON HOVER
		initShareDrop: function() {

			var timeout;

			$('.share-btn a, .social-container').hover(function() {

				clearTimeout(timeout);
				social.shareDrop(true);

			}, function() {

				timeout = setTimeout(function() { social.shareDrop(false); }, 800);

			});

		},

		// ANIMATE SHOW & HIDE DEPENDING ON "SHOW=TRUE/FALSE"
		shareDrop: function(show) {

			if(show) {

				$('.share-btn a').addClass('active');
				$('.social-container').stop(true, true).delay(50).animate({ "height": "show", "opacity": "show" }, 250 );

			} else {

				$('.share-btn a').removeClass('active');
				$('.social-container').stop(true, true).delay(50).animate({ "height": "hide", "opacity": "hide" }, 250 );

			}

		},

		// SETUP BUTTON VARIABLE AND TURN COUNT ON
		button: '.share',
		shareCount: true,

		// SETUP SHARE BUTTONS ATTRIBUTES
		shareSetup: function() {

			// SETUP ATTRIBUTES & LINKS FOR EACH BUTTON
			$(social.button).each(function() {

				var elem 		= $(this);
				var	url			= encodeURIComponent(elem.attr('data-url') || document.location.href);
				var	related 	= encodeURIComponent(elem.attr('data-related') || '');
				var	text 		= elem.attr('data-text') || document.title;
				var	via			= elem.attr('data-via') || '';
				var	shareType	= elem.attr('data-type');
				var	api			= social.createAPI(url, shareType);

				// SETUP SOCIAL COUNT
				if (social.shareCount) { social.setupCount(elem, api, shareType); }

				// UPDATE HREF WITH SHARE LINK
				elem.attr({ href: social.createSharer(url, related, text, via, shareType), target: '_blank' });

			});

		},

		// DEFINE SHARE LINK HREF ATTRIBUTES
		createSharer: function(url, related, text, via, type) {

			var link;

			if (type == 'twitter') {

				link = 'https://twitter.com/intent/tweet' +
					   '?original_referer=' + encodeURIComponent(document.location.href) +
					   '&related=' + related +
					   '&source=tweetbutton&text=' + text +
					   '&url=' + url +
					   '&via=' + via;

			} else if (type == 'facebook') {

				link = 'http://www.facebook.com/share.php' + '?u=' + url + '&t=' + related;

			} else if (type == 'google') {

				link = 'https://plus.google.com/share?url=' + url;

			} else if (type == 'stumble') {

				link = 'http://www.stumbleupon.com/submit?url=' + url + '&title=' + text;

			} else if (type == 'buffer') {

				link = 'http://bufferapp.com/add?url=' + url + '&text=' + text + '&via=' + via;

			} else if (type == 'reddit') {

				link = 'http://reddit.com/submit?url=' + url;

			} else {

				link = '';

			}

			return link;
		},

		// SETUP API CONNECTION THRU SOCIAL-CONNECT.PHP
		createAPI: function(url, type) {

			switch(type) {

				case 'twitter':
				return '/wp-content/themes/devly/assets/admin/social.php?fn=twitter&url=' + url;

				case 'facebook':
				return '/wp-content/themes/devly/assets/admin/social.php?fn=facebook&url=' + url;

				case 'google':
				return '/wp-content/themes/devly/assets/admin/social.php?fn=google&url=' + url;

				case 'stumble':
				return '/wp-content/themes/devly/assets/admin/social.php?fn=stumble&url=' + url;

				case 'buffer':
				return '/wp-content/themes/devly/assets/admin/social.php?fn=buffer&url=' + url;

				case 'reddit':
				return '/wp-content/themes/devly/assets/admin/social.php?fn=reddit&url=' + url;

			}

		},

		// SETUP JSON REQUESTS TO RETREIVE SHARE COUNT
		setupCount: function(elem, requestAPI, requestType) {

			var shareCount;

			if (requestType == 'twitter') {

				$.getJSON(requestAPI, function(response) {

					shareCount  = (response) ? response.count : 0;

					elem.append('<span class="digits">' + shareCount + '</span>');

				});

			} else if (requestType == 'facebook') {

				$.getJSON(requestAPI, function(response) {

					shareCount  = (response) ? response[0].share_count : 0;

					elem.append('<span class="digits">' + shareCount + '</span>');

				});

			} else if (requestType == 'google') {

				$.getJSON(requestAPI, function(response) {

					shareCount  = (response) ? response[0].result.metadata.globalCounts.count : 0;

					elem.append('<span class="digits">' + shareCount + '</span>');

				});

			} else if (requestType == 'stumble') {

				$.getJSON(requestAPI, function(response) {

					shareCount  = (!response || response.result.views === undefined) ? 0 : response.result.views;

					elem.append('<span class="digits">' + shareCount + '</span>');

				});

			} else if (requestType == 'buffer') {

				$.getJSON(requestAPI, function(response) {

					shareCount  = (response) ? response.shares : 0;

					elem.append('<span class="digits">' + shareCount + '</span>');

				});

			} else if (requestType == 'reddit') {

				$.getJSON(requestAPI, function(response) {

					shareCount  = (response) ? response.data.children[0].data.downs : 0;

					elem.append('<span class="digits">' + shareCount + '</span>');

				});

			} else {

				shareCount = '';

			}

		},

		totalCount: function() {

				var countTotal	= 0;
				var theCounts 	= $('.social-container .digits');

				theCounts.each(function() {

					countTotal += Number($(this).text());

				});

				console.log(countTotal);

				//`$('.share-btn').after('<span class="socialCount"></span>');

				jQuery(function($) {
			        $('.socialCount').countTo({
			            from: 0,
			            to: countTotal,
			            speed: 1000,
			            refreshInterval: 50,
			            onComplete: function(value) {
			                console.debug(this);
			            }
			        });
			    });

		},

		// CREATE A POPUP WINDOW FOR SHARE NETWORK CONTENT
		shareTrigger: function() {

			$('.share').live('click', function() {

				var w = 840;
				var	h = 450;
				var	l = (window.screen.width * 0.5) - (w * 0.5);
				var	t = (window.screen.height * 0.3) - (h * 0.3);

				window.open($(this).attr('href'), 'sharer', 'toolbar=0, status=0, width=' + w + ', height=' + h + ', top=' + t + ', left=' + l);

				return false;
			});

		}

} // CLOSE social OBJECT




(function($) {
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 100,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };
})(jQuery);

