<?php get_header();?>

<!-- IMAGE AND PRODUCT INFO AND ATRIBUTES START-->
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();
        ?>
       
        <div class="container-single-product-info">
            <div class="container-14">
            <div class="single-product-wrapper">
            <div class="product-gallery">
            <?php if (has_post_thumbnail()) {
                $thumbnail_id = get_post_thumbnail_id();
                $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full', true)[0];
                ?>
                
            <?php
            echo do_shortcode('[product_gallery_slider]');
            ?>
            </div>
            <div class="product-info">
                <h1><?php echo the_title() ?></h1>
                <p class="product-description"><?php echo get_the_excerpt()?></p>
                <button id="related-modal-button" class="button-type-3">Book this product <img src="/wp-content/uploads/2024/05/Vector-4-1.png" alt=""></button>
                <div class="product-atributes-wrapper">
    <div class="product-atributes">
    <?php 
    global $product;
    if($product){
        
        $product_ID = $product->get_id();
        $additional_information = get_field('additional_information');
       
        $attributes = $product->get_attributes();
        $counter = 1; // Brojac za praćenje trenutnog atributa

        foreach ($attributes as $attribute) {
            $attribute_label = wc_attribute_label(($attribute->get_name()));
            $attribute_values = $attribute->get_terms();

            if($attribute_label && $attribute_values){
                ?> 
                <div class="single-attribute">
                    
                    <div class="attribute-icon-and-name">
                        <?php
                        // Dodati sliku u zavisnosti od brojaca
                        $image_path = '';
                        switch ($counter) {
                            case 1:
                                $image_path = "/wp-content/uploads/2024/05/Frame-956222.png";
                                break;
                            case 2:
                                $image_path = "/wp-content/uploads/2024/05/Frame-956.png";
                                break;
                            case 3:
                                $image_path = "/wp-content/uploads/2024/05/Frame-95623.png";
                                break;
                            case 4:
                                $image_path = "/wp-content/uploads/2024/05/Frame-9563.png";
                                break;
                            case 5:
                                $image_path = "/wp-content/uploads/2024/05/12.png";
                                break;
                            default:
                                break;
                        }
                        ?>
                        <img src="<?php echo $image_path; ?>" alt="Attribute Image">
                        <h3><?php echo $attribute_label ?></h3>
                    </div>
                    
                    <?php foreach($attribute_values as $attribute_value){
                        ?>
                        <p><?php echo $attribute_value->name; ?></p>
                        <?php
                    } ?>
                </div>
                <?php

                $counter++; // Povećaj brojač nakon svakog atributa
            } else {
                echo "This product has no attributes!";
            }
        }
    }
    ?>
    </div>
</div>


            </div>
            </div>
            </div>
        </div>
       
        <?php
    }
}
} else {
    echo "Single product not found";
}
?>
<!-- IMAGE AND PRODUCT INFO AND ATRIBUTES END-->

<!-- PRODUCT TAB DESCRIPTION AND ADDITIONAL INFORMATION START-->
<div class="container-tabs-product-desc">
    <div class="container-14">
        <div class="tab-description-wrapper">
        <div class="tab-buttons-wrapper">
        
    <button id="tab-button-1" class="single-tab-button button-active" onclick="openTab('tab1','tab-button-1')">Description</button>
    <button id="tab-button-2" class="single-tab-button" onclick="openTab('tab2','tab-button-2')">Additional information</button>
    <div class="tab-button-divider"></div>
    
    </div>

    <div id="tab1" class="tab">
    
    <div class="short-description"><p><?php echo get_the_content()?></p></div>
    
    </div >

    <div id="tab2" class="tab" style="display:none">
    
    <div class="additional-information"><p><?php echo $additional_information ?></p></div>
    </div>
        </div>
        

    
    </div>
</div>

<!-- PRODUCT TAB DESCRIPTION AND ADDITIONAL INFORMATION START-->

<!-- RELATED PRODUCTS SECTION START -->
<div class="container-related-products">
    <div class="container-14">
        <section class="related-product-section-wrapper">
            <div class="related-product-section-underwrapper">
                <div class="head-and-text-related">
                <h2>Related Products</h2>
                <p>Dignissim massa duis eget turpis fringilla nam ridiculus ultricies urna vehicula fusce. Elit interdum urna iaculis leo sit pharetra vel. Vitae letius luctus lacinia rhoncus penatibus per.</p>
                </div>
                <div class="related-single-products-wrapper">
    <?php
    global $product;

    // Dobijanje ID-a kategorije trenutnog proizvoda
    $category_ids = $product->get_category_ids();

    if (!empty($category_ids)) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $category_ids,
                    'operator' => 'IN'
                )
            ),
            'post__not_in' => array($product->get_id()) // Isključuje trenutni proizvod
        );

        $products = new WP_Query($args);

        if ($products->have_posts()) {
            echo '<div class="related-products-wrapper">';
            while ($products->have_posts()) {
                $products->the_post();
                ?>
                <a href="<?php echo get_permalink() ?>">
                <div class="related-single-product-card">
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
            echo 'There are no related products.';
        }
    } else {
        echo 'Current product has no categories.';
    }
    ?>
</div>


            </div>
        </section>
    </div>
</div>
<!-- RELATED PRODUCTS SECTION END -->

<!-- PRODUCT FORM MODAL START -->
<section id="rel-modal" class="related-modal-wrapper">
<div class="related-modal-underwrapper">
    <div class="related-modal-main">
        <div id="icon-exit" class="exit-icon"><img src="/wp-content/uploads/2024/05/Frame-876.png"></div>
        <div class="related-modal-heading">
            <h3>You are booking:</h3>
            <p><?php echo the_title() ?></p>
        </div>
        <div class="single-product-form">
        <div class="form-single-product">
        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 8 ) ); ?>
        </div>
        </div>
    </div>
</div>
    
</section>
<!-- PRODUCT FORM MODAL END -->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button') ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<?php get_footer();?>

<!-- SKRIPTA ZA POKRETANJE TABOVA START -->
<script>
function openTab(tabId, buttonId) {
  var i;
  var x = document.getElementsByClassName("tab");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(tabId).style.display = "block";

  // Dodavanje klase "button-active" na kliknuto dugme
  var buttons = document.getElementsByClassName("single-tab-button");
  for (i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove("button-active");
  }
  document.getElementById(buttonId).classList.add("button-active");
}
</script>
<!-- SKRIPTA ZA POKRETANJE TABOVA START -->

<!-- SKRIPTA ZA OTVARANJE MODALA START -->
<script>
var openModalButton =document.getElementById('related-modal-button');
var relatedModal =document.getElementById('rel-modal');
var exitIconModal =document.getElementById('icon-exit');
var cancelModals =document.getElementsByClassName('cancel-button');

openModalButton.addEventListener("click",function(){
relatedModal.style.display="block";
});

exitIconModal.addEventListener("click",function(){
relatedModal.style.display="none";
});

for(var i = 0; i < cancelModals.length; i++){
    var cancelModal =cancelModals[i];

    cancelModal.addEventListener("click", function(){
    relatedModal.style.display="none";
});
}



</script>
<!-- SKRIPTA ZA OTVARANJE MODALA END -->


<!-- SKIRPTA ZA POSTAVLJANJE URL-ADRESE TRENUTNE STRANICE START -->
<script>
    document.getElementById('field_avsnp').value = "<?php echo get_permalink(); ?>";
</script>
<!-- SKIRPTA ZA POSTAVLJANJE URL-ADRESE TRENUTNE STRANICE START -->