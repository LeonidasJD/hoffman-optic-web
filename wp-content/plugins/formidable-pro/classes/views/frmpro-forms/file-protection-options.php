<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<?php if ( $has_file_field ) : ?>
	<p class="frm_form_field">
		<label for="protect_files" class="frm-inline-select">
			<input type="checkbox" name="options[protect_files]" id="protect_files" value="1" <?php checked( $values['protect_files'], 1 ); ?> onchange="document.getElementById('noindex_files').disabled=!this.checked;document.querySelector('label[for=\'noindex_files\']').classList.toggle('frm_noallow',!this.checked);" />
			<?php
			esc_html_e( 'Protect all files uploaded in this form', 'formidable-pro' );
			if ( 'Windows NT' === FrmProAppHelper::get_server_os() ) :
				?>
				<span class="howto">
				<?php
				if ( stripos( FrmAppHelper::get_server_value( 'SERVER_SOFTWARE' ), 'apache' ) !== false ) {
					esc_html_e( '( *Locking files is not supported on Windows servers currently. )', 'formidable-pro' );
				} elseif ( stripos( FrmAppHelper::get_server_value( 'SERVER_SOFTWARE' ), 'nginx' ) !== false ) {
					esc_html_e( '( *Files will still be directly accessible on Windows. )', 'formidable-pro' );
				}
				?>
				</span>
			<?php endif; ?>
		</label>
	</p>

	<p class="frm4 frm_form_field hide_protect_files frm_indent_opt <?php echo esc_attr( $values['protect_files'] ? '' : 'frm_hidden' ); ?>">
		<label for="protect_files_role">
			<?php esc_html_e( 'Role required to access file', 'formidable-pro' ); ?>
		</label>
	</p>
	<p class="frm8 frm_form_field hide_protect_files <?php echo esc_attr( $values['protect_files'] ? '' : 'frm_hidden' ); ?>">
		<select name="options[protect_files_role][]" id="protect_files_role" multiple="multiple" class="frm_multiselect">
			<?php $roles = isset( $values['protect_files_role'] ) ? $values['protect_files_role'] : array( '' ); ?>
			<option <?php FrmProAppHelper::selected( $roles, '' ); ?> value=""><?php esc_html_e( 'Everyone', 'formidable-pro' ); ?></option>
			<?php FrmAppHelper::roles_options( $roles ); ?>
		</select>
	</p>

	<p class="frm_form_field">
		<label for="noindex_files" class="<?php echo esc_attr( $values['protect_files'] ? '' : 'frm_noallow' ); ?>">
			<input type="checkbox" name="options[noindex_files]" id="noindex_files" value="1" <?php checked( $values['protect_files'] ? $values['noindex_files'] : 0, 1 ); ?> <?php disabled( $values['protect_files'], 0 ); ?> />
			<?php esc_html_e( 'Prevent search engines from indexing uploads', 'formidable-pro' ); ?>
			<span class="frm_help frm_icon_font frm_tooltip_icon" data-container="body" title="<?php esc_attr_e( 'This feature requires that file protection is turned on.', 'formidable-pro' ); ?>"></span>
		</label>
	</p>
<?php else : ?>
	<input type="hidden" value="0" name="options[protect_files]" />
	<input type="hidden" value="0" name="options[noindex_files]" />
<?php endif; ?>
