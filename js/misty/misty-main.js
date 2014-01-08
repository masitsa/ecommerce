$(document).ready(function(){

    // Slider
/*	$("#mainslider").flexslider({
		animation: "slide",
		useCSS: false,
		controlNav: false,   
		animationLoop: false,
		smoothHeight: true
	});
*/


    // Twitter - sidebar
    if ($('#sidebar .twitter').length) {
        $('.twitter').tweet({
            join_text: "auto",
            username: "twitter",
            avatar_size:40,
            count: 3,
            auto_join_text_default: "we said,",
            auto_join_text_ed: "we",
            auto_join_text_ing: "we were",
            auto_join_text_reply: "we replied",
            auto_join_text_url: "we were checking out",
            loading_text: "loading tweets..."
        });
    } else {}

    // Twitter - footer
    if ($('#footer .twitter').length) {
        $(".twitter").tweet({
            join_text: "auto",
            username: "twitter",
            avatar_size: 40,
            count: 1,
            auto_join_text_default: "we said,",
            auto_join_text_ed: "we",
            auto_join_text_ing: "we were",
            auto_join_text_reply: "we replied",
            auto_join_text_url: "we were checking out",
            loading_text: "loading tweets..."
        });
    } else {}

    // Disable href="#" links
    $('a').click(function(){if ($(this).attr('href') == '#') {return false;}});
    $('a').live('click', function(){if ($(this).attr('href') == '#') {return false;}});

    // tooltip
    $("a[rel^='tooltip']").tooltip();

    //prettyPhoto
    $('a[data-rel]').each(function() {
        $(this).attr('rel', $(this).data('rel'));
    });
    $("a[rel^='prettyPhoto']").prettyPhoto();

    //Menu
/*	
    jQuery('#menu > ul').superfish({ 
        delay:       0,
        animation:   {
            opacity:'show',
            height:'show'
        },
        speed:       'fast',
        autoArrows:  false
    });
*/

    (function() {
		var $menu = $('#menu ul'),
			optionsList = '<option value="" selected>Menu...</option>';

		$menu.find('li').each(function() {
			var $this   = $(this),
				$anchor = $this.children('a'),
				depth   = $this.parents('ul').length - 1,
				indent  = '';

			if( depth ) {
				while( depth > 0 ) {
					indent += ' - ';
					depth--;
				}
			}
			optionsList += '<option value="' + $anchor.attr('href') + '">' + indent + ' ' + $anchor.text() + '</option>';
		}).end().after('<select class="res-menu">' + optionsList + '</select>');

		$('.res-menu').on('change', function() {
			window.location = $(this).val();
		});
		
	})();
    
    //Flickr Widget Footer
/*
    $('#footer .flickr').jflickrfeed({
        limit: 8,
        qstrings: {
            id: '36621592@N06'
        },
        itemTemplate: ''+
            '<li class="thumbnail">'+
                '<a rel="prettyPhoto[flickr]" href="{{image}}" title="{{title}}">' +
                    '<img src="{{image_s}}" alt="{{title}}" />' +
                    '<span class="frame-overlay"></span>' +
                '</a>' +
            '</li>'
    }, function(data) {
        $("a[rel^='prettyPhoto']").prettyPhoto();
    });

	//Flickr Widget Sidebar
    $('#sidebar .sidebar-flickr').jflickrfeed({
		limit: 8,
		qstrings: {
			id: '36621592@N06'
		},
		itemTemplate: ''+
            '<li class="thumbnail">'+
                '<a rel="prettyPhoto[flickr]" href="{{image}}" title="{{title}}">' +
                    '<img src="{{image_s}}" alt="{{title}}" />' +
                    '<span class="frame-overlay"></span>' +
                '</a>' +
            '</li>'
	}, function(data) {
		$("a[rel^='prettyPhoto']").prettyPhoto();
	});
*/

	//Portfolio
	var $portfolioClone = $(".filtrable").clone();
	$("#filtrable a").live('click', function(e){
		
		$("#filtrable li").removeClass("current");	
		
		var $filterClass = $(this).parent().attr("class");
        var $filteredPortfolio = $portfolioClone.find("article");

		if ( $filterClass == "all" ) {
			$filteredPortfolio = $portfolioClone.find("article");
		} else {
			$filteredPortfolio = $portfolioClone.find("article[data-type~=" + $filterClass + "]");
		}
	
		$(".filtrable").quicksand( $filteredPortfolio, { 
			duration: 800, 
			easing: 'easeOutQuint' 
		}, function(){
            $("a[rel^='prettyPhoto']").prettyPhoto();
		});

		$(this).parent().addClass("current");
        
		e.preventDefault();
	});

    // To Top Button
    $(function(){
        $().UItoTop({ easingType: 'easeOutQuart' });
    });

    // Placeholder
    $('[placeholder]').focus(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    }).blur(function() {
        var input = $(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
        }).blur().parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });

    // Accordion settings
    $(function() {
        $('.accordion').on('show', function (e) {
            $(e.target).prev('.accordion-heading').find('i').removeClass('icon-plus');
            $(e.target).prev('.accordion-heading').find('i').addClass('icon-minus');
        });
        $('.accordion').on('hide', function (e) {
            $(e.target).prev('.accordion-heading').find('i').removeClass('icon-minus');
            $(e.target).prev('.accordion-heading').find('i').addClass('icon-plus');
        });
    });
	
	/**
	 * Test whether the browser supports matchmedia
	 * Matchmedia is used by enquire.js to perform the media-queries
	 *
	 */
	Modernizr.load({
		test: window.matchMedia,
		nope: CI.base_url+'js/media.match.min.js'
	});
	
	/**
	 * Use enquire to perform media-queries and change attributes accordingly
	 * Adds data-toggle to dropdown nav on mobile devices
	 * Removes data-toggle from dropdown nav on larger devices
	 * Assumes that a device is not capable of all resolutions from mobile to large desktop
	 *
	 */
	enquire
	.register("screen and (max-width:979px)", {
		match : function() {
		  console.log("handler 1 matched");
		  $('ul.nav > li.dropdown > a:first-child').attr('data-toggle','dropdown');
		},
		unmatch : function() {
		  console.log("handler 1 unmatched");
		  $('ul.nav > li > a').removeAttr("data-toggle");
		}
	})
	.register("screen and (max-width:480px)", {
		match : function() {
		  console.log("handler 2 matched");
		  $('ul.nav > li.dropdown > a:first-child').attr('data-toggle','dropdown');
		},
		unmatch : function() {
		  console.log("handler 2 unmatched");
		  $('ul.nav > li > a').removeAttr("data-toggle");
		}
	});
});