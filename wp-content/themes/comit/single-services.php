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
                     $section_subtitle = get_sub_field('subtitle');
                     $section_description = get_sub_field('description');
                     $section_image = get_sub_field('service_image');

                     echo '<div class="service-section-wrapper">';

                     if(!empty($section_title)){
                        ?>
                        <div class="services-section-title"><h2><?php echo $section_title ?></h2></div>
                        <?php
                     }
                     if(!empty($section_subtitle)){
                        ?>
                        <div class="services-section-subtitle"><h3><?php echo $section_subtitle ?></h3></div>
                        <?php
                     }
                     if(!empty($section_description)){
                        ?>
                        <div class="services-section-description"><p><?php echo $section_description ?></p></div>
                        <?php
                     }
                     if(!empty($section_image)){
                        ?>
                        <div class="services-section-image"><img src="<?php echo $section_image ?>"></div>
                        <?php
                     }

                     echo '</div>';

            endwhile;
            else:
                echo '<div class="no-info-message"><h4>Information will be available soon</h4></div>';
        endif;
      
            ?>
            <!--kraj petlje i ispisivanje sekcija -->
        </div>
        <div class="post-form">
        <div class="post-form-wrapper">
            <h2 class="post-form-title">Schedule an appointment</h2>
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 3 ) ); ?>
        </div>    
        
    </div>
    </div>
</section>
<!--TEXT AND FORM SECTION END-->

<!--RELATED SERVICES SECTION START-->
<section class="related-services-section-wrapper">
    <div class="related-services-section-underwrapper">
        <h2>Related services</h2>
        <p>Dignissim massa duis eget turpis fringilla nam ridiculus ultricies urna vehicula fusce. Elit interdum urna iaculis leo sit pharetra vel. Vitae letius luctus lacinia rhoncus penatibus per.</p>
        <div class="related-services-cards-wrapper">

        <?php 
        $current_post_id = get_the_ID();

        $related_services_card_args= array(
            'post_type' =>'services',
            'posts_per_page' =>'3',
            'post__not_in' => array($current_post_id),
        );

        $related_services = new WP_Query( $related_services_card_args );

        if($related_services->have_posts()){
            while($related_services->have_posts()){
                $related_services->the_post();
                ?> 
                <div class="related-single-service-card">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                    <div class="related-service-card-info">
                    <h2><?php echo get_the_title(); ?></h2>
                    <p><?php echo get_the_excerpt(); ?></p>
                    <div class="related-service-button"><a href="<?php echo get_permalink(); ?>">Read more</a></div>
                    
                    </div>
                    
                </div>
                <?php
            }
        }else{
            'Services not found!';
        }
        ?>
        </div>
    </div>
</section>
<!--RELATED SERVICES SECTION END-->

<!--CONTACT US GREEN BANNER SECTION START-->
<section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
<!--CONTACT US GREEN BANNER SECTION START-->



<?php get_footer();?>

