<?php

if ( ! class_exists( 'WPGS_Variation_images' ) ) {

	class WPGS_Variation_images {

		public function __construct() {
		}
		public function init_actions() {
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_csf' ), 10, 3 );
			add_action( 'woocommerce_variation_options', array( $this, 'variation_image_option_field' ), 10, 3 );
			add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_images' ), 10, 2 );
			add_filter( 'woocommerce_available_variation', array( $this, 'variation_field_data' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'wpgs_load_custom_admin_js' ) );
			add_action( 'save_post', array( $this, 'product_variation_clear_transient_data' ) );
		}

		public function wpgs_load_custom_admin_js() {
			$screen = get_current_screen();
			// Enqueue the JavaScript file only in the admin area
			if ( is_admin() && 'product' == $screen->id ) {
				wp_enqueue_script( 'wpgs-public', WPGS_ROOT_URL . 'assets/js/admin.js', array( 'jquery', 'csf' ), CIPG_VERSION, true );
			}
		}

		/**
		 * Clears the transient data for a product variation.
		 *
		 * This function checks if the given ID belongs to a product post type.
		 * If it does, it constructs the cache key for the product variation and checks if the transient data exists.
		 * If the site is a multisite installation, it appends the current site ID to the cache key.
		 * If the transient data exists, it deletes the transient data.
		 *
		 * @param int $id The ID of the product variation.
		 * @return void
		 */
		public function product_variation_clear_transient_data( $id ) {
			if ( 'product' === get_post_type( $id ) ) {
				$product_variation_cache = 'wpgs_product_variation_' . $id;
				if ( is_multisite() ) {
					$product_variation_cache = 'site_' . get_current_blog_id() . $product_variation_cache;
					if ( get_site_transient( $product_variation_cache ) ) {
						delete_site_transient( $product_variation_cache );
					}
				} elseif ( get_transient( $product_variation_cache ) ) {
					delete_transient( $product_variation_cache );
				}
			}
		}
		/**
		 * Delete all 'wpgs_product_variation_' transients from the database.
		 */
		public static function delete_transients() {
			$pf = new self();
			$pf->delete_transients_with_prefix( 'wpgs_product_variation_' );
		}
		/**
		 * Delete all transients from the database whose keys have a specific prefix.
		 *
		 * @param string $prefix The prefix. Example: 'my_cool_transient_'.
		 */
		public function delete_transients_with_prefix( $prefix ) {
			foreach ( $this->get_transient_keys_with_prefix( $prefix ) as $key ) {
				delete_transient( $key );
			}
		}
		/**
		 * Gets all transient keys in the database with a specific prefix.
		 *
		 * Note that this doesn't work for sites that use a persistent object
		 * cache, since in that case, transients are stored in memory.
		 *
		 * @param  string $prefix Prefix to search for.
		 * @return array          Transient keys with prefix, or empty array on error.
		 */
		private function get_transient_keys_with_prefix( $prefix ) {
			global $wpdb;

			$prefix = $wpdb->esc_like( '_transient_' . $prefix );
			$sql    = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%s'";
			$keys   = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );

			if ( is_wp_error( $keys ) ) {
				return array();
			}

			return array_map(
				function ( $key ) {
					// Remove '_transient_' from the option name.
					return substr( $key['option_name'], strlen( '_transient_' ) );
				},
				$keys
			);
		}
		/**
		 * Custom set transient
		 *
		 * @param  mixed $key Key.
		 * @param  mixed $data Data.
		 * @param  mixed $time Time.
		 * @return void
		 */
		public function set_cache( $key, $data, $time ) {
			if ( ! is_admin() ) {
				if ( is_multisite() ) {
					set_site_transient( 'site_' . get_current_blog_id() . $key, $data, $time );
				} else {
					set_transient( $key, $data, $time );
				}
			}
		}

		/**
		 * Retrieves the cached data from the transient storage.
		 *
		 * @param string $key The key used to store the cached data.
		 * @return mixed The cached data retrieved from the transient storage.
		 */
		public function get_cache( $key ) {
			$cached_data = '';
			if ( is_multisite() ) {
				$cached_data = get_site_transient( 'site_' . get_current_blog_id() . $key );
			} else {
				$cached_data = get_transient( $key );
			}
			return $cached_data;
		}
		/**
		 * @param $product_id
		 */
		public function get_variation_data( $product_id ) {

			// get all porduct variations
			$product_variations = new \WC_Product_Variable( $product_id );

			$variations = $product_variations->get_available_variations();

			$variation_images = array();

			$product = wc_get_product( $product_id );
			if ( ! $product->is_type( 'variable' ) ) {
				return false;
			}
			$thumbnails = implode( ',', $product->get_gallery_image_ids() );

			$variation_images[0] = $this->html_markup( $product->get_image_id(), $thumbnails );

			return $variation_images;
		}

		/**
		 * Quick hack for load CSF anywhere
		 *
		 * @return void
		 */
		public function add_csf() {
			CSF::$enqueue = true;
			CSF::add_admin_enqueue_scripts();
		}
		/**
		 * @param $variations
		 * @return mixed
		 */
		public function variation_field_data( $variations ) {
			$variations['wavi_value'] = get_post_meta( $variations['variation_id'], 'wavi_value', true );

			return $variations;
		}
		/**
		 * @param $variation_id
		 * @param $i
		 */
		public function save_variation_images( $variation_id, $i ) {
			$custom_field = sanitize_text_field( $_POST['wavi_value'][ $i ] );

			if ( isset( $custom_field ) ) {
				update_post_meta( $variation_id, 'wavi_value', esc_attr( $custom_field ) );
			}
		}
		/**
		 * @param $loop
		 * @param $variation_data
		 * @param $variation
		 */
		function variation_image_option_field( $loop, $variation_data, $variation ) {

			echo '<div class="csf-onload wpgs-variaiton-wrapper" style="margin-left: -25px;">';

			CSF::field(
				array(
					'id'          => $loop,
					'type'        => 'gallery',
					'title'       => 'Additional Images',
					'add_title'   => 'Add Images',
					'edit_title'  => 'Edit Images',
					'clear_title' => 'Remove Images',
				),
				get_post_meta( $variation->ID, 'wavi_value', true ),
				'wavi_value'
			);

			echo '</div>';
		}

		/**
		 * @param $id
		 */
		public function html_markup( $post_thumbnail_id, $thumbnails = '' ) {

			$attachment_ids = explode( ',', $thumbnails );
			if ( empty( $thumbnails ) ) {
				$attachment_ids = array();
			}

			$slider_rtl = ( is_rtl() ) ? 'true' : 'false';
			ob_start();

			?>
			<div class="woocommerce-product-gallery images wpgs-wrapper <?php echo esc_attr( apply_filters( 'wpgs_wrapper_add_classes', '', $attachment_ids ) ); ?>" style="opacity:0">

				<div class="wpgs-for" <?php echo esc_attr( 'true' == $slider_rtl ? 'dir=rtl' : '' ); ?> >

				<?php
				if ( $post_thumbnail_id ) {

								$html = wpgs_get_image_gallery_html( $post_thumbnail_id, true );
					if ( apply_filters( 'wpgs_show_featured_image_in_gallery', true ) ) {
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
					}
				}

				if ( apply_filters( 'wpgs_carousel_mode', true ) ) {
					if ( ! empty( $attachment_ids ) ) {
						foreach ( $attachment_ids as $attachment_id ) {
							$html = wpgs_get_image_gallery_html( $attachment_id );
							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ); // phpcs:disable
						}
					}

			}

			?>

				</div>


				<div class="wpgs-nav" <?php echo esc_attr( 'true' == $slider_rtl ? 'dir=rtl' : '' ); ?>>
				<?php

			if ( $post_thumbnail_id && !empty( $attachment_ids )) {
					$html = wpgs_get_image_gallery_thumb_html( $post_thumbnail_id, false );
					if ( apply_filters( 'wpgs_show_featured_image_in_gallery', true ) && apply_filters( 'wpgs_carousel_mode', true ) ) {
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
						}
			}

			if ( !empty( $attachment_ids ) ) {
					foreach ( $attachment_ids as $attachment_id ) {
						$html = wpgs_get_image_gallery_thumb_html( $attachment_id );
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ); // phpcs:disable
						}
			}
			?>
				</div>

			</div>
			<?php

			return ob_get_clean();
		}

	}

}
