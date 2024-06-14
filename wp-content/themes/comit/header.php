<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package comit
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<!--FONTS START-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Glegoo:wght@400;700&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lustria&display=swap" rel="stylesheet">
<!--FONTS END-->

<!--OPEN STREET MAPS START-->
<link rel = "stylesheet" href = "http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
<script src = "http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<!--OPEN STREET MAPS END-->

<!--SWIPER JS START-->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!--SWIPER JS END-->

<!-- FONT AWESOME START -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 <!-- FONT AWESOME END -->


	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'comit' ); ?></a>

	<!-- DESKTOP HEADER START -->
	<div class="container-header">
		<div class="container-14">
		<header id="masthead" class="site-header">
		<div class="header-wrapper">
			<div class="header-under-wrapper">
			<div><?php  wp_nav_menu( 
        array( 
            'theme_location' => 'menu-1'
        ) 
    );  ?></div>
		<div class="header-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="/wp-content/uploads/2024/06/logo-heder.webp" alt=""></a></div>
		<div class="button-wrapper">
			<a class="button-type-1 shop-button" href="/shop/">Produkte</a>
			<a class="button-type-2 contact-button" href="/kontakt/">Kontakt</a>
		</div>
			</div>
			<!-- <div class="horizontal-line"></div> -->
		
		</div>
		
	</header><!-- #masthead -->
		</div>
	</div>
	<!-- DESKTOP HEADER END -->


<!-- MOBILE HEADER START -->
<div class="mobile-heahder-wrapper">
		<header>
			<div class="header-items-wrapper">
			<div><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="mobile-logo" src="/wp-content/uploads/2024/06/logotype-2.webp" ></a></div>
			<div class="btn-and-hamburger">
			<div class="header-contact-button"><a href="/contact-us/">Contact</a></div>
			<div id="open-dropdown-menu" class="hamburger-wrapper"><img class="hamburger-icon" src="/wp-content/uploads/2024/06/Frame-876.webp" ></div>
			</div>
			
			</div>
			
		</header>
</div>

<section id="under-wrapper" class="under-menu-wrapper">
	<div class="close-btn-logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="dropdown-logo" src="/wp-content/uploads/2024/06/layer_1_2x.webp" alt=""></a>
		<button id="close-mobile-menu-btn"><img src="/wp-content/uploads/2024/06/vector_2x.webp" alt=""></button>
	</div>
<div><?php 
    wp_nav_menu( 
        array( 
            'theme_location' => 'mobile-menu'
        ) 
    ); 
?></div>
<div class="book-now-section">
<div class="book-now-section1">
	<div class="shop-image-mob">
		
	</div>
	<div class="shop-wrapper-mob">
	<p>Our store <br>in Neuchatel </p>
	<button id="neu-button" class="book-now-menu">Book now <img src="/wp-content/uploads/2024/06/vector_4.webp" alt=""></button>
	</div>
</div>
<div class="book-now-section1">
	<div class="shop-image-mob2">
		
	</div>
	<div class="shop-wrapper-mob">
	<p>Our store <br>in Müllheim </p>
	<button id="mull-button" class="book-now-menu">Book now <img src="/wp-content/uploads/2024/06/vector_4.webp" alt=""></button>
	</div>
</div>

</div>


</section>
<!-- MOBILE HEADER END -->


<!-- OPEN CLOSE MOBILE MENU START -->
<script>
var openDropwdown =document.getElementById('open-dropdown-menu');
var closeDropdown = document.getElementById('close-mobile-menu-btn');
var underMenuWrapper =document.getElementById('under-wrapper');

openDropwdown.addEventListener('click', function(){
	underMenuWrapper.style.top = "0%";
});
closeDropdown.addEventListener('click', function(){
underMenuWrapper.style.top = "-220%";
});

var neuButton =document.getElementById('neu-button');
var mullButton = document.getElementById('mull-button');

neuButton.addEventListener('click', function(){
	window.open("https://www.click2date.eu/hoffmann-optik-neuenburg/appointment/start", "_blank");
});
mullButton.addEventListener('click', function(){
	window.open("https://www.click2date.eu/hoffmann-Optik-muellheim/appointment/start ", "_blank");
});
</script>
<!-- OPEN CLOSE MOBILE MENU END -->