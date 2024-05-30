<?php

use Product_Gallery_Sldier\Product;

if ( ! function_exists( 'cix_get_wp_image_sizes' ) ) {
	/**
	 * @param $value
	 */
	function cix_get_wp_image_sizes() {
		// Get the image sizes.
		global $_wp_additional_image_sizes;
		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}x{$height}" );

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}X{$height}" );
			}
		}
		return $sizes;
	}
}

if ( ! function_exists( 'wpgs_get_option' ) ) {
	/**
	 * Get Setting option
	 *
	 * @param  [string] $option
	 * @param  [string] $section
	 * @param  string   $default
	 * @return void
	 */
	function wpgs_get_option( $option, $default = '' ) {
		$options = get_option( 'wpgs_form' );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}
}

if ( ! function_exists( 'cix_only_pro' ) ) {
	/**
	 * @param $value
	 */
	function cix_only_pro( $value ) {
		if ( 'only_pro' == $value || 'ondemand' == $value || 'progressive' == $value || true == $value || false == $value || 'x' == $value ) {
			return esc_html__( 'Available in PRO', 'wpgs-td' );
		}
	}
}

if ( ! function_exists( 'cix_get_wp_image_sizes' ) ) {
	/**
	 * @param $value
	 */
	function cix_get_wp_image_sizes() {
		// Get the image sizes.
		global $_wp_additional_image_sizes;
		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}x{$height}" );

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}X{$height}" );
			}
		}
		return $sizes;
	}
}

add_filter( 'plugin_row_meta', 'wpgs_plugin_meta_links', 10, 2 );
/**
 * Add links to plugin's description in plugins table
 *
 * @param array  $links Initial list of links.
 * @param string $file  Basename of current plugin.
 */
function wpgs_plugin_meta_links( $links, string $file ) {
	if ( WPGS_PLUGIN_BASE !== $file ) {
		return $links;
	}

	$support_link = '<a style="color:red;" target="_blank" href="https://codeixer.com/contact-us/" title="' . __( 'Get help', 'woo-product-gallery-slider' ) . '">' . __( 'Support', 'woo-product-gallery-slider' ) . '</a>';
	$rate_twist   = '<a target="_blank" href="https://wordpress.org/support/plugin/woo-product-gallery-slider/reviews/?filter=5"> Rate this plugin » </a>';

	$links[] = $support_link;
	$links[] = $rate_twist;

	return $links;
} // plugin_meta_links

add_filter( 'wc_get_template', 'wpgs_get_template', 10, 5 );

if ( ! function_exists( 'wpgs_get_template' ) ) {
	/**
	 * @param $located
	 * @param $template_name
	 * @param $args
	 * @param $template_path
	 * @param $default_path
	 */
	function wpgs_get_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'single-product/product-image.php' == $template_name ) {
			$located = WPGS_INC . 'product-image.php';
		}

		return $located;
	}
}
if ( ! function_exists( 'wpgs_get_image_gallery_html' ) ) {

	/**
	 * @param $attachment_id
	 * @param $main_image
	 */
	function wpgs_get_image_gallery_html( $attachment_id, $main_image = false ) {

		$size = apply_filters( 'wpgs_new_main_img_size', Product::option( 'slider_image_size' ) );
		/* Plugin Options */
		$lightbox = ( Product::option( 'lightbox_picker' ) == 1 ) ? 'true' : 'false';

		$lightbox_img_alt = ( Product::option( 'lightbox_alt_text' ) == 1 ) ? 'true' : 'false';
		$img_caption      = ( Product::option( 'slider_caption' ) == 'caption' ) ? wp_get_attachment_caption( $attachment_id ) : get_the_title( $attachment_id );
		( 'true' == $lightbox_img_alt ) ? $img_caption : $img_caption = '';
		// Check if Gallery have Video URL

		$zoom_image_size = Product::option( 'zoom_image_size', 'large' );

		$lightbox_animation        = Product::option( 'lightbox_oc_effect' );
		$lightbox_slides_animation = Product::option( 'lightbox_slide_effect' );
		$lightbox_img_count        = ( Product::option( 'lightbox_img_count' ) == 1 ) ? 'true' : 'false';

		$img_has_video            = get_post_meta( $attachment_id, 'twist_video_url', true );
		$gallery_first_item_class = ( Product::option( 'variation_slide' ) == 'default' ) ? 'woocommerce-product-gallery__image' : 'wpgs1';
		$video_class              = $img_has_video ? 'wpgs-video' : '';
		$gallery__image           = ( $main_image ) ? 'class="' . $gallery_first_item_class . ' wpgs_image"' : 'class="wpgs_image"';

		$img_lightbox_url = $img_has_video ? $img_has_video : wp_get_attachment_image_url( $attachment_id, apply_filters( 'gallery_slider_lightbox_image_size', 'full' ) );
		$caption_html     = ( Product::option( 'slider_alt_text' ) == 1 ) ? '<span class="wpgs-gallery-caption">' . $img_caption . '</span>' : '';
		$image            = wp_get_attachment_image(
			$attachment_id,
			$size,
			false,
			array(
				// 'title'            => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'alt'              => trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
				'class'            => esc_attr( $main_image ? 'wp-post-image img-attr ' . apply_filters( 'wpgs_add_img_class', '' ) : 'img-attr ' . apply_filters( 'wpgs_add_img_class', '' ) ),
				'src'              => apply_filters( 'wpgs_lazyload_src', wp_get_attachment_image_url( $attachment_id, $size ) ),
				'data-lazy'        => wp_get_attachment_image_url( $attachment_id, $size ),
				'data-o_img'       => wp_get_attachment_image_url( $attachment_id, $size ),
				'data-large_image' => wp_get_attachment_image_url( $attachment_id, apply_filters( 'gallery_slider_zoom_image_size', $zoom_image_size ) ),
				'data-zoom-image'  => wp_get_attachment_image_url( $attachment_id, apply_filters( 'gallery_slider_zoom_image_size', $zoom_image_size ) ),
				'data-caption'     => $img_caption,

			),
			$attachment_id,
			$main_image
		);

		if ( 'true' == $lightbox ) {
			$markup = '<div ' . $gallery__image . ' data-attachment-id=' . $attachment_id . ' ><a aria-label="Zoom Icon" class="' . $video_class . '"
			href = "' . $img_lightbox_url . '"
			data-elementor-open-lightbox="no"
			data-caption="' . $img_caption . '"
			data-thumb="' . wp_get_attachment_image_url( $attachment_id, apply_filters( 'wpgs_new_thumb_img_size', 'woocommerce_gallery_thumbnail' ) ) . '"
			data-fancybox="wpgs"
			data-large_image=' . wp_get_attachment_image_url( $attachment_id, apply_filters( 'gallery_slider_zoom_image_size', $zoom_image_size ) ) . '
			data-animation-effect="' . $lightbox_animation . '"
			data-transition-effect="' . $lightbox_slides_animation . '"
			data-infobar="' . $lightbox_img_count . '"
			data-loop="true"
			data-hash="false"
			data-click-slide="close"
			data-options=\'{"buttons": ["zoom","slideShow","fullScreen","thumbs","close"] }\'

			>' . $image . '
			</a>' . $caption_html . '</div>';
			return $markup;
		} elseif ( 'false' == $lightbox ) {
			$markup = '<div ' . $gallery__image . ' data-attachment-id=' . $attachment_id . '>' . $image . $caption_html . '</div>';

			return $markup;
		}
	}
}

if ( ! function_exists( 'wpgs_get_image_gallery_thumb_html' ) ) {

	// Custom HTML layout
	/**
	 * @param $attachment_id
	 * @param $main_image
	 */
	function wpgs_get_image_gallery_thumb_html( $attachment_id, $main_image = false ) {

		$size = apply_filters( 'wpgs_new_thumb_img_size', Product::option( 'thumbnail_image_size' ) );

		/* Plugin Options */

		$lightbox_img_alt = ( Product::option( 'lightbox_alt_text' ) == 1 ) ? 'true' : 'false';
		$img_caption      = ( empty( wp_get_attachment_caption( $attachment_id ) ) ) ? get_the_title( $attachment_id ) : wp_get_attachment_caption( $attachment_id );
		( 'true' == $lightbox_img_alt ) ? $img_caption : $img_caption = '';
		// Check if Gallery have Video URL

		$lightbox_animation        = Product::option( 'lightbox_oc_effect' );
		$lightbox_slides_animation = Product::option( 'lightbox_slide_effect' );
		$lightbox_img_count        = ( Product::option( 'lightbox_img_count' ) == 1 ) ? 'true' : 'false';

		$img_has_video = get_post_meta( $attachment_id, 'twist_video_url', true );
		$video_class   = $img_has_video ? 'wpgs-video' : '';

		$gallery_thumb_image = $main_image ? 'class="gallery_thumbnail_first thumbnail_image ' . $video_class . ' "' : 'class="thumbnail_image ' . $video_class . '"';

		$img_lightbox_url = $img_has_video ? $img_has_video : wp_get_attachment_image_url( $attachment_id, apply_filters( 'gallery_slider_lightbox_image_size', 'full' ) );

		$image = wp_get_attachment_image(
			$attachment_id,
			$size,
			false,
			array(

				'alt'        => trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
				'class'      => esc_attr( $main_image ? 'wp-post-image img-attr ' . apply_filters( 'wpgs_add_img_class', '' ) : 'img-attr ' . apply_filters( 'wpgs_add_img_class', '' ) ),
				'src'        => apply_filters( 'wpgs_lazyload_src', wp_get_attachment_image_url( $attachment_id, $size ) ),
				'data-lazy'  => wp_get_attachment_image_url( $attachment_id, $size ),
				'data-thumb' => wp_get_attachment_image_url( $attachment_id, $size ),

			),
			$attachment_id,
			$main_image
		);

		if ( apply_filters( 'wpgs_carousel_mode', true ) != true ) {
			$markup = '<a ' . $gallery_thumb_image . '
			href = "' . $img_lightbox_url . '"
			data-elementor-open-lightbox="no"
			data-caption="' . $img_caption . '"
			data-thumb="' . wp_get_attachment_image_url( $attachment_id, $size ) . '"
			data-fancybox="wpgs" data-animation-effect="' . $lightbox_animation . '" data-transition-effect="' . $lightbox_slides_animation . '"
			data-infobar="' . $lightbox_img_count . '"
			data-loop="true"
			data-hash="false"
			data-click-slide="close"
			data-options=\'{"buttons": ["zoom","slideShow","fullScreen","thumbs","close"] }\'

			>
			' . $image . '
			</a>';
			return $markup;
		} else {
			// the thumbnail markup
			return '<div ' . $gallery_thumb_image . '>' . $image . '</div>';
		}
	}
}

// Phlox Pro "shop" plugin conflicts with gallery html markup
remove_filter( 'woocommerce_single_product_image_thumbnail_html', 'auxin_single_product_lightbox', 10, 2 );
