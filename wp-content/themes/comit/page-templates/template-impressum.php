<?php 
//Template Name: Impressum
get_header();

?>
<div class="impressum-container">
    <div class="container-14">
        <div class="impressum-container-wrapper">
        <h1>Impressum</h1>
        </div>
        
    </div>
</div>

<div class="impressum-content-container">
    <div class="container-14">
        <div class="impresum-content-wrapper">
            <?php 
            if(have_rows('impressum_group')):
                while(have_rows('impressum_group')) :
                    the_row();
                    $impressum_heading = get_sub_field('impresum_heading');
                    $impressum_content = get_sub_field('impressum_content');

                    echo '<div class="impresum-content-underwrapper">';
                    if($impressum_heading){
                        ?>
                        <div class="impresum-heading"><h2><?php echo $impressum_heading ?></h2"></div>
                        <?php
                    }

                    if($impressum_content){
                        ?> 
                        <div class="impressum-content"><p><?php echo $impressum_content ?></p"></div>
                        <?php
                    }

                    echo '</div>';
                endwhile;
                endif;
            ?>
        </div>
    </div>
</div>
<?php get_footer() ?>