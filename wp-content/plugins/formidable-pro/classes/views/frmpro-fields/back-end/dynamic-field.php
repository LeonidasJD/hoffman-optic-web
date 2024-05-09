<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>

<p class="frm6 frm_form_field">
	<label><?php esc_html_e( 'Display Type', 'formidable-pro' ); ?></label>
	<select name="field_options[data_type_<?php echo absint( $field['id'] ); ?>]">
		<?php
		foreach ( $field_types as $type_key => $type_name ) {
			// Use "dropdown" as an alias for "select" because some security tools block requests containing "select".
			$use_type_value = 'select' === $type_key ? 'dropdown' : $type_key;
			?>
			<option value="<?php echo esc_attr( $use_type_value ); ?>" <?php selected( isset( $field['data_type'] ) && $field['data_type'] === $type_key ); ?>>
				<?php echo esc_html( $type_name ); ?>
			</option>
		<?php } ?>
	</select>
</p>
