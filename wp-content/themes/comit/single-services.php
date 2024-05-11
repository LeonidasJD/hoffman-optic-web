<?php get_header(); ?>
<!--HERO SECTION START-->
<section class="services-post-hero-wrapper">
<?php  get_template_part('template-parts/hero-banner'); ?>
</section>
<!--HERO SECTION END-->

<!--TEXT AND FORM SECTION START-->
<section class="text-form-section-wrapper">
    <div class="text-form-section-underwrapper">
        <div class="post-text"><h2>text placeholder</h2></div>
        <div class="post-form"><?php echo FrmFormsController::get_form_shortcode( array( 'id' => 3 ) ); ?></div>
    </div>
</section>
<!--TEXT AND FORM SECTION END-->


<?php get_footer();?>

