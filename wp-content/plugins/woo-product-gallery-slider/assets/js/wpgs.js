(function ($) {
	'use strict';

	var cix_product_gallery_slider = {

		animation: (wpgs_js_data.slider_animation == 'true') ? true : false,
		lazyload: wpgs_js_data.slider_lazyload,
		adaptiveHeight: (wpgs_js_data.slider_adaptiveHeight == 'true') ? true : false,
		dots: (wpgs_js_data.slider_dots == 'true') ? true : false,
		rtl: (wpgs_js_data.slider_rtl == 'true') ? true : false,
		infinity: (wpgs_js_data.slider_infinity == 'true') ? true : false,
		dragging: (wpgs_js_data.slider_dragging == 'true') ? true : false,
		nav: (wpgs_js_data.slider_nav == 'true') ? true : false,
		autoplay: (wpgs_js_data.slider_autoplay == 'true') ? true : false,
		OnHover: (wpgs_js_data.slider_autoplay_pause_on_hover == 'true') ? true : false,
		variableWidth: (wpgs_js_data.variableWidth == 1) ? true : false,
		centerMode: (wpgs_js_data.centerMode == 1) ? true : false,
		thumb_to_show: parseInt(wpgs_js_data.thumb_to_show),
		carousel_mode: (wpgs_js_data.carousel_mode == 1) ? true : false,

		tp_horizontal: (wpgs_js_data.thumb_v != 'bottom') ? true : false,
		tpm_horizontal: (wpgs_js_data.thumb_position_mobile != 'bottom') ? true : false,
		tpt_horizontal: (wpgs_js_data.thumb_v_tablet != 'bottom') ? true : false,
		thumbnails_nav: (wpgs_js_data.thumbnails_nav == 1) ? true : false,


		slick: function () {

			$('.wpgs-nav').slick({
				slidesToShow: cix_product_gallery_slider.thumb_to_show,
				slidesToScroll: parseInt(wpgs_js_data.thumbnails_mobile_thumb_scroll_by),
				rtl: cix_product_gallery_slider.rtl,
				arrows: cix_product_gallery_slider.thumbnails_nav,
				speed: wpgs_js_data.thumbnail_animation_speed,
				infinite: cix_product_gallery_slider.infinity,
				focusOnSelect: (cix_product_gallery_slider.carousel_mode) ? false : true,
				asNavFor: (wpgs_js_data.carousel_mode != 1) ? '.wpgs-for' : '',
				variableWidth: cix_product_gallery_slider.variableWidth,// $variableWidth
				centerMode: cix_product_gallery_slider.centerMode,// $centerMode
				vertical: cix_product_gallery_slider.tp_horizontal,
				verticalSwiping: (cix_product_gallery_slider.tp_horizontal) ? true : false,

				responsive: [

					{
						breakpoint: 1025,
						settings: {
							variableWidth: false,
							vertical: cix_product_gallery_slider.tpt_horizontal,
							verticalSwiping: (cix_product_gallery_slider.tpt_horizontal) ? true : false,
							rtl: cix_product_gallery_slider.rtl,
							slidesToShow: parseInt(wpgs_js_data.thumbnails_tabs_thumb_to_show),
							slidesToScroll: parseInt(wpgs_js_data.thumbnails_tabs_thumb_scroll_by),
							swipeToSlide: true,

						}
					},

					{
						breakpoint: 767,
						settings: {
							variableWidth: false,
							vertical: cix_product_gallery_slider.tpm_horizontal,
							verticalSwiping: (cix_product_gallery_slider.tpm_horizontal) ? true : false,
							rtl: cix_product_gallery_slider.rtl,
							slidesToShow: parseInt(wpgs_js_data.thumbnails_mobile_thumb_to_show),
							slidesToScroll: parseInt(wpgs_js_data.thumbnails_mobile_thumb_scroll_by),
							swipeToSlide: true,
						}
					}

				]

			});
			if (cix_product_gallery_slider.carousel_mode) {
				console.log('WPGS: Carousel Mode On');
				return;
			}
			$('.wpgs-for').slick({

				fade: cix_product_gallery_slider.animation,
				asNavFor: '.wpgs-nav',
				lazyLoad: cix_product_gallery_slider.lazyload,
				adaptiveHeight: cix_product_gallery_slider.adaptiveHeight,
				dots: cix_product_gallery_slider.dots,
				dotsClass: 'slick-dots wpgs-dots',
				focusOnSelect: false,
				rtl: cix_product_gallery_slider.rtl,
				infinite: cix_product_gallery_slider.infinity,
				draggable: cix_product_gallery_slider.dragging,
				arrows: cix_product_gallery_slider.nav,
				prevArrow: '<i class="flaticon-back"></i>',
				nextArrow: '<i class="flaticon-right-arrow"></i>',
				speed: wpgs_js_data.slider_animation_speed,
				autoplay: cix_product_gallery_slider.autoplay,
				pauseOnHover: cix_product_gallery_slider.OnHover,
				pauseOnDotsHover: cix_product_gallery_slider.OnHover,
				autoplaySpeed: wpgs_js_data.slider_autoplay_time,
			});



		},
		lightBox: function () {
			if (typeof $.fn.fancybox == 'function') {
				$.fancybox.defaults = $.extend(true, {}, $.fancybox.defaults, {

					thumbs: false,
					afterShow: function (instance, current) {

						current.opts.$orig.closest(".slick-initialized").slick('slickGoTo', parseInt(current.index), true);
					}

				});

				var selector = '.wpgs-for .slick-slide:not(.slick-cloned) a';

				// Skip cloned elements
				$().fancybox({
					selector: selector,
					backFocus: false,
				});

				// Attach custom click event on cloned elements, 
				// trigger click event on corresponding link
				$(document).on('click', '.slick-cloned a', function (e) {
					$(selector)
						.eq(($(e.currentTarget).attr("data-slick-index") || 0) % $(selector).length)
						.trigger("click.fb-start", {
							$trigger: $(this)
						});
					return false;
				});

			}
		},
		lazyLoad: function () {
			if (wpgs_js_data.slider_lazyload != 'disable')
				$('.wpgs-image .wpgs-for img').each(function () {
					$(this).removeAttr('srcset');
					$(this).removeAttr('sizes');

				});
		},
		misc: function () {
			$('.wpgs-wrapper').hide();
			$('.wpgs-wrapper').css("opacity", "1");
			$('.wpgs-wrapper').show();

			// Variation Data

			var get_thumb_first = $(document).find('.gallery_thumbnail_first');
			var get_main_first = $(document).find('.woocommerce-product-gallery__image');
			get_main_first.find('img').removeAttr('srcset');

			$('.thumbnail_image').each(function (index) {
				$(this).on('click', function () {
					$('.thumbnail_image').removeClass('slick-current');
					$(this).addClass('slick-current');
					$('.woocommerce-product-gallery__lightbox').css({ "display": "none" });
					setTimeout(function () {
						$('.slick-current .woocommerce-product-gallery__lightbox').css({ "display": "block", "opacity": "1" });
						$('.woocommerce-product-gallery__image .woocommerce-product-gallery__lightbox').css({ "display": "block", "opacity": "1" });
					}, 400);

				});
			});

			if (wpgs_js_data.zoom == 1) {

				$('.wpgs-for img').each(function () {
					$(this).wrap("<div class='zoomtoo-container' data-zoom-image=" + $(this).data("large_image") + "></div>");
				});

				if (wpgs_js_data.is_mobile == 1 && wpgs_js_data.mobile_zoom == 'false') {
					$('.wpgs-for > div').each(function () {
						$(this).removeClass('zoomtoo-container');
					});
				}

				// var imgUrl = $(this).data("zoom-image");
				if (typeof $.fn.zoom == 'function') {

					$('.zoomtoo-container').zoom({

						// Set zoom level from 1 to 5.
						magnify: wpgs_js_data.zoom_level,
						// Set what triggers the zoom. You can choose mouseover, click, grab, toggle.
						on: wpgs_js_data.zoom_action,
						touch: false
					});
				}
			}

			if (wpgs_js_data.lightbox_icon == 'none' && wpgs_js_data.zoom_action == 'mouseover') {
				$('.zoomtoo-container').on('click', function () {
					$(this).next().trigger("click");
				});

			}

			// Remove SRCSET for Thumbanils
			$('.thumbnail_image img').each(function () {
				$(this).removeAttr('srcset', 'data-thumb_image');
				$(this).removeAttr('data-thumb_image');
				$(this).removeAttr('sizes');
				$(this).removeAttr('data-large_image');
			});
			$('.wpgs_image img').each(function () {
				$(this).removeAttr('srcset');

			});

			function ZoomIconApperce() {
				setTimeout(function () {
					$('.woocommerce-product-gallery__lightbox').css({ "display": "block", "opacity": "1" });

				}, 500);

			}

			// On swipe event
			$('.wpgs-image').on('swipe', function (event, slick, direction) {
				$('.woocommerce-product-gallery__lightbox').css({ "display": "none" });
				ZoomIconApperce();
			});
			// On edge hit
			$('.wpgs-image').on('afterChange', function (event, slick, direction) {
				ZoomIconApperce();
			});
			$('.wpgs-image').on('click', '.slick-arrow ,.slick-dots', function () {
				$('.woocommerce-product-gallery__lightbox').css({ "display": "none" });
				ZoomIconApperce();
			});
			$('.wpgs-image').on('init', function (event, slick) {
				ZoomIconApperce();
			});
			// if found prettyphoto rel then unbind click
			$(window).on('load', function () {
				$("a.woocommerce-product-gallery__lightbox").attr('rel', ''); // remove prettyphoto
				$("a.woocommerce-product-gallery__lightbox").removeAttr('data-rel'); // remove prettyphoto ("id")	
				$('a.woocommerce-product-gallery__lightbox').unbind('click.prettyphoto');

			});


		},
		resetImages: function (wrapper, parent) {
			wrapper.find('.woocommerce-product-gallery').remove();
			parent.prepend(wpgs_js_data.variation_data[0]);
			cix_product_gallery_slider.lazyLoad();
			cix_product_gallery_slider.slick();
			cix_product_gallery_slider.lightBox();
			cix_product_gallery_slider.misc();
		},
		variationImage: function () {
			
			var variation_form = $('.variations_form'),
				i = 'input.variation_id',
				product_wrap = $('.post-' + wpgs_js_data.product_id),
				wpgs_variation_list = wpgs_js_data.variation_data,
				DivParent = product_wrap.find('.woocommerce-product-gallery').parent();
			variation_form.on('found_variation', function (event, variation) {

				
				if (wpgs_variation_list.hasOwnProperty(variation.variation_id)) {
					
					product_wrap.find('.woocommerce-product-gallery').remove();
					DivParent.prepend(wpgs_variation_list[variation.variation_id]);
					cix_product_gallery_slider.lazyLoad();
					cix_product_gallery_slider.slick();
					cix_product_gallery_slider.lightBox();
					cix_product_gallery_slider.misc();


				} else {
					cix_product_gallery_slider.resetImages(product_wrap, DivParent);
				}


			})
				// On clicking the reset variation button
				.on('reset_data', function (event) {
					cix_product_gallery_slider.resetImages(product_wrap, DivParent);

				});

		}

	};

	$(document).ready(function () {

		cix_product_gallery_slider.lazyLoad();
		cix_product_gallery_slider.slick();
		cix_product_gallery_slider.lightBox();
		cix_product_gallery_slider.misc();


	});
	console.log('WPGS: Loaded');
	// jquery on load
	$(window).on('load', function () {

		cix_product_gallery_slider.variationImage();

	});
	


})(jQuery);

