<?php get_header(); ?>
<!--HERO SECTION START-->
<section class="services-post-hero-wrapper">
<?php  get_template_part('template-parts/hero-banner'); ?>
</section>
<!--HERO SECTION END-->

<!--TEXT AND FORM SECTION START-->
<div class="container-text-and-form-section">
    <div class="container-14">
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
            <h2 class="post-form-title">Termin Buchen</h2>
            <div class="formidable-form">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 3 ) ); ?>
        </div>
        </div>    
        
    </div>
    </div>
</section>
    </div>
</div>

<!--TEXT AND FORM SECTION END-->

<!--RELATED SERVICES SECTION START-->
<div class="container0related-services-section">
    <div class="container-14">
    <section class="related-services-section-wrapper">
    <div class="related-services-section-underwrapper">
        <h2>Weitere Leistungen</h2>
        <p class="related-services-underhead">Dignissim massa duis eget turpis fringilla nam ridiculus ultricies urna vehicula fusce. Elit interdum urna iaculis leo sit pharetra vel. Vitae letius luctus lacinia rhoncus penatibus per.</p>
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
                <a href="<?php echo get_permalink(); ?>">
                <div class="related-single-service-card">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                    <div class="related-service-card-info">
                    <h3><?php echo get_the_title(); ?></h3>
                    <p><?php echo get_the_excerpt(); ?></p>
                    <div class="related-service-button"><a href="<?php echo get_permalink(); ?>">Mehr über</a></div>
                    
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

<!--RELATED SERVICES SECTION END-->

<!--CONTACT US GREEN BANNER SECTION START-->
<div class="container-banner-section">
    <div class="container-14">
    <section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
    </div>
</div>

<!--CONTACT US GREEN BANNER SECTION START-->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button') ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<!-- MOBILE SIDE BANNER START -->
<?php get_template_part('template-parts/mobile-side-banner') ?>
<!-- MOBILE SIDE BANNER END -->

<?php get_footer();?>

<script>


var newDiv = document.createElement("div");


newDiv.classList.add("paralel-fileds-wrapper"); 


var field59 = document.getElementById("frm_field_59_container");
var field81 = document.getElementById("frm_field_81_container");


var newImage = document.createElement("img");
newImage.src = "/wp-content/uploads/2024/06/vector_21.webp";


newDiv.appendChild(field59);
newDiv.appendChild(newImage);
newDiv.appendChild(field81);


var referenceElement = document.getElementById("frm_field_58_container");


referenceElement.insertAdjacentElement('afterend', newDiv);

// OGRANICAVANJE DA SE ODABERE DATUM U PROSLOSTI U FORMI START
$( function() {
    $("#field_5hgrh" ).datepicker({ minDate: 0, maxDate: "+12M" });
  } );
  // OGRANICAVANJE DA SE ODABERE DATUM U PROSLOSTI U FORMI START


  // KADA ODABEREMO OPCIJU IZ DROPDOWNA ONDA OPCIJA BUDE BOLD START
  const dropdownSingleService = document.getElementById('field_8njfd');

  dropdownSingleService.addEventListener('change', function() {
  if (dropdownSingleService.value === "") {
    dropdownSingleService.classList.remove('change-select');
  } else {
    dropdownSingleService.classList.add('change-select');
  }
});

  const dropdownSingleHour = document.getElementById('field_kvh2f_H');

  dropdownSingleHour.addEventListener('change', function() {
  if (dropdownSingleHour.value === "") {
    dropdownSingleHour.classList.remove('change-select');
  } else {
    dropdownSingleHour.classList.add('change-select');
  }
});
const dropdownSingleMinute = document.getElementById('field_kvh2f_m');

dropdownSingleMinute.addEventListener('change', function() {
  if (dropdownSingleMinute.value === "") {
    dropdownSingleMinute.classList.remove('change-select');
  } else {
    dropdownSingleMinute.classList.add('change-select');
  }
});
// KADA ODABEREMO OPCIJU IZ DROPDOWNA ONDA OPCIJA BUDE BOLD END
</script>