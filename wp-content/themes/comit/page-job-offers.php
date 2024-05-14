<?php get_header(); ?>
<!--HERO SECTION START-->
<section class="job-offers-hero-wrapper">
    <div class="job-offers-hero-under-wrapper">
        <?php get_template_part('template-parts/hero-banner') ?>
    </div>
</section>
<!--HERO SECTION END-->


<!--WELCOME SECTION TEXT  START-->
<section class="welcome-info-section-wrapper">
    <div class="welcome-info-section-underwrapper">
        <h2>Welcome to the Hoffmannoptik job portal.</h2>
        <p>We are pleased that you are interested in a job, training or internship with us. Get to know us and become part of our team!</p>
        <p>Would you like to reorient yourself or start a new career? Contact us or send us your application documents online.</p>
    </div>
</section>
<!--WELCOME SECTION TEXT  END-->

<!--POSITION TO APPLY SECTION   START-->
<section class="position-apply-section-wrapper">
    <div class="postition-applay-section-underwrapper">
        <div class="position-apply-cards-wrapper">
        <div class="single-position-appy-card">
            <h2>Master optician</h2>
            <div class="apply-card-text-button">
            <p>Master optician lorem ipsum sit
            ametcon sec tetur adipisicing
            eiusmod tempor incididunt ut.</p>
            <button class="apply-here-btn">Apply here <img src="/wp-content/uploads/2024/05/Vector-4-1.png" alt=""></button>
            </div>
            
        </div>

        <div class="single-position-appy-card">
            <h2>Optometrist</h2>
            <div class="apply-card-text-button">
            <p>Optometrist lorem ipsum sit
            ametcon sec tetur adipisicing
            eiusmod tempor incididunt ut.</p>
            <button class="apply-here-btn">Apply here <img src="/wp-content/uploads/2024/05/Vector-4-1.png" alt=""></button>
            </div>
            
        </div>

        <div class="single-position-appy-card">
            <h2>Apprenticeship to become
            an optician</h2>
            <div class="apply-card-text-button">
            <p>Apprenticeship lorem ipsum sit
            ametcon sec tetur adipisicing
            eiusmod tempor incididunt ut.</p>
            <button class="apply-here-btn">Apply here <img src="/wp-content/uploads/2024/05/Vector-4-1.png" alt=""></button>
            </div>
            
        </div>

        <div class="single-position-appy-card">
            <h2>Student internship</h2>
            <div class="apply-card-text-button">
            <p>Student internship lorem ipsum sit
            ametcon sec tetur adipisicing
            eiusmod tempor incididunt ut.</p>
            <button class="apply-here-btn">Apply here <img src="/wp-content/uploads/2024/05/Vector-4-1.png" alt=""></button>
            </div>
            
        </div>
        </div>
        
    </div>

</section>
<!--POSITION TO APPLY SECTION   END-->

<!--MODAL SECTION   START-->
<section class="modal-wrapper">
<div class="modal-underwrapper">
    <div class="modal-main">
        <span class="exit-icon-wrapper"><img class="exit-icon" src="/wp-content/uploads/2024/05/Frame-876.png" ></span>
        <h2>Job application form</h2>
        <div class="job-application-form-wrapper">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 6 ) ); ?>
        </div>
    </div>
</div>
    
</section>

<!--MODAL SECTION   END-->



<!--BANNER SECTION START-->
<section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
<!--BANNER SECTION END-->
<?php get_footer(); ?>

<script>

$(document).ready(function(){

/**OTVARANJE MODALA */
    $('.apply-here-btn').each(function(index){
        $(this).click(function(){
            var btnNumber =index;
            $('.modal-wrapper').show();
        });
    }); 


/**ZATVARANJE MODALA */
    $('.exit-icon').click(function(){
        $('.modal-wrapper').hide();
    });
    $('.cancel-button').click(function(){
        $('.modal-wrapper').hide();
    });
});

</script>