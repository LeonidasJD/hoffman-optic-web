<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProDashboardController {

	/**
	 * Handle name used for registering controller scripts and style.
	 */
	const ASSETS_HANDLE_NAME = 'formidable-pro-dashboard';

	/**
	 * Get PRO view top counters args: All View and Installed Apps.
	 * Called via FrmDashboardController only.
	 *
	 * @return array
	 */
	public static function get_counters() {
		$counters_value = array(
			'views' => self::get_views_count(),
			'apps'  => FrmProApplication::get_applications_count(),
		);

		$cta_views_link = post_type_exists( 'frm_display' ) ? admin_url( 'edit.php?post_type=frm_display' ) : admin_url( 'admin.php?page=formidable-views' );

		return array(
			FrmDashboardController::view_args_build_counter(
				__( 'All Views', 'formidable-pro' ),
				FrmDashboardController::view_args_build_cta(
					__( 'Learn More', 'formidable-pro' ),
					$cta_views_link,
					FrmDashboardController::display_counter_cta( 'views', $counters_value['views'] )
				),
				self::get_views_count()
			),
			FrmDashboardController::view_args_build_counter(
				__( 'Installed Apps', 'formidable-pro' ),
				FrmDashboardController::view_args_build_cta(
					__( 'Learn More', 'formidable-pro' ),
					admin_url( 'admin.php?page=formidable-applications' ),
					FrmDashboardController::display_counter_cta( 'applications', $counters_value['apps'] )
				),
				FrmProApplication::get_applications_count()
			),
		);
	}

	/**
	 * Get the "Views" count
	 *
	 * @return int
	 */
	private static function get_views_count() {
		if ( is_callable( 'FrmViewsDisplay::get_views_count' ) ) {
			return FrmViewsDisplay::get_views_count();
		}
		$views_count = wp_count_posts( 'frm_display' );
		if ( ! isset( $views_count->private ) || ! isset( $views_count->publish ) ) {
			return 0;
		}
		return $views_count->private + $views_count->publish;
	}

	/**
	 * Load controller assets.
	 *
	 * @return void
	 */
	public static function init() {
		self::register_assets();
		self::enqueue_assets();

		add_filter( 'frm_license_type_text', 'FrmProEddHelper::get_license_type_info' );
	}

	/**
	 * Register the controller assets.
	 *
	 * @return void
	 */
	private static function register_assets() {
		$version = FrmProDb::$plug_version;
		wp_register_script( self::ASSETS_HANDLE_NAME, FrmProAppHelper::plugin_url() . '/js/dashboard.js', array( 'formidable_admin', 'formidable-dashboard' ), $version, true );
		wp_register_style( self::ASSETS_HANDLE_NAME, FrmProAppHelper::plugin_url() . '/css/admin/dashboardpro.css', array(), $version );
	}

	/**
	 * Init inline script.
	 *
	 * @return void
	 */
	private static function init_inline_script() {
		$inline_script = 'var frmDashboardProOptions=' . wp_json_encode(
			array(
				'upgrade' => esc_url( FrmAppHelper::admin_upgrade_link( 'license-modal', 'account/downloads' ) ),
			)
		);
		wp_add_inline_script( self::ASSETS_HANDLE_NAME, $inline_script, 'before' );
	}

	/**
	 * Enqueue controller assets.
	 *
	 * @return void
	 */
	private static function enqueue_assets() {
		$is_dashboard_page = is_callable( 'FrmDashboardController::is_dashboard_page' ) && FrmDashboardController::is_dashboard_page();
		if ( ! $is_dashboard_page ) {
			return;
		}

		wp_enqueue_style( self::ASSETS_HANDLE_NAME );
		wp_enqueue_script( self::ASSETS_HANDLE_NAME );

		self::init_inline_script();
	}
}
