<?php
/**
 * fora Theme Customizer.
 *
 * @package fora
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fora_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
}
add_action( 'customize_register', 'fora_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function fora_customize_preview_js() {
	wp_enqueue_script( 'fora_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'fora_customize_preview_js' );

/**
 * Register Custom Settings
 */
function fora_custom_settings_register( $wp_customize ) {
	
	/*
	Start Fora Colors
	=====================================================
	*/
	
	$colors = array();
	
	$colors[] = array(
	'slug'=>'fora_border_color', 
	'default' => '#ffffff',
	'label' => __('Site Border color', 'fora'),
	'activecallback' => 'fora_is_border_active'
	);
	
	$colors[] = array(
	'slug'=>'fora_box_background_color', 
	'default' => '#f7f7f7',
	'label' => __('Background color', 'fora'),
	'activecallback' => ''
	);
	
	$colors[] = array(
	'slug'=>'fora_main_text_color', 
	'default' => '#666666',
	'label' => __('Main text color', 'fora'),
	'activecallback' => ''
	);
	
	$colors[] = array(
	'slug'=>'fora_secondary_color', 
	'default' => '#eaeaea',
	'label' => __('Secondary color', 'fora'),
	'activecallback' => ''
	);
	
	$colors[] = array(
	'slug'=>'fora_special_text_color', 
	'default' => '#7fc7af',
	'label' => __('Link and special color', 'fora'),
	'activecallback' => ''
	);
	
	$colors[] = array(
	'slug'=>'fora_footer_background_color', 
	'default' => '#2f2f2f',
	'label' => __('Footer background color', 'fora'),
	'activecallback' => ''
	);
	
	$colors[] = array(
	'slug'=>'fora_footer_text_color', 
	'default' => '#8e8e8e',
	'label' => __('Footer text color', 'fora'),
	'activecallback' => ''
	);
	
	$colors[] = array(
	'slug'=>'fora_footer_link_color', 
	'default' => '#ffffff',
	'label' => __('Footer link color', 'fora'),
	'activecallback' => ''
	);
	
	foreach( $colors as $fora_theme_options ) {
		// SETTINGS
		$wp_customize->add_setting( 'fora_theme_options[' . $fora_theme_options['slug'] . ']', array(
			'default' => $fora_theme_options['default'],
			'type' => 'option', 
			'sanitize_callback' => 'sanitize_hex_color',
			'capability' => 'edit_theme_options'
		)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$fora_theme_options['slug'], 
				array('label' => $fora_theme_options['label'], 
				'section' => 'colors',
				'settings' =>'fora_theme_options[' . $fora_theme_options['slug'] . ']',
				'active_callback' => $fora_theme_options['activecallback'],
				)
			)
		);
	}
	
	/*
	Start Fora Options
	=====================================================
	*/
	$wp_customize->add_section( 'cresta_fora_options', array(
	     'title'    => esc_html__( 'Fora Theme Options', 'fora' ),
	     'priority' => 50,
	) );
	
	/*
	Show site border
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_siteborder', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'fora_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('fora_theme_options_siteborder', array(
        'label'      => __( 'Show site border', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_siteborder',
        'type'       => 'checkbox',
    ) );
	
	/*
	Show social on header
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_socialheader', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'fora_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('fora_theme_options_socialheader', array(
        'label'      => __( 'Show Social Buttons on Header', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_socialheader',
        'type'       => 'checkbox',
    ) );
	
	/*
	Show post slider
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_postslider', array(
        'default'    => '',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'fora_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('fora_theme_options_postslider', array(
        'label'      => __( 'Show posts slider', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_postslider',
		'active_callback' => 'fora_is_home',
        'type'       => 'checkbox',
    ) );
	
	/*
	Flash news number of posts
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_slidernumber', array(
        'default'    => '4',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'absint'
    ) );
	
	$wp_customize->add_control('fora_theme_options_slidernumber', array(
        'label'      => __( 'Slider: number of posts to show', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_slidernumber',
		'active_callback' => 'fora_is_home_and_active',
        'type'       => 'number',
    ) );
	
	/*
	Show search button
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_showsearch', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'fora_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('fora_theme_options_showsearch', array(
        'label'      => __( 'Show search button on header', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_showsearch',
        'type'       => 'checkbox',
    ) );
	
	/*
	Show social on footer
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_socialfooter', array(
        'default'    => '',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'fora_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('fora_theme_options_socialfooter', array(
        'label'      => __( 'Show Social Buttons on Footer', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_socialfooter',
        'type'       => 'checkbox',
    ) );
	
	/*
	Social Icons
	=====================================================
	*/
	$socialmedia = array();
	
	$socialmedia[] = array(
	'slug'=>'facebookurl', 
	'default' => '',
	'label' => __('Facebook URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'twitterurl', 
	'default' => '',
	'label' => __('Twitter URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'googleplusurl', 
	'default' => '',
	'label' => __('Google Plus URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'linkedinurl', 
	'default' => '',
	'label' => __('Linkedin URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'instagramurl', 
	'default' => '',
	'label' => __('Instagram URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'youtubeurl', 
	'default' => '',
	'label' => __('YouTube URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'pinteresturl', 
	'default' => '',
	'label' => __('Pinterest URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'tumblrurl', 
	'default' => '',
	'label' => __('Tumblr URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'vkurl', 
	'default' => '',
	'label' => __('VK URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'xingurl', 
	'default' => '',
	'label' => __('Xing URL', 'fora')
	);
	$socialmedia[] = array(
	'slug'=>'telegramurl', 
	'default' => '',
	'label' => __('Telegram URL', 'fora')
	);
	
	foreach( $socialmedia as $fora_theme_options ) {
		// SETTINGS
		$wp_customize->add_setting(
			'fora_theme_options_' . $fora_theme_options['slug'], array(
				'default' => $fora_theme_options['default'],
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
				'type'     => 'theme_mod',
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			$fora_theme_options['slug'], 
			array('label' => $fora_theme_options['label'], 
			'section'    => 'cresta_fora_options',
			'settings' =>'fora_theme_options_' . $fora_theme_options['slug'],
			'active_callback' => 'fora_is_social_active',
			)
		);
	}
	
	/*
	Show full post or excerpt
	=====================================================
	*/
	$wp_customize->add_setting('fora_theme_options_postshow', array(
        'default'    => 'excerpt',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'fora_sanitize_select'
    ) );
	
	$wp_customize->add_control('fora_theme_options_postshow', array(
        'label'      => __( 'Post show', 'fora' ),
        'section'    => 'cresta_fora_options',
        'settings'   => 'fora_theme_options_postshow',
        'type'       => 'select',
		'choices' => array(
			'full' => __( 'Show full post', 'fora'),
			'excerpt' => __( 'Show excerpt', 'fora'),
		),
    ) );
	
	/*
	Upgrade to PRO
	=====================================================
	*/
	class Fora_Customize_Upgrade_Control extends WP_Customize_Control {
		public function render_content() {  ?>
			<p class="fora-upgrade-title">
				<span class="customize-control-title">
					<h3 style="text-align:center;"><div class="dashicons dashicons-megaphone"></div> <?php esc_html_e('Get Fora PRO WP Theme for only', 'fora'); ?> 29,90&euro;</h3>
				</span>
			</p>
			<p style="text-align:center;" class="fora-upgrade-button">
				<a style="margin: 10px;" target="_blank" href="https://crestaproject.com/demo/fora-pro/" class="button button-secondary">
					<?php esc_html_e('Watch the demo', 'fora'); ?>
				</a>
				<a style="margin: 10px;" target="_blank" href="https://crestaproject.com/downloads/fora/" class="button button-secondary">
					<?php esc_html_e('Get fora PRO Theme', 'fora'); ?>
				</a>
			</p>
			<ul>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Advanced Theme Options', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Logo Upload', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Font switcher', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Loading Page', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Unlimited Colors and Skin', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Manage Sidebar Position', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('More Options for Posts Slider', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Custom Slider', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('WooCommerce Slider', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Flash News on top of the page', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Footer Widgets', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Breadcrumb', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Stick menu', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Sticky sidebar', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Post views counter', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Post formats (Audio, Video, Gallery)', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('7 Shortcodes', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('10 Exclusive Widgets', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Related Posts Box', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('Information About Author Box', 'fora'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php esc_html_e('And much more...', 'fora'); ?></b></li>
			<ul><?php
		}
	}

	$wp_customize->add_section( 'cresta_upgrade_pro', array(
		 'title'    => esc_html__( 'More features? Upgrade to PRO', 'fora' ),
		 'priority' => 999,
	));

	$wp_customize->add_setting('fora_section_upgrade_pro', array(
		'default' => '',
		'type' => 'option',
		'sanitize_callback' => 'esc_attr'
	));

	$wp_customize->add_control(new Fora_Customize_Upgrade_Control($wp_customize, 'fora_section_upgrade_pro', array(
		'section' => 'cresta_upgrade_pro',
		'settings' => 'fora_section_upgrade_pro',
	)));
}
add_action( 'customize_register', 'fora_custom_settings_register' );

function fora_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}

function fora_sanitize_select( $input, $setting ) {
	$input = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function fora_is_home() {
	if (is_home()) {
		return true;
	}
	return false;
}

function fora_is_home_and_active() {
	$showSlider = get_theme_mod('fora_theme_options_postslider', '1');
	if (is_home() && $showSlider == 1) {
		return true;
	}
	return false;
}

function fora_is_border_active() {
	$siteBorder = get_theme_mod('fora_theme_options_siteborder', '1');
	if ($siteBorder == 1) {
		return true;
	}
	return false;
}

function fora_is_social_active() {
	$showHeader = get_theme_mod('fora_theme_options_socialheader', '');
	$showFooter = get_theme_mod('fora_theme_options_socialfooter', '');
	if ($showHeader != 1 && $showFooter != 1) {
		return false;
	}
	return true;
}

/**
 * Add Custom CSS to Header 
 */
function fora_custom_css_styles() {
	global $fora_theme_options;
		$se_options = get_option( 'fora_theme_options', $fora_theme_options );
		if( isset( $se_options[ 'fora_border_color' ] ) ) {
			$fora_border_color = $se_options['fora_border_color'];
		}
		if( isset( $se_options[ 'fora_box_background_color' ] ) ) {
			$fora_box_background_color = $se_options['fora_box_background_color'];
		}
		if( isset( $se_options[ 'fora_main_text_color' ] ) ) {
			$fora_main_text_color = $se_options['fora_main_text_color'];
		}
		if( isset( $se_options[ 'fora_secondary_color' ] ) ) {
			$fora_secondary_color = $se_options['fora_secondary_color'];
		}
		if( isset( $se_options[ 'fora_special_text_color' ] ) ) {
			$fora_special_text_color = $se_options['fora_special_text_color'];
		}
		if( isset( $se_options[ 'fora_footer_background_color' ] ) ) {
			$fora_footer_background_color = $se_options['fora_footer_background_color'];
		}
		if( isset( $se_options[ 'fora_footer_text_color' ] ) ) {
			$fora_footer_text_color = $se_options['fora_footer_text_color'];
		}
		if( isset( $se_options[ 'fora_footer_link_color' ] ) ) {
			$fora_footer_link_color = $se_options['fora_footer_link_color'];
		}
		?>
		<style type="text/css">
			<?php if (!empty($fora_border_color) ) : ?>
				.border-fixed {
					background: <?php echo esc_html($fora_border_color); ?>;
				}
			<?php endif; ?>
			<?php if (!empty($fora_special_text_color) ) : ?>
				blockquote,
				button:hover,
				input[type="button"]:hover,
				input[type="reset"]:hover,
				input[type="submit"]:hover,
				button:focus,
				input[type="button"]:focus,
				input[type="reset"]:focus,
				input[type="submit"]:focus,
				button:active,
				input[type="button"]:active,
				input[type="reset"]:active,
				input[type="submit"]:active,
				input[type="text"]:focus,
				input[type="email"]:focus,
				input[type="url"]:focus,
				input[type="password"]:focus,
				input[type="search"]:focus,
				input[type="number"]:focus,
				input[type="tel"]:focus,
				input[type="range"]:focus,
				input[type="date"]:focus,
				input[type="month"]:focus,
				input[type="week"]:focus,
				input[type="time"]:focus,
				input[type="datetime"]:focus,
				input[type="datetime-local"]:focus,
				input[type="color"]:focus,
				textarea:focus,
				select:focus,
				.site-main .navigation.pagination .nav-links .page-numbers.current,
				.woocommerce-pagination .page-numbers li .current.page-numbers,
				#wp-calendar tbody td#today,
				.tagcloud a:hover,
				.tagcloud a:focus,
				.tagcloud a:active,
				.tags-links a:hover,
				.tags-links a:focus,
				.tags-links a:active,
				.read-more a:hover,
				.read-more a:focus,
				.read-more a:active,
				.foraSliderCaption .sliderMore a:hover,
				.foraSliderCaption .sliderMore a:focus,
				.foraSliderCaption .sliderMore a:active,
				.site-main .navigation.pagination .nav-links a.page-numbers:hover,
				.site-main .navigation.pagination .nav-links a.page-numbers:focus,
				.site-main .navigation.pagination .nav-links a.page-numbers:active,
				.page-links a .page-links-number:hover,
				.page-links a .page-links-number:focus,
				.page-links a .page-links-number:active,
				.woocommerce-pagination .page-numbers li .page-numbers:hover,
				.woocommerce-pagination .page-numbers li .page-numbers:focus,
				.woocommerce-pagination .page-numbers li .page-numbers:active,
				.fora_slider,
				.foraSliderCaption .inner-item .caption,
				.page-links > .page-links-number,
				.woocommerce ul.products > li h2:after,
				.woocommerce .wooImage .button:hover,
				.woocommerce .wooImage .added_to_cart:hover,
				.woocommerce-error li a:hover,
				.woocommerce-message a:hover,
				.return-to-shop a:hover,
				.wc-proceed-to-checkout .button.checkout-button:hover,
				.widget_shopping_cart p.buttons a:hover {
					border-color: <?php echo esc_html($fora_special_text_color); ?>;
				}
				blockquote::before,
				button:hover,
				input[type="button"]:hover,
				input[type="reset"]:hover,
				input[type="submit"]:hover,
				button:focus,
				input[type="button"]:focus,
				input[type="reset"]:focus,
				input[type="submit"]:focus,
				button:active,
				input[type="button"]:active,
				input[type="reset"]:active,
				input[type="submit"]:active,
				a, a:visited,
				.main-navigation .current_page_item > a,
				.main-navigation .current-menu-item > a,
				.main-navigation .current_page_ancestor > a,
				.main-navigation .current-menu-ancestor > a,
				.main-navigation > div > ul li:hover > a, 
				.main-navigation > div > ul li.focus > a,
				.site-search .search-container label:after,
				.tagcloud a:hover,
				.tagcloud a:focus,
				.tagcloud a:active,
				.tags-links a:hover,
				.tags-links a:focus,
				.tags-links a:active,
				.read-more a:hover,
				.read-more a:focus,
				.read-more a:active,
				.foraSliderCaption .sliderMore a:hover,
				.foraSliderCaption .sliderMore a:focus,
				.foraSliderCaption .sliderMore a:active,
				.site-main .navigation.pagination .nav-links a.page-numbers:hover,
				.site-main .navigation.pagination .nav-links a.page-numbers:focus,
				.site-main .navigation.pagination .nav-links a.page-numbers:active,
				.page-links a .page-links-number:hover,
				.page-links a .page-links-number:focus,
				.page-links a .page-links-number:active,
				.woocommerce-pagination .page-numbers li .page-numbers:hover,
				.woocommerce-pagination .page-numbers li .page-numbers:focus,
				.woocommerce-pagination .page-numbers li .page-numbers:active,
				.woocommerce ul.products > li .price,
				.woocommerce div.product .summary .price,
				.woocommerce .wooImage .button:hover,
				.woocommerce .wooImage .added_to_cart:hover,
				.woocommerce-error li a:hover,
				.woocommerce-message a:hover,
				.return-to-shop a:hover,
				.wc-proceed-to-checkout .button.checkout-button:hover,
				.widget_shopping_cart p.buttons a:hover,
				.widget_price_filter .price_slider_amount .button:hover,
				.widget_price_filter .price_slider_amount .button:focus,
				.widget_price_filter .price_slider_amount .button:active,
				.woocommerce div.product form.cart .button:hover,
				.woocommerce div.product form.cart .button:focus,
				.woocommerce div.product form.cart .button:active,
				header.entry-header h2.entry-title a:hover,
				header.entry-header h2.entry-title a:focus,
				header.entry-header h2.entry-title a:active,
				.site-description,
				.sticky header.entry-header .entry-title:before {
					color: <?php echo esc_html($fora_special_text_color); ?>;
				}
				button,
				input[type="button"],
				input[type="reset"],
				input[type="submit"],
				.site-main .navigation.pagination .nav-links .page-numbers,
				.woocommerce-pagination .page-numbers li .page-numbers,
				#wp-calendar > caption,
				.tagcloud a,
				.tags-links a,
				.read-more a,
				.foraSliderCaption .sliderMore a,
				.page-links .page-links-number,
				header.page-header,
				#toTop,
				.content-area .onsale,
				.woocommerce .wooImage .button,
				.woocommerce .wooImage .added_to_cart,
				.woocommerce-error li a,
				.woocommerce-message a,
				.return-to-shop a,
				.wc-proceed-to-checkout .button.checkout-button,
				.widget_shopping_cart p.buttons a,
				.woocommerce .wishlist_table td.product-add-to-cart a,
				.woocommerce .content-area .woocommerce-tabs .tabs li.active a,
				.widget_price_filter .ui-slider .ui-slider-handle {
					background: <?php echo esc_html($fora_special_text_color); ?>;
				}
				.main-navigation > div > ul > .current_page_item > a,
				.main-navigation > div > ul > .current-menu-item > a,
				.main-navigation > div > ul > .current_page_ancestor > a,
				.main-navigation > div > ul > .current-menu-ancestor > a,
				.main-navigation > div > ul > li > a:hover,
				.main-navigation > div > ul > li > a:focus {
					border-top: 1px solid <?php echo esc_html($fora_special_text_color); ?>;
				}
				@media all and (max-width: 1025px) {
					.main-navigation {
						border-color: <?php echo esc_html($fora_special_text_color); ?> !important;
					}
					.main-navigation ul li .indicator {
						color: <?php echo esc_html($fora_special_text_color); ?>;
					}
					.menu-toggle,
					.menu-toggle:hover,
					.menu-toggle:focus,
					.menu-toggle:active {
						background: <?php echo esc_html($fora_special_text_color); ?>;
					}
				}
			<?php endif; ?>
			<?php if (!empty($fora_footer_link_color) ) : ?>
				footer.site-footer a,
				footer.site-footer a:hover,
				footer.site-footer a:focus,
				footer.site-footer a:active {
					color: <?php echo esc_html($fora_footer_link_color); ?>;
				}
				.main_footer .site-social-footer a {
					border-color: <?php echo esc_html($fora_footer_link_color); ?>;
				}
			<?php endif; ?>
			<?php if (!empty($fora_box_background_color) ) : ?>
				<?php list($r, $g, $b) = sscanf($fora_box_background_color, '#%02x%02x%02x'); ?>
				.foraSliderCaption .inner-item {
					border-color: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.3);
				}
				body,
				input[type="text"],
				input[type="email"],
				input[type="url"],
				input[type="password"],
				input[type="search"],
				input[type="number"],
				input[type="tel"],
				input[type="range"],
				input[type="date"],
				input[type="month"],
				input[type="week"],
				input[type="time"],
				input[type="datetime"],
				input[type="datetime-local"],
				input[type="color"],
				textarea,
				select,
				.main-navigation ul ul a,
				.singleSliderItem {
					background: <?php echo esc_html($fora_box_background_color); ?>;
				}
				button,
				input[type="button"],
				input[type="reset"],
				input[type="submit"],
				.site-main .navigation.pagination .nav-links .page-numbers,
				.woocommerce-pagination .page-numbers li .page-numbers,
				#wp-calendar > caption,
				.tagcloud a,
				.tags-links a,
				.read-more a,
				.foraSliderCaption .sliderMore a,
				.page-links .page-links-number,
				header.page-header,
				#toTop,
				.foraSliderCaption .inner-item .caption,
				.foraSliderCaption .caption a,
				.content-area .onsale,
				.woocommerce .wooImage .button,
				.woocommerce .wooImage .added_to_cart,
				.woocommerce-error li a,
				.woocommerce-message a,
				.return-to-shop a,
				.wc-proceed-to-checkout .button.checkout-button,
				.widget_shopping_cart p.buttons a,
				.woocommerce .wishlist_table td.product-add-to-cart a,
				.woocommerce .content-area .woocommerce-tabs .tabs li.active a,
				.widget_price_filter .price_slider_amount .button,
				.woocommerce div.product form.cart .button {
					color: <?php echo esc_html($fora_box_background_color); ?>;
				}
				@media all and (max-width: 1025px) {
					.menu-toggle,
					.menu-toggle:hover,
					.menu-toggle:focus,
					.menu-toggle:active {
						color: <?php echo esc_html($fora_box_background_color); ?>;
					}
				}
			<?php endif; ?>
			<?php if (!empty($fora_main_text_color) ) : ?>
				<?php list($r, $g, $b) = sscanf($fora_main_text_color, '#%02x%02x%02x'); ?>
				body,
				input,
				select,
				textarea,
				input[type="text"]:focus,
				input[type="email"]:focus,
				input[type="url"]:focus,
				input[type="password"]:focus,
				input[type="search"]:focus,
				input[type="number"]:focus,
				input[type="tel"]:focus,
				input[type="range"]:focus,
				input[type="date"]:focus,
				input[type="month"]:focus,
				input[type="week"]:focus,
				input[type="time"]:focus,
				input[type="datetime"]:focus,
				input[type="datetime-local"]:focus,
				input[type="color"]:focus,
				textarea:focus,
				select:focus,
				a:hover,
				a:focus,
				a:active,
				header.entry-header h2.entry-title a,
				header.entry-header h2.entry-title a,
				header.entry-header h2.entry-title a,
				.site-branding a,
				.main-navigation a,
				.site-main .post-navigation span.meta-nav,
				.site-main .navigation.pagination .nav-links .page-numbers.current,
				.woocommerce-pagination .page-numbers li .current.page-numbers,
				.page-links > .page-links-number {
					color: <?php echo esc_html($fora_main_text_color); ?>;
				}
				.woocommerce ul.products > li .price {
					color: <?php echo esc_html($fora_main_text_color); ?> !important;
				}
				.star-rating:before {
					color: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.1); ?>;
				}
				#wp-calendar th,
				#owl-post-nav-content .foraSliderCaption:hover,
				.wp-caption .wp-caption-text,
				.woocommerce .content-area .images figure div a,
				.woocommerce-message,
				.woocommerce-info,
				.woocommerce-error,
				.woocommerce div.product form.cart .variations tr,
				.woocommerce .woocommerce-tabs,
				.woocommerce table.shop_attributes tr,
				.woocommerce table.shop_attributes tr th,
				.woocommerce table.shop_attributes tr.alt th,
				.woocommerce table.shop_attributes tr.alt td,
				.woocommerce-page .entry-content table thead th,
				.woocommerce-page .entry-content table tr:nth-child(even),
				#payment .payment_methods li .payment_box {
					background: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.1); ?>;
				}
				.foraSliderCaption,
				.widget_price_filter .ui-slider .ui-slider-range {
					background: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.3); ?>;
				}
				#owl-post-nav-content .synced .foraSliderCaption .inner-item .caption {
					background: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.6); ?>;
				}
				.widget_price_filter .price_slider_wrapper .ui-widget-content {
					background: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.2); ?>;
				}
				@media all and (max-width: 1025px) {
					.foraSliderCaption .inner-item .caption {
						background: rgba(<?php echo esc_html($r).', '.esc_html($g).', '.esc_html($b); ?>,0.6); ?>;
					}
				}
			<?php endif; ?>
			<?php if (!empty($fora_secondary_color) ) : ?>
				hr {
					background-color: <?php echo esc_html($fora_secondary_color); ?>;
				}
				.sepHentry {
					color: <?php echo esc_html($fora_secondary_color); ?>;
				}
				input[type="text"],
				input[type="email"],
				input[type="url"],
				input[type="password"],
				input[type="search"],
				input[type="number"],
				input[type="tel"],
				input[type="range"],
				input[type="date"],
				input[type="month"],
				input[type="week"],
				input[type="time"],
				input[type="datetime"],
				input[type="datetime-local"],
				input[type="color"],
				textarea,
				select,
				.main-navigation,
				.main-navigation ul ul,
				.main-navigation ul ul a,
				.post-navigation .nav-next,
				.site-main .post-navigation,
				.site-main .navigation.pagination .nav-links,
				.widget,
				#wp-calendar tbody td,
				.site-branding,
				.site-search .search-container label:after,
				.site-social a,
				header.entry-header .entry-title,
				.widget .widget-title:before, .widget .widget-title:after,
				.entry-meta,
				.entry-meta .posted-on, 
				.entry-meta .byline,
				.sepHentry:before, .sepHentry:after,
				aside ul li,
				#comments ol .pingback,
				#comments ol article,
				.woocommerce ul.products > li,
				.woocommerce .product_meta,
				.woocommerce .single_variation,
				.woocommerce #reviews #comments ol.commentlist li .comment-text,
				.woocommerce p.stars a.star-1,
				.woocommerce p.stars a.star-2,
				.woocommerce p.stars a.star-3,
				.woocommerce p.stars a.star-4,
				.single-product div.product .woocommerce-product-rating,
				.woocommerce-page .entry-content table,
				.woocommerce-page .entry-content table thead th,
				.woocommerce-page .entry-content table tbody td,
				.woocommerce-page .entry-content table td, .woocommerce-page .entry-content table th,
				#order_review, #order_review_heading,
				#payment,
				#payment .payment_methods li,
				.widget_shopping_cart p.total,
				aside ul.menu li a,
				aside ul.menu .indicatorBar {
					border-color: <?php echo esc_html($fora_secondary_color); ?>;
				}
				@media all and (max-width: 1025px) {
					.main-navigation a,
					.main-navigation ul li .indicator,
					.main-navigation ul ul li:last-child > a {
						border-color: <?php echo esc_html($fora_secondary_color); ?>;
					}
				}
			<?php endif; ?>
			<?php if (!empty($fora_footer_background_color) ) : ?>
				footer.site-footer {
					background: <?php echo esc_html($fora_footer_background_color); ?>;
				}
			<?php endif; ?>
			<?php if (!empty($fora_footer_text_color) ) : ?>
				footer.site-footer {
					color: <?php echo esc_html($fora_footer_text_color); ?>;
				}
			<?php endif; ?>
		</style>
		<?php
	}
add_action('wp_head', 'fora_custom_css_styles');
