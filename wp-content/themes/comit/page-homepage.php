<?php
get_header();
?>
<!--HERO SECTION START-->
<section class="hero-wrapper">
    <div class="under-hero-wrapper">
    <div class="hero-info">
        <h1>Service that'll make <br>you see us with new eyes</h1>
        <p>We Provides always our best services for our clients</p>
        <button class="hero-button"><a href="#">Schedule an eye exam today!</a></button>
        
    </div>
    </div>
   
    
</section>
<!--HERO SECTION END-->


<!--BOOK SECTION START-->
<section class="book-section-wrapper">
    <div class="book-section-under-wrapper">
    <div class="book-div-left">
        
        <div class="left-info">
            <h2>Our store in Neuenburg</h2>
        <ul>
            <li><img src="/wp-content/uploads/2024/05/🦆-icon-_phone_.png" alt=""> <p>07631-73606</p></li>
            <li><img src="/wp-content/uploads/2024/05/🦆-icon-_mail_.png" alt=""><p>neuenburg@hoffmann-optik.de</p></li>
            <li><img src="/wp-content/uploads/2024/05/🦆-icon-_clock-outline_.png" alt=""><p> 08:30 - 13:00 & 14:00 - 18:00 Uhr</p></li>
        </ul>
    </div>
    <div class="button-wrapper"><button>Book now <img src="/wp-content/uploads/2024/05/Vector-4.png" alt="arrow"></button></div>
        
    </div>
    <div class="book-div-right">
   
        <div class="right-info">
        <h2>Our store in Müllheim  </h2>
        <ul>
            <li><img src="/wp-content/uploads/2024/05/🦆-icon-_phone_.png" alt=""><p>07631-3375</p></li>
            <li><img src="/wp-content/uploads/2024/05/🦆-icon-_mail_.png" alt=""><p>muellheim@hoffmann-optik.de</p></li>
            <li><img src="/wp-content/uploads/2024/05/🦆-icon-_clock-outline_.png" alt=""><p>08:30 - 13:00 & 14:00 - 18:00 Uhr</p></li>
        </ul>
        </div>
        <div class="button-wrapper"><button>Book now  <img src="/wp-content/uploads/2024/05/Vector-4.png" alt="arrow"></button></div>
       
    </div>
    </div>
    
</section>
<!--BOOK SECTION END-->


<!--OUR SERVICE SECTION START-->
<section class="our-service-section-wrapper">
    <div class="our-service-under-wrapper">
        <div class="heading-info">
        <h2>Our Services</h2>
        <p>We Provides always our best services for our clients <a href="">Check services</a> <img src="/wp-content/uploads/2024/05/Vector-41.png" alt=""></p>
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
<!--OUR SERVICE SECTION END-->

<!--OUR PRODUCT SECTION START-->
<section class="our-product-section-wrapper">
    <div class="our-product-under-wrapper">
        <div class="heading-product-info">
            <h2>Our Products</h2>
            <p><a href="">Check all products</a> <img src="/wp-content/uploads/2024/05/Vector-41.png"></p>
            
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
             <div class="single-product-card">
             <?php
            // Dohvatam URL slike proizvoda
            $thumbnail_url = get_the_post_thumbnail_url();
            if ($thumbnail_url) {
                
                echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '">';
               
            }
            ?>
            <h2><?php the_title(); ?></h2>
            <p><a href="">Read more</a></p>
                </div>
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
<!--OUR PRODUCT SECTION END-->


<!--OUR STORY SECTION START-->
<section class="our-story-section-wrapper">
    <div class="our-story-under-wrapper">
        <div class="our-story-info">
            <h2>Our Story</h2>
            <p>Lorem ipsum dolor sit amet consectetur. Mauris risus adipiscing felis iaculis. Sed massa odio eget nullam ornare felis vitae urna risus. Id suspendisse nullam facilisi sed amet. Cursus sed aliquam gravida in nec id. Interdum nisi at ac eu arcu id viverra pellentesque accumsan. Et commodo enim suspendisse maecenas. Auctor velit diam </p>
            <p>Lorem ipsum dolor sit amet consectetur. Mauris risus adipiscing felis iaculis. Sed massa odio eget nullam ornare felis vitae urna risus. Id suspendisse nullam facilisi sed amet. Cursus sed aliquam gravida in nec id. Interdum nisi at ac eu arcu id viverra pellentesque accumsan. Et commodo enim suspendisse maecenas. Auctor velit diam </p>
            <h3>Our Mission</h3>
            <p>Eros placerat ut consequat in tellus enim. Egestas sed viverra ut volutpat velit a adipiscing. Auctor vitae odio sagittis faucibus turpis. Diam tempor euismod dictumst facilisi. Amet morbi vitae magna mauris ultrices tellus eu sagittis.</p>
            <p>Eros placerat ut consequat in tellus enim. Egestas sed viverra ut volutpat velit a adipiscing. Auctor vitae odio sagittis faucibus turpis. Diam tempor euismod dictumst facilisi. Amet morbi vitae magna mauris ultrices tellus eu sagittis.</p>
        </div>
    </div>
</section>
<!--OUR STORY SECTION END-->

<!--OUR NEWS SECTION START-->
<section class="our-news-section-wrapper">
    <div class="our-news-under-wrapper">
    <div class="heading-info heading-info-news">
        <h2>Our News</h2>
        <p>We Provides always our best insight for our clients <a href="">Check services</a> <img src="/wp-content/uploads/2024/05/Vector-41.png" alt=""></p>
        </div>
        <div class="form-news-wrapper">
            <div class="news-wrapper">
                <h2>placeholder</h2>
            </div>
            <div class="form-wrapper">
                <h2>Schedule an appointment</h2>
            <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 2 ) ); ?>
            </div>
        </div>
    </div>
</section>
<!--OUR NEWS SECTION END-->




<?php
get_footer();
?>

<script>
    console.log("homeee test");
    document.getElementById('date').addEventListener('change', function() {
    // Uzimamo vrednost datuma koji je izabran
    var selectedDate = this.value;
    // Postavljamo stilizaciju izabranog datuma
    this.style.color = '#76BA51'; 
    this.style.fontWeight = '600';
    this.style.fontSize = '16';
    this.style.fontFamily = 'Lato';
    
});
</script>