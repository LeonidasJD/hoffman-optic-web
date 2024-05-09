<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<h3>
	<?php esc_html_e( 'Save Drafts', 'formidable' ); ?>
</h3>
<p class="frm4 frm_form_field">
	<label for="save_draft" class="frm-inline-select">
		<input type="checkbox" name="options[save_draft]" id="save_draft" value="1" <?php checked( $values['save_draft'], 1 ); ?>  />
		<?php esc_html_e( 'Allow saving drafts', 'formidable-pro' ); ?>
	</label>
</p>

<p class="frm8 frm_form_field hide_save_draft <?php echo empty( $values['save_draft'] ) ? esc_attr( ' frm_hidden' ) : ''; ?>">
	<select name="options[edit_draft_role][]" id="edit_draft_role" multiple="multiple" class="frm_multiselect">
		<option value="" <?php FrmProAppHelper::selected( $values['edit_draft_role'], '' ); ?>><?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?></option>
		<?php
		/**
		 * Extend the roles for draft edit options.
		 *
		 * @since 6.8
		 *
		 * @param array<string> $values['edit_draft_role'] Selected roles.
		 */
		do_action( 'frm_settings_edit_draft_role', $values['edit_draft_role'] );
		?>
	</select>
</p>

<p class="frm4 frm_form_field frm_indent_opt hide_save_draft" style="align-self: start;">
	<label for="draft_msg">
		<?php esc_html_e( 'Saved Draft Message', 'formidable-pro' ); ?>
	</label>
</p>
<p class="frm8 frm_form_field frm_has_textarea frm_has_shortcodes hide_save_draft">
	<textarea name="options[draft_msg]" id="draft_msg" cols="50" rows="2" class="frm_long_input"><?php echo FrmAppHelper::esc_textarea( $values['draft_msg'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></textarea>
</p>

<p class="frm4 frm_form_field frm_indent_opt hide_save_draft">
	<label for="frm_save_draft_label">
		<?php esc_html_e( 'Save Draft Text', 'formidable-pro' ); ?>
	</label>
</p>
<p class="frm8 frm_form_field hide_save_draft">
	<input id="frm_save_draft_label" type="text" name="options[draft_label]" value="<?php echo esc_attr( '' === $values['draft_label'] ? __( 'Save Draft', 'formidable-pro' ) : $values['draft_label'] ); ?>" />
</p>

<?php
/**
 * Allow form permission settings after save draft to extend.
 *
 * @since 6.8
 *
 * @param mixed $values Form settings value.
 */
do_action( 'frm_settings_after_save_draft', $values );
