<?php
//Template Name: Datenschutz
get_header();
?>

<!-- MAIN TITLE SECTION START -->
<div class="datenschutz-container">
    <div class="container-14">
        <div class="datenschutz-wrapper">
            <h1>Datenschutzerklärung</h1>
            <p>Datenschutzerklärung für Webseitennutzer von Hofmannoptik e. K.</p>
        </div>
    </div>
</div>
<div class="daten-divider"></div>
<!-- MAIN TITLE SECTION END -->

<!-- TEXT SECTION START -->
 <div class="datenschutz-container">
    <div class="container-14">
        <div class="text-section-wrapper">
            <?php
            if(have_rows('datenschutz_section')):
                while(have_rows('datenschutz_section')):
                    the_row();
                    $daten_heading = get_sub_field('datenschutz_heading');
                    ?>
                    <div class="daten-wrapper">
                        <h2><?php echo $daten_heading ?></h2>
                        <?php
                        if(have_rows('datenschutz_sub_section')):
                            while(have_rows('datenschutz_sub_section')):
                                the_row();
                                $daten_sub_heading = get_sub_field('datenschutz_sub_heading');
                                $daten_content = get_sub_field('datenschutz_content');
                                ?>
                                <h3><?php echo $daten_sub_heading ?></h3>
                                <p><?php echo $daten_content ?></p>
                                <?php
                            endwhile;
                        endif;
                        ?>
                    </div>
                    <?php
                endwhile;
            endif;  
            ?>
            
    </div>
 </div>
 <!-- TEXT SECTION END -->
<?php get_footer(); ?>