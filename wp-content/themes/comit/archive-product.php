<?php
get_header();
?>
<!--SEARCH AND CATEGORY SECTION START-->
<div class="container-search-and-category">
    <div class="container-14">
    <section class="shop-category-section-wrapper">
    <div class="shop-category-section-underwrapper">
        <div class="search-div">
            <h1>Unsere produkte</h1>
            <div class="product-search-bar">
                <div class="test"> <?php echo do_shortcode('[yith_woocommerce_ajax_search preset=default]'); ?></div>

            <!--SEARCH BAR BEZ AJAXA KOJI NE KORISTIMO-->
            <!-- <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	            <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
	            <input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search specific product', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	            <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>" class="<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"><img src="/wp-content/uploads/2024/05/bi_search.png" alt=""></button>
	            <input type="hidden" name="post_type" value="product" />
            </form>  -->
            <!--SEARCH BAR BEZ AJAXA KOJI NE KORISTIMO-->
            
            </div>
        </div>
        <div class="category-div">
            <?php 
            $categories = get_terms( 'product_cat', array(
                'hide_empty' => false, // Prikazujemo i prazne kategorije
            ) );

            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                echo '<ul class="category-list">';
                foreach ( $categories as $category ) {
                    // Dohvatamo URL slike kategorije ako postoji
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                    $image = wp_get_attachment_url( $thumbnail_id );
                    
                    
                    echo '<li class="category-item">';
                    // Prikazujemo sliku kategorije ako postoji
                    if ($image) {
                        echo '<a href="' . esc_url(get_term_link($category)) . '">';
                        echo '<div class="category-image-wrapper ';
                        echo is_tax('product_cat', $category->slug) ? 'current-category' : '';
                        echo '">';
                        echo '<img src="' . esc_url($image) . '"/>';
                        echo '</div>';
                        echo '</a>';
                    }
                    // Prikazujemo naziv kategorije
                    echo '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            }
            ?>
        </div>
    </div>
</section>
    </div>
</div>

<!--SEARCH AND CATEGORY SECTION END-->

<!--FILTER AND PRODUCT SECTION START-->
<div class="container-filter-and-product">
    <div class="container-14">
    <section class="filter-and-product-section-wrapper">
    <div class="filter-and-product-section-underwrapper">
        <div id="filterss" class="filter-section">
        <?php echo do_shortcode('[br_filter_single filter_id=287]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=293]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=295]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=297]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=298]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=456]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=457]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=458]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=480]'); ?>
       

        </div>
        <div  class="more-filter-button-wrapper">
            <button id="more-filters-btn">
            <span id="more-filters-text">More filters</span>
            <svg id="more-filters-svg" width="13" height="9" viewBox="0 0 13 9" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path id="more-filters-img" fill="#76BA51" d="M6.5 8.5L0 0.5L13 0.500001L6.5 8.5Z" />
            </svg>
            </button>
        </div>
		<div class="product-section">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
        endwhile;
    endif;

    // WooCommerce loop za prikaz proizvoda
    if ( function_exists( 'woocommerce_product_loop' ) ) {
        woocommerce_catalog_ordering();
        woocommerce_product_loop_start();
        
        if ( wc_get_loop_prop( 'total' ) ) {
            while ( have_posts() ) {
                the_post();
                wc_get_template_part( 'content', 'product' );
            }
        }
        woocommerce_product_loop_end();
        do_action( 'woocommerce_after_shop_loop' );
    }
    ?>
   
</div>



    </div>

</section>
    </div>
    <!--BANNER SECTION START-->
    <div class="wrapper-for-mobile">
           <div class="container-banner-section">
    <div class="container-14">
    <section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
    </div>
</div>
           </div>


<!--BANNER SECTION END-->
</div>

<!--FILTER AND PRODUCT SECTION END-->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button') ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<?php
get_footer();
?>


<script>
    // SHOW AND HIDE FILTERS ON MOBILE START
var moreFilterBtn = document.getElementById('more-filters-btn');
var filterSection = document.getElementById('filterss');
var moreFiltersIcon = document.getElementById('more-filters-img');
var moreFiltersText = document.getElementById('more-filters-text');
var moreFiltersSvg = document.getElementById('more-filters-svg');

moreFilterBtn.addEventListener('click', function() {
    if (filterSection.style.display === 'block') {
        filterSection.style.display = 'none';
        moreFiltersText.textContent = 'More filters';
        moreFilterBtn.style.border = '1px solid #76BA51';
        moreFilterBtn.style.color = '#76BA51';
        moreFiltersIcon.setAttribute('fill', '#76BA51');
        moreFiltersSvg.style.rotate = '0deg';
    } else {
        filterSection.style.display = 'block';
        moreFiltersText.textContent = 'Less filters';
        moreFilterBtn.style.border = '1px solid black';
        moreFilterBtn.style.color = 'black';
        moreFiltersIcon.setAttribute('fill', 'black');
        moreFiltersSvg.style.rotate = '180deg';
        
    }
});
// SHOW AND HIDE FILTERS ON MOBILE END
</script>
