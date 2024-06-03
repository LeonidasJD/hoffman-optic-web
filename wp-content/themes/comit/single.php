<?php
get_header();
?>
<!--SINGLE POST INFO START-->
<div class="container-single-post-info">
   <div class="container-14">
   <section class="single-post-info-wrapper">
	<div class="single-post-info-underwrapper">
		<div class="single-post-info">
		<?php /**prolazim kroz repeater i proveravam da li ima sekcija u single service postu */
            if(have_rows('news_section')):

				
            $blog_post_id_cur = get_the_ID()
				?> 
				<div class="post-date">
				<img src="/wp-content/uploads/2024/05/22.png">
				<p><?php echo get_the_date(); ?></p>
				</div>
				
				
				<?php
				

                 while(have_rows('news_section')) :
                     the_row();
                
                     $post_section_title = get_sub_field('post_section_title');
                     $post_section_subtitle = get_sub_field('post_section_subtitle');
                     $post_section_description = get_sub_field('post_section_description');
                     $post_section_image = get_sub_field('post_section_image');

					

                     echo '<div class="post-section-wrapper">';

					

                     if(!empty($post_section_title)){
                        ?>
                        <div class="post-single-section-title">
							<h2><?php echo $post_section_title  ?></h2>
							
						</div>
                        <?php
                     }
                     if(!empty($post_section_subtitle)){
                        ?>
                        <div class="post-section-subtitle"><h3><?php echo $post_section_subtitle ?></h3></div>
                        <?php
                     }
                     if(!empty($post_section_description)){
                        ?>
                        <div class="post-section-description"><p><?php echo $post_section_description ?></p></div>
                        <?php
                     }
                     if(!empty($post_section_image)){
                        ?>
                        <div class="post-section-image"><img src="<?php echo $post_section_image ?>"></div>
                        <?php
                     }

                     echo '</div>';

            endwhile;
            else:
                echo '<div class="no-post-info-message"><h4>Information will be available soon</h4></div>';
                $blog_post_id_cur = get_the_ID();
                
        endif;
      
            ?>
            <!--kraj petlje i ispisivanje sekcija -->
		</div>

		 <!--SIDE BANNER START-->
		<div class="single-post-side">
		<?php  get_template_part('template-parts/latest-post'); ?>
		</div>
		<!--SIDE BANNER START-->
	</div>
</section>
   </div>
</div>

<!--SINGLE POST INFO END-->



<!--RELATED POSTS START-->
<div class="container-related-blogs">
   <div class="container-14">
   <section class="related-posts-wrapper">
   <div class="related-posts-underwrapper">
    <div class="related-heading-and-paragraph">
    <h2>RELATED BLOGS</h2>
		<p>Dignissim massa duis eget turpis fringilla nam ridiculus ultricies urna vehicula fusce. Elit interdum urna iaculis leo sit pharetra vel. Vitae letius luctus lacinia rhoncus penatibus per.</p>
    </div>
		
   <div class="related-news-cards-wrapper">

      <?php 
        
        
         $related_news_card_args= array(
         'post_type' =>'post',
         'posts_per_page' =>'2',
         'order' => 'DSC',
         'post__not_in' => array( $blog_post_id_cur ),
         );

         $related_news = new WP_Query( $related_news_card_args );

         if($related_news->have_posts()){
         while($related_news->have_posts()){
         $related_news->the_post();
         ?> 
         <a href="<?php echo get_permalink(); ?>">
         <div class="related-single-news-card">
                <div class="related-card-img">
                <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                </div>
                
               <div class="related-news-card-info">
                  <p class="single-news-date"><img src="/wp-content/uploads/2024/05/22.png"><?php echo get_the_date(); ?></p>
                  <div class="single-card-heading-excerpt">
                  <h2><?php echo get_the_title(); ?></h2>
               <p><?php echo get_the_excerpt(); ?></p>
               
                  </div>
              
               </div>
               
            </div></a>
         <?php
            }
         }else{
            echo 'Nema pronađenih srodnih postova!';
         }
      
      ?>

   </div>
</div>
</section>
   </div>
</div>


<!--RELATED POSTS END-->


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
<?php get_footer();?>
