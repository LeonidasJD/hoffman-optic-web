<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProEddHelper {

	public static $plugin_slug = 'formidable_pro';

	public static function get_defined_license() {
		return defined( 'FRM_PRO_LICENSE' ) ? FRM_PRO_LICENSE : false;
	}

	/**
	 * @since 6.8
	 *
	 * @return boolean
	 */
	public static function is_authorized() {
		global $frm_vars;
		return $frm_vars['pro_is_authorized'];
	}

	/**
	 * @since 6.8
	 *
	 * @return void
	 */
	public static function show_connect_links( $button_classes = '' ) {
		$buttons = self::get_action_for_license();
		if ( is_callable( 'FrmDashboardHelper::show_connect_links' ) ) {
			FrmDashboardHelper::show_connect_links( $buttons, $button_classes );
			return;
		}

		// Fallback for older versions of Formidable Lite. Remove this by 2024-03-01.
		foreach ( $buttons as $button ) {
			$add_classes = isset( $button['classes'] ) ? ' ' . $button['classes'] : ' frm-button-secondary';
			?>
			<a href="<?php echo esc_url( $button['link'] ); ?>" target="_blank"
				class="<?php echo esc_attr( $button_classes . $add_classes ); ?>"
				>
				<?php echo esc_html( $button['label'] ); ?>
			</a>
			<?php
		}
	}

	/**
	 * @since 6.8
	 *
	 * @return array
	 */
	private static function get_action_for_license() {
		$buttons = array();
		if ( is_callable( 'FrmDashboardHelper::get_license_buttons' ) ) {
			$buttons = FrmDashboardHelper::get_license_buttons();
		} else {
			// Fallback for older versions of Formidable Lite.
			$buttons[] = array(
				'label'   => __( 'Connect Account', 'formidable' ),
				'link'    => FrmAddonsController::connect_link(),
				'classes' => 'frm-button-primary frm-show-unauthorized',
			);
		}

		$license_type = strtolower( FrmProAddonsController::get_readable_license_type() );
		if ( 'lite' === $license_type ) {
			return $buttons;
		}

		$license_status = FrmProAddonsController::get_license_status();
		$upgrade_renew  = FrmAppHelper::admin_upgrade_link( 'settings-upgrade', 'account/downloads/' );
		if ( $license_status === 'expired' ) {
			$buttons[] = array(
				'label'   => __( 'Renew Now', 'formidable-pro' ),
				'link'    => $upgrade_renew,
				'classes' => 'frm-button-primary',
			);
		}

		if ( 'elite' !== $license_type ) {
			$buttons[] = array(
				'label' => __( 'Upgrade Account', 'formidable-pro' ),
				'link'  => $upgrade_renew,
			);
		}

		return $buttons;
	}

	/**
	 * @since 6.8
	 *
	 * @param string $license_type The saved license type label.
	 *
	 * @return string
	 */
	public static function get_license_type_info( $license_type = '' ) {
		if ( empty( $license_type ) ) {
			$license_type = FrmProAddonsController::get_readable_license_type();
		}

		if ( $license_type ) {
			return 'You\'re using Formidable Forms ' . esc_html( $license_type ) . '. Enjoy! 🙂';
		}
		return '';
	}

	/**
	 * @since 6.8
	 *
	 * @return void
	 */
	public static function show_disconnect_link() {
		$config_license = self::get_defined_license();
		if ( $config_license ) {
			// Don't show disconnect link if license is defined in wp-config.php.
			return;
		}

		?>
		<a href="#" id="frm_deauthorize_link" class="frm-show-authorized" data-plugin="<?php echo esc_attr( self::$plugin_slug ); ?>">
			<?php esc_html_e( 'Disconnect site', 'formidable-pro' ); ?>
		</a>
		<?php
	}

	/**
	 * @since 6.8
	 *
	 * @return void
	 */
	public static function show_clear_license_cache_link() {
		$data_attr_refresh = '';
		if ( is_callable( 'FrmDashboardController::is_dashboard_page' ) && FrmDashboardController::is_dashboard_page() ) {
			$data_attr_refresh = 'data-refresh=1';
		}
		?>
		<a href="#" id="frm_reconnect_link" <?php echo esc_attr( $data_attr_refresh ); ?> class="frm-show-authorized">
			<?php esc_html_e( 'Check for a recent purchase', 'formidable-pro' ); ?>
		</a>
		<?php
	}

	/**
	 * @since 6.8
	 *
	 * @param array|string $creds
	 *
	 * @return void
	 */
	public static function insert_license_form( $creds = 'auto' ) {
		if ( self::is_authorized() ) {
			$placeholder = __( 'Verify a different license key', 'formidable-pro' );
		} else {
			$placeholder = __( 'Enter your license key here', 'formidable-pro' );
		}

		if ( $creds === 'auto' ) {
			$license_updater = FrmProAppHelper::get_updater();
			$creds           = $license_updater->get_pro_cred_form_vals();
		}

		?>
		<div id="pro_cred_form" class="frm_grid_container frm-show-unauthorized frm_hidden">
			<p class="frm9 frm_form_field frm-license-input">
				<input type="text" name="proplug-license" value="" placeholder="<?php echo esc_attr( $placeholder ); ?>" id="edd_<?php echo esc_attr( self::$plugin_slug ); ?>_license_key" />
				<span class="frm-show-authorized">
					<?php esc_html_e( 'License is active', 'formidable-pro' ); ?>
					<?php FrmProAppHelper::icon_by_class( 'frm_icon_font frm_check1_icon' ); ?>
				</span>
			</p>
			<p class="frm3 frm_form_field">
				<button class="frm-button-secondary frm_authorize_link" data-plugin="<?php echo esc_attr( self::$plugin_slug ); ?>" type="button">
					<?php esc_attr_e( 'Save License', 'formidable-pro' ); ?>
				</button>
			</p>
			<?php
			if ( is_multisite() ) {
				?>
				<p class="frm12 frm_form_field">
					<label for="proplug-wpmu">
						<input type="checkbox" value="1" name="proplug-wpmu" id="proplug-wpmu" <?php checked( $creds['wpmu'], 1 ); ?> />
						<?php esc_html_e( 'Use this license to enable Formidable Pro site-wide', 'formidable-pro' ); ?>
					</label>
				</p>
			<?php } ?>
		</div>
		<?php
	}

	public static function show_manual_license_link() {
		?>
		<a href="#" id="frm-manual-key" class="frm-dashboard-open-license-modal" data-frmhide="#frm-manual-key" data-frmshow="#pro_cred_form">
			<?php esc_html_e( 'Add a license manually', 'formidable-pro' ); ?>
		</a>
		<?php
	}
}
