<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<p class="howto">
	<?php esc_html_e( 'Determine who can see, submit, and edit form entries.', 'formidable-pro' ); ?>
</p>

<div class="frm_grid_container">
	<p class="frm4 frm_form_field">
		<label id="for_logged_in_role" for="logged_in">
			<input type="checkbox" name="logged_in" id="logged_in" value="1" <?php checked( $values['logged_in'], 1 ); ?> data-frmshow="#logged_in_role" data-toggleclass="frm_invisible" />
			<?php printf( esc_html__( 'Limit form visibility %1$sto%2$s', 'formidable-pro' ), '<span class="hide_logged_in ' . esc_attr( $values['logged_in'] ? '' : 'frm_invisible' ) . '">', '</span>' ); ?>
		</label>
	</p>
	<p class="frm8 frm_form_field frm_select_with_label">
		<select name="options[logged_in_role][]" id="logged_in_role" class="frm_multiselect hide_logged_in <?php echo esc_attr( $values['logged_in'] ? '' : 'frm_invisible' ); ?>" multiple="multiple">
			<option value="" <?php FrmProAppHelper::selected( $values['logged_in_role'], '' ); ?>><?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?></option>
			<?php FrmAppHelper::roles_options($values['logged_in_role']); ?>
		</select>
	</p>

	<p class="frm4 frm_form_field">
		<label for="single_entry">
			<input type="checkbox" name="options[single_entry]" id="single_entry" value="1" <?php checked( $values['single_entry'], 1 ); ?> />
			<?php printf( esc_html__( 'Limit number of entries %1$sto one per%2$s', 'formidable-pro' ), '<span class="frm-single-entry-setting' . esc_attr( $values['single_entry'] ? '' : ' frm_invisible' ) . '">', '</span>' ); ?>
		</label>
	</p>
	<p class="frm8 frm_form_field frm_select_with_label">
		<select id="frm_single_entry_type" name="options[single_entry_type][]" class="frm_multiselect frm-single-entry-setting<?php echo esc_attr( $values['single_entry'] ? '' : ' frm_invisible' ); ?>" multiple="multiple">
			<option value="user" <?php selected( in_array( 'user', $values['single_entry_type'], true ) ); ?>>
				<?php esc_html_e( 'Logged-in User', 'formidable-pro' ); ?>
			</option>
			<?php if ( FrmAppHelper::ips_saved() ) { ?>
			<option value="ip" <?php selected( in_array( 'ip', $values['single_entry_type'], true ) ); ?>>
				<?php esc_html_e( 'IP Address', 'formidable-pro' ); ?>
			</option>
			<?php } ?>
			<option value="cookie" <?php selected( in_array( 'cookie', $values['single_entry_type'], true ) ); ?>>
				<?php esc_html_e( 'Saved Cookie', 'formidable-pro' ); ?>
			</option>
			<?php if ( $email_fields ) { ?>
			<option value="email" <?php selected( in_array( 'email', $values['single_entry_type'], true ) ); ?>>
				<?php esc_html_e( 'Email Address', 'formidable-pro' ); ?>
			</option>
			<?php } ?>
		</select>
	</p>

	<div id="frm_cookie_expiration" class="frm-single-entry-type-cookie-setting frm_grid_container <?php echo FrmProFormsHelper::check_single_entry_type( $values, 'cookie' ) ? '' : 'frm_hidden'; ?>">
		<p class="frm4 frm_indent_opt frm_first">
			<label for="cookie_expiration">
				<?php esc_html_e( 'Cookie Expiration', 'formidable-pro' ); ?>
			</label>
		</p>
		<p class="frm8">
			<input type="text" id="cookie_expiration" name="options[cookie_expiration]" value="<?php echo esc_attr( $values['cookie_expiration'] ); ?>" size="6" class="frm-w-auto">
			<span class="howto"><?php esc_html_e( 'hours', 'formidable-pro' ); ?></span>
		</p>
	</div>

	<?php
	if ( count( $email_fields ) === 1 ) {
		?>
		<input type="hidden" name="options[unique_email_id]" value="<?php echo esc_attr( reset( $email_fields )->id ); ?>" />
		<?php
	} elseif ( $email_fields ) {
		$single_entry_email_wrapper_params = array(
			'class' => 'frm8 frm_first frm_form_field frm_select_with_label frm-single-entry-type-email-setting',
		);
		if ( ! FrmProFormsHelper::check_single_entry_type( $values, 'email' ) ) {
			$single_entry_email_wrapper_params['class'] .= ' frm_hidden';
		}
		$single_entry_label_wrapper_params['class'] = str_replace( 'frm8', 'frm4 frm_indent_opt', $single_entry_email_wrapper_params['class'] );
		?>
	<p <?php FrmAppHelper::array_to_html_params( $single_entry_label_wrapper_params, true ); ?>>
		<label for="frm_unique_email_id">
			<?php esc_html_e( 'Email Address Field', 'formidable-pro' ); ?>
		</label>
	</p>
	<p <?php FrmAppHelper::array_to_html_params( $single_entry_email_wrapper_params, true ); ?>>
		<select id="frm_unique_email_id" name="options[unique_email_id]">
			<option value="">
				<?php echo esc_html_e( '&mdash; Select Field &mdash;', 'formidable-pro' ); ?>
			</option>
			<?php
			foreach ( $email_fields as $email_field ) {
				FrmProHtmlHelper::echo_dropdown_option(
					$email_field->name,
					isset( $values['unique_email_id'] ) && (string) $values['unique_email_id'] === (string) $email_field->id,
					array(
						'value' => $email_field->id,
					)
				);
			}
			?>
		</select>
	</p>
		<?php
		unset( $single_entry_email_wrapper_params, $single_entry_label_wrapper_params );
	}
	?>

<?php
require FrmProAppHelper::plugin_path() . '/classes/views/frmpro-forms/file-protection-options.php';

if ( is_multisite() ) {
	if ( current_user_can( 'setup_network' ) ) {
	?>
		<p>
			<label for="copy">
				<input type="checkbox" name="options[copy]" id="copy" value="1" <?php echo ( $values['copy'] ) ? ' checked="checked"' : ''; ?> />
				<?php esc_html_e( 'Copy this form to other blogs when Formidable Forms is activated', 'formidable-pro' ); ?>
			</label>
		</p>
	<?php
	} elseif ( $values['copy'] ) {
		?>
		<input type="hidden" name="options[copy]" id="copy" value="1" />
		<?php
	}
}

require FrmProAppHelper::plugin_path() . '/classes/views/frmpro-forms/edit-entry-options.php';
require FrmProAppHelper::plugin_path() . '/classes/views/frmpro-forms/save-draft-options.php';
?>
</div>
