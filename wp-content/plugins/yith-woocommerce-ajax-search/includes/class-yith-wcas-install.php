<?php
/**
 * Class that install the plugin tables
 *
 * @package YITH/Search
 * @author  YITH
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange

if ( ! class_exists( 'YITH_WCAS_Install' ) ) {
	/**
	 * The class that init the db
	 */
	class YITH_WCAS_Install {

		const YITH_WCAS_DB_VERSION = '1.53';

		/**
		 * The function that init the configuration
		 *
		 * @author YITH
		 * @since  2.0.0
		 */
		public static function init() {
			self::check_version();
			self::initialize_table_name();
			self::install_tables();

			add_action( 'init', array( __CLASS__, 'first_indexing' ) );
		}

		/**
		 * Define new table name on wpbd
		 *
		 * @author YITH
		 * @since  2.0.0
		 */
		protected static function initialize_table_name() {
			global $wpdb;

			$wpdb->yith_wcas_data_index_lookup  = $wpdb->prefix . 'yith_wcas_data_index_lookup';
			$wpdb->yith_wcas_taxonomy_lookup    = $wpdb->prefix . 'yith_wcas_taxonomy_lookup';
			$wpdb->yith_wcas_index_token        = $wpdb->prefix . 'yith_wcas_index_token';
			$wpdb->yith_wcas_index_relationship = $wpdb->prefix . 'yith_wcas_index_relationship';
			$wpdb->yith_wcas_query_log          = $wpdb->prefix . 'yith_wcas_query_log';
		}

		/**
		 *
		 * /**
		 * Create the plugin tables
		 *
		 * @return void
		 * @since  1.0.0
		 * @author YITH
		 */
		protected static function install_tables() {
			$current_db_version = get_option( 'yith_wcas_db_version' );

			if ( version_compare( $current_db_version, self::YITH_WCAS_DB_VERSION, '>=' ) ) {
				return;
			}

			// assure dbDelta function is defined.
			if ( ! function_exists( 'dbDelta' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}
			global $wpdb;
			// retrieve table charset.
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $wpdb->yith_wcas_data_index_lookup (
				post_id bigint(20) NOT NULL,
				name varchar(255) NOT NULL DEFAULT '',
				description text NOT NULL DEFAULT '',
				summary text NOT NULL DEFAULT '',
				url varchar(255) NOT NULL DEFAULT '',
				sku varchar(100) NOT NULL DEFAULT '',
				thumbnail text NOT NULL DEFAULT '',
				min_price decimal(19,4) NOT NULL DEFAULT 0.0,
				max_price decimal(19,4) NOT NULL DEFAULT 0.0,
				onsale tinyint(1) NOT NULL DEFAULT 0,
				instock tinyint(1) NOT NULL DEFAULT 0,
				stock_quantity double NOT NULL DEFAULT 0,
				is_purchasable tinyint(1) NOT NULL DEFAULT 1,
				rating_count bigint(20) DEFAULT 0,
				average_rating decimal(3.2) DEFAULT 0.00,
				total_sales bigint(20)  DEFAULT 0,
				post_type varchar(20) NOT NULL,
				post_parent bigint(20) DEFAULT 0,
				product_type varchar(20) DEFAULT '',
				parent_category varchar(255) DEFAULT '',
				tags mediumtext DEFAULT '',
				lang varchar(20) NOT NULL DEFAULT '',
				featured tinyint(1) NOT NULL DEFAULT 0,
				custom_fields mediumtext DEFAULT '',
				custom_taxonomies mediumtext DEFAULT '',
				boost decimal(4,2) NOT NULL DEFAULT 0
                )ENGINE=InnoDB $charset_collate;";

			dbDelta( $sql );

			$sql = "CREATE TABLE $wpdb->yith_wcas_index_relationship (
				token_id bigint(20) NOT NULL,
				post_id bigint(20) NOT NULL,
				frequency int NOT NULL DEFAULT 0,
				source_type varchar(20),
				position varchar(255),
				PRIMARY KEY (token_id,post_id)
                )ENGINE=InnoDB $charset_collate;";

			dbDelta( $sql );

			$sql = "CREATE TABLE $wpdb->yith_wcas_index_token (
				token_id bigint(20) NOT NULL AUTO_INCREMENT,
				token varchar(255) NOT NULL ,
				frequency int NOT NULL DEFAULT 0,
				doc_frequency int NOT NULL DEFAULT 0,
				lang varchar(20) NOT NULL DEFAULT '',
                PRIMARY KEY (token_id)
                )ENGINE=InnoDB $charset_collate;";

			dbDelta( $sql );

			$sql = "CREATE TABLE $wpdb->yith_wcas_query_log (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				user_id bigint(20) DEFAULT 0,
				query varchar(200) NOT NULL,
				search_date datetime NOT NULL,
				num_results int(11) DEFAULT 0,
				clicked_product bigint(20) DEFAULT 0,
				lang varchar(20) NOT NULL DEFAULT '',
                PRIMARY KEY (id)
                )ENGINE=InnoDB $charset_collate;";

			dbDelta( $sql );

			$wpdb->query( "CREATE INDEX index_post_id ON $wpdb->yith_wcas_data_index_lookup (post_id)" );
			$wpdb->query( "CREATE INDEX index_r_token_id ON $wpdb->yith_wcas_index_relationship (token_id)" );
			$wpdb->query( "CREATE INDEX index_r_post_id ON $wpdb->yith_wcas_index_relationship (post_id)" );
			$wpdb->query( "CREATE INDEX index_token_lang_freq_desc ON $wpdb->yith_wcas_index_token (token,lang,frequency DESC)" );
			$wpdb->query( "CREATE INDEX index_query ON $wpdb->yith_wcas_query_log (query)" );
			$wpdb->query( "CREATE INDEX index_query_lang_search_date ON $wpdb->yith_wcas_query_log (query,lang,search_date DESC)" );

			$token_index = $wpdb->get_row( "SHOW INDEX FROM {$wpdb->yith_wcas_index_token} WHERE column_name = 'token' and Key_name = 'token'" );
			$primary_key = $wpdb->get_row( "SELECT constraint_name FROM information_schema.table_constraints WHERE  table_name = '{$wpdb->yith_wcas_index_relationship}' AND constraint_name = 'PRIMARY'" );

			if ( ! is_null( $token_index ) ) {
				$wpdb->query( "ALTER TABLE {$wpdb->yith_wcas_index_token} DROP INDEX token;" );
				update_option( 'ywcas_first_indexing', 'no' );
			}

			if ( is_null( $primary_key ) ) {
				$wpdb->query( "ALTER TABLE {$wpdb->yith_wcas_index_relationship} ADD PRIMARY KEY (token_id,post_id)" );
			}

			self::maybe_update_table_fields();
			update_option( 'yith_wcas_db_version', self::YITH_WCAS_DB_VERSION );
		}


		/**
		 * Check if is needed update the lang field
		 *
		 * @return bool
		 */
		protected static function maybe_update_table_fields() {
			global $wpdb;
			$updated = false;

			if ( self::table_has_field_type( $wpdb->yith_wcas_data_index_lookup, 'id', 'bigint(20)' ) ) {
				$wpdb->query( "TRUNCATE TABLE {$wpdb->yith_wcas_data_index_lookup}" );
				$wpdb->query( "ALTER TABLE {$wpdb->yith_wcas_data_index_lookup} DROP PRIMARY KEY" );
				$wpdb->query( "ALTER TABLE {$wpdb->yith_wcas_data_index_lookup} DROP COLUMN id" );
				$wpdb->query( "ALTER TABLE {$wpdb->yith_wcas_data_index_lookup} ADD PRIMARY KEY (post_id)" );
				$updated = true;
			} else {
				$primary_key = $wpdb->get_row( "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE  table_schema = schema() AND column_key = 'PRI' AND table_name = '{$wpdb->yith_wcas_data_index_lookup}' AND column_name = 'post_id'" );
				if ( ! $primary_key ) {
					$wpdb->query( "ALTER TABLE {$wpdb->yith_wcas_data_index_lookup} ADD PRIMARY KEY (post_id)" );
					$updated = true;
				}
			}

			if ( self::table_has_field_type( $wpdb->yith_wcas_data_index_lookup, 'lang', 'varchar(10)' ) ) {
				self::change_field_type( $wpdb->yith_wcas_data_index_lookup, 'lang', 'varchar(20)' );
				$updated = true;
			}

			if ( self::table_has_field_type( $wpdb->yith_wcas_index_token, 'lang', 'varchar(10)' ) ) {
				self::change_field_type( $wpdb->yith_wcas_index_token, 'lang', 'varchar(20)' );
				$updated = true;
			}

			if ( self::table_has_field_type( $wpdb->yith_wcas_query_log, 'lang', 'varchar(10)' ) ) {
				self::change_field_type( $wpdb->yith_wcas_query_log, 'lang', 'varchar(20)' );
				$updated = true;
			}

			if ( $updated && 'yes' === get_option( 'ywcas_first_indexing', 'no' ) ) {
				update_option( 'ywcas_first_indexing', 'no' );
			}

			return $updated;

		}

		/**
		 * Check if a field has specific type
		 *
		 * @param string $table_name The table name.
		 * @param string $field_name The field name.
		 * @param string $field_type The field type.
		 *
		 * @return bool
		 */
		protected static function table_has_field_type( $table_name, $field_name, $field_type ) {
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			global $wpdb;
			$res = $wpdb->get_row( $wpdb->prepare( "SHOW COLUMNS FROM {$table_name} WHERE Field = %s AND type = %s ", $field_name, $field_type ) );
			// phpcs:enable
			return ! is_null( $res );
		}

		/**
		 * Change a field type
		 *
		 * @param string $table_name The table name.
		 * @param string $field_name The field name.
		 * @param string $field_type The new field type.
		 *
		 * @return bool
		 */
		protected static function change_field_type( $table_name, $field_name, $field_type ) {
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery.SchemaChange
			global  $wpdb;
			return $wpdb->query( "ALTER TABLE {$table_name} MODIFY {$field_name} {$field_type}" );
		}
		/**
		 * Check if the plugin is new or is an update.
		 *
		 * @return void
		 */
		protected static function check_version() {
			$ywcas_option_version = get_option( 'yith_wcas_free_option_version', '2.0.0' );
			$check_free_option    = get_option( 'yith_wcas_enable_transient' );
			if ( false !== $check_free_option || version_compare( $ywcas_option_version, '2.0.0', '<' ) ) {
				self::update_to_2_0_0_version();
			}

			if ( false !== $check_free_option || version_compare( $ywcas_option_version, '2.1.0', '<' ) ) {
				self::update_to_2_1_0_version();
			}

			update_option( 'yith_wcas_free_option_version', '2.1.0' );
		}

		/**
		 * Update the options from the oldest version to 2.0.0
		 */
		private static function update_to_2_0_0_version() {
			yith_wcas_save_default_shortcode_options();
			update_option( 'ywcas_updated_to_v2', true );
		}

		/**
		 * Update the options from the oldest version to 2.1.0
		 */
		private static function update_to_2_1_0_version() {
			update_option( 'ywcas_user_switch_to_block', true );
		}

		/**
		 * Start to make the first indexing
		 */
		public static function first_indexing() {
			if ( 'no' === get_option( 'ywcas_first_indexing', 'no' ) ) {
				ywcas()->indexer->process_data();
				update_option( 'ywcas_first_indexing', 'yes' );
			}
		}
	}
}
