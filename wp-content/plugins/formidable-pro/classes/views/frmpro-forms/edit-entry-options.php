<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<h3>
	<?php esc_html_e( 'Entry editing', 'formidable-pro' ); ?>
</h3>

<p>
	<label for="editable" class="frm-inline-select">
		<input type="checkbox" name="editable" id="editable" value="1" <?php checked( $values['editable'], 1 ); ?> />
		<?php esc_html_e( 'Allow front-end editing of entries', 'formidable-pro' ); ?>
	</label>
</p>

<p class="frm4 frm_form_field hide_editable frm_indent_opt <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
	<label id="for_editable_role" for="editable_role">
		<?php esc_html_e( 'Role required to edit one\'s own entries', 'formidable-pro' ); ?>
	</label>
</p>

<p class="frm8 frm_form_field hide_editable <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
	<select name="options[editable_role][]" id="editable_role" multiple="multiple" class="frm_multiselect">
		<option value="" <?php FrmProAppHelper::selected( $values['editable_role'], '' ); ?>><?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?></option>
		<?php
		FrmAppHelper::roles_options( $values['editable_role'] );

		/**
		 * Extend the roles for front-end editing own entries options.
		 *
		 * @since 6.8
		 *
		 * @param array<string> $values['editable_role'] Selected roles.
		 */
		do_action( 'frm_settings_editable_role', $values['editable_role'] );
		?>
	</select>
</p>

<?php
if ( isset( $values['open_editable'] ) && empty( $values['open_editable'] ) ) {
    $values['open_editable_role'] = '-1';
}
?>
<p class="frm4 frm_form_field hide_editable frm_indent_opt <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
	<label id="for_open_editable_role" for="open_editable_role">
		<?php esc_html_e( 'Role required to edit other users\' entries', 'formidable-pro' ); ?>
	</label>
</p>
<p class="frm8 frm_form_field hide_editable <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
	<select name="options[open_editable_role][]" id="open_editable_role" multiple="multiple" class="frm_multiselect">
		<option value="" <?php FrmProAppHelper::selected( $values['open_editable_role'], '' ); ?>><?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?></option>
		<?php FrmAppHelper::roles_options( $values['open_editable_role'] ); ?>
	</select>
</p>

<?php
/**
 * Allow form permission settings after edit entry to extend.
 *
 * @since 6.8
 *
 * @param mixed $values Form settings value.
 */
do_action( 'frm_settings_after_edit_entry', $values );
