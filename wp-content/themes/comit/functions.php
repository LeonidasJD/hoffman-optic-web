<?php
/**
 * comit functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package comit
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function comit_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on comit, use a find and replace
		* to change 'comit' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'comit', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'comit' ),
			'mobile-menu' => esc_html__( 'Mobile-menu', 'comit' ),
			'footer-menu' => esc_html__('Footer-menu','comit'),
			
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'comit_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'comit_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function comit_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'comit_content_width', 640 );
}
add_action( 'after_setup_theme', 'comit_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function comit_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'comit' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'comit' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'comit_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function comit_scripts() {
	wp_enqueue_style( 'comit-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'comit-style', 'rtl', 'replace' );

	wp_enqueue_script( 'comit-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'global-script', get_template_directory_uri() . '/js/global.js', array(), _S_VERSION, true );
	wp_enqueue_style( 'comit-style-home', get_template_directory_uri() . '/inc/css/homepage.css', array(), _S_VERSION, true );
	wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), null, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'comit_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';



/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


/**SERVICES CUSTOM POST TYPE START */
function services_custom_post_type(){
	$services_args = array(
		'public'=> true,
		'label'=>'Services',
		'supports'=>array(
			'title','editor','thumbnail','excerpt',
		),
		'menu_icon'   => 'dashicons-editor-kitchensink',
	);

	register_post_type('services',$services_args);
}
add_action('init','services_custom_post_type');


/**SERVICES CUSTOM POST TYPE END */

/**OUR TEAM CUSTOM POST TYPE START */
function our_team_custom_post_type(){
	$our_team_args = array(
		'public' => true,
		'label' => 'Our Team',
		'supports' => array(
            'title','editor','thumbnail','excerpt',
        ),
        'menu_icon' => 'dashicons-universal-access',
	);
	register_post_type('our-team',$our_team_args);
}

add_action('init','our_team_custom_post_type');
/**OUR TEAM CUSTOM POST TYPE END */

add_theme_support( 'woocommerce' );




// LOGIN PAGE STYLED START 
add_action('login_enqueue_scripts', 'getNewLoginPage');

function getNewLoginPage(){
	?>
	<style>
		.web-logo{
			display: flex;
			justify-content: center;
			padding-top: 120px !important;
		}
		.web-logo img{
			width: 20rem;
		}
		#login h1{
			display: none;
		}
	.login-action-login{
		background-image: url('/wp-content/uploads/2024/06/business_finance_and_employment-scaled.webp');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
	}
	#login-message{
		border-radius: 12px;
    border: 2px solid #36641C !important;
    box-shadow: -5px 6px 15px 10px rgba(0, 0, 0, .04);
	}
	#login-message p{
		font-weight: 500;
    color: #0E130B;
	}
	#loginform{
		border-radius: 12px;
		border: 2px solid #36641C!important;
		box-shadow: -5px 6px 15px 10px rgba(0, 0, 0, .04);
		display: flex;
		flex-direction: column;
	}
	#loginform label{
		font-family: 'Glegoo',sans-serif;
		color: #0E130B;
		font-weight: bold;
	}
	#loginform input{
		border-color: #36641C!important;
	}
	#loginform input:focus{
		border-color: #36641C!important;
	}
	.wp-core-ui .button.button-large{
		background-color: #36641C!important;
		width: 100%;
		margin-top: 30px;
	}
	.dashicons-visibility:before{
		color:#36641C;
	}
	.login #nav,.login #backtoblog {
		padding: 0px!important;
	}
	.login #nav a,.login #backtoblog a{
		color: #36641C;
    font-weight: 500;
	}
	</style>

	<html>
		<div class="web-logo">
		<img src="/wp-content/uploads/2024/06/footer-logo.webp" alt="">
		</div>
		
	</html>
	<?php
}
// LOGIN PAGE STYLED END