<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

$option_index = 0;
foreach ( $field['options'] as $opt_key => $opt_value ) {
	$checked = ( in_array( $opt_value, $saved_value_array ) ) ? ' checked="checked"' : '';
	?>
	<div class="<?php echo esc_attr( apply_filters( 'frm_checkbox_class', 'frm_checkbox', $field, $opt_value ) ); ?>" id="frm_checkbox_<?php echo esc_attr( $field['id'] ); ?>-<?php echo esc_attr( $opt_key ); ?>">
		<label for="<?php echo esc_attr( $html_id . '-' . $opt_key ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $field_name ); ?>"
				   id="<?php echo esc_attr( $html_id . '-' . $opt_key ); ?>"
				   value="<?php echo esc_attr( $opt_value ); ?>" <?php
			echo $checked . $disabled . ' '; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( 0 === $option_index && FrmField::is_required( $field ) ) {
				echo ' aria-required="true" ';
			}

			do_action( 'frm_field_input_html', $field );
			?> /> <?php echo $opt_value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</label>
	</div>
	<?php
	++$option_index;
}
