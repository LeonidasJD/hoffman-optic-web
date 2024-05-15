<div class="latest-news-heading">
        <h2>Latest news</h2>
    </div>
    <!--LATEST BLOG SECTION START-->
    <div class="lates-news-wrapper">
    <?php
                $latest_post_args= array(
                'post_type'      => 'post',
                'posts_per_page' => 4, 
                );
                $latest_blog_posts = new WP_Query( $latest_post_args);

                if($latest_blog_posts->have_posts()){
                    while($latest_blog_posts->have_posts()){
                        $latest_blog_posts->the_post();
                        ?>
                        <div class="latest-single-blog-card">
                         <div class="lates-post-image">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                         </div>
                            <div class="latest-post-info">
                            <h2><?php the_title(); ?></h2>
                            <p> <?php echo get_the_date();?></p>
                            <a href="<?php echo get_permalink(); ?>">Read more</a>
                            </div>
                            
                        </div>
                        <?php
                    }    
                }else{
                    echo "No posts found";
                }
                ?>
    </div>
     <!--LATEST BLOG SECTION START-->
     <div class="side-banner-wrapper">
    <h2>Consultation Service</h2>
    <p>If you have questions, feel free to call us anytime!</p>
    <a href="tel:076313375">07631-3375</a>
    <div class="side-image">
    <img src="/wp-content/uploads/2024/05/logotype-1-1.png" >
    </div>
    
</div>