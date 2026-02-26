(function($) {
    $.fn.lazyAjaxLoad = function(options) {

        var call_ajax = function(opt_data, container, buttn) { //console.log(opt_data);
            $.ajax({
                url: misha_loadmore_params.ajaxurl,
                type: "post",
                data: opt_data,
                beforeSend: function() {
                    //console.log(opt_data);
                    //$('.load_button').hide();
                    //console.log(opt_data);
                    options.ajax_call_flag = false;

                },
                success: function(resp) {
                    //console.log(resp);
                    container.append(resp).promise().done(function() {
                        options.callback ? options.callback() : null;
                        //var new_currpggnum = opt_data.curpg;
                    });

                    var new_currpggnum = opt_data.curpg;
                    //console.log(container,new_currpggnum,options.ajax_totalpagenum)
                    if (new_currpggnum < options.ajax_totalpagenum) {
                        options.ajax_call_flag = true;
                        //console.log(new_currpggnum);
                        $("." + buttn).data('currpagenum', parseInt(new_currpggnum + 1));
                        //console.log($('#gal_loadmore').data('currpagenum', parseInt(new_currpggnum+1)));
                        options.regenarate_fun;
                    } else {
                        //console.log('else',buttn);
                        $("." + buttn).hide();
                    }

                    if (options.call_load == 'display_pro_list' || options.call_load == 'display_archive_pro_list') {
                        $(".brandstory-section .product_item").each(function() {
                            var $id = $(this).data('product');
                            var $url = $(this).data('url');
                            var $jplayerdivid = "#jquery_jplayer_" + $id;
                            var $containerdivid = "#cp_container_" + $id;
                            //console.log($id);
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
                    }
                }
            });
        }

        $(document).scroll(function() {

            var wrapper_classname = options.wrapper_classname;
            var buttontag = options.button_tag;
            //console.log(wrapper_classname);
            //console.log($(".press_loadmore").data());	
            if (options.call_load == 'press') {
                data = {
                    'action': options.action,
                    'curpg': $("." + buttontag).data('currpagenum'),
                    'itemperpage': $("." + buttontag).data('itemperpage'),
                    'data_sreach': $("." + buttontag).data('sreach'),
                    'term_slug': $("." + buttontag).data('term'),
                    'taxonomy': $("." + buttontag).data('taxonomy'),

                };
            }
            if (options.call_load == 'movie') {
                data = {
                    'action': options.action,
                    'curpg': $("." + buttontag).data('currpagenum'),
                    'itemperpage': $("." + buttontag).data('itemperpage'),
                    'actor': $("." + buttontag).data('actor'),
                    'director': $("." + buttontag).data('director'),
                    'gener': $("." + buttontag).data('gener'),
                    'year': $("." + buttontag).data('year'),
                };
            }


            if (options.call_load == 'call_load') {

                //options.html_conatiner= $('.'+$("."+buttontag).data('content'));

                //console.log($("."+buttontag).data());
                data = {
                    'action': options.action,
                    'galid': $("." + buttontag).data('galid'),
                    'curpg': $("." + buttontag).data('currpagenum'),
                    'itemperpage': $("." + buttontag).data('itemperpage'),
                    'fieldname': $("." + buttontag).data('fieldname'),

                };
            }

            if (options.call_load == 'display_pro_list') {
                data = {
                    'action': options.action,
                    'curpg': $("." + buttontag).data('currpagenum'),
                    'slug': $("." + buttontag).data('slug'),
                    'itemperpage': $("." + buttontag).data('itemperpage'),
                };
            }

            if (options.call_load == 'display_archive_pro_list') {
                data = {
                    'action': options.action,
                    'curpg': $("." + buttontag).data('currpagenum'),
                    'key': $("." + buttontag).data('key'),
                    'itemperpage': $("." + buttontag).data('itemperpage'),
                };
            }


            var diff = options.diff;
            var scrltp = $(document).scrollTop();
            var outerheight = $("." + wrapper_classname).height();
            var offsettop = $("." + wrapper_classname).offset().top;
            var outerheight_with_offsettop = (outerheight + offsettop);

            // console.log(data.galid, data.curpg ,options.ajax_totalpagenum);

            //console.log(outerheight_with_offsettop, (outerheight_with_offsettop-scrltp),  );

            //console.log(outerheight_with_offsettop+'::'+scrltp);

            if ((diff > (outerheight_with_offsettop - scrltp)) && options.ajax_call_flag == true) {
                call_ajax(data, options.html_conatiner, options.button_tag);
                //console.log('hi');
            }

        });

    }

}(jQuery));


jQuery(function($) {

    //===========================
    $(".press_loadmore").length && $(".press_loadmore").lazyAjaxLoad({
        action: 'press_loadmore',
        ajax_call_flag: true,
        html_conatiner: $('.press_list'),
        wrapper_classname: 'press_list',
        call_load: 'press',
        button_tag: 'press_loadmore',
        diff: '400',
        ajax_totalpagenum: $(".press_loadmore").data('totalpagenum'),
    });
    //=============================

    //===========================
    $("#gal_loadmore").length && $("#gal_loadmore").lazyAjaxLoad({
        action: 'load_more_portfolio',
        ajax_call_flag: true,
        wrapper_classname: 'home-video-wrapper',
        call_load: 'call_load',
        diff: '400',
        ajax_totalpagenum: $(".gal_loadmore").data('totalpagenum'),
        html_conatiner: $('#films_vid_gallery'),
        button_tag: 'gal_loadmore',
        callback: function() {
            $('.popup-youtube').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            })
        }
    });
    //===========================

    //===========================
    $("#gal_img_loadmore").length && $("#gal_img_loadmore").lazyAjaxLoad({
        action: 'load_more_gallery',
        ajax_call_flag: true,
        wrapper_classname: 'movie-gal',
        call_load: 'call_load',
        diff: '195',
        ajax_totalpagenum: $(".gal_img_loadmore").data('totalpagenum'),
        html_conatiner: $('#movie-gal'),
        button_tag: 'gal_img_loadmore',
        callback: function() {
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
            })
        }
    });
    //===========================

    //===========================
    $(".sfv_office_gallery_loadmore").length && $(".sfv_office_gallery_loadmore").lazyAjaxLoad({
        action: 'load_more_svf_office_gallery',
        ajax_call_flag: true,
        wrapper_classname: $(".sfv_office_gallery_loadmore").data('content'),
        call_load: 'call_load',
        diff: '150',
        ajax_totalpagenum: $(".sfv_office_gallery_loadmore").data('totalpagenum'),
        html_conatiner: $('.' + $(".sfv_office_gallery_loadmore").data('content')),
        button_tag: 'sfv_office_gallery_loadmore',
        callback: function() {
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
            })
        }
    });
    //===========================

    //===========================	
    $(".sfv_office_gallery_loadmore_pastrelse").length && $(".sfv_office_gallery_loadmore_pastrelse").lazyAjaxLoad({
        action: 'load_more_svf_office_gallery',
        ajax_call_flag: true,
        wrapper_classname: $(".sfv_office_gallery_loadmore_pastrelse").data('content'),
        call_load: 'call_load',
        diff: '100',
        ajax_totalpagenum: $(".sfv_office_gallery_loadmore_pastrelse").data('totalpagenum'),
        html_conatiner: $('.' + $(".sfv_office_gallery_loadmore_pastrelse").data('content')),
        button_tag: 'sfv_office_gallery_loadmore_pastrelse',
        callback: function() {
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
            })
        }
    });
    //===========================

    //===========================
    $("#movie_loadmore").length && $("#movie_loadmore").lazyAjaxLoad({
        action: 'movie_filter',
        ajax_call_flag: true,
        html_conatiner: $('#film_content'),
        wrapper_classname: 'Movies_items',
        call_load: 'movie',
        button_tag: 'movie_loadmore',
        diff: '1500',
        ajax_totalpagenum: $(".movie_loadmore").data('totalpagenum'),
    });
    //===========================

    //===========================
    $("#product_loadmore").length && $("#product_loadmore").lazyAjaxLoad({
        action: 'get_product_data',
        ajax_call_flag: true,
        wrapper_classname: 'products_list',
        call_load: 'display_pro_list',
        button_tag: 'product_loadmore',
        diff: '500',
        ajax_totalpagenum: $(".product_loadmore").data('totalpagenum'),
        html_conatiner: $('#products_list')
    });
    //===========================

    //===========================
    $("#archive_product_loadmore").length && $("#archive_product_loadmore").lazyAjaxLoad({
        action: 'get_archive_product_data',
        ajax_call_flag: true,
        wrapper_classname: 'products',
        call_load: 'display_archive_pro_list',
        button_tag: 'archive_product_loadmore',
        diff: '500',
        ajax_totalpagenum: $(".archive_product_loadmore").data('totalpagenum'),
        html_conatiner: $('.products')
    });
    //===========================


    /*var $container = $('.home-social-tab .socialpost').imagesLoaded( function() {
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



    //===========================
    $('.wrap_social_topbox > ul li').length && $('.wrap_social_topbox > ul li').on('click',function(){
    	//console.log($(this).attr('class'));
    	$(this).closest('.home-social-wrap').find('h2').toggleClass('clicked');
    	$(this).closest('ul').slideToggle();
    	var cls = $(this).attr('class');
    	var container = $('.social-cont .'+cls+'-post');
    	if(container.children().length == 0)
    	{
    		$.ajax({
    	            url:misha_loadmore_params.ajaxurl,
    	            type:"post",
    	            data:{action : 'loadsocialfeed', type : cls},
    	            beforeSend: function(){
    					container.html('');
    					var loadimg = '<img src="'+misha_loadmore_params.stylesheeturi+'/images/Spinner.gif" class="social_loader_img">';
    					//console.log(loadimg);
    	            	container.append(loadimg);
    				},
    	            success:function(resp){
    	            	//console.log(resp);
    	            	container.html('');
    	            	container.append(resp);

    	            	$container.isotope('destroy');
    	            	$container = $('.home-social-tab .socialpost').imagesLoaded( function() {
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
    });*/
    //===========================		 

});