<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * @since 3.0
 */
class FrmProFieldEmail extends FrmFieldEmail {
	use FrmProFieldAutocompleteField;

	/**
	 * @return array
	 */
	protected function field_settings_for_type() {
		$settings = parent::field_settings_for_type();

		$settings['autopopulate'] = true;
		$settings['conf_field']   = true;
		$settings['unique']       = true;
		$settings['read_only']    = true;
		$settings['prefix']       = true;
		$settings['autocomplete'] = true;

		FrmProFieldsHelper::fill_default_field_display( $settings );
		return $settings;
	}

	/**
	 * @since 4.05
	 */
	protected function builder_text_field( $name = '' ) {
		$html  = FrmProFieldsHelper::builder_page_prepend( $this->field );
		$field = parent::builder_text_field( $name );
		return str_replace( '[input]', $field, $html );
	}

	/**
	 * @since 4.0
	 * @param array $args - Includes 'field', 'display', and 'values'.
	 */
	public function show_primary_options( $args ) {
		$field = $args['field'];
		include FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/back-end/confirmation.php';

		parent::show_primary_options( $args );
	}

	/**
	 * @since 4.0
	 * @param array $args - Includes 'field', 'display'.
	 */
	public function show_after_default( $args ) {
		$field = $args['field'];
		include FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/back-end/confirmation-placeholder.php';
	}

	/**
	 * @since 3.06.01
	 */
	public function translatable_strings() {
		$strings   = parent::translatable_strings();
		$strings[] = 'conf_desc';
		$strings[] = 'conf_msg';
		return $strings;
	}

	/**
	 * @since 6.6
	 *
	 * @return array<string>
	 */
	protected function get_filter_keys() {
		return array( 'on', 'off', 'email' );
	}

	/**
	 * @return array
	 */
	public function get_new_field_defaults() {
		$field = parent::get_new_field_defaults();
		$field['field_options']['autocomplete'] = 'email';
		return $field;
	}
}
