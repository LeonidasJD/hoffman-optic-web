<a?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package comit
 */

?>
<div class="container-footer">
	<div class="container-14">
	<footer id="colophon" class="site-footer">
		<section class="footer-logo-section">
			<div><img src="/wp-content/uploads/2024/06/footer-logo.webp" alt="footer logo"></div>
		</section>
		<section class="footer-navi-section">
			<div>
				<h2>Menü</h2>
				<nav>
					<?php wp_nav_menu( 
        array( 
            'theme_location' => 'footer-menu'
        ) 
    ); ?>
				</nav>
			</div>
			<div class="footer-divider"></div>
			<div>
			<h2>Öffnungszeiten</h2>
			<ul>
				<li><p>Montag-Freitag</p></li>
				<li><p>08:30 - 13:00 & 14:00 - 18:00 Uhr</p></li>
				<li><p>Samstag</p></li>
				<li><p>08.30 - 13.00 Uhr</p></li>
			</ul>
			</div>
			<div class="footer-divider"></div>
			<div>
			<h2>Standorte</h2>
			<ul>
				<li>
					<p>Geschäft: Müllheim</p>
			</li>
			<li><p><a class="underline-links" href="mailto:muellheim@hoffmann-optik.de">muellheim@hoffmann-optik.de</a></p></li>
			<li><a class="underline-links"  href="tel:076313375"><p class="tel-number">07631-3375</p></a></li>
			<li><p>Werderstraße 45,</p></li>
			<li><p>79379 Müllheim</p></li>
			</ul>
			<div class="horizontal-line-footer"></div>
			<ul>
			<li><p>Geschäft: Neuenburg</p></li>
			<li><p><a class="underline-links" href="mailto:neuenburg@hoffmann-optik.de">neuenburg@hoffmann-optik.de</a></p></li>
			<li><a class="underline-links" href="tel:0763173606"><p class="tel-number">07631-73606</p></a></li>
			<li><p>Rebstraße 4,</p></li>
			<li><p>79395 Neuenburg am Rhein</p></li>
			</ul>
			</div>
			<div class="footer-divider"></div>
			<div>
			<h2>Sozialen Medien</h2>
			<ul class="social-icon-ul">
				<li><a href="https://www.facebook.com/profile.php?id=100033324590454" target="_blank"><img src="/wp-content/uploads/2024/06/frame_4.webp" alt="facebook icon"></a></li>
				<li><a href="https://www.instagram.com/hoffmannoptik_markgraeflerland/"target="_blank"><img src="/wp-content/uploads/2024/06/frame_3.webp" alt="instagram icon"></a></li>
			</ul>
			</div>
		</section>
		
	</footer><!-- #colophon -->
	</div>
	<section class="footer-white-section">
			<div><p>Copyright © 2024 Hoffmannoptik. All rights reserved</p> <p><a href="/impressum">Impressum</a> und <a href="/datenschutz">Datenschutz</a></p></div>
		</section>
</div>
	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
