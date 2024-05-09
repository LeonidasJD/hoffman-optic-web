<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * Helper class for the Pro Reports page.
 *
 * @since 6.6
 */
class FrmProReportsHelper {

	/**
	 * Show the graphs on the form's Reports page
	 *
	 * @since 2.02.05
	 * @since 6.6 Moved from FrmProGraphsController.
	 *
	 * @return void
	 */
	public static function show_reports() {
		global $wpdb;

		self::try_to_extend_server_timeout();

		add_filter( 'frm_form_stop_action_reports', '__return_true' );
		FrmAppHelper::permission_check( 'frm_view_reports' );

		$form = self::get_form_for_reports();

		if ( ! $form ) {
			require FrmProAppHelper::plugin_path() . '/classes/views/frmpro-statistics/select.php';
			return;
		}

		$date_range = self::get_start_and_end_dates();
		$start_date = $date_range['start_date'];
		$end_date   = $date_range['end_date'];

		$entry_status_options = self::get_entry_statuses( $form->id );
		$selected_status      = self::get_selected_status( $entry_status_options );

		$entries = self::get_entry_count( $form );

		$selected_date_range = FrmAppHelper::simple_get( 'date_range' );

		if ( empty( $entries ) ) {
			$fields = array();
			include FrmProAppHelper::plugin_path() . '/classes/views/frmpro-statistics/show.php';
			return;
		}

		$fields = self::get_fields_for_reports( $form->id );
		$data   = self::generate_graphs_for_reports( $form, $fields );

		foreach ( $fields as $field ) {
			if ( ! isset( $data[ $field->id ] ) ) {
				continue;
			}

			if ( 'user_id' === $field->type ) {
				$user_ids           = FrmDb::get_col( $wpdb->users, array(), 'ID', 'display_name ASC' );
				$submitted_user_ids = FrmEntryMeta::get_entry_metas_for_field( $field->id, '', '', array( 'unique' => true ) );
				break;
			}
		}

		include FrmProAppHelper::plugin_path() . '/classes/views/frmpro-statistics/show.php';
	}

	/**
	 * Make sure a slow report doesn't fail because of a timeout issue.
	 *
	 * @return void
	 */
	private static function try_to_extend_server_timeout() {
		if ( function_exists( 'set_time_limit' ) ) {
			set_time_limit( 0 );
		}
	}

	/**
	 * Get the form for the reports
	 *
	 * @since 6.6
	 *
	 * @return bool|object
	 */
	private static function get_form_for_reports() {
		$form = FrmAppHelper::get_simple_request(
			array(
				'param'   => 'form',
				'default' => false,
				'type'    => 'request',
			)
		);

		if ( $form ) {
			$form = FrmForm::getOne( $form );
		}

		return $form;
	}

	/**
	 * Get all fields for the reports page
	 *
	 * @since 2.02.05
	 * @since 6.6 Moved from FrmProGraphsController.
	 *
	 * @param int $form_id
	 * @return mixed
	 */
	private static function get_fields_for_reports( $form_id ) {
		$exclude_types = FrmField::no_save_fields();
		$exclude_types = array_merge(
			$exclude_types,
			array( 'file', 'grid', 'password', 'credit_card', 'address', 'signature', 'form', 'table', 'name' )
		);

		$fields = FrmField::getAll(
			array(
				'fi.form_id'  => $form_id,
				'fi.type not' => $exclude_types,
			),
			'field_order'
		);

		/**
		 * Allows changing fields in the Reports page.
		 *
		 * @since 5.0
		 *
		 * @param array $fields Array of fields.
		 * @param array $args   The arguments. Contains `$args`.
		 */
		return apply_filters( 'frm_fields_in_reports', $fields, compact( 'form_id' ) );
	}

	/**
	 * Generate the graphs for the Reports page
	 *
	 * @since 2.02.05
	 * @since 6.6 Moved from FrmProGraphsController and made public.
	 *
	 * @param object $form
	 * @param array $fields
	 * @return array
	 */
	private static function generate_graphs_for_reports( $form, $fields ) {
		$data = self::get_time_span_reports( $form );
		self::add_field_graphs_for_reports( $fields, $data );

		return $data;
	}

	/**
	 * Add all the field graphs for the reports page
	 *
	 * @since 2.02.05
	 * @since 6.6 Moved from FrmProGraphsController.
	 * @param array $fields
	 * @param array $data
	 */
	private static function add_field_graphs_for_reports( $fields, &$data ) {
		$atts = array(
			'y_min'          => 0,
			'width'          => '100%',
			'height'         => 'auto',
			'bg_color'       => 'transparent',
			'title'          => '',
			'chart_area'     => 'top:30;height:90%',
			'x_slanted_text' => 1,
			'x_order'        => 'field_opts',
			'include_zero'   => 1,
			'x_grid_color'   => '#fff',
			'colors'         => '#3177c7',
			'pagesize'       => 10,
			'sort_column'    => 1,
			'sort_ascending' => false,
		);
		self::add_report_page_filters( $atts );

		$table_types = FrmProGraphsController::table_graph_types();
		$add_table   = self::get_field_types_to_use_in_tables();

		foreach ( $fields as $field ) {
			$atts['id'] = $field->id;

			if ( $field->type === 'user_id' ) {
				$atts['height'] = '400';
				$atts['type']   = 'pie';
			} elseif ( in_array( $field->type, $table_types, true ) ) {
				$atts['type']   = 'table';
				$atts['height'] = 'auto';
			} else {
				$atts['type']   = 'column';
				$atts['height'] = '400';
			}

			if ( in_array( $field->type, array( 'radio', 'checkbox', 'select' ), true ) ) {
				$atts['x_order'] = 'field_opts';
			} elseif ( isset( $atts['x_order'] ) ) {
				unset( $atts['x_order'] );
			}

			if ( $field->type === 'scale' ) {
				$atts['x_min'] = FrmField::get_option( $field, 'minnum' ) - 1;
				$atts['x_max'] = FrmField::get_option( $field, 'maxnum' ) + 1;
			} elseif ( isset( $atts['x_min'] ) ) {
				unset( $atts['x_min'], $atts['x_max'] );
			}

			$this_data = FrmProGraphsController::graph_shortcode( $atts );

			if ( strpos( $this_data, 'frm_no_data_graph' ) === false ) {
				$data[ $field->id ] = $this_data;

				if ( in_array( $field->type, $add_table, true ) ) {
					$atts['type']                  = 'table';
					$atts['height']                = 'auto';
					$this_data                     = FrmProGraphsController::graph_shortcode( $atts );
					$data[ $field->id . '_table' ] = $this_data;
				}
			}
		}
	}

	/**
	 * @since 6.8.3
	 *
	 * @return array
	 */
	private static function get_field_types_to_use_in_tables() {
		$types = array( 'radio', 'checkbox', 'select' );

		/**
		 * @param array $types
		 */
		$filtered_types = apply_filters( 'frm_reports_page_table_types', $types );

		if ( is_array( $filtered_types ) ) {
			$types = $filtered_types;
		} else {
			_doing_it_wrong( __METHOD__, esc_html__( 'Field types to use in tables should be an array.', 'formidable-pro' ), '6.8.3' );
		}

		return $types;
	}

	/**
	 * Get a list of boxes to list with the graph on the reports page.
	 *
	 * @since 5.0.02
	 * @since 6.6 Moved from FrmProGraphsController.
	 * @used-by show.php
	 */
	public static function get_field_boxes( $args ) {
		$filter_atts = array();
		self::add_report_page_filters( $filter_atts );

		$field = $args['field'];
		$atts  = $filter_atts + array(
			'id'   => $field->id,
			'type' => 'count',
		);

		$total = FrmProStatisticsController::stats_shortcode( $atts );

		if ( ! $total ) {
			return array();
		}

		$post_boxes = array(
			array(
				'label' => __( 'Answered', 'formidable-pro' ),
				'stat'  => $total . ' (' . round( ( $total / count( $args['entries'] ) ) * 100, 2 ) . '%)',
			),
		);

		$args['filter_atts'] = $filter_atts;
		self::add_average_box( $args, $post_boxes );

		return apply_filters( 'frm_pro_reports_boxes', $post_boxes, $args );
	}

	/**
	 * Add the Average and Median reports for some field types.
	 *
	 * @since 5.0.02
	 * @since 6.6 Moved from FrmProGraphsController.
	 *
	 * @param array $args
	 * @param array $post_boxes
	 * @return void
	 */
	private static function add_average_box( $args, &$post_boxes ) {
		$field = $args['field'];
		if ( ! self::should_show_average_boxes_for_field_type( $field, $args ) ) {
			return;
		}

		$post_boxes[] = array(
			'label' => __( 'Average', 'formidable-pro' ),
			'stat'  => FrmProStatisticsController::stats_shortcode(
				$args['filter_atts'] + array(
					'id'   => $field->id,
					'type' => 'average',
				)
			),
		);

		$post_boxes[] = array(
			'label' => __( 'Median', 'formidable-pro' ),
			'stat'  => FrmProStatisticsController::stats_shortcode(
				$args['filter_atts'] + array(
					'id'   => $field->id,
					'type' => 'median',
				)
			),
		);
	}

	/**
	 * Some numeric field types will also include "Average" and "Median" boxes
	 * on the reports page.
	 *
	 * @since 6.8.4
	 *
	 * @param object $field
	 * @param array  $args
	 * @return bool
	 */
	private static function should_show_average_boxes_for_field_type( $field, $args ) {
		if ( in_array( $field->type, array( 'number', 'scale' ), true ) ) {
			// These types should always have numeric data.
			return true;
		}

		if ( 'hidden' === $field->type ) {
			// Confirm the hidden field type is all numeric data.
			return ! FrmProStatisticsController::has_non_numeric_values( $field, $args['filter_atts'] );
		}

		return false;
	}

	/**
	 * Get the deaily and monthly graphs for the reports page.
	 *
	 * @since 6.6
	 * @param object $form
	 * @return array
	 */
	private static function get_time_span_reports( $form ) {
		$common_atts = array(
			'form'       => $form->id,
			'type'       => 'line',
			'bg_color'   => 'transparent',
			'width'      => '100%',
			'y_min'      => 0,
			'title'      => '',
			'chart_area' => 'top:30;height:90%',
			'colors'     => '#3177c7',
		);
		self::add_status_filter( $common_atts );

		return array(
			'time'  => self::add_daily_graph( $common_atts ),
			'month' => self::add_monthly_graph( $common_atts ),
		);
	}

	/**
	 * Add the daily graph to the reports page.
	 *
	 * @since 6.6
	 * @param array $atts
	 * @return string
	 */
	private static function add_daily_graph( $atts ) {
		$date_range = self::get_start_and_end_dates();

		$atts['created_at_greater_than'] = '-1 month';
		if ( ! empty( $date_range['start_date'] ) ) {
			$atts['created_at_greater_than'] = $date_range['start_date'];
			$atts['created_at_less_than']    = $date_range['end_date'];
		}

		return FrmProGraphsController::graph_shortcode( $atts );
	}

	/**
	 * Add the monthly graph to the reports page.
	 *
	 * @since 6.6
	 * @param array $atts
	 * @return string
	 */
	private static function add_monthly_graph( $atts ) {
		$date_range   = self::get_start_and_end_dates();

		$atts['created_at_greater_than'] = '-1 year';
		$atts['created_at_less_than']    = '+1 month';
		$atts['group_by']                = 'month';

		if ( ! empty( $date_range['end_date'] ) ) {
			$atts['created_at_less_than']    = $date_range['end_date'];
			$atts['created_at_greater_than'] = $date_range['start_date'];

			// Show a minimum of 3 months.
			$current_length = strtotime( $date_range['end_date'] ) - strtotime( $date_range['start_date'] );
			$minimum_length = MONTH_IN_SECONDS * 3;
			if ( $current_length < $minimum_length ) {
				$atts['created_at_greater_than'] = gmdate( 'Y-m-d', strtotime( $date_range['end_date'] ) - $minimum_length );
			}
		}

		return FrmProGraphsController::graph_shortcode( $atts );
	}

	/**
	 * Get the form status options.
	 *
	 * @since 6.6
	 *
	 * @param int $form_id
	 * @return array
	 */
	private static function get_entry_statuses( $form_id ) {
		if ( ! is_callable( 'FrmEntriesHelper::get_entry_statuses' ) ) {
			return array();
		}

		$entry_statuses = array( '' => __( 'All', 'formidable-pro' ) ) + FrmEntriesHelper::get_entry_statuses();
		self::maybe_remove_extra_statuses( $form_id, $entry_statuses );

		return $entry_statuses;
	}

	/**
	 * If abandonment is not enabled, remove the in progress and abandoned statuses.
	 *
	 * @since 6.6
	 *
	 * @param int   $form_id
	 * @param array $statuses
	 * @return void
	 */
	private static function maybe_remove_extra_statuses( $form_id, &$statuses ) {
		if ( count( $statuses ) < 4 ) {
			// Bail early if there are only 3 statuses.
			return;
		}

		$form_uses_abandonment = is_callable( 'FrmAbandonmentAppHelper::is_abandonment_enabled' ) && FrmAbandonmentAppHelper::is_abandonment_enabled( $form_id );
		if ( $form_uses_abandonment ) {
			return;
		}

		$remove   = array(
			FrmAbandonmentAppHelper::IN_PROGRESS_ENTRY_STATUS,
			FrmAbandonmentAppHelper::ABANDONED_ENTRY_STATUS,
		);
		$statuses = array_diff_key( $statuses, array_flip( $remove ) );
	}

	/**
	 * Get the selected entry status on the reports page.
	 * If the selected status is invalid, set it to an empty string.
	 *
	 * @since 6.6
	 * @param array $entry_statuses
	 * @return int|string
	 */
	private static function get_selected_status( $entry_statuses ) {
		$selected_status = FrmAppHelper::simple_get( 'entry_status' );
		if ( '' !== $selected_status ) {
			$selected_status = (int) $selected_status;
		}

		// Make sure the selected status is valid.
		if ( ! isset( $entry_statuses[ $selected_status ] ) ) {
			$selected_status      = '';
			$_GET['entry_status'] = '';
		}
		return $selected_status;
	}

	/**
	 * Get a list of relative date options.
	 *
	 * @used-by show.php
	 * @since 6.2
	 *
	 * @return array
	 */
	public static function get_date_range_options() {
		return array(
			'all_time'         => __( 'All Time', 'formidable-pro' ),
			'custom'           => __( 'Custom', 'formidable-pro' ),
			'today'            => __( 'Today', 'formidable-pro' ),
			'yesterday'        => __( 'Yesterday', 'formidable-pro' ),
			'this_week'        => __( 'This week', 'formidable-pro' ),
			'last_week'        => __( 'Last week', 'formidable-pro' ),
			'last_thirty_days' => __( 'Last 30 days', 'formidable-pro' ),
			'last_month'       => __( 'Last month', 'formidable-pro' ),
			'this_quarter'     => __( 'This quarter', 'formidable-pro' ),
			'last_quarter'     => __( 'Last quarter', 'formidable-pro' ),
			'this_year'        => __( 'This year', 'formidable-pro' ),
			'last_year'        => __( 'Last year', 'formidable-pro' ),
		);
	}

	/**
	 * Get start and end dates from relative date queries.
	 * Used on reports page.
	 *
	 * @since 6.6
	 *
	 * @return array
	 */
	private static function get_start_and_end_dates() {
		$start_date = '';
		$end_date   = '';
		$date_range = FrmAppHelper::simple_get( 'date_range' );

		if ( empty( $date_range ) ) {
			return compact( 'start_date', 'end_date' );
		}

		$start_date_format = 'Y-m-d 00:00:00';
		$end_date_format   = 'Y-m-d 23:59:59';

		switch ( $date_range ) {
			case 'custom':
				$start_date = FrmAppHelper::simple_get( 'start_date' );
				$end_date   = FrmAppHelper::simple_get( 'end_date' );

				$start_date = $start_date ? gmdate( $start_date_format, strtotime( $start_date ) ) : '';
				$end_date   = $end_date ? gmdate( $end_date_format, strtotime( $end_date ) ) : '';
				break;
			case 'today':
				$start_date = gmdate( $start_date_format );
				$end_date   = gmdate( $end_date_format );
				break;
			case 'yesterday':
				$start_date = gmdate( $start_date_format, strtotime( $date_range ) );
				$end_date   = gmdate( $end_date_format, strtotime( $date_range ) );
				break;
			case 'this_week':
			case 'last_week':
				$date_range = str_replace( '_', ' ', $date_range );
				$start_date = gmdate( $start_date_format, strtotime( 'monday ' . $date_range ) );
				$end_date   = gmdate( $end_date_format, strtotime( 'sunday ' . $date_range ) );
				break;
			case 'last_thirty_days':
				$start_date = gmdate( $start_date_format, strtotime( 'last month' ) );
				$end_date   = gmdate( $end_date_format );
				break;
			case 'last_month':
				$start_date = gmdate( $start_date_format, strtotime( 'first day of last month' ) );
				$end_date   = gmdate( $end_date_format, strtotime( 'last day of last month' ) );
				break;
			case 'this_quarter':
				$current_quarter = ceil( gmdate( 'n' ) / 3 );
				$start_date      = gmdate( $start_date_format, strtotime( gmdate( 'Y' ) . '-' . ( ( $current_quarter * 3 ) - 2 ) . '-1' ) );
				$end_date        = gmdate( 'Y-m-t 23:59:59', strtotime( gmdate( 'Y' ) . '-' . ( ( $current_quarter * 3 ) ) . '-1' ) );
				break;
			case 'last_quarter':
				$start_date = gmdate( $start_date_format, strtotime( 'first day of -' . ( ( ( gmdate( 'n' ) - 1 ) % 3 ) + 3 ) . ' month' ) );
				$end_date   = gmdate( $end_date_format, strtotime( 'last day of -' . ( ( ( gmdate( 'n' ) - 1 ) % 3 ) + 1 ) . ' month' ) );
				break;
			case 'this_year':
			case 'last_year':
				$period     = str_replace( '_', ' ', $date_range );
				$start_date = gmdate( $start_date_format, strtotime( 'first day of january ' . $period ) );
				$end_date   = gmdate( $end_date_format, strtotime( 'last day of december ' . $period ) );
				break;
			default:
		}

		return compact( 'start_date', 'end_date' );
	}

	/**
	 * Get the filtered number of entries for the reports page.
	 *
	 * @since 6.6
	 *
	 * @param object $form
	 * @return array
	 */
	private static function get_entry_count( $form ) {
		$args = array( 'form_id' => $form->id );

		$date_range = self::get_start_and_end_dates();
		if ( $date_range['start_date'] !== '' ) {
			$args['created_at >'] = get_gmt_from_date( $date_range['start_date'] );
		}

		if ( $date_range['end_date'] !== '' ) {
			$args['created_at <'] = get_gmt_from_date( $date_range['end_date'] );
		}

		self::add_status_filter( $args );
		if ( is_numeric( $args['drafts'] ) ) {
			$args['is_draft'] = $args['drafts'];
		}
		unset( $args['drafts'] );

		return FrmDb::get_col( 'frm_items', $args, 'created_at' );
	}

	/**
	 * Adds different filters to meta query.
	 * Used on reports page.
	 *
	 * @since 6.6
	 *
	 * @param array $args
	 * @return void
	 */
	private static function add_report_page_filters( &$args ) {
		self::maybe_add_date_filter( $args );
		self::add_status_filter( $args );
	}

	/**
	 * Adds date filters to argument, converting them to gmt.
	 * Used on reports page.
	 *
	 * @since 6.6
	 *
	 * @param array $args
	 * @return void
	 */
	private static function maybe_add_date_filter( &$args ) {
		$start_end_dates = self::get_start_and_end_dates();

		if ( $start_end_dates['start_date'] ) {
			$args['created_at_greater_than'] = get_gmt_from_date( $start_end_dates['start_date'] );
		}

		if ( $start_end_dates['end_date'] ) {
			$args['created_at_less_than'] = get_gmt_from_date( $start_end_dates['end_date'] );
		}
	}

	/**
	 * Adds entry status filter to meta args.
	 * Used on reports page.
	 *
	 * @since 6.6
	 *
	 * @param array $args
	 * @return void
	 */
	private static function add_status_filter( &$args ) {
		$entry_status   = FrmAppHelper::simple_get( 'entry_status' );
		$args['drafts'] = is_numeric( $entry_status ) ? $entry_status : 'both';
	}
}
