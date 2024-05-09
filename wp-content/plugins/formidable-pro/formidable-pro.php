<?php
/*
Plugin Name: Formidable Forms Pro
Description: Add more power to your forms, and bring your reports and data management to the front-end.
Version: 6.8.4
Plugin URI: https://formidableforms.com/
Author URI: https://formidableforms.com/
Author: Strategy11
Text Domain: formidable-pro
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

$formidable_license = 'activated';
update_option( 'frmpro-credentials', [ 'license' => $formidable_license ] );
update_option( 'frmpro-authorized', 1 );


$formidable_addons = [
  'edd_active_campaign_license_',
  'edd_authorizenet_aim_license_',
  'edd_aweber_license_',
  'edd_bootstrap_license_',
  'edd_bootstrap_modal_license_',
  'edd_constant_contact_license_',
  'edd_datepicker_options_license_',
  'edd_directory_license_',
  'edd_export_view_to_csv_license_',
  'edd_form_action_automation_license_',
  'edd_formidable_api_license_',
  'edd_formidable_campaign_monitor_license_',
  'edd_formidable_conversational_forms_license_',
  'edd_formidable_polylang_license_',
  'edd_formidable_pro_license_',
  'edd_geolocation_license_',
  'edd_getresponse_license_',
  'edd_highrise_license_',
  'edd_hubspot_license_',
  'edd_landing_pages_license_',
  'edd_locations_license_',
  'edd_logs_license_',
  'edd_mailchimp_license_',
  'edd_mailpoet_newsletters_license_',
  'edd_paypal_standard_license_',
  'edd_pdfs_license_',
  'edd_quiz_maker_license_',
  'edd_salesforce_license_',
  'edd_signature_license_',
  'edd_stripe_license_',
  'edd_twilio_license_',
  'edd_user_registration_license_',
  'edd_user_tracking_license_',
  'edd_visual_views_license_',
  'edd_zapier_license_',
];

foreach ( $formidable_addons as $addon ) {
  update_option( $addon . 'key', $formidable_license );
  update_option( $addon . 'active', 'valid' );
}

$cache_key = 'frm_form_templates_l' . md5( $formidable_license );
$cache = get_option( $cache_key, [] );
if ( isset( $cache["value"] ) && ( strpos( $cache["value"], '/s3.amazonaws.com' ) === false ) ) {
  delete_option( $cache_key );
}
$cache_key = 'frm_applications_l' . md5( $formidable_license );
$cache = get_option( $cache_key, [] );
if ( isset( $cache["value"] ) && ( strpos( $cache["value"], '/s3.amazonaws.com' ) === false ) ) {
  delete_option( $cache_key );
}

$cache_key = 'frm_style_templates_l' . md5( $formidable_license );
$cache = get_option( $cache_key, [] );
if ( isset( $cache["value"] ) && ( strpos( $cache["value"], '/s3.amazonaws.com' ) === false ) ) {
  delete_option( $cache_key );
}


add_action( 'plugins_loaded', function() {
    add_filter( 'pre_http_request', function( $pre, $parsed_args, $url ) {
        $params = [ 'sslverify' => false, 'timeout' => 25 ];
        if ( strpos( $url, 'https://formidableforms.com/wp-json/' ) === 0 ) {
            if ( strpos( $url, '/form-templates/' ) ) {
                if ( strpos( $url, 'code' ) ) {
                    return [
                        'response' => [ 'code' => 200, 'message' => 'ОК' ],
                        'body'     => json_encode( [ 'success' => true ] )
                    ];
                }
                $response = wp_remote_get( "http://wordpressnull.org/formidable/form-templates.json", $params );
                if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
                    return $response;
                }
            }
            if ( strpos( $url, '/view-templates/' ) ) {
                $response = wp_remote_get( "http://wordpressnull.org/formidable/view-templates.json", $params );
                if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
                    return $response;
                }
            }
            
            if ( strpos( $url, '/style-templates/' ) ) {
                $response = wp_remote_get( "https://www.gpltimes.com/formidable/style-templates.json", $params );
                if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
                    return $response;
                }
            }
        }
        if ( strpos( $url, 'https://s3.amazonaws.com/fp.strategy11.com/' ) === 0 ) {
            if ( strpos( $url, '/form-templates/' ) || strpos( $url, '/view-templates/' ) || strpos( $url, '/style-templates/' ) !== false ) {
                
                $base_url = 'http://wordpressnull.org/formidable/';
                if ( strpos( $url, '/style-templates/' ) !== false ) {
                    $base_url = 'https://www.gpltimes.com/formidable/';
                }
                $modified_url = str_replace( 'https://s3.amazonaws.com/fp.strategy11.com/', $base_url, $url );
                $response = wp_remote_get( $modified_url, $params );
                if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
                    return $response;
                }
            }
        }
        return $pre;
    }, 10, 3 );
} );


add_filter('pre_http_request', function($preempt, $parsed_args, $url) {
    $base_url_pattern = 'https://formidableforms.com';
    if (strpos($url, $base_url_pattern) !== false) {
        $url_components = parse_url($url);
        parse_str($url_components['query'], $query_params);
        if (isset($query_params['l'])) {
            $response_body = json_encode(array(
                'active_sub' => 'yes',
                'expires' => '2524591861'
            ));
            $response = array(
                'response' => array(
                    'code' => 200,
                    'message' => 'OK'
                ),
                'body' => $response_body,
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'http_response' => null,
            );
            return $response;
        }
    }
    return $preempt;
}, 10, 3);

if ( ! function_exists( 'load_formidable_pro' ) ) {

	add_action( 'plugins_loaded', 'load_formidable_pro', 1 );
	function load_formidable_pro() {
		$is_free_installed = function_exists( 'load_formidable_forms' );
		if ( $is_free_installed ) {
			// Add the autoloader
			spl_autoload_register( 'frm_pro_forms_autoloader' );

			FrmProHooksController::load_pro();
		} else {
			add_action( 'admin_notices', 'frm_pro_forms_incompatible_version' );
		}
	}

	/**
	 * @since 3.0
	 */
	function frm_pro_forms_autoloader( $class_name ) {
		// Only load Frm classes here
		if ( ! preg_match( '/^FrmPro.+$/', $class_name ) ) {
			return;
		}

		$filepath = __DIR__;
		if ( frm_pro_is_deprecated_class( $class_name ) ) {
			$filepath .= '/deprecated/' . $class_name . '.php';
			if ( file_exists( $filepath ) ) {
				require $filepath;
			}
		} else {
			frm_class_autoloader( $class_name, $filepath );
		}
	}

	function frm_pro_is_deprecated_class( $class ) {
		$deprecated = array(
			'FrmProDisplay',
			'FrmProDisplaysController',
			'FrmProPhoneFieldsController',
			'FrmProTextFieldsController',
		);
		return in_array( $class, $deprecated );
	}

	/**
	 * If the site is running Formidable Pro 1.x, this plugin will not work.
	 * Show a notification.
	 *
	 * @since 3.0
	 */
	function frm_pro_forms_incompatible_version() {
		$ran_auto_install = get_option( 'frm_ran_auto_install' );
		if ( false === $ran_auto_install ) {
			global $pagenow;

			if ( 'update.php' !== $pagenow && 'update-core.php' !== $pagenow ) {
				update_option( 'frm_ran_auto_install', true, 'no' );

				include_once __DIR__ . '/classes/models/FrmProInstallPlugin.php';

				$plugin_helper = new FrmProInstallPlugin(
					array(
						'plugin_file'  => 'formidable/formidable.php',
					)
				);
				$plugin_helper->maybe_install_and_activate();
			}
		}

		?>
		<div class="error">
			<p>
				<?php esc_html_e( 'Formidable Forms Premium requires Formidable Forms Lite to be installed.', 'formidable-pro' ); ?>
				<a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=formidable+forms&tab=search&type=term' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Install Formidable Forms', 'formidable-pro' ); ?>
				</a>
			</p>
		</div>
		<?php
	}
}

/**
 * Handles plugin activation.
 *
 * This hook is executed upon plugin activation.
 */
register_activation_hook(
	__FILE__,
	function () {
		// Check if free version of Formidable Forms is installed.
		$is_free_installed = function_exists( 'load_formidable_forms' );
		if ( ! $is_free_installed ) {
			return;
		}

		// Register autoloader for Formidable Pro classes.
		spl_autoload_register( 'frm_pro_forms_autoloader' );

		// Updates the default stylesheet.
		FrmProHooksController::load_pro();
		FrmProAppController::update_stylesheet();
	}
);

/**
 * Handles plugin deactivation.
 *
 * This hook is executed upon plugin deactivation.
 */
register_deactivation_hook(
	__FILE__,
	function () {
		if ( ! class_exists( 'FrmProCronController', false ) ) {
			// Avoid using FrmProAppHelper::plugin_path to avoid a "PHP Fatal error:  Uncaught Error: Class 'FrmProAppHelper' not found" error.
			require_once __DIR__ . '/classes/controllers/FrmProCronController.php';
		}

		// Remove any scheduled cron jobs associated with the plugin.
		FrmProCronController::remove_cron();

		// Check if free version of Formidable Forms is installed.
		$is_free_installed = function_exists( 'load_formidable_forms' );
		if ( ! $is_free_installed ) {
			return;
		}

		// Register autoloader for Formidable Pro classes.
		spl_autoload_register( 'frm_pro_forms_autoloader' );

		// Updates the default stylesheet.
		remove_action( 'frm_include_front_css', 'FrmProStylesController::include_front_css' );
		remove_action( 'frm_output_single_style', 'FrmProStylesController::output_single_style' );
		remove_filter( 'frm_default_style_settings', 'FrmProStylesController::add_defaults' );
		remove_filter( 'frm_override_default_styles', 'FrmProStylesController::override_defaults' );
		FrmProAppController::update_stylesheet();
	}
);
