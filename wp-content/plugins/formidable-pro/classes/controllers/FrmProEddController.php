<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProEddController extends FrmAddon {

	public $plugin_file;
	public $plugin_name = 'Formidable Pro';
	public $download_id = 93790;
	private $pro_cred_store  = 'frmpro-credentials';
	private $pro_auth_store  = 'frmpro-authorized';
	public $pro_wpmu_store  = 'frmpro-wpmu-sitewide';
	private $pro_wpmu = false;

	public function __construct() {
		$this->version = FrmProDb::$plug_version;
		$this->set_download();

		if ( $this->get_license() && is_multisite() && get_site_option( $this->pro_wpmu_store ) ) {
			$this->pro_wpmu = true;
		}

		global $frm_vars;
		$frm_vars['pro_is_authorized'] = $this->pro_is_authorized();

		parent::__construct();

		if ( is_admin() ) {
			add_action( 'frm_license_error', array( &$this, 'maybe_clear_license' ) );
		}
	}

	public static function load_hooks() {
		// don't use the addons page
	}

	/**
	 * @since 3.0
	 */
	private function set_download() {
		$this->plugin_file = FrmProAppHelper::plugin_path() . '/formidable-pro.php';
	}

	public function set_license( $license ) {
		update_option( $this->pro_cred_store, array( 'license' => $license ) );
	}

	public function get_license() {
		if ( is_multisite() && get_site_option( $this->pro_wpmu_store ) ) {
			$creds = get_site_option( $this->pro_cred_store );
		} else {
			$creds = get_option( $this->pro_cred_store );
		}

		$license = '';
		if ( $creds && is_array( $creds ) && isset( $creds['license'] ) ) {
			$license = $creds['license'];
			if ( strpos( $license, '-' ) ) {
				// this is a fix for licenses saved in the past
				$license = strtoupper( $license );
			}
		}

		if ( empty( $license ) ) {
			$license = $this->activate_defined_license();
		}

		return $license;
	}

	/**
	 * Override the parent method to use the defined license.
	 *
	 * @return string
	 */
	public function get_defined_license() {
		return FrmProEddHelper::get_defined_license();
	}

	public function clear_license() {
		delete_option( $this->pro_cred_store );
		delete_option( $this->pro_auth_store );
		delete_site_option( $this->pro_cred_store );
		delete_site_option( $this->pro_auth_store );
		parent::clear_license();
	}

	public function set_active( $is_active ) {
		$is_active = ( $is_active == 'valid' );
		$creds     = $this->get_pro_cred_form_vals();

		if ( is_multisite() ) {
			update_site_option( $this->pro_wpmu_store, $creds['wpmu'] );
		}

		if ( $creds['wpmu'] ) {
			update_site_option( $this->pro_cred_store, $creds );
			update_site_option( $this->pro_auth_store, $is_active );
		} else {
			update_option( $this->pro_auth_store, $is_active );
		}

		/**
		 * Update stylesheet to make sure pro css is included.
		 * This will be incomplete if there are add-ons installed because a lot of hooks do not get added
		 * when Pro is not active on load.
		 * As of v6.8, the stylesheet is re-generated again with an AJAX action in a separate request.
		 * This is mostly still here for backward compatibility. If Lite is not up to date, the frm_after_authorize hook
		 * will never get called.
		 */
		$frm_style = new FrmStyle();
		$frm_style->update( 'default' );

		parent::set_active( $is_active );

		// The child class crease the option we don't need.
		delete_option( $this->option_name . 'active' );
	}

	public function get_pro_cred_form_vals() {
		$license = isset( $_POST['license'] ) ? sanitize_text_field( $_POST['license'] ) : $this->get_license();
		$wpmu = isset( $_POST['wpmu'] ) ? absint( $_POST['wpmu'] ) : $this->pro_wpmu;

		return compact( 'license', 'wpmu' );
	}

	public function pro_is_authorized() {
		$license = $this->get_license();
		if ( empty( $license ) ) {
			return false;
		}

		if ( is_multisite() && $this->pro_wpmu ) {
			$authorized = get_site_option( $this->pro_auth_store );
		} else {
			$authorized = get_option( $this->pro_auth_store );
		}

		return $authorized;
	}

	public function pro_is_installed_and_authorized() {
		return $this->pro_is_authorized();
	}

	/**
	 * @return void
	 */
	public function pro_cred_form() {
		global $frm_vars;

		$authorized   = $frm_vars['pro_is_authorized'];
		$license_type = FrmProAddonsController::get_readable_license_type();
		?>

<div id="frm_license_top" class="<?php echo esc_attr( $authorized ? 'frm_authorized_box' : 'frm_unauthorized_box' ); ?>">
	<p id="frm-connect-btns" class="frm-show-unauthorized">
		<?php FrmProEddHelper::show_connect_links( 'frm-button-sm' ); ?>
	</p>

	<div class="frm-show-authorized">
		<p><?php echo esc_html( FrmProEddHelper::get_license_type_info( $license_type ) ); ?></p>
		<?php if ( 'Elite' !== $license_type ) { ?>
		<p style="font-size:1.1em">
			To <b>unlock more features</b> consider <a href="<?php echo esc_url( FrmAppHelper::make_affiliate_url( FrmAppHelper::admin_upgrade_link( 'settings-upgrade', 'account/downloads/' ) ) ); ?>">upgrading to the Elite plan</a>.
		</p>
		<?php } ?>
	</div>
	<?php
	$this->display_form();

	FrmProEddHelper::show_disconnect_link();
	if ( ! FrmProEddHelper::get_defined_license() ) {
			?>
		<span class="frm-show-authorized">|</span>
			<?php
	}
	FrmProEddHelper::show_clear_license_cache_link();
	?>
</div>

<div class="frm_pro_license_msg frm_hidden"></div>
<div class="clear"></div>
		<?php
	}

	/**
	 * This is the view for the license form
	 */
	public function display_form() {
		$creds = $this->get_pro_cred_form_vals();
		FrmProEddHelper::insert_license_form( $creds );
		?>
		<p class="frm-show-unauthorized">
			<?php FrmProEddHelper::show_manual_license_link(); ?>
		</p>
		<?php
	}
}
