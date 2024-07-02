<?php
//Template Name:Job Offers
get_header(); ?>
<!--HERO SECTION START-->
<section class="job-offers-hero-wrapper">
    <div class="job-offers-hero-under-wrapper">
        <?php get_template_part('template-parts/hero-banner') ?>
    </div>
</section>
<!--HERO SECTION END-->


<!--WELCOME SECTION TEXT  START-->
<div class="container-welcome-section">
    <div class="container-14">
    <section class="welcome-info-section-wrapper">
    <div class="welcome-info-section-underwrapper">
        <h2>Herzlich Willkommen auf dem Stellenportal von Hoffmannoptik im Markgräflerland</h2>
        <p>Wir freuen uns, dass Du Dich für einen Arbeitsplatz, eine Ausbildung oder ein Praktikum bei uns interessierst. Lerne uns kennen und werde auch Du Teil unseres Teams!
        </p>
        <p>Wir bieten Dir:
        </p>
        <ul>
            <li>Ein angenehmes Betriebsklima
            </li>
            <li>Topmoderne technische Ausstattung
            </li>
            <li>Persönliche Betreuung und Förderung mit Weiterbildungsmaßnahmen
            </li>
            <li>Gute Aufstiegschancen
            </li>
            <li>Übertarifliche Bezahlung
            </li>
            <li>JobRad
            </li>
            <li>Regelmäßige Teamevents

            </li>
    </ul>
    <p>Bist Du an einer Anstellung bei uns interessiert? Dann wirf einen Blick auf unsere offenen Stellenangebote.
Selbstverständlich kannst Du Dich auch jederzeit initiativ bewerben.<br><br>
Zur besseren Lesbarkeit von Personenbezeichnungen & personenbezogenen Wörtern wird die männliche Form genutzt. Diese Begriffe gelten für alle Geschlechter
        </p>
        
    </div>
</section>
<div class="section-divider"><img src="/wp-content/uploads/2024/06/ellipse_11-1.webp" ></div>
    </div>
</div>

<!--WELCOME SECTION TEXT  END-->

<!--POSITION TO APPLY SECTION   START-->
<div class="container-apply-section">
    <div class="container-14">
    <section class="position-apply-section-wrapper">
    <div class="postition-applay-section-underwrapper">
        <div class="position-apply-cards-wrapper">
        <div class="single-position-appy-card">
            <h2>Augenoptiker-Meister (m/w/d)</h2>
            <div class="apply-card-text-button">
            <p>Deine Aufgaben:</p>
            <ul>
                <li>Beratung und Verkauf</li>
                <li>Refraktion</li>
                <li>Kontaktlinsenanpassung </li>
                <li>Unseren Kunden perfektes Sehen ermöglichen</li>
            </ul>
            <p>Dein Profil:</p>
            <ul>
                <li>Erfolgreicher Abschluss als Augenoptikermeister</li>
                <li>Freundliches und sicheres Auftreten</li>
            </ul>
            <div class="apply-card-button-wrapper"><button class="apply-here-btn button-type-3">Apply here <img src="/wp-content/uploads/2024/06/vector_4-1.webp" alt=""></button></div>
            
            </div>
            
        </div>

        <div class="single-position-appy-card">
            <h2>Augenoptiker (m/w/d)
            </h2>
            <div class="apply-card-text-button">
            <p>Deine Aufgaben:</p>
            <ul>
                <li>Beratung und Verkauf</li>
                <li>Unseren Kunden perfektes Sehen ermöglichen</li>
                <li>Werkstattarbeit
                </li>
                
            </ul>
            <p>Dein Profil:</p>
            <ul>
                <li>Erfolgreicher Abschluss als Augenoptiker
                </li>
                <li>Freundliches und sicheres Auftreten</li>
            </ul>
            <div class="apply-card-button-wrapper"><button class="apply-here-btn button-type-3">Apply here <img src="/wp-content/uploads/2024/06/vector_4-1.webp" alt=""></button></div>
            
            </div>
            
        </div>

        <div class="single-position-appy-card">
            <h2>Ausbildung zum Augenoptiker (m/w/d)</h2>
            <div class="apply-card-text-button">
            <p>Apprenticeship lorem ipsum sit
            ametcon sec tetur adipisicing
            eiusmod tempor incididunt ut.</p>
            <div class="apply-card-button-wrapper"><button class="apply-here-btn button-type-3">Apply here <img src="/wp-content/uploads/2024/06/vector_4-1.webp" alt=""></button></div>
            
            </div>
            
        </div>

        <div class="single-position-appy-card">
            <h2>Schülerparktikum<br> (m/w/d)
            </h2>
            <div class="apply-card-text-button">
            <p>Student internship lorem ipsum sit
            ametcon sec tetur adipisicing
            eiusmod tempor incididunt ut.</p>
            <div class="apply-card-button-wrapper"><button class="apply-here-btn button-type-3">Apply here <img src="/wp-content/uploads/2024/06/vector_4-1.webp" alt=""></button></div>
            
            </div>
            
        </div>
        </div>
        
    </div>
    <div class="section-divider"><img src="/wp-content/uploads/2024/06/ellipse_11-1.webp" ></div>
</section>

    </div>
</div>

<!--POSITION TO APPLY SECTION   END-->

<!--MODAL SECTION   START-->

<!-- MODAL 1 START -->
<section class="modal-wrapper">
<div class="modal-underwrapper">
    <div class="modal-main">
        <span class="exit-icon-wrapper"><img class="exit-icon" src="/wp-content/uploads/2024/06/frame_876.webp" ></span>
        <h2>Bewerbungsformular für  Augenoptiker-Meister</h2>
        <div class="job-application-form-wrapper">
        <div class="job-offer-form">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 11 ) ); ?>
        </div>
        </div>
    </div>
</div>
    
</section>
<!-- MODAL 1 END -->

<!-- MODAL 2 START -->
<section class="modal-wrapper">
<div class="modal-underwrapper">
    <div class="modal-main">
        <span class="exit-icon-wrapper"><img class="exit-icon" src="/wp-content/uploads/2024/06/frame_876.webp" ></span>
        <h2>Bewerbungsformular für Augenoptiker</h2>
        <div class="job-application-form-wrapper">
        <div class="job-offer-form">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 9 ) ); ?>
        </div>
        </div>
    </div>
</div>
    
</section>
<!-- MODAL 2 END -->

<!-- MODAL 3 START -->
<section class="modal-wrapper">
<div class="modal-underwrapper">
    <div class="modal-main">
        <span class="exit-icon-wrapper"><img class="exit-icon" src="/wp-content/uploads/2024/06/frame_876.webp" ></span>
        <h2>Bewerbungsformular für Ausbildung zum Augenoptiker</h2>
        <div class="job-application-form-wrapper">
        <div class="job-offer-form">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 10 ) ); ?>
        </div>
        </div>
    </div>
</div>
    
</section>
<!-- MODAL 3 END -->

<!-- MODAL 4 START -->
<section class="modal-wrapper">
<div class="modal-underwrapper">
    <div class="modal-main">
        <span class="exit-icon-wrapper"><img class="exit-icon" src="/wp-content/uploads/2024/06/frame_876.webp" ></span>
        <h2>Bewerbungsformular für Schülerparktikum</h2>
        <div class="job-application-form-wrapper">
        <div class="job-offer-form">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 6 ) ); ?>
        </div>
        </div>
    </div>
</div>
    
</section>
<!-- MODAL 4 END -->
<!--MODAL SECTION   END-->



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

<script>

$(document).ready(function(){

/**OTVARANJE I ZATVARANJE  MODALA */ 



    
    const applyButtons = document.querySelectorAll('.apply-here-btn');
    const modals =document.querySelectorAll('.modal-wrapper');
    const exitIcons = document.querySelectorAll('.exit-icon-wrapper');
    const cancelButtons = document.querySelectorAll('.cancel-button ');


    applyButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            
            modals[index].style.display = 'block';
            document.body.classList.add('no-scroll');
            
        });
    });

   
   exitIcons.forEach(function(icon,index){
    icon.addEventListener('click', () => {
        modals[index].style.display = 'none';
        document.body.classList.remove('no-scroll');
    });
   });

   cancelButtons.forEach(function(cancelButton,index){
    cancelButton.addEventListener('click', () => {
        modals[index].style.display = 'none';
        document.body.classList.remove('no-scroll');
    });
   })

   
});
/**OTVARANJE I ZATVARANJE  MODALA **/ 
</script>
