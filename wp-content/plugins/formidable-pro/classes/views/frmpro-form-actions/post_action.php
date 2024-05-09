<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProPostAction extends FrmFormAction {

	public function __construct() {
		$action_ops = array(
			'classes'     => 'frm_wordpress_icon frm_icon_font frm-inverse',
			'color'       => 'rgb(0,160,210)',
			'limit'       => 1,
			'priority'    => 40,
			'event'       => array( 'create', 'update', 'import' ),
			'force_event' => true,
		);

		parent::__construct( 'wppost', __( 'Create Post', 'formidable-pro' ), $action_ops );
	}

	public function form( $form_action, $args = array() ) {
	    extract( $args );

	    $post_types = FrmProAppHelper::get_custom_post_types();
        if ( ! $post_types ) {
            return;
        }

        $post_type      = FrmProFormsHelper::post_type( $args['values']['id'] );
        $taxonomies     = get_object_taxonomies($post_type);
        $action_control = $this;
        $echo           = true;
		$form_id        = $form->id;
		$display        = $this->get_form_action_display( $form_id, $form_action );

        // Get array of all custom fields
        $custom_fields = array();
        if ( isset( $form_action->post_content['post_custom_fields'] ) ) {
            foreach ( $form_action->post_content['post_custom_fields'] as $custom_field_opts ) {
				if ( isset( $custom_field_opts['meta_name'] ) ) {
					$custom_fields[] = $custom_field_opts['meta_name'];
				}
                unset( $custom_field_opts );
            }
        }

		$embedded_fields = $this->get_embedded_fields( $form_id );
		$fields          = array_merge( $values['fields'], $embedded_fields );

		$embedded_field_ids = array_column( $embedded_fields, 'id' );

		if ( empty( $form_action->post_content['post_category'] ) && ! empty( $fields ) ) {
			foreach ( $values['fields'] as $fo_key => $fo ) {
				if ( $fo['post_field'] === 'post_category' ) {
					if ( ! isset( $fo['taxonomy'] ) || $fo['taxonomy'] == '' ) {
						$fo['taxonomy'] = 'post_category';
					}

					$tax_count = FrmProFormsHelper::get_taxonomy_count( $fo['taxonomy'], $form_action->post_content['post_category'] );

					$form_action->post_content['post_category'][ $fo['taxonomy'] . $tax_count ] = array(
						'field_id'    => $fo['id'],
						'exclude_cat' => isset( $fo['exclude_cat'] ) ? $fo['exclude_cat'] : 0,
						'meta_name'   => $fo['taxonomy'],
					);
					unset( $tax_count );
				} elseif ( $fo['post_field'] === 'post_custom' && ! in_array( $fo['custom_field'], $custom_fields ) ) {
					$form_action->post_content['post_custom_fields'][ $fo['custom_field'] ] = array(
						'field_id'  => $fo['id'],
						'meta_name' => $fo['custom_field'],
					);
				}
				unset( $fo_key, $fo );
			}
		}

		$values = FrmProFormActionsController::maybe_merge_fields( $values, $embedded_fields );
		unset( $embedded_fields );

		include __DIR__ . '/post_options.php';
	}

	/**
	 * Returns a formatted list of embedded fields in a form.
	 *
	 * @since 6.8
	 *
	 * @param int   $form_id
	 * @return array
	 */
	private function get_embedded_fields( $form_id ) {
		$embedded_form_ids = FrmProFormsHelper::get_embedded_form_ids( $form_id );
		if ( ! $embedded_form_ids ) {
			return array();
		}
		$embedded_fields = FrmDb::get_results( 'frm_fields', array( 'form_id' => $embedded_form_ids ) );

		$formatted_fields = array();

		foreach ( $embedded_fields as $field ) {
			FrmAppHelper::unserialize_or_decode( $field->field_options );
			$field = (array) $field;
			$opts  = (array) $field['field_options'];
			$field = array_merge( $opts, $field );
			if ( ! isset( $field['post_field'] ) ) {
				$field['post_field'] = '';
			}
			$formatted_fields[] = $field;
			unset( $field, $opts );
		}

		return $formatted_fields;
	}

	private function get_form_action_display( $form_id, $form_action ) {
		if ( is_callable( 'FrmViewsDisplay::get_form_action_display' ) ) {
			return FrmViewsDisplay::get_form_action_display( $form_id, $form_action );
		}
		return false;
	}

	private function post_options_for_views( $display, $form_id, $form_action ) {
		if ( is_callable( 'FrmViewsDisplay::post_options_for_views' ) ) {
			return FrmViewsDisplay::post_options_for_views( $display, $form_id, $this );
		}
		$link                  = $this->get_views_placeholder_link( $form_id );
		$display_id_field_name = $this->get_field_name( 'display_id' );
		$display_id            = isset( $form_action->post_content['display_id'] ) ? $form_action->post_content['display_id'] : '';
		require __DIR__ . '/post_options_for_views_placeholder.php';
	}

	private function get_views_placeholder_link( $form_id ) {
		return admin_url( 'admin.php?page=formidable-views&frm-full=1&form=' . absint( $form_id ) );
	}

	public function get_defaults() {
	    return array(
            'post_type'          => 'post',
            'post_category'      => array(),
            'post_content'       => '',
            'post_excerpt'       => '',
            'post_title'         => '',
            'post_name'          => '',
            'post_date'          => '',
            'post_status'        => '',
            'post_custom_fields' => array(),
            'post_password'      => '',
			'post_parent'        => '',
			'menu_order'         => '',
			'event'              => array( 'create', 'update' ),
        );
	}

	public function get_switch_fields() {
		return array(
			'post_category'      => array( 'field_id' ),
			'post_custom_fields' => array( 'field_id' ),
		);
	}

	/**
	 * Shows the post parent dropdown.
	 *
	 * @since 4.10.01
	 *
	 * @param array $args Arguments. See FrmAppHelper::maybe_autocomplete_pages_options().
	 */
	public static function post_parent_dropdown( $args ) {
		$defaults = array(
			'autocomplete_placeholder' => __( 'Select a Post', 'formidable-pro' ),
			'placeholder'              => __( 'Select a Post', 'formidable-pro' ),
		);

		$dropdown_args = array_merge( $defaults, $args );

		if ( is_callable( 'FrmAppHelper::maybe_autocomplete_pages_options' ) ) {
			FrmAppHelper::maybe_autocomplete_pages_options( $dropdown_args );
		} else {
			FrmAppHelper::wp_pages_dropdown( $dropdown_args );
		}
	}
}
