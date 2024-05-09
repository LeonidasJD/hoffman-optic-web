<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProDashboardHelper {

	/**
	 * Dashboard - load pro license management template.
	 *
	 * @param array $template
	 *
	 * @return void
	 */
	public static function load_license_management( $template ) {
		$template['license'] = self::view_args_license();
		include FrmProAppHelper::plugin_path() . '/classes/views/dashboard/templates/license-management.php';
	}

	/**
	 * Dashboard - init pro license management view args.
	 *
	 * @return array
	 */
	private static function view_args_license() {
		$license_status = FrmProAddonsController::get_license_status();
		$license_type   = strtolower( FrmProAddonsController::get_readable_license_type() );

		return array(
			'status-tag-classname' => self::get_license_status_tag_classname( $license_type, $license_status ),
			'status-copy'          => self::get_license_status_copy( $license_type, $license_status ),
			// Used for showing and hiding elements in the license box.
			'class'                => FrmProEddHelper::is_authorized() ? 'frm_authorized_box' : 'frm_unauthorized_box',
		);
	}

	/**
	 * Build the license status tag classname based license status.
	 *
	 * @param string $license_type
	 * @param string $license_status
	 *
	 * @return string
	 */
	private static function get_license_status_tag_classname( $license_type, $license_status ) {
		if ( 'lite' === $license_type ) {
			return '';
		}

		switch ( $license_status ) {
			case 'active':
				return 'frm-lt-green-tag';

			case 'expiring':
				return 'frm-orange-tag';

			case 'expired':
			case 'grace':
				return 'frm-red-tag';

			default:
				return '';
		}
	}

	/**
	 * Build the pro license copy based on license type and license status.
	 *
	 * @param string $license_type
	 * @param string $license_status
	 *
	 * @return string
	 */
	private static function get_license_status_copy( $license_type, $license_status ) {
		if ( 'lite' === $license_type ) {
			return '';
		}

		switch ( $license_status ) {
			case 'active':
				return __( 'Active', 'formidable-pro' );
			case 'grace':
			case 'expired':
				return __( 'Expired', 'formidable-pro' );
			case 'expiring':
				$expiring_days = FrmProAddonsController::is_license_expiring();
				if ( false !== $expiring_days ) {
					return 'Expiring soon (' . $expiring_days . ' days)';
				}
				return __( 'Expiring soon', 'formidable-pro' );
			default:
				return '';
		}
	}

	/**
	 * The pro dashboard widget that will show on the bottom.
	 *
	 * @return void
	 */
	public static function get_main_widget( $entries_template ) {
		if ( ! $entries_template['show-placeholder'] ) {
			self::get_chart( $entries_template );
			return;
		}
		self::show_main_or_bottom_widget( $entries_template );
	}

	/**
	 * The pro dashboard widget that will show at the bottom of the page.
	 *
	 * @return void
	 */
	public static function get_bottom_widget( $entries_template ) {
		if ( $entries_template['show-placeholder'] ) {
			self::get_chart( $entries_template );
			return;
		}
		self::show_main_or_bottom_widget( $entries_template );
	}

	/**
	 * Displays entries or chart widget on top or bottom.
	 *
	 * @return void
	 */
	private static function show_main_or_bottom_widget( $entries_template ) {
		if ( is_callable( 'FrmDashboardHelper::load_entries_template' ) ) {
			FrmDashboardHelper::load_entries_template( $entries_template );
			return;
		}
		self::get_chart( $entries_template );
	}

	/**
	 * Dashboard - init chart widget view args.
	 *
	 * @return array
	 */
	private static function view_args_chart() {
		$date_range  = '-6 days';
		$start_date  = gmdate( 'Y-m-d', strtotime( $date_range ) ) . ' 00:00:00';
		$entry_count = FrmDb::get_count(
			'frm_items',
			array(
				'created_at >'   => $start_date,
				'is_draft'       => 0,
				'parent_item_id' => 0,
			)
		);
		return array(
			'widget-heading' => __( 'Weekly Submissions', 'formidable-pro' ),
			'chart-heading'  => sprintf(
				/* translators: %1$s: Number of total weekly form submission */
				_n( '%1$s Form Submission', '%1$s Form Submissions', $entry_count, 'formidable-pro' ),
				'<b>' . esc_attr( $entry_count ) . '</b>'
			),
			'weekly-entries' => $entry_count,
			'chart-data'     => array(
				'form'                    => 'all',
				'type'                    => 'line',
				'y_min'                   => 0,
				'width'                   => '100%',
				'height'                  => '350px',
				'bg_color'                => 'transparent',
				'title'                   => '',
				'chart_area'              => 'width:90%, height:80%',
				'include_zero'            => 1,
				'x_grid_color'            => '#fff',
				'x_slanted_text'          => 0,
				'date_format'             => 'D',
				'colors'                  => '#3177c7',
				'created_at_greater_than' => $start_date,
				// Prevent tomorrow from showing.
				'created_at_less_than'    => gmdate( 'Y-m-d H:i:s', time() ),
			),
			'cta'            => array(
				'label' => __( 'View More Reports', 'formidable-pro' ),
				'link'  => admin_url( 'admin.php?page=formidable&frm_action=reports' ),
			),
			'placeholder'    => array(
				'background' => 'chart-placeholder',
				'heading'    => __( 'Check Your Form Performance', 'formidable-pro' ),
				'copy'       => __( 'See the number of views and submissions daily and improve your forms performance. Once you have at least one entry this week you\'ll see it here', 'formidable-pro' ),
				'button'     => null,
			),
		);
	}

	/**
	 * Load the chart widget template. Load chart container of chart placehorder if there are no entries.
	 *
	 * @param array $entries_template
	 *
	 * @return void
	 */
	private static function get_chart( $entries_template ) {
		$template = self::view_args_chart();
		if ( ! $entries_template['show-placeholder'] && 0 < $template['weekly-entries'] ) {
			self::load_chart_template( $template );
			return;
		}
		self::load_chart_placeholder_template( $template );
	}

	/**
	 * Load chart template
	 *
	 * @param array $template
	 *
	 * @return void
	 */
	private static function load_chart_template( $template ) {
		include FrmProAppHelper::plugin_path() . '/classes/views/dashboard/templates/chart.php';
	}

	/**
	 * Load chart placeholder template
	 *
	 * @param array $template
	 *
	 * @return void
	 */
	private static function load_chart_placeholder_template( $template ) {
		if ( ! is_callable( 'FrmDashboardHelper::load_placeholder_template' ) ) {
			return;
		}

		FrmDashboardHelper::load_placeholder_template( $template );
	}
}
