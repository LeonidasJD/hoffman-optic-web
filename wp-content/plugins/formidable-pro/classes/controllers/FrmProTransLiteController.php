<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * @since 6.8
 */
class FrmProTransLiteController {

	/**
	 * Include embedded form ids in field options for Name / Address customer information settings.
	 *
	 * @since 6.8
	 *
	 * @param int|int[] $form_id
	 * @return array
	 */
	public static function trans_action_get_field_options_form_id( $form_id ) {
		if ( is_array( $form_id ) ) {
			return $form_id;
		}

		$form_ids   = FrmProFormsHelper::get_embedded_form_ids( $form_id );
		$form_ids[] = absint( $form_id );
		return $form_ids;
	}
}
