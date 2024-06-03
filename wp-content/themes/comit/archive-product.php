<?php
get_header();
?>
<!--SEARCH AND CATEGORY SECTION START-->
<div class="container-search-and-category">
    <div class="container-14">
    <section class="shop-category-section-wrapper">
    <div class="shop-category-section-underwrapper">
        <div class="search-div">
            <h1>Our products</h1>
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
        <div class="filter-section">
        <?php echo do_shortcode('[br_filter_single filter_id=287]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=293]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=295]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=297]'); ?>
        <?php echo do_shortcode('[br_filter_single filter_id=298]'); ?>
       

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
</div>

<!--FILTER AND PRODUCT SECTION END-->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button') ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<?php
get_footer();
?>

