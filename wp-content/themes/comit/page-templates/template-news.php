
<?php
//Template Name:News
get_header(); ?>
<!--HERO SECTION START-->
<section class="job-offers-hero-wrapper">
    <div class="job-offers-hero-under-wrapper">
        <?php get_template_part('template-parts/hero-banner') ?>
    </div>
</section>
<!--HERO SECTION END-->

<!--BLOG SECTION START-->
<div class="container-blog-section">
    <div class="container-14">
    <section class="news-section-wrapper">
    <div class="news-section-underwrapper">
    <div class="posts-section-wrapper">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $post_args = array(
        'post_type' => 'post',
        'posts_per_page' => 4, 
        'paged' => $paged ,
    );
    $blog_posts = new WP_Query($post_args);

    if ($blog_posts->have_posts()) {
        while ($blog_posts->have_posts()) {
            $blog_posts->the_post();
            ?>
            <a href="<?php echo get_permalink(); ?>">
            <div class="single-blog-card">
                <div class="post-image">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                </div>
                
                <div class="post-info">
                    <p ><img src="/wp-content/uploads/2024/06/green-clock.webp"> <?php echo get_the_date(); ?></p>
                    <h2><?php the_title(); ?></h2>
                    <h3><?php echo get_the_excerpt(); ?></h3>
                </div>
                
            </div>
            </a>
            <?php
        }
        // Paginacija
        echo '<div class="pagination">';
        echo paginate_links(array(
            'total' => $blog_posts->max_num_pages,
            'current' => max(1, $paged),
            'prev_text' => ($paged > 1) ? '<img src="/wp-content/uploads/2024/06/vector222.webp" alt="prev arrow">' : '<img src="/wp-content/uploads/2024/05/Vector-1.png" alt="prev arrow">',
            'next_text' => '<img src="/wp-content/uploads/2024/06/vector22.webp" alt="next arrow">',
            'show_all' => true,
            
        ));
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo "No post found";
    }
    ?>
</div>
<div class="latest-posts-section-wrapper">
<?php  get_template_part('template-parts/latest-post'); ?>
</div>


        </div>
        
    </div>
</section>
    </div>
</div>

<!--BLOG SECTION START-->

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