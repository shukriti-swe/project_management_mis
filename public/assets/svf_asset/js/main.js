jQuery(function($) {

    //$('.top_search').hide();
    $('.dotoggle').on('click', function(e) {
        //e.preventDefault();
        $('.top_search').animate({
            width: 'toggle'
        });
    });

    // Home page video popup
    var popiframe = function() {
        $('.popup-youtube').magnificPopup({
            disableOn: 0,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
    }

    popiframe();

    $('.svf_video li a').magnificPopup({
        disableOn: 0,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: true
    });

    // cinema details gallery popup

    var popgal = function() {
        $('.below_part ul li').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            }
        });
    }

    popgal();


    // apply now popupup

    $('.form_pop').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#name',
    });
    $('.content_sec').on('click', '.form_pop', function(e) {
        //console.log('+++');
        $('.join-our-team .wpcf7-response-output').hide().html('');
    });

    // Home page footer twitter social




    // Inner page banner

    $('.banner_carasoul').length && $('.banner_carasoul').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: false,
        prevArrow: '<div class="prev"><span class="fa fa-angle-left"></span></div>',
        nextArrow: '<div class="next"> <span class="fa fa-angle-right"></span></div>',
        dots: false
    });

    // Music page digital content slider

    $('.wrap_digital_cont_sec').length && $('.wrap_digital_cont_sec').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: true,
        prevArrow: '<div class="prev"><span class="fa fa-angle-left"></span></div>',
        nextArrow: '<div class="next"> <span class="fa fa-angle-right"></span></div>',
        dots: false,
        speed: 3500,
    });


    // Home page video banner under < 767px

    function slickify() {
        $('body.home .home-video-wrapper').length && $('body.home .home-video-wrapper').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            autoplay: false,
            prevArrow: '<div class="prev"><span class="fa fa-angle-left"></span></div>',
            nextArrow: '<div class="next"> <span class="fa fa-angle-right"></span></div>',
            dots: false,
            responsive: [{
                    breakpoint: 400,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 760,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    var $windowWidth = $(document).width();
    if ($windowWidth < 767) {
        slickify();
    }



    $(window).load(function() {
        var head_ht = $('.site-header').innerHeight();

        if ($windowWidth > 1024) {
            if ($('body').hasClass('logged-in')) {
                head_ht += 32;
            }
            //console.log(head_ht);
            $('.site-header').next().css('margin-top', head_ht);
        } else {
            $('.site-header').next().css('margin-top', 0);
        }

        $(window).resize(function() {
            //var $windowWidth = $(document).width();

            if ($windowWidth > 1024) {
                var head_ht = $('.site-header').height();
                if ($('body').hasClass('logged-in')) {
                    head_ht += 32;
                }
                //console.log(head_ht);
                $('.site-header').next().css('margin-top', head_ht);
            } else {
                $('.site-header').next().css('margin-top', 0);
            }


            if ($windowWidth < 767) {
                slickify();
            } else {
                if ($('body.home .home-video-wrapper').length > 0) {
                    $('body.home .home-video-wrapper').slick("unslick");
                }
            }
        });

    });


    // Home banner


    $('.slider-for').length && $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: false,
        adaptiveHeight: true,
        prevArrow: '<div class="prev"><span class="fa fa-angle-left"></span></div>',
        nextArrow: '<div class="next"><span class="fa fa-angle-right"></span></div>',
        dots: false,
        autoplaySpeed: 3000,
        pauseOnHover: true,
        asNavFor: '.slider-nav'
    });

    $('.slider-nav').length && $('.slider-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        centerMode: true,
        centerPadding: "217px",
        infinite: true,
        autoplay: false,
        prevArrow: '<div class="prev"><span class="fa fa-angle-left"></span><em>Prev</em></div>',
        nextArrow: '<div class="next"><em>Next</em> <span class="fa fa-angle-right"></span></div>',
        autoplaySpeed: 3000,
        pauseOnHover: true,
        focusOnSelect: true,
        dots: true,
        dotsClass: 'custom_paging',
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ],
        customPaging: function(slider, i) {
            //console.log(slider);
            return (i + 1) + '/' + slider.slideCount;
        }
    });



    $('.slider-nav .slick-slide').on('click', function() {
        var minus_height = 125;
        var offposition = $('.home-banner-slider-section').offset().top - minus_height;
        $('body,html').animate({
            scrollTop: offposition
        }, 1000);
    });



    // Career listing page tabs

    /*$('#jobtab li.resp-tab-item').each(function(){
		$(this).removeClass('resp-tab-active');
	});*/

    $("#jobtab").easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion           
        width: 'auto', //auto or any custom width
        fit: true, // 100% fits in a container
        closed: false, // Close the panels on start, the options 'accordion' and 'tabs' keep them closed in there respective view types
        activate: function() {}, // Callback function, gets called if tab is switched
        tabidentify: 'tab_identifier_child', // The tab groups identifier *This should be a unique name for each tab group and should not be defined in any styling or css file.
        activetab_bg: '#B5AC5F', // background color for active tabs in this group
        inactive_bg: '#E0D78C', // background color for inactive tabs in this group
        active_border_color: '#9C905C', // border color for active tabs heads in this group
        active_content_border_color: '#9C905C' // border color for active tabs contect in this group so that it matches the tab head border
    });



    //add js for social tab

    $(".social-cont > div").not(":first").hide();


    var $container = $('.home-social-tab .socialpost').imagesLoaded(function() {
        $container.isotope({
            percentPosition: true,
            itemSelector: '.social-box',
            masonry: {
                columnWidth: '.social-box'
            }
        });
        // reveal all items after init
        var iso = $container.data('isotope');
        //$container.isotope( 'reveal', iso.items );
    });

    //$(".home-social-tab li").click(function(e){
    $('.wrap_social_topbox > ul li').length && $('.wrap_social_topbox > ul li').on('click', function() {

        //=====Updated By sukanta to load via ajax start=====
        //console.log($(this).attr('class'));
        $(this).closest('.home-social-wrap').find('h2').toggleClass('clicked');
        $(this).closest('ul').slideToggle();
        var cls = $(this).attr('class');
        var container = $('.social-cont .' + cls + '-post');
        console.log(container.children().length);
        if (container.children().length == 0) {
            $.ajax({
                url: misha_loadmore_params.ajaxurl,
                type: "post",
                data: {
                    action: 'loadsocialfeed',
                    type: cls
                },
                beforeSend: function() {
                    container.html('');
                    var loadimg = '<img src="' + misha_loadmore_params.stylesheeturi + '/images/Spinner.gif" class="social_loader_img">';
                    //console.log(loadimg);
                    container.append(loadimg);
                },
                success: function(resp) {
                    //console.log(resp);
                    container.html('');
                    container.append(resp);

                    $container.isotope('destroy');
                    $container = $('.home-social-tab .socialpost').imagesLoaded(function() {
                        $container.isotope({
                            percentPosition: true,
                            itemSelector: '.social-box',
                            masonry: {
                                columnWidth: '.social-box'
                            }
                        });
                        // reveal all items after init
                        var iso = $container.data('isotope');
                        //$container.isotope( 'reveal', iso.items );
                    });
                }
            });
        }
        //=====Updated By sukanta to load via ajax end=====

        var minus_height = 125;
        var offposition = $('.home-social-section').offset().top - minus_height;
        $('body,html').animate({
            scrollTop: offposition
        }, 1000);

        var TS = $(this).index();
        $(".social-cont > div").hide().eq(TS).show(function() {
            $container.isotope('layout');
        });

        $('.home-social-tab li.current').removeClass('current');
        $(this).addClass('current');

    });






    // this is for select dropdown
    // $('.filter_section select, .filter_field li select, .join-box-right select, .contact-right select').Selectyze({
    //     theme : 'grey',
    //      sortField: 'text'        
    // });

    $('.filter_field li select,  .contact-right select').selectize();

    // $('.filter_section select').selectize().on('blur', function(e){
    //     console.log(e);
    //     if (e.which === 8) {
    // 	        e.preventDefault();
    // 	        alert();
    // 	    }
    // });

    var $all_select = $(".filter_section select");
    $all_select.selectize({
        onChange: function(value) {
            var ar = [];
        }
    });

    $('.othr_text').hide();
    var $job_select = $(".join-box-right select");
    $job_select.selectize({
        onChange: function(value) {
            //console.log(value)					
            if (value == 'Others') {
                $('.othr_text').show();
            }

        }

    });


    // get the parent menu highlighted

    if ($("body").hasClass('single-movie')) {
        $('.main-menu li.movie').addClass('current-menu-item');
        $('.main-menu li ul li.movie').addClass('current-menu-item');
        $('.footer-top-left-section ul li.movie').addClass('current-menu-item');
    }

    if ($("body").hasClass('single-post')) {
        $('.main-menu li.press').addClass('current-menu-item');
        $('.footer-top-left-section ul li.press').addClass('current-menu-item');
    }

    if ($("body").hasClass('single-cinema')) {
        $('.main-menu li.cinema').addClass('current-menu-item');
        $('.footer-top-left-section ul li.cinema').addClass('current-menu-item');
    }

    if ($("body").hasClass('single-career')) {
        $('.main-menu li.career').addClass('current-menu-item');
        $('.footer-top-left-section ul li.career').addClass('current-menu-item');
    }

    if ($("body").hasClass('page-template-template-job')) {
        $('.main-menu li.career').addClass('current-menu-item');
        $('.footer-top-left-section ul li.career').addClass('current-menu-item');
    }

    if ($("body").hasClass('single-tvproject')) {
        $('.main-menu li.serial').addClass('current-menu-item');
        $('.footer-top-left-section ul li.serial').addClass('current-menu-item');
    }

    if ($("body").hasClass('tax-article_type')) {
        $('.main-menu li.press').addClass('current-menu-item');
        $('.footer-top-left-section ul li.press').addClass('current-menu-item');
    }

    if ($("body").hasClass('category')) {
        $('.main-menu li.press').addClass('current-menu-item');
        $('.footer-top-left-section ul li.press').addClass('current-menu-item');
    }

    $('form.widget_wysija').on('click', '.wysija-submit', function() {

        setTimeout(function() {
                //console.log('===');
                if ($('.shortcode_wysija .wysija-msg').html() != "" && $('form.widget_wysija').css('display') == 'none') {
                    //$('.shortcode_wysija .wysija-msg').fadeOut();
                    $('form.widget_wysija').addClass('msg_exist');
                    $('form.widget_wysija').fadeIn();
                }
            },
            5000
        );
    });

    $('div.contact-form-section form.wpcf7-form').on('click', '.wpcf7-submit', function() {
        //console.log('+++');
        setTimeout(function() {
                //console.log('===');
                if ($('form.wpcf7-form .wpcf7-response-output').html() != "" && $('form.wpcf7-form .wpcf7-response-output').hasClass('wpcf7-mail-sent-ok')) {
                    $('form.wpcf7-form .wpcf7-response-output').fadeOut();
                }
            },
            5000
        );
    });

    // leadeship page view more/ less

    $('.leadership_sec li > .more_link').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('less')) {
            $(this).removeClass("less");
            $(this).text("View More");
        } else {
            $(this).addClass("less");
            $(this).text("View Less");
        }
        $(this).prev().slideToggle('slow');
    });

    $('#social_links li a').on('click', function(e) {
        e.preventDefault();
    });


    $('a.add_to_cart_button').on('click', function() { //alert('aa');
        $pid = $(this).data('product_id');
        $("#ajax_loader-" + $pid).show();

        /*setTimeout(function() {
        	//if($( ".music_box_area .album_description .added_to_cart.wc-forward" ).length > 0 )
        	//{ 
        		//$("#ajax_loader-"+$pid).hide();
        	//}
        	//console.log($( ".music_box_area #album_description-"+$pid+" .added_to_cart.wc-forward" ).length);
        	if($( ".music_box_area #album_description-"+$pid+" .added_to_cart.wc-forward" ).length > 0 )
        	{ 
        		$("#ajax_loader-"+$pid).hide();
        	}
        }, 10000); */


    });

    //woocommerce add to cart trigger loader hide
    $('body').on('added_to_cart', function(event, fragments, cart_hash, $button) {
        $button.parents('.album_description').find('img').hide();
    });

    $('.thumb_carasoul').length && $('.thumb_carasoul').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: true,
        arrows: true,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });



    $(".product_item").each(function() {
        var $id = $(this).data('product');
        var $url = $(this).data('url');
        var $jplayerdivid = "#jquery_jplayer_" + $id;
        var $containerdivid = "#cp_container_" + $id;

        var myCirclePlayer = new CirclePlayer(
            $jplayerdivid, {
                mp3: $url,
            }, {
                cssSelectorAncestor: $containerdivid,
                swfPath: "../music-player/dist/jplayer",
                wmode: "window",
                supplied: "mp3",
                keyEnabled: true
            }
        );
    });

    /*$(".album_poster").click(function(){
    	$('.album_poster .cp-container').hide();
    	var $id=$(this).data('product');
    	var $containerdivid = "#cp_container_"+$id;
    	$($containerdivid).show();
    	$($containerdivid + ' .cp-play').trigger('click');
    });*/

    $("#search_btn").click(function() {
        $("#prodsrchfrm").submit();
    });



    // This is for film flip section

    $('.flip i').bind({
        click: function() {
            $(this).closest('.flip').find('.card').toggleClass('flipped');
            $(this).toggleClass('click2');
        }
    });


    $('.social_open_icon').on('click', function() {
        $(this).closest('.wrap_social_topbox').find('ul').slideToggle();
        $(this).closest('.wrap_social_topbox').find('h2').toggleClass('clicked');
    });


    $(document).on('mouseup', function(e) {
        var container = $('.wrap_social_topbox > ul');

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.closest('.wrap_social_topbox').find('h2').removeClass('clicked');
            container.hide();
        }
    });

    $(document).on('mouseout', function(e) {
        var container = $('.wrap_social_topbox');

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.find('h2').removeClass('clicked');
            container.find('ul').hide();
        }
    });

});