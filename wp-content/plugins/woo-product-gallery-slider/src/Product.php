<?php

namespace Product_Gallery_Sldier;

class Product {
	/**
	 * @var mixed
	 */
	private $wpgs_variation_images;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 90 );
		add_action( 'after_setup_theme', array( $this, 'remove_default_gallery_support' ), 100 );
		$this->hooks();
		add_shortcode( 'product_gallery_slider', array( $this, 'shortcode_render' ) );
		$this->wpgs_variation_images = new \WPGS_Variation_images();
		$this->wpgs_variation_images->init_actions();
		add_action( 'wp_ajax_twist_variation_ajax', array( $this, 'product_variation' ) );
		add_action( 'wp_ajax_nopriv_twist_variation_ajax', array( $this, 'product_variation' ) );
	}
	/**
	 * @return mixed
	 */
	public function shortcode_render() {
		ob_start();
		if ( is_product() ) {
			wc_get_template( 'single-product/product-image.php' );
		}

		$output = ob_get_clean();
		return $output;
	}
	/**
	 * Get the value of a settings field
	 *
	 * @param  string $option  settings field name
	 * @param  string $default default text if it's not found
	 * @return mixed
	 */
	public static function option( $option, $default = '' ) {
		$options = get_option( 'wpgs_form' );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}
	public function remove_default_gallery_support() {

		remove_theme_support( 'wc-product-gallery-zoom' );
		remove_theme_support( 'wc-product-gallery-lightbox' );
		remove_theme_support( 'wc-product-gallery-slider' );
		if ( function_exists( 'woostify_version' ) || wpgs_get_option( 'check_divi_builder' ) == '1' ) {
			remove_action( 'woocommerce_before_single_product_summary', array( $this, 'wpgs_product_image' ), 20 );
		}
		add_filter( 'astra_addon_override_single_product_layout', '__return_false' );
	}

	/**
	 * @return mixed
	 */
	public function hooks() {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'wpgs_product_image' ), 20 );
		if ( wpgs_get_option( 'load_assets' ) == '1' ) {
			add_filter( 'wpgs_load_entrie_site', '__return_true' );
		}
		add_filter(
			'woocommerce_gallery_image_size',
			function ( $size ) {
				return self::option( 'slider_image_size', 'woocommerce_single' );
			}
		);
	}

	// Output the product image before the single product summary.
	public function wpgs_product_image() {
		require_once CIPG_PATH . '/includes/product-image.php';
	}
	public function product_variation() {
		if ( ! DOING_AJAX ) {
			wp_die();
		} // Not Ajax

			// Check for nonce security
			$nonce            = sanitize_text_field( $_POST['nonce'] );
			$product_id       = sanitize_text_field( $_POST['product_id'] );
			$variation_id     = sanitize_text_field( $_POST['variation_id'] );
			$expire_time      = DAY_IN_SECONDS * 7;
			$variation_images = get_post_meta( $variation_id, 'wavi_value', true );

			$product            = wc_get_product( $product_id );
			$variation          = wc_get_product( $variation_id );
			$variation_image_id = $variation->get_image_id();
			$thumbnails         = implode( ',', $product->get_gallery_image_ids() );
			$data               = array();

		if ( ! wp_verify_nonce( $nonce, 'wcavi_nonce' ) ) {
			wp_die( 'Oops! nonce error' );
		}
		$variation_cache_data = $this->wpgs_variation_images->get_cache( 'wpgs_product_variation_' . $product_id );

		if ( ! empty( $variation_images ) ) {
				$variation_markup = $this->wpgs_variation_images->html_markup( $variation_image_id, $variation_images );

		} elseif ( $product->get_image_id() != $variation_image_id ) {

			$variation_markup = $this->wpgs_variation_images->html_markup( $variation_image_id, $thumbnails );

		} else {
			$variation_markup = $this->wpgs_variation_images->html_markup( $product->get_image_id(), $thumbnails );
		}
		if ( $variation_cache_data && ! array_key_exists( $variation_id, $variation_cache_data ) ) {

			$variation_cache_data[ $variation_id ] = $variation_markup;

			set_transient( 'wpgs_product_variation_' . $product_id, $variation_cache_data, $expire_time );

		}
		$data['variation_images'] = $variation_markup;

			wp_send_json_success( $data );
			wp_die(); // this is required to terminate immediately and return a proper response
	}
	/**
	 * @param $product_id
	 * @return mixed
	 */
	public function get_variaton_markup( $product_id ) {

		$variation_cache_data = $this->wpgs_variation_images->get_cache( 'wpgs_product_variation_' . $product_id );
		$data                 = $this->wpgs_variation_images->get_variation_data( $product_id );
		if ( $variation_cache_data ) {
			return $variation_cache_data;
		} elseif ( $data ) {

			$this->wpgs_variation_images->set_cache( 'wpgs_product_variation_' . $product_id, $data, apply_filters( 'wpgs_clear_variation_cache', DAY_IN_SECONDS * 7 ) );
			return $data;

		}
	}
	/**
	 * Frontend styles/scripts
	 *
	 * @return void
	 */
	public function frontend_scripts() {
		$wpgs_load_assets = ( apply_filters( 'wpgs_load_entrie_site', false ) ) ? true : is_product();
		$product_id       = apply_filters( 'wpgs_product_id', get_the_ID() );
		global $post;
		if ( $post && has_shortcode( $post->post_content, 'product_page' ) ) {
			// The page has the 'product_page' shortcode
			/**
			 * Extracts the product ID from the post content if a shortcode is being used.
			 *
			 * @param WP_Post $post The post object.
			 * @return void
			 */
			$pattern = '/\[product_page\s+id="(\d+)"\]/';
			preg_match( $pattern, $post->post_content, $matches );
			if ( isset( $matches[1] ) ) {

				$product_id = apply_filters( 'wpgs_product_id', $matches[1] );
			}
		}

		if ( ! $wpgs_load_assets || 'product' !== get_post_type( $product_id ) ) {

			return;
		}

		$twist_product  = new \WC_Product( $product_id );
		$attachment_ids = $twist_product->get_gallery_image_ids();

		/* Plugin Options */
		$lightbox                          = ( self::option( 'lightbox_picker' ) == 1 ) ? 'true' : 'false';
		$lightbox_bg                       = self::option( 'lightbox_bg' );
		$lightbox_txt_color                = self::option( 'lightbox_txt_color' );
		$icon_bg_color                     = self::option( 'lightbox_icon_bg_color' );
		$icon_link_color                   = self::option( 'lightbox_icon_color' );
		$slider_rtl                        = ( is_rtl() ) ? 'true' : 'false';
		$slider_dragging                   = ( self::option( 'slider_dragging' ) == 1 ) ? 'true' : 'false';
		$slider_infinity                   = ( self::option( 'slider_infinity' ) == 1 ) ? 'true' : 'false';
		$slider_adaptiveHeight             = ( self::option( 'slider_adaptiveHeight' ) == 1 ) ? 'true' : 'false';
		$slider_nav                        = ( self::option( 'slider_nav' ) == 1 ) ? 'true' : 'false';
		$slider_nav_animation              = ( self::option( 'slider_nav_animation' ) == 1 ) ? 'true' : 'false';
		$slider_nav_bg                     = self::option( 'slider_nav_bg' );
		$slider_nav_icon                   = self::option( 'slider_nav_color' );
		$slider_icon                       = self::option( 'slider_icon' );
		$slider_animation                  = ( self::option( 'slider_animation' ) );
		$slider_animation_speed            = ( self::option( 'gallery_animation_speed', '500' ) );
		$thumbnail_animation_speed         = ( self::option( 'thumbnail_animation_speed', '500' ) );
		$slider_lazyload                   = self::option( 'slider_lazy_laod', 'disable' );
		$slider_autoplay                   = ( self::option( 'slider_autoplay' ) == 1 ) ? 'true' : 'false';
		$slider_autoplay_time              = self::option( 'autoplay_timeout', '4000' );
		$slider_autoplay_pause_on_hover    = ( self::option( 'slider_autoplay_pause' ) == 1 ) ? 'true' : 'false';
		$zoom                              = ( self::option( 'image_zoom' ) == 1 ) ? 'true' : 'false';
		$thumbnails_active                 = ( self::option( 'thumbnails' ) == 1 ) ? 'true' : 'false';
		$thumbnails_id                     = ( 'true' == $thumbnails_active ? '\'.wpgs-thumb\'' : 'false' );
		$thumb_to_show                     = self::option( 'thumb_to_show' );
		$thumb_scroll_by                   = self::option( 'thumb_scroll_by' );
		$thumbnails_mobile_thumb_to_show   = self::option( 'thumbnails_mobile_thumb_to_show' );
		$thumbnails_mobile_thumb_scroll_by = self::option( 'thumbnails_mobile_thumb_scroll_by' );
		$thumbnails_tabs_thumb_to_show     = self::option( 'thumbnails_tabs_thumb_to_show' );
		$thumbnails_tabs_thumb_scroll_by   = self::option( 'thumbnails_tabs_thumb_scroll_by' );
		$thumb_position                    = self::option( 'thumb_position' );
		$thumb_position_mobile             = self::option( 'thumbnails_mobile_thumb_position', 'bottom' );
		$thumb_position_tablet             = self::option( 'thumbnails_tabs_thumb_position', 'bottom' );
		$thumbnails_style                  = self::option( 'thumbnails_layout' );
		$slider_dots                       = ( self::option( 'dots' ) == 1 ) ? 'true' : 'false';
		wp_dequeue_script( 'photoswipe-ui-default' );
		wp_dequeue_script( 'photoswipe' );
		wp_dequeue_style( 'photoswipe' );
		wp_dequeue_style( 'photoswipe-default-skin' );

		wp_enqueue_script( 'slick', CIPG_ASSETS . '/js/slick.min.js', array( 'jquery' ), CIPG_VERSION, true );
		if ( '1' == self::option( 'image_zoom' ) ) {
			wp_enqueue_script( 'imagezoom', CIPG_ASSETS . '/js/imagezoom.js', array( 'jquery' ), CIPG_VERSION, true );
		}
		wp_enqueue_script( 'fancybox', CIPG_ASSETS . '/js/jquery.fancybox.min.js', array( 'jquery' ), CIPG_VERSION, true );

		wp_enqueue_script( 'wpgs-public', CIPG_ASSETS . '/js/wpgs.js', array( 'jquery', 'fancybox', 'slick' ), CIPG_VERSION, true );
		$variableWidth = false;
		$centerMode    = false;
		if ( is_product() ) {
			if ( count( $attachment_ids ) + 1 > 2 && count( $attachment_ids ) + 1 < $thumb_to_show - 1 && 'bottom' == $thumb_position ) {
				$variableWidth = true;
				$centerMode    = true;
			}
		}
		// Localize the script with new data
		$wpgs_js_data = array(
			'thumb_axis'                        => self::option( 'lightbox_thumb_axis', 'x' ),
			'thumb_autoStart'                   => self::option( 'lightbox_thumb_autoStart', '' ),
			'variation_mode'                    => self::option( 'variation_slide', '' ),
			'zoom'                              => self::option( 'image_zoom', 0 ),
			'zoom_action'                       => self::option( 'image_zoom_action', 'mouseover' ),
			'zoom_level'                        => self::option( 'image_zoom_level', '1' ),
			'lightbox_icon'                     => self::option( 'lightbox_icon' ),
			'thumbnails_lightbox'               => self::option( 'thumbnails_lightbox' ),
			'slider_caption'                    => self::option( 'slider_caption' ),
			'is_mobile'                         => wp_is_mobile(),
			'ajax_url'                          => admin_url( 'admin-ajax.php', 'relative' ),
			'ajax_nonce'                        => wp_create_nonce( 'wcavi_nonce' ),
			'product_id'                        => $product_id,
			'slider_animation'                  => $slider_animation,
			'thumbnails_id'                     => $thumbnails_id,
			'slider_lazyload'                   => $slider_lazyload,
			'slider_adaptiveHeight'             => $slider_adaptiveHeight,
			'slider_dots'                       => $slider_dots,
			'slider_rtl'                        => $slider_rtl,
			'slider_infinity'                   => $slider_infinity,
			'slider_dragging'                   => $slider_dragging,
			'slider_nav'                        => $slider_nav,
			'slider_animation_speed'            => $slider_animation_speed,
			'slider_autoplay'                   => $slider_autoplay,
			'slider_autoplay_pause_on_hover'    => $slider_autoplay_pause_on_hover,
			'slider_autoplay_pause_on_hover'    => $slider_autoplay_pause_on_hover,
			'slider_autoplay_time'              => $slider_autoplay_time,
			'thumb_to_show'                     => $thumb_to_show,
			'thumb_scroll_by'                   => $thumb_scroll_by,
			'thumb_v'                           => $thumb_position,
			'variableWidth'                     => apply_filters( 'wpgs_variable_width', $variableWidth ),
			'thumbnails_nav'                    => self::option( 'thumb_nav' ),
			'thumbnail_animation_speed'         => $thumbnail_animation_speed,
			'centerMode'                        => $centerMode,
			'thumb_v_tablet'                    => $thumb_position_tablet,
			'thumbnails_tabs_thumb_to_show'     => $thumbnails_tabs_thumb_to_show,
			'thumbnails_tabs_thumb_scroll_by'   => $thumbnails_tabs_thumb_scroll_by,
			'thumbnails_mobile_thumb_to_show'   => $thumbnails_mobile_thumb_to_show,
			'thumbnails_mobile_thumb_scroll_by' => $thumbnails_mobile_thumb_scroll_by,
			'carousel_mode'                     => self::option( 'thumbnails_lightbox' ),
			'thumb_position_mobile'             => $thumb_position_mobile,
			'variation_data'                    => $this->get_variaton_markup( $product_id ),

		);
		wp_localize_script( 'wpgs-public', 'wpgs_js_data', $wpgs_js_data );
		wp_enqueue_style( 'slick', CIPG_ASSETS . '/css/slick.css', null, CIPG_VERSION );
		wp_enqueue_style( 'slick-theme', CIPG_ASSETS . '/css/slick-theme.css', null, CIPG_VERSION );
		wp_enqueue_style( 'fancybox', CIPG_ASSETS . '/css/jquery.fancybox.min.css', null, CIPG_VERSION );

		$custom_css = self::option( 'custom_css' );

		if ( is_product() ) {
			$twist_product  = new \WC_Product( $product_id );
			$attachment_ids = $twist_product->get_gallery_image_ids();

			if ( count( $attachment_ids ) + 1 <= $thumb_to_show ) {
				$custom_css .= '
					.wpgs-nav .slick-track {
						transform: inherit !important;
					}
				';
			}
			if ( empty( $attachment_ids ) ) {
				$custom_css .= '
					.wpgs-dots {
						display:none;
					}
				';

			}
		}
		if ( '1' == self::option( 'lightbox_picker' ) ) {
			$custom_css .= '.wpgs-for .slick-slide{cursor:pointer;}';
		} else {
			$custom_css .= '.wpgs-for .slick-slide{cursor: default;}';
		}

		wp_add_inline_style( 'fancybox', $custom_css );

		wp_enqueue_style( 'flaticon-wpgs', CIPG_ASSETS . '/css/font/flaticon.css', null, CIPG_VERSION );
	}
}
