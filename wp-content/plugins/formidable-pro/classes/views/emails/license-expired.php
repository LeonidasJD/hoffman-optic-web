<?php
/**
 * Template for license expired email
 *
 * @since 6.7
 * @package Formidable
 *
 * @var array $args Content args.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<div style="line-height: 1.5; <?php echo esc_attr( FrmEmailSummaryHelper::get_section_style( '' ) ); ?>">
	<p><?php esc_html_e( 'Hi there,', 'formidable-pro' ); ?></p>

	<p><?php esc_html_e( 'I hope you have loved the past year of using Formidable Forms! Your subscription was set to manually renew and has just expired.', 'formidable-pro' ); ?></p>

	<p><?php esc_html_e( 'Renewing your license grants you access to our legendary support services, form templates, Pro features, and updates for another year.', 'formidable-pro' ); ?></p>

	<p>
		<a href="<?php echo esc_url( $args['renew_url'] ); ?>" title="" style="<?php echo esc_attr( FrmEmailSummaryHelper::get_button_style( true ) ); ?>margin-top:0;">
			<?php esc_html_e( 'Renew Now', 'formidable-pro' ); ?>
		</a>
	</p>

	<p><?php esc_html_e( 'If you don\'t need Formidable Pro right now, that\'s OK. You can let this subscription expire and simply resubscribe in the future when needed.', 'formidable-pro' ); ?></p>

	<p>
		<?php esc_html_e( 'Cheers!', 'formidable-pro' ); ?>
		<br />
		<strong>Steph Wells</strong>
		<br />
		<span style="color: #667085;">Co-founder and CTO of Formidable Forms</span>
	</p>
</div>
