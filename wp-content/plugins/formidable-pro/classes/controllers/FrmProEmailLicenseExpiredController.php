<?php
/**
 * License expired email controller
 *
 * @since 6.7
 * @package FormidablePro
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProEmailLicenseExpiredController {

	const LICENSE_EXPIRED = 'license';

	/**
	 * Maybe send the license expired email.
	 */
	public static function maybe_send() {
		if ( ! class_exists( 'FrmEmailSummaryHelper', false ) || ! FrmEmailSummaryHelper::is_enabled() ) {
			return;
		}

		$last_sent_date = FrmEmailSummaryHelper::get_last_sent_date( self::LICENSE_EXPIRED );
		// Check for license expired email.
		if ( ! FrmProAddonsController::is_license_expired() ) {
			if ( $last_sent_date ) {
				FrmEmailSummaryHelper::set_last_sent_date( self::LICENSE_EXPIRED, '' ); // Clear last sent date if license is renewed.
			}
			return;
		}

		if ( ! $last_sent_date ) {
			// If license expired and license email hasn't been sent, send it.
			self::send();
		}
	}

	/**
	 * Sends the license expired email.
	 */
	private static function send() {
		$license_email = new FrmProEmailLicenseExpired();

		if ( $license_email->send() ) {
			FrmEmailSummaryHelper::set_last_sent_date( self::LICENSE_EXPIRED );
		}
	}
}
