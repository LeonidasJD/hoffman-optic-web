<?php get_header(); ?>
<!--HERO SECTION START-->
<section class="hero-services-section-wrapper">
    <div class="hero-services-section-under-wrapper">
        <h1>We accompany you and we educate in your process, whatever be the objective.</h1>
    </div>
</section>
<!--HERO SECTION END-->

<!--SERVICES SECTION START-->

<section class="services-section-wrapper">
    <div class="services-section-under-wrapper">
        <div class="services-cards-wrapper">

        <?php 
        $related_services_card_args= array(
            'post_type' =>'services',
            'posts_per_page' =>'3'
        );

        $related_services = new WP_Query( $related_services_card_args );

        if($related_services->have_posts()){
            while($related_services->have_posts()){
                $related_services->the_post();
                ?> 
                <div class="single-service-card">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                    <div class="service-card-info">
                    <h2><?php echo get_the_title(); ?></h2>
                    <p><?php echo get_the_excerpt(); ?></p>
                    <div class="service-button"><a href="<?php echo get_permalink(); ?>">Read more</a></div>
                    
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
<!--SERVICES SECTION END-->

<!--BANNER SECTION START-->
<section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
<!--BANNER SECTION END-->
<?php get_footer(); ?>