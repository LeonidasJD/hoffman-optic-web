<?php
get_header();
?>
<!--HERO SECTION START-->
<div class="container-hero">
    <div class="container-14">
    <section class="hero-wrapper">
    <div class="under-hero-wrapper">
    <div class="hero-info">
        <h1>Service that'll make <br>you see us with new eyes</h1>
        <p>We Provides always our best services for our clients</p>
        <button class="hero-button button-type-2"><a href="/contact-us/">Schedule an eye exam today!</a></button>
        
    </div>
    </div>
   
    
</section>
    </div>
</div>

<!--HERO SECTION END-->


<!--BOOK SECTION START-->
<div class="container-book-section">
    <div class="container-14">
    <section class="book-section-wrapper">
    <div class="book-section-under-wrapper">
    <div class="book-div-left">
        <div class="left-wrapper">
            <div class="left-info">
            <h2>Our store in Neuenburg</h2>
        <ul>
            <li><img src="/wp-content/uploads/2024/05/icon-_phone_.png" alt=""> <p><a href="tel:0763173606">07631-73606</a></p></li>
            <li><img src="/wp-content/uploads/2024/05/icon-_mail_.png" alt=""><p><a href="mailto:neuenburg@hoffmann-optik.de">neuenburg@hoffmann-optik.de</a></p></li>
            <li><img src="/wp-content/uploads/2024/05/icon-_clock-outline_.png" alt=""><p> 08:30 - 13:00 & 14:00 - 18:00 Uhr</p></li>
        </ul>
    </div>
    <div class="button-wrapper"><button id="neuenburg-btn" class="book-now-btn button-type-3">Book now <img src="/wp-content/uploads/2024/05/Vector-4.png" alt="arrow"></button></div>
        </div>
        
        
    </div>
    <div class="book-div-right">
   
    <div class="left-wrapper">
            <div class="left-info">
            <h2>Our store in Müllheim  </h2>
        <ul>
            <li><img src="/wp-content/uploads/2024/05/icon-_phone_.png" alt=""> <p><a href="tel:076313375">07631-3375</a></p></li>
            <li><img src="/wp-content/uploads/2024/05/icon-_mail_.png" alt=""><p><a href="mailto:muellheim@hoffmann-optik.de">muellheim@hoffmann-optik.de</a></p></li>
            <li><img src="/wp-content/uploads/2024/05/icon-_clock-outline_.png" alt=""><p>08:30 - 13:00 & 14:00 - 18:00 Uhr</p></li>
        </ul>
    </div>
    <div class="button-wrapper"><button id="mullheim-btn" class="book-now-btn button-type-3">Book now <img src="/wp-content/uploads/2024/05/Vector-4.png" alt="arrow"></button></div>
        </div>
       
    </div>
    </div>
    
</section>
    </div>
</div>

<!--BOOK SECTION END-->


<!--OUR SERVICE SECTION START-->
<div class="container-service-section">
    <div class="container-14">
    <section class="our-service-section-wrapper">
    <div class="our-service-under-wrapper">
        <div class="heading-info">
        <h2>Our Services</h2>
        <p>We Provides always our best services for our clients <a class="scale-link" href="/services/">Check services</a> <img src="/wp-content/uploads/2024/05/Vector-41.png" alt=""></p>
        </div>
        <div class="services-cards">

        <div class="service-card">
            <img src="/wp-content/uploads/2024/05/Icon1.png" >
            <h2>Eye care</h2>
            <p>Enhancing Your Vision sit
ametcon sec tetur adipisicing
eiusmod tempor incididunt ut.
            </p>
        </div>
        <div class="service-card">
        <img src="/wp-content/uploads/2024/05/Icon2.png" >
            <h2>Eyes check</h2>
            <p>Enhancing Your Vision sit
ametcon sec tetur adipisicing
eiusmod tempor incididunt ut.
            </p>
        </div>
        <div class="service-card">
        <img src="/wp-content/uploads/2024/05/Icon3.png" >
            <h2>Fashionable glasses</h2>
            <p>Enhancing Your Vision sit
ametcon sec tetur adipisicing
eiusmod tempor incididunt ut.
            </p>
        </div>
        <div class="service-card">
        <img src="/wp-content/uploads/2024/05/Icon4.png" >
            <h2>Contact lens </h2>
            <p>Enhancing Your Vision sit
ametcon sec tetur adipisicing
eiusmod tempor incididunt ut.
            </p>
        </div>
        </div>
        
    </div>
</section>
    </div>
</div>

<!--OUR SERVICE SECTION END-->


<!--OUR PRODUCT SECTION START-->
<div class="container-our-product-section">
    <div class="container-14">
    <section class="our-product-section-wrapper">
    <div class="our-product-under-wrapper">
        <div class="heading-product-info">
            <h2>Our Products</h2>
            <p><a class="scale-link" href="/shop/">Check all products</a> <img src="/wp-content/uploads/2024/05/Vector-41.png"></p>
            
        </div>
        <?php
            $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 8, 
            );

            $products = new WP_Query($args);
            if ($products->have_posts()) {
            echo '<div class="products-wrapper">';
             while ($products->have_posts()) {
             $products->the_post();
             
             ?>
             <a href="<?php echo get_permalink() ?>">
             <div class="single-product-card">
             <?php
            // Dohvatam URL slike proizvoda
            $thumbnail_url = get_the_post_thumbnail_url();
            if ($thumbnail_url) {
                
                echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '">';
               
            }
            ?>
            <h2><?php the_title(); ?></h2>
            <p><a href="<?php echo get_permalink() ?>">Read more</a></p>
                </div>
             </a>
             
            <?php
    }
    echo '</div>';
    wp_reset_postdata(); 
} else {
    echo 'Nema proizvoda.';
}
?>


        

    </div>
</section>
    </div>
</div>

<!--OUR PRODUCT SECTION END-->


<!--OUR STORY SECTION START-->
<div class="container-our-story-section">
    <div class="container-14">
    <section class="our-story-section-wrapper">
    <div class="our-story-under-wrapper">
        <div class="our-story-info">
            <h2>Our Story</h2>
            <p>Lorem ipsum dolor sit amet consectetur. Mauris risus adipiscing felis iaculis. Sed massa odio eget nullam ornare felis vitae urna risus. Id suspendisse nullam facilisi sed amet. Cursus sed aliquam gravida in nec id. Interdum nisi at ac eu arcu id viverra pellentesque accumsan. Et commodo enim suspendisse maecenas. Auctor velit diam  </p>
            <h3>Our Mission</h3>
            <p>Eros placerat ut consequat in tellus enim. Egestas sed viverra ut volutpat velit a adipiscing. Auctor vitae odio sagittis faucibus turpis. Diam tempor euismod dictumst facilisi. </p>
            <p>Eros placerat ut consequat in tellus enim. Egestas sed viverra ut volutpat velit a adipiscing. Auctor vitae odio sagittis faucibus turpis. Diam tempor euismod dictumst facilisi. </p>
        </div>
    </div>
</section>
    </div>
</div>

<!--OUR STORY SECTION END-->

<!--OUR NEWS SECTION START-->
<div class="container-our-news-section">
    <div class="container-14">
    <section class="our-news-section-wrapper">
    <div class="our-news-under-wrapper">
    <div class="heading-info heading-info-news">
        <h2>Our News</h2>
        <p>We Provides always our best insight for our clients <a class="scale-link" href="/services/">Check services</a> <img src="/wp-content/uploads/2024/05/Vector-41.png" alt=""></p>
        </div>
        <div class="form-news-wrapper">
            <div class="news-wrapper">
                <?php
                $post_args= array(
                'post_type'      => 'post',
                'posts_per_page' => 2, 
                );
                $blog_posts = new WP_Query($post_args);

                if($blog_posts->have_posts()){
                    while($blog_posts->have_posts()){
                        $blog_posts->the_post();
                        ?>
                        <a href="<?php echo get_permalink(); ?>">
                        <div class="single-blog-card">
                         <div class="post-image">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                         </div>
                            <div class="post-info">
                            <p><img src="/wp-content/uploads/2024/05/Vector.png"> <?php echo get_the_date();?></p>
                            <h2><?php the_title(); ?></h2>
                            <h3><?php echo get_the_excerpt();?></h3>
                            </div>
                            
                        </div>
                        </a>
                        
                        <?php
                    }    
                }else{
                    echo "No posts found";
                }
                ?>
                
            </div>
            <div class="form-wrapper">
                <h2>Schedule an appointment</h2>
                <div class="formidable-form">
            <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 2 ) ); ?>
            </div>
            </div>
        </div>
    </div>
</section>
    </div>
</div>

<!--OUR NEWS SECTION END-->


<!--TESTIMONIAL SECTION START-->
<div class="container-testimonial-section">
    <div class="container-14">
    <section class="testimonial-section-wrapper">
    <div class="testimonial-section-under-wrapper">
        <div class="heading-undersec">
            <h2>WHAT OUR PATIENT’S Think about us?</h2>
        </div>
        <div class="testimonial-undersec">
        <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img class="swiper-icon" src="/wp-content/uploads/2024/05/Icon.png" alt="">
        <p class="swiper-text">Long established fact that a reader will be distracted
            by the readable content of a page when looking at it's
            layout. The point of using Lorem Ipsum</p>
        <img class="swiper-person-img" src="/wp-content/uploads/2024/05/testimonial-01.jpg.png" alt="">
        <h4>Joerg Porter</h4>
      </div>
      <div class="swiper-slide">Slide 2</div>
      <div class="swiper-slide">Slide 3</div>
      
    </div>
    <div class="swiper-pagination"></div>
  </div>
        </div>
    </div>
</section>
    </div>
</div>

<!--TESTIMONIAL SECTION END-->

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
<div class="sticky-button-wrapper">
    <button id="sticky-btn"><img src="/wp-content/uploads/2024/06/Vector-9.png"></button>
</div>
<!-- STICKY BACK TO TOP BUTTON END -->



<?php
get_footer();
?>

<script>
/**SWIPER LOGIC START*/
var swiper = new Swiper(".mySwiper", {
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },
    });

/**SWIPER LOGIC END */


//     console.log("homeee test");
//     document.getElementById('date').addEventListener('change', function() {
//     // Uzimamo vrednost datuma koji je izabran
//     var selectedDate = this.value;
//     // Postavljamo stilizaciju izabranog datuma
//     this.style.color = '#76BA51'; 
//     this.style.fontWeight = '600';
//     this.style.fontSize = '16';
//     this.style.fontFamily = 'Lato';
    
// });

// BUTTON HREF LOGIC START

    const nuenburgBtn = document.getElementById('neuenburg-btn');
    const mullheimBtn = document.getElementById('mullheim-btn');

    nuenburgBtn.addEventListener('click', function(){
         window.open("https://www.click2date.eu/hoffmann-optik-neuenburg/appointment/start", "_blank");
        
    });

    mullheimBtn.addEventListener('click', function(){
        window.open("https://www.click2date.eu/hoffmann-Optik-muellheim/appointment/start ", "_blank");
    });

// BUTTON HREF LOGIC START

//STICY BUTTON TO TOP START
var stickyButton = document.getElementById('sticky-btn');

stickyButton.addEventListener('click', function(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
//STICY BUTTON TO TOP END
</script>