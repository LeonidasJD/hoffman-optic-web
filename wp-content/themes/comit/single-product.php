<?php get_header();?>


<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();
        ?>
        <div class="single-product-wrapper">
            <div class="product-image">
            <?php if (has_post_thumbnail()) {
                $thumbnail_id = get_post_thumbnail_id();
                $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full', true)[0];
                ?>
                <img src="<?php echo $thumbnail_url?>" alt="">
            </div>
            <div class="product-info">
                <h1><?php echo the_title() ?></h1>
                <p class="product-description"><?php echo get_the_content()?></p>
                <button>Book this product</button>
                <div class="product-atributes-wrapper">
    <div class="product-atributes">
    <?php 
    global $product;
    if($product){
        $attributes = $product->get_attributes();
        $counter = 1; // Brojac za praćenje trenutnog atributa

        foreach ($attributes as $attribute) {
            $attribute_label = wc_attribute_label(($attribute->get_name()));
            $attribute_values = $attribute->get_terms();

            if($attribute_label && $attribute_values){
                ?> 
                <div class="single-attribute">
                    <div>
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
        <?php
    }
}
} else {
    echo "Single product not found";
}
?>

<?php get_footer();?>