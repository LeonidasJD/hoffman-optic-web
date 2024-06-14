<?php 
// Template Name:Services
get_header(); ?>
<!--HERO SECTION START-->
<div class="container-services-hero">
    <div class="container-14">
    <section class="hero-services-section-wrapper">
    <div class="hero-services-section-under-wrapper">
        <h1>We accompany you and we educate in your process, whatever be the objective.</h1>
    </div>
</section>
    </div>
</div>

<!--HERO SECTION END-->

<!--SERVICES SECTION START-->
<div class="container-services-section">
    <div class="container-14">
    <section class="services-section-wrapper">
    <div class="services-section-under-wrapper">
        <div class="services-cards-wrapper">

        <?php 
        $services_card_args= array(
            'post_type' =>'services',
            'posts_per_page' =>'9'
        );

        $services = new WP_Query( $services_card_args );

        if($services->have_posts()){
            while($services->have_posts()){
                $services->the_post();
                ?> 
                <a href="<?php echo get_permalink(); ?>">
                <div class="single-service-card">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                    <div class="service-card-info">
                    <h2><?php echo get_the_title(); ?></h2>
                    <p><?php echo get_the_excerpt(); ?></p>
                    <div class="service-button"><a href="<?php echo get_permalink(); ?>">Read more</a></div>
                    
                    </div>
                    
                </div>
                </a>
               
                <?php
            }
        }else{
            'Services not found!';
        }
        ?>
        </div>
    </div>
</section>
    </div>
</div>


<!--SERVICES SECTION END-->

<!--BANNER SECTION START-->
<div class="container-banner-section">
    <div class="container-14">
    <section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
    </div>
</div>

<!--BANNER SECTION END-->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button') ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<!-- MOBILE SIDE BANNER START -->
<?php get_template_part('template-parts/mobile-side-banner') ?>
<!-- MOBILE SIDE BANNER END -->
<?php get_footer(); ?>