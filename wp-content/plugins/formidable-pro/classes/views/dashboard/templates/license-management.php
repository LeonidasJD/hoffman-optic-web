<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<div class="frm-dashboard-license-management dropdown <?php echo esc_attr( $template['license']['class'] ); ?>">
	<div class="frm-flex-box frm-justify-between">
		<h3>
			<?php esc_html_e( 'License Key', 'formidable' ); ?>
			<?php if ( $template['license']['status-copy'] ) : ?>
				<span class="frm-meta-tag frm-text-xs <?php echo esc_attr( $template['license']['status-tag-classname'] ); ?>">
					<?php echo esc_html( $template['license']['status-copy'] ); ?>
				</span>
			<?php endif; ?>
		</h3>
		<div class="frm-dashboard-license-options">
			<a href="#" class="frm-with-icon frm-flex-center dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
				<?php FrmAppHelper::icon_by_class( 'frmfont frm_thick_more_vert_icon' ); ?>
			</a>
			<ul class="dropdown-menu frm-dropdown-menu frm-on-top" role="menu" aria-labelledby="dropdownMenuButton">
				<li class="frm-show-authorized">
					<?php FrmProEddHelper::show_disconnect_link(); ?>
				</li>
				<li class="frm-show-authorized">
					<?php FrmProEddHelper::show_clear_license_cache_link(); ?>
				</li>
				<li class="frm-show-unauthorized">
					<?php FrmProEddHelper::show_manual_license_link(); ?>
				</li>
			</ul>
		</div>
	</div>
	<span><?php echo esc_html( FrmProEddHelper::get_license_type_info() ); ?></span>
	<div class="frm-flex-box frm-gap-xs">
		<?php FrmProEddHelper::show_connect_links(); ?>
	</div>
	<?php FrmProEddHelper::insert_license_form(); ?>
</div>
