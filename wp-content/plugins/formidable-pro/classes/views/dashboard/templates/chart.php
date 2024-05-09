<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<div class="frm-flex-box frm-justify-between">
	<h2 class="frm-widget-heading"><?php echo esc_html( $template['widget-heading'] ); ?></h2>
	<a class="frm-widget-cta" href="<?php echo esc_url( $template['cta']['link'] ); ?>"><?php echo esc_html( $template['cta']['label'] ); ?></a>
</div>
<h4 class="frm-weekly-submission-heading"><?php echo wp_kses_post( $template['chart-heading'] ); ?></h4>
<?php echo wp_kses_post( FrmProGraphsController::graph_shortcode( $template['chart-data'] ) ); ?>
