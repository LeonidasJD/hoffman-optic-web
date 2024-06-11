<div class="header-wrapper">
<?php get_header(); ?>

</div>


<section class="category-wrapper">
    <div class="category-under-wrapper">
    <?php

require_once(ABSPATH . 'wp-load.php');

$args = array(
    'type'         => 'napredak', 
    'hide_empty'   => 1, 
    'taxonomy'     => 'category', 
    'exclude'      => array(7), //ID kategorije koje ne želim da izlistam
);

// Dobijanje kategorija
$categories = get_categories($args);

$active_category_ID = get_queried_object_id();

// Ručno postavljanje redosleda kategorija
$category_order = array(
    '2024', 
    '2023', 
    '2022', 
    '2021',
    'Старија издања'
  
);



foreach($category_order as $category_name) {
   
    foreach($categories as $category) {
        if($category->name == $category_name) {

            $active_class = ($category->term_id === $active_category_ID ) ? 'active-category' : '';
            
            echo '<div class="single-category-wrapper single-category-wrapper-desktop">';
            echo '<a href="' . get_category_link($category->term_id) . '" class="'.$active_class.'">' . $category->name . '</a><br>';
            echo '</div>';
            break; 
        }
    }
}


?>

<div class="swiper mySwiper my-custom-swiper">
    <div class="swiper-wrapper">
<?php
foreach($category_order as $category_name) {
    foreach($categories as $category) {
        if($category->name == $category_name) {
            $active_ = ($category->term_id === $active_category_ID ) ? 'active-category' : '';
            echo '<div class="swiper-slide">';
            echo '<a href="' . get_category_link($category->term_id) . '" class="'. $active_ . '">' . $category->name . '</a><br>';
            echo '</div>';
            break; 
        }
    }
}
?>
    </div>
</div>

    </div>
</section>

<!--HERO TEXT SECTION START-->
<section class="hero-text-wrapper">
    <div class="hero-button-underwrapper">
        <button id="hero-button"><img src="/wp-content/uploads/2024/05/iconamoon_arrow-up-2-duotone-1.png"></button>
    </div>
    <div class="hero-text-underwrapper">
       <?php

if (strpos($_SERVER['REQUEST_URI'], '/en') !== false) {
   
    ?>
    <h1>Journal "Napredak"</h1>
    <p>The journal NAPREDAK publishes academic papers about political theory and political practice
– in the broadest terms. Our wish is to promote political culture and dialogue through this
journal, to enable academic authors, as well as policy creators and implementors to publish their
papers referring to international relations, geopolitics, Euro-integrations, social matters in
general, public policies, as well as political tradition, history, remembrance culture etc. In that
respect, NAPREDAK is the encounter place of theory and practice in the field of politics.</p>
    <?php
} else {
    
    ?>
    <h1>Часопис “Напредак”</h1>
    <p>Часопис НАПРЕДАК објављује академске радове који се односе на политичку теорију и политичку праксу – у најширем смислу. Жеља нам је да кроз овај часопис промовишемо политичку културу и дијалог, да омогућимо академским ствараоцима, али и креаторима и спроводиоцима политика да објављују своје радове који се односе на међународне односе, геополитику, евроинтеграције, друштвена питања уопште, јавне политике, али и на политичку традицију, историју, културу сећања итд. У том смислу, НАПРЕДАК је место сусрета теорије и праксе у области политике.</p>
    <?php
}
?>
    </div>
</section>
<!--HERO TEXT SECTION END-->

<!--POST SECTION START-->
<section class="posts-wrapper">
    <div class="posts-underwrapper">
       
        <?php
       $args = array(
        'post_type' => 'napredak', // Post type
        'posts_per_page' => -1, // Broj postova za prikazivanje (-1 za prikazivanje svih)
        'tax_query' => array(
            array(
                'taxonomy' => 'category', // Taxonomy
                'field'    => 'term_id',
                'terms'    => 12, // ID kategorije
            ),
        ),
    );
    
   
    $query = new WP_Query($args);
    
   
    if($query->have_posts()) {
        
        while($query->have_posts()) {
            $query->the_post();


            $kratak_opis = get_field('kratak_opis');
            ?>
            <div class="single-post-card">
                <div class="post-image">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>">
                </div>
                <div class="post-infoo">
                 <div class="post-date-mob"><p><?php echo get_the_date(); ?></p></div>   
                <h3><?php echo get_the_title() ?></h3>
                <div class="post-excerpt">
                <?php echo $kratak_opis?>
                </div>
                <div class="date-button-wrapper">
                <div class="post-date">
                    <p><?php echo get_the_date(); ?></p>
                    
                </div>
                <div class="post-button">
               <?php
			
				if (strpos($_SERVER['REQUEST_URI'], '/en') !== false) {
					
					?>
					<button class="preuzmi-button">Download <img src="/wp-content/uploads/2024/05/bi_filetype-pdf.png" ></button>
					<?php
				} else {
					
					?>
					<button class="preuzmi-button">Преузми <img src="/wp-content/uploads/2024/05/bi_filetype-pdf.png" ></button>
					<?php
				}
				?>
                <a href="<?php echo get_permalink(); ?>"><img src="/wp-content/uploads/2024/05/iconamoon_arrow-up-2-duotone.png"></a>
                </div>
                <!--ELEMENTI KOJI SU DISPLAY NONE NA STRANICI KATEGORIJE ALI SE PREUZIMAJU I POSTAVLJAJU U MODAL START-->
                <div class="post-title"><?php if (strpos($_SERVER['REQUEST_URI'], '/en') !== false) {
					  ?>
						<p>Download <?php echo get_the_title(); ?></p>
						<?php
					} else {

						?>
						<p>Преузми <?php echo get_the_title(); ?></p>
						<?php
					}
					?></div>
                <div class="casopisi">
                <div class="pdf-casopisa-srpski">
                <?php 
                        // casopis na srpskom
                        $pdf_casopisa_na_srpskom = get_field('pdf_casopisa_srpski');
                        if($pdf_casopisa_na_srpskom):
                        ?>
                        <a href="<?php echo $pdf_casopisa_na_srpskom; ?>" target="_blank">
                        <p class="link-casopisa-wrapper"><img src="/wp-content/uploads/2024/05/Vector.png">
                         
                         <?php 
                        //labela na srpskom
                        $labela_za_srpski_jezik = get_field('labela_za_srpski_jezik');
                        if($labela_za_srpski_jezik):
                     ?>
                    <p class="labela-linka"><?php echo $labela_za_srpski_jezik; ?></p>
                    <?php endif; ?>
                        </a>
                        </p>
                    <?php endif; ?>

                    
            </div>

            <div class="pdf-casopisa-engleski">
            <?php 
                       // casopis na engleskom
                        $pdf_casopisa_na_engleskom = get_field('pdf_casopisa_na_engleskom');
                        if($pdf_casopisa_na_engleskom):
                     ?>
                     <a href="<?php echo $pdf_casopisa_na_engleskom; ?>" target="_blank">
                        <p class="link-casopisa-wrapper"><img src="/wp-content/uploads/2024/05/Vector.png">
                         
                         <?php 
                       //labela na engleskom
                        $labela_za_engleski_jezik = get_field('labela_za_engleski_jezik');
                        if($labela_za_engleski_jezik):
                    ?>
                     <p class="labela-linka"><?php echo $labela_za_engleski_jezik; ?></p>
                    <?php endif; ?>
                        </a>
                        </p>
                       <?php endif; ?>

                    
            </div>

            <div class="pdf-casopisa-kineski">
             <?php 
                       // casopis na kineskom
                        $pdf_casopisa_na_kineskom = get_field('pdf_casopisa_na_kineskom');
                        if($pdf_casopisa_na_kineskom):
                     ?>
                     <a href="<?php echo $pdf_casopisa_na_kineskom; ?>" target="_blank">
                        <p class="link-casopisa-wrapper"><img src="/wp-content/uploads/2024/05/Vector.png">
                         
                         <?php 
                       //labela za kineski
                        $labela_za_kineski_jezik = get_field('labela_za_kineski_jezik');
                        if($labela_za_kineski_jezik):
                    ?>
                    <p class="labela-linka"> <?php echo $labela_za_kineski_jezik; ?></p>
                    <?php endif; ?>
                        </a>
                        </p>
                    <?php endif; ?>

                    
            </div>
            </div>
               <!--ELEMENTI KOJI SU DISPLAY NONE NA STRANICI KATEGORIJE ALI SE PREUZIMAJU I POSTAVLJAJU U MODAL END--> 

                </div>
            </div>
            </div>
            <?php
        }
       
    } else {
       
        echo 'Nema pronađenih postova.';
    }
    
    // Resetovanje upita
    wp_reset_postdata();
       
        ?>
    </div>
</section>
<!--POST SECTION END-->

<!--BANNER SECTION START-->
<section class="banner-section-wrapper">
    <div class="banner-section-underwrapper">
        <div class="banner-heading">
            <p>Индексираност часописа у базама</p>
        </div>
        <div class="banner-images">
            <a href="https://scindeks.ceon.rs/journaldetails.aspx?issn=2683-6106&lang=sr" target="_blank"><img src="/wp-content/uploads/2024/05/scindeks.png" ></a>
            
            <img src="/wp-content/uploads/2024/05/Vector-7.png">
            <a href="https://kanalregister.hkdir.no/publiseringskanaler/erihplus/periodical/info.action?id=504790" target="_blank"><img src="/wp-content/uploads/2024/05/erihplus.png" ></a>
            
        </div>
    </div>
</section>
<!--BANNER SECTION END-->


<!--MODAL SECTION START-->
<div id="myModal" class="modal">
    <div class="modal-underwrapper">
        <div class="close-button-wrapper">
        <button id="close-modal-btn"><img src="/wp-content/uploads/2024/05/solar_close-circle-linear.png" ></button>
        </div>
  <div class="modal-title"></div>
 <?php

if (strpos($_SERVER['REQUEST_URI'], '/en') !== false) {
    
    ?>
    <p class="pomocni-text">Choose PDF language</p>
    <?php
} else {
   
    ?>
    <p class="pomocni-text">Изаберите на ком језику ћете скинути ПДФ</p>
    <?php
}
?>
    <div class="modal-content">
    </div>
 
    
    <div class="pdf-casopisa"></div>
  </div>

</div>
<!--MODAL SECTION END-->



<?php get_footer(); ?>

<script>

// Dohvati modal
var modal = document.getElementById("myModal");
var btn = document.getElementById("modalBtn");
var closeModal =document.getElementById("close-modal-btn");


var downloadButton = document.querySelectorAll('.preuzmi-button');


downloadButton.forEach(function(button, index) {
    
    button.addEventListener('click', function() {
        
        var naslovCasopisa = document.getElementsByClassName('post-title')[index];
        var casopisiSection = document.querySelectorAll('.casopisi')[index];
      
        modal.style.display = "flex";
       
        modal.querySelector('.modal-title').innerHTML = naslovCasopisa.innerHTML;
        modal.querySelector('.modal-content').innerHTML = casopisiSection.innerHTML;
    });
});

closeModal.onclick = function() {
  modal.style.display = "none";
}

var backButton = document.getElementById('hero-button');

backButton.addEventListener('click', function(){
    location.href = "/nasa-izdanja/";

});
</script>

<!--SWIPER SCRIPT START -->
<script>
    var swiper = new Swiper(".mySwiper", {
       slidesPerView:2,
        
    });
</script>
<!--SWIPER SCRIPT END -->