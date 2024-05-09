<?php
/**
 * License expired email class
 *
 * @since 6.7
 * @package Formidable
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProEmailLicenseExpired extends FrmEmailSummary {

	protected function get_subject() {
		return __( 'Your Formidable Forms license is expired', 'formidable-pro' );
	}

	protected function get_inner_content() {
		$args = $this->get_content_args();

		ob_start();
		include FrmProAppHelper::plugin_path() . '/classes/views/emails/license-expired.php';
		$content = ob_get_clean();

		if ( $this->is_html ) {
			return $content;
		}

		$content = wp_specialchars_decode( wp_strip_all_tags( $content ), ENT_QUOTES );

		$content = str_replace( "\t", '', $content );
		$content = str_replace(
			array(
				"\n\n\n\n",
				"\n\n\n",
				"\r\n\r\n\r\n\r\n",
				"\r\n\r\n\r\n",
			),
			"\r\n\r\n",
			$content
		);

		$content = str_replace(
			__( 'Renew Now', 'formidable-pro' ),
			sprintf(
				// translators: renew URL.
				__( 'Renew now at %s', 'formidable-pro' ),
				$args['renew_url']
			),
			$content
		);

		$content = str_replace(
			'Co-founder and CTO of Formidable Forms',
			"Co-founder and CTO of Formidable Forms\r\n\r\n",
			$content
		);

		return $content;
	}

	protected function get_content_args() {
		$args = parent::get_content_args();

		$args['renew_url'] = FrmEmailSummaryHelper::get_frm_url( 'account/downloads/', 'renew_url' );

		return $args;
	}
}
