<?php
get_header();
?>
<section class="shop-category-section-wrapper">
    <div class="shop-category-section-underwrapper">
        <div class="search-div">
            <h1>Our products</h1>
            <div class="product-search-bar">
            <?php
    
            the_widget('WC_Widget_Product_Search');
            ?>
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
                    if ( $image ) {
                        echo '<img src="' . esc_url( $image ) . '"/>';
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
<?php
if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;

// WooCommerce loop za prikaz proizvoda
if ( function_exists( 'woocommerce_product_loop' ) ) {
    woocommerce_product_loop_start();
    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();
            wc_get_template_part( 'content', 'product' );
        }
    }
    woocommerce_product_loop_end();
}
?>
<?php
get_footer();
?>