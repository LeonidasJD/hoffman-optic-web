<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

$themes     = FrmProStylesController::jquery_themes( $style->post_content['theme_css'] );
$use_themes = count( $themes ) > 1;
$is_default = 1 === $style->menu_order;
$show       = 'frm_date_color';

if ( ! $is_default ) {
	$frm_style     = new FrmStyle( 'default' );
	$default_style = $frm_style->get_one();

	if ( ! empty( $default_style->post_content['theme_css'] ) && 'ui-lightness' !== $default_style->post_content['theme_css'] ) {
		$show = 'frm_hidden';
	}
}
?>

<?php if ( 'frm_hidden' === $show ) { ?>
	<p class="howto">
		<?php
		printf(
			/* translators: %1$s: Start link HTML, %2$s: End link HTML */
			esc_html__( 'Make changes to the date themes in the %1$sdefault style%2$s.', 'formidable-pro' ),
			'<a href="?page=formidable-styles">',
			'</a>'
		);
		?>
	</p>
<?php } ?>

<p class="<?php echo esc_attr( $use_themes ? '' : 'frm_hidden' ); ?>">
	<select name="<?php echo esc_attr( $frm_style->get_field_name( 'theme_selector' ) ); ?>">
		<?php foreach ( $themes as $theme_name => $theme_title ) { ?>
			<option value="<?php echo esc_attr( $theme_name ); ?>" <?php selected( $theme_name, $style->post_content['theme_css'] ); ?>>
				<?php echo esc_html( $theme_title ); ?>
			</option>
		<?php } ?>
	</select>

	<input type="hidden" value="<?php echo esc_attr( $style->post_content['theme_css'] ); ?>" id="frm_theme_css" name="<?php echo esc_attr( $frm_style->get_field_name( 'theme_css' ) ); ?>" />
	<input type="hidden" value="<?php echo esc_attr( $style->post_content['theme_name'] ); ?>" id="frm_theme_name" name="<?php echo esc_attr( $frm_style->get_field_name( 'theme_name' ) ); ?>" />
</p>

<p class="frm4 frm_first frm_form_field <?php echo esc_attr( $use_themes ? 'frm_hidden' : $show ); ?>">
	<label for="frm_date_head_bg_color"><?php esc_html_e( 'Head Color', 'formidable-pro' ); ?></label>
	<input type="text" name="<?php echo esc_attr( $frm_style->get_field_name( 'date_head_bg_color' ) ); ?>" id="frm_date_head_bg_color" class="hex" value="<?php echo esc_attr( $style->post_content['date_head_bg_color'] ); ?>" size="4" <?php do_action( 'frm_style_settings_input_atts', 'date_head_bg_color' ); ?> />
</p>

<p class="frm4 frm_form_field <?php echo esc_attr( $use_themes ? 'frm_hidden' : $show ); ?>">
	<label for="frm_date_head_color"><?php esc_html_e( 'Text Color', 'formidable-pro' ); ?></label>
	<input type="text" name="<?php echo esc_attr( $frm_style->get_field_name( 'date_head_color' ) ); ?>" id="frm_date_head_color" class="hex" value="<?php echo esc_attr( $style->post_content['date_head_color'] ); ?>" <?php do_action( 'frm_style_settings_input_atts', 'date_head_color' ); ?> />
</p>

<p class="frm4 frm_form_field <?php echo esc_attr( $use_themes ? 'frm_hidden' : $show ); ?> frm_end">
	<label for="frm_date_band_color"><?php esc_html_e( 'Band Color', 'formidable-pro' ); ?></label>
	<input type="text" name="<?php echo esc_attr( $frm_style->get_field_name( 'date_band_color' ) ); ?>" id="frm_date_band_color" class="hex" value="<?php echo esc_attr( $style->post_content['date_band_color'] ); ?>" <?php do_action( 'frm_style_settings_input_atts', 'date_band_color' ); ?> />
</p>
