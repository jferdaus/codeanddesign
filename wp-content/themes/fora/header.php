<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fora
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php 
	$siteBorder = get_theme_mod('fora_theme_options_siteborder', '1');
	$socialHeader = get_theme_mod('fora_theme_options_socialheader', '1');
	$showSlider = get_theme_mod('fora_theme_options_postslider', '');
	$postsSlider = get_theme_mod('fora_theme_options_slidernumber', '4');
	$showSearch = get_theme_mod('fora_theme_options_showsearch', '1');
?>
<?php if($siteBorder == 1): ?>
	<div class="border-fixed border-top"></div>
	<div class="border-fixed border-bottom"></div>
	<div class="border-fixed border-left"></div>
	<div class="border-fixed border-right"></div>
<?php endif; ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'fora' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="main_header">
			<div class="header_wrapper">
				<?php if($socialHeader == 1): ?>
					<div class="site-social">
						<?php fora_social_buttons(); ?>
					</div><!-- .site-social -->
				<?php endif; ?>
				<div class="site-branding">
					<?php
					if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
					endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
					<?php
					endif; ?>
				</div><!-- .site-branding -->
				<?php if($showSearch == 1): ?>
					<div class="site-search">
						<!-- Start: Search Form -->
						<div class="search-container">
							<?php get_search_form(); ?>
						</div>
						<!-- End: Search Form -->
					</div><!-- .site-search -->
				<?php endif; ?>
			</div><!-- .header_wrapper -->
		</div><!-- .main_header -->
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Main Menu', 'fora' ); ?><i class="fa fa-bars spaceLeftRight"></i></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
	<?php if(is_home() && $showSlider == 1): ?>
		<div id="mainslider" class="fora_slider">
			<div class="blockSliderImage" id="owl-main-slide">
				<?php
					$args = array( 'posts_per_page' => intval($postsSlider),'post_status'=>'publish','post_type'=>'post', 'ignore_sticky_posts' => 1 );
					$the_query = new WP_Query( $args );
					while( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="singleSliderItem">
						<?php 
							if ( '' != get_the_post_thumbnail() ) {
								the_post_thumbnail('fora-slider');
							} else {
								echo '<img src="' . esc_url(get_template_directory_uri()) . '/images/no-image-slide.png" alt="'. esc_attr__( 'No image', 'fora' ) .'" />';
							}
						?>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
			</div>
			<div class="blockSliderContent" id="owl-post-nav-content">
				<?php
					$args = array( 'posts_per_page' => intval($postsSlider),'post_status'=>'publish','post_type'=>'post', 'ignore_sticky_posts' => 1 );
					$the_query = new WP_Query( $args );
					while( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="foraSliderCaption">
						<div class="singleSliderItem">
							<?php 
								if ( '' != get_the_post_thumbnail() ) {
									the_post_thumbnail('fora-slider');
								} else {
									echo '<img src="' . esc_url(get_template_directory_uri()) . '/images/no-image-slide.png" alt="'. esc_attr__( 'No image', 'fora' ) .'" />';
								}
							?>
						</div>
						<div class="inner-item">
							<div class="caption">
								<?php $foraTitle = wp_trim_words( get_the_title(), 7 ) ?>
								<h3><a href="<?php echo esc_url(get_permalink()); ?>" class="flexTitle"><?php echo esc_html($foraTitle); ?></a></h3>
								<div class="entry-slider">
									<span class="posted-on"><i class="fa fa-calendar-o spaceRight"></i><?php the_time(get_option('date_format')); ?></span>
									<span class="cat-links"><i class="fa fa-folder-open-o spaceLeftRight"></i><?php $cat = get_the_category(); $cat = $cat[0]; echo $cats = get_category_parents($cat, TRUE, '');  ?></span>
								</div>
								<div class="sliderMore"><a href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e( 'Read More', 'fora' ); ?><i class="fa spaceLeft fa-caret-right"></i></a></div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
			</div>
		</div>
	<?php endif; ?>

	<div id="content" class="site-content">