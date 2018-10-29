<?php
/**
 * fora functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fora
 */

if ( ! function_exists( 'fora_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fora_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on fora, use a find and replace
	 * to change 'fora' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'fora', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'fora-slider', 1200, 800, true);
	add_image_size( 'fora-standard', 800, 9999);
	add_image_size( 'fora-little-post', 800, 400, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'fora' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
}
endif;
add_action( 'after_setup_theme', 'fora_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fora_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fora_content_width', 790 );
}
add_action( 'after_setup_theme', 'fora_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fora_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'fora' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'fora' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
}
add_action( 'widgets_init', 'fora_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fora_scripts() {
	wp_enqueue_style( 'fora-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version') );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css', array(), '4.7.0');
	$query_args = array(
		'family' => 'Roboto:400,700%7CMontserrat:400,700'
	);
	wp_enqueue_style( 'fora-googlefonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );

	wp_enqueue_script( 'fora-custom', get_template_directory_uri() . '/js/jquery.fora.js', array('jquery'), wp_get_theme()->get('Version'), true );
	wp_enqueue_script( 'fora-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'fora-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'fora-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '1.23', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	/* Dequeue default WooCommerce Layout */
	wp_dequeue_style ( 'woocommerce-layout' );
	wp_dequeue_style ( 'woocommerce-smallscreen' );
	wp_dequeue_style ( 'woocommerce-general' );
}
add_action( 'wp_enqueue_scripts', 'fora_scripts' );

/**
 * WooCommerce Support
 */
if ( ! function_exists( 'fora_woocommerce_support' ) ) :
	function fora_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
	}
	add_action( 'after_setup_theme', 'fora_woocommerce_support' );
endif; // fora_woocommerce_support

/**
 * WooCommerce: Chenge default max number of related products to 3
 */
if ( function_exists( 'is_woocommerce' ) ) :
	if ( ! function_exists( 'fora_related_products_args' ) ) :
		add_filter( 'woocommerce_output_related_products_args', 'fora_related_products_args' );
		function fora_related_products_args( $args ) {
			$args['posts_per_page'] = 3;
			return $args;
		}
	endif;
endif;


/**
 * Replace Excerpt More
 */
if ( ! function_exists( 'fora_new_excerpt_more' ) ) {
	function fora_new_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}
		return '&hellip;';
	}
}
add_filter('excerpt_more', 'fora_new_excerpt_more');

 /**
 * Delete font size style from tag cloud widget
 */
if ( ! function_exists( 'fora_fix_tag_cloud' ) ) {
	function fora_fix_tag_cloud($tag_string){
	   return preg_replace('/ style=("|\')(.*?)("|\')/','',$tag_string);
	}
}
add_filter('wp_generate_tag_cloud', 'fora_fix_tag_cloud',10,1);

 /**
 * Social Buttons
 */
if ( ! function_exists( 'fora_social_buttons' ) ) {
	function fora_social_buttons(){
		$facebookURL = get_theme_mod('fora_theme_options_facebookurl', '');
		$twitterURL = get_theme_mod('fora_theme_options_twitterurl', '');
		$googleplusURL = get_theme_mod('fora_theme_options_googleplusurl', '');
		$linkedinURL = get_theme_mod('fora_theme_options_linkedinurl', '');
		$instagramURL = get_theme_mod('fora_theme_options_instagramurl', '');
		$youtubeURL = get_theme_mod('fora_theme_options_youtubeurl', '');
		$pinterestURL = get_theme_mod('fora_theme_options_pinteresturl', '');
		$tumblrURL = get_theme_mod('fora_theme_options_tumblrurl', '');
		$vkURL = get_theme_mod('fora_theme_options_vkurl', '');
		$xingURL = get_theme_mod('fora_theme_options_xingurl', '');
		$telegramURL = get_theme_mod('fora_theme_options_telegramurl', '');
		?>
		<?php if (!empty($facebookURL)) : ?>
			<a href="<?php echo esc_url($facebookURL); ?>" title="<?php esc_attr_e( 'Facebook', 'fora' ); ?>"><i class="fa fa-facebook"><span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($twitterURL)) : ?>
			<a href="<?php echo esc_url($twitterURL); ?>" title="<?php esc_attr_e( 'Twitter', 'fora' ); ?>"><i class="fa fa-twitter"><span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($googleplusURL)) : ?>
			<a href="<?php echo esc_url($googleplusURL); ?>" title="<?php esc_attr_e( 'Google Plus', 'fora' ); ?>"><i class="fa fa-google-plus"><span class="screen-reader-text"><?php esc_html_e( 'Google Plus', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($linkedinURL)) : ?>
			<a href="<?php echo esc_url($linkedinURL); ?>" title="<?php esc_attr_e( 'Linkedin', 'fora' ); ?>"><i class="fa fa-linkedin"><span class="screen-reader-text"><?php esc_html_e( 'Linkedin', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($instagramURL)) : ?>
			<a href="<?php echo esc_url($instagramURL); ?>" title="<?php esc_attr_e( 'Instagram', 'fora' ); ?>"><i class="fa fa-instagram"><span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($youtubeURL)) : ?>
			<a href="<?php echo esc_url($youtubeURL); ?>" title="<?php esc_attr_e( 'YouTube', 'fora' ); ?>"><i class="fa fa-youtube"><span class="screen-reader-text"><?php esc_html_e( 'YouTube', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($pinterestURL)) : ?>
			<a href="<?php echo esc_url($pinterestURL); ?>" title="<?php esc_attr_e( 'Pinterest', 'fora' ); ?>"><i class="fa fa-pinterest"><span class="screen-reader-text"><?php esc_html_e( 'Pinterest', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($tumblrURL)) : ?>
			<a href="<?php echo esc_url($tumblrURL); ?>" title="<?php esc_attr_e( 'Tumblr', 'fora' ); ?>"><i class="fa fa-tumblr"><span class="screen-reader-text"><?php esc_html_e( 'Tumblr', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($vkURL)) : ?>
			<a href="<?php echo esc_url($vkURL); ?>" title="<?php esc_attr_e( 'VK', 'fora' ); ?>"><i class="fa fa-vk"><span class="screen-reader-text"><?php esc_html_e( 'VK', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($xingURL)) : ?>
			<a href="<?php echo esc_url($xingURL); ?>" title="<?php esc_attr_e( 'Xing', 'fora' ); ?>"><i class="fa fa-xing"><span class="screen-reader-text"><?php esc_html_e( 'Xing', 'fora' ); ?></span></i></a>
		<?php endif; ?>
		<?php if (!empty($telegramURL)) : ?>
			<a href="<?php echo esc_url($telegramURL); ?>" title="<?php esc_attr_e( 'Telegram', 'fora' ); ?>"><i class="fa fa-telegram"><span class="screen-reader-text"><?php esc_html_e( 'Telegram', 'fora' ); ?></span></i></a>
		<?php endif;
	}
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

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

/* Calling in the admin area for the Welcome Page */
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin/fora-admin-page.php';
}

/**
 * Load PRO Button in the customizer
 */
require_once( trailingslashit( get_template_directory() ) . 'inc/pro-button/class-customize.php' );
