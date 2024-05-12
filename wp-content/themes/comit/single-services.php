<?php get_header(); ?>
<!--HERO SECTION START-->
<section class="services-post-hero-wrapper">
<?php  get_template_part('template-parts/hero-banner'); ?>
</section>
<!--HERO SECTION END-->

<!--TEXT AND FORM SECTION START-->
<section class="text-form-section-wrapper">
    <div class="text-form-section-underwrapper">
        <div class="post-text">
            <?php /**prolazim kroz repeater i proveravam da li ima sekcija u single service postu */
            if(have_rows('service_section')):
                 while(have_rows('service_section')) :
                     the_row();
                
                     $section_title = get_sub_field('title');

                     if(!empty($section_title)){
                        ?>
                        <div class="services-section-title"><?php echo $section_title ?></div>
                        <?php
                     }

            endwhile;
            else:
                echo 'No services found';
        endif;
      
            ?>
            <!--kraj petlje i ispisivanje sekcija -->
        </div>
        <div class="post-form"><?php echo FrmFormsController::get_form_shortcode( array( 'id' => 3 ) ); ?></div>
    </div>
</section>
<!--TEXT AND FORM SECTION END-->


<?php get_footer();?>

