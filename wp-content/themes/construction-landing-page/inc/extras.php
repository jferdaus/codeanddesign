<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Construction_Landing_Page
 */
if( ! function_exists( 'construction_landing_page_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function construction_landing_page_body_classes( $classes ) {
	
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
    
    // Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
	if( !( is_active_sidebar( 'right-sidebar' ) ) ) {
        $classes[] = 'full-width'; 
    }

    if( construction_landing_page_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || 'product' === get_post_type() ) && ! is_active_sidebar( 'shop-sidebar' ) ){
        $classes[] = 'full-width';
    }
    
	if( is_page() ){
		$sidebar_layout = construction_landing_page_sidebar_layout();
        if( $sidebar_layout == 'no-sidebar' )
		$classes[] = 'full-width';
	}
    
    if( is_page_template( 'template-home.php' ) ){
        $ed_banner = get_theme_mod( 'construction_landing_page_ed_banner_section' );
        $home_page = get_option( 'page_on_front' );
        if( $ed_banner && has_post_thumbnail( $home_page ) ){
            $classes[] = 'has-banner';    
        }else{
            $classes[] = 'no-banner';
        }
    }else{
        $classes[] = 'no-banner';
    }

	return $classes;
}
endif;
add_filter( 'body_class', 'construction_landing_page_body_classes' );

/**
* Filter wp_nav_menu() to add profile link
* 
* @link http://www.wpbeginner.com/wp-themes/how-to-add-custom-items-to-specific-wordpress-menus/
*/
if( ! function_exists( 'construction_landing_page_phone_link' ) ) :

function construction_landing_page_phone_link( $menu, $args ){
    
    $phone = get_theme_mod( 'construction_landing_page_phone' );
            
    if( $phone && $args->theme_location == 'primary' ){
        
        $menu .= '<li><a href="tel:' . preg_replace( '/\D/', '', $phone ) . '" class="tel-link"><span class="fa fa-phone"></span>' . esc_html( $phone ) . '</a></li>';        
               
    }
       
    return $menu; 
}
endif;
add_filter( 'wp_nav_menu_items', 'construction_landing_page_phone_link', 10, 2 );

if( ! function_exists( 'construction_landing_page_get_header' ) ) :
/**
 * Page Header
*/
function construction_landing_page_get_header(){ 
    if( ! is_front_page() && ! is_page_template( 'template-home.php' ) ){
        
        $ed_bc = get_theme_mod( 'construction_landing_page_ed_breadcrumb' ); //from customizer
    
        if( is_single() ){ 
            if( $ed_bc ){ ?>
            
            <div class="header-block">
        		<div class="container">
                    <?php construction_landing_page_breadcrumb(); ?>
                </div>
            </div>
            
        <?php   
            }
        }else{
            ?>
            <!-- Page Header for inner pages only -->
            <div class="header-block">
                <div class="container">
                
                    <div class="page-header">
                    
                        <h1 class="page-title">
                        <?php 
                            
                            if( is_home() ) single_post_title();
                            
                            if( is_page() ) the_title();
                            
                            if( is_search() ) printf( esc_html__( 'Search Results for: "%s"', 'construction-landing-page' ), '<span>' . get_search_query() . '</span>' );
                            
                                    /** For Woocommerce */
                            if( construction_landing_page_is_woocommerce_activated() && ( is_product_category() || is_product_tag() || is_shop() ) ){
                                if( is_shop() ){
                                    if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                                        return;
                                    }
                                    $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                                
                                    if ( ! $_name ) {
                                        $product_post_type = get_post_type_object( 'product' );
                                        $_name = $product_post_type->labels->singular_name;
                                    }
                                    echo esc_html( $_name );
                                }elseif( is_product_category() || is_product_tag() ){
                                    $current_term = $GLOBALS['wp_query']->get_queried_object();
                                    echo esc_html( $current_term->name );
                                }
                            }else{
                                if( is_archive() ) the_archive_title();    
                            }
                            
                            if( is_404() ) esc_html_e( '404 - Page not found', 'construction-landing-page' );
                            
                        ?>
                        </h1>
                    </div>
                
                    <?php construction_landing_page_breadcrumb(); ?>
                </div>
            </div>
            <?php
        }
    }
}
endif;
add_action( 'construction_landing_page_page_header', 'construction_landing_page_get_header' );

if( ! function_exists( 'construction_landing_page_breadcrumb' ) ) :
/**
 * Breadcrumb 
*/
function construction_landing_page_breadcrumb(){
    
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = get_theme_mod( 'construction_landing_page_breadcrumb_separator', __( '>', 'construction-landing-page' ) ); // delimiter between crumbs
    $home        = get_theme_mod( 'construction_landing_page_breadcrumb_home_text', __( 'Home', 'construction-landing-page' ) ); // text for the 'Home' link
    $showCurrent = get_theme_mod( 'construction_landing_page_ed_current', '1' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before      = '<span class="current">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
    
    global $post;
    $homeLink = home_url();
    
    if( get_theme_mod( 'construction_landing_page_ed_breadcrumb' ) ){
        if ( is_front_page() ) {
        
            if ( $showOnHome == 1 ) echo '<div id="crumbs"><a href="' . esc_url( $homeLink ) . '">' . esc_html( $home ) . '</a></div>';
        
        } else {
        
            echo '<div id="crumbs"><a href="' . esc_url( $homeLink ) . '">' . esc_html( $home ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
        
            if ( is_category() ) {
                $thisCat = get_category( get_query_var( 'cat' ), false );
                if ( $thisCat->parent != 0 ) echo get_category_parents( $thisCat->parent, TRUE, ' <span class="separator">' . $delimiter . '</span> ' );
                echo $before .  esc_html( single_cat_title( '', false ) ) . $after;
            
            } elseif( construction_landing_page_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ //For Woocommerce archive page
        
                $current_term = $GLOBALS['wp_query']->get_queried_object();
                if( is_product_category() ){
                    $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo ' <a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                        }
                    }
                }           
                echo $before . esc_html( $current_term->name ) . $after;
                
            } elseif( construction_landing_page_is_woocommerce_activated() && is_shop() ){ //Shop Archive page
                if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                    return;
                }
                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
        
                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }
                echo $before . esc_html( $_name ) . $after;
                
            } elseif ( is_search() ) {
                echo $before . esc_html__( 'Search Results for "', 'construction-landing-page' ) . esc_html( get_search_query() ) . esc_html__( '"', 'construction-landing-page' ) . $after;
            
            } elseif ( is_day() ) {
                echo '<a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'construction-landing-page' ) ) ) ) . '">' . esc_html( get_the_time( __( 'Y', 'construction-landing-page' ) ) ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                echo '<a href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'construction-landing-page' ) ), get_the_time( __( 'm', 'construction-landing-page' ) ) ) ) . '">' . esc_html( get_the_time( __( 'F', 'construction-landing-page' ) ) ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                echo $before . esc_html( get_the_time( __( 'd', 'construction-landing-page' ) ) ) . $after;
            
            } elseif ( is_month() ) {
                echo '<a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'construction-landing-page' ) ) ) ) . '">' . esc_html( get_the_time( __( 'Y', 'construction-landing-page' ) ) ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                echo $before . esc_html( get_the_time( __( 'F', 'construction-landing-page' ) ) ) . $after;
            
            } elseif ( is_year() ) {
                echo $before . esc_html( get_the_time( __( 'Y', 'construction-landing-page' ) ) ) . $after;
        
            } elseif ( is_single() && !is_attachment() ) {
                
                if( construction_landing_page_is_woocommerce_activated() && 'product' === get_post_type() ){ //For Woocommerce single product
                    if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                        $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                        $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                        $ancestors = array_reverse( $ancestors );
                        foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );    
                            if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                                echo ' <a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                            }
                        }
                        echo ' <a href="' . esc_url( get_term_link( $main_term ) ) . '">' . esc_html( $main_term->name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                    }
                    
                    echo $before . esc_html( get_the_title() ) . $after;
                
                } elseif ( get_post_type() != 'post' ) {
                    
                    $post_type = get_post_type_object( get_post_type() );
                    
                    if( $post_type->has_archive == true ){
                       
                        // Add support for a non-standard label of 'archive_title' (special use case).
                       $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                       // Core filter hook.
                       $label = apply_filters( 'post_type_archive_title', $label, $post_type->name );
                       printf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type ) ), $label );
                       echo '<span class="separator">' . esc_html( $delimiter ) . '</span> ';
        
                    }
                    if ( $showCurrent == 1 ){ 
                       
                        echo $before . esc_html( get_the_title() ) . $after;
                    }

                } else {
                    $cat = get_the_category(); 
                    if( $cat ){
                        $cat = $cat[0];
                        $cats = get_category_parents( $cat, TRUE, ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' );
                        if ( $showCurrent == 0 ) $cats = preg_replace( "#^(.+)\s$delimiter\s$#", "$1", $cats );
                        echo $cats;
                    }
                    if ( $showCurrent == 1 ) echo $before . esc_html( get_the_title() ) . $after;
                }
            
            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                if ( get_query_var('paged') ) {
                    echo '<a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '">' . esc_html( $post_type->label ) . '</a>';
                    if( $showCurrent == 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' . $before . sprintf( __('Page %s','construction-landing-page'), get_query_var('paged') ) . $after;
                } else {
                    if ( $showCurrent == 1 ) echo $before . esc_html( $post_type->label ) . $after;
                }

            } elseif ( is_attachment() ) {
                $parent = get_post( $post->post_parent );
                $cat = get_the_category( $parent->ID ); 
                if( $cat ){
                    $cat = $cat[0];
                    echo get_category_parents( $cat, TRUE, ' <span class="separator">' . esc_html( $delimiter ) . '</span> ');
                    echo '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( $parent->post_title ) . '</a>' . ' <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                }
                if ( $showCurrent == 1 ) echo  $before . esc_html( get_the_title() ) . $after;
            
            } elseif ( is_page() && !$post->post_parent ) {
                if ( $showCurrent == 1 ) echo $before . esc_html( get_the_title() ) . $after;
        
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while( $parent_id ){
                    $page = get_post( $parent_id );
                    $breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs = array_reverse( $breadcrumbs );
                for ( $i = 0; $i < count( $breadcrumbs) ; $i++ ){
                    echo $breadcrumbs[$i];
                    if ( $i != count( $breadcrumbs ) - 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                }
                if ( $showCurrent == 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' . $before . esc_html( get_the_title() ) . $after;
            
            } elseif ( is_tag() ) {
                echo $before . esc_html( single_tag_title( '', false ) ) . $after;
            
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata( $author );
                echo $before . esc_html( $userdata->display_name ) . $after;
            
            } elseif ( is_404() ) {
                echo $before . esc_html__( '404 Error - Page not Found', 'construction-landing-page' ) . $after;
            } elseif( is_home() ){
                echo $before;
                single_post_title();
                echo $after;
            }
        
            echo '</div>';
        
        }
    }
}
endif;

if( ! function_exists( 'construction_landing_page_sidebar_layout' ) ) :
/**
 * Return sidebar layouts for pages
*/
function construction_landing_page_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'construction_landing_page_sidebar_layout', true ) ){
        return get_post_meta( $post->ID, 'construction_landing_page_sidebar_layout', true );    
    }else{
        return 'right-sidebar';
    }
}
endif;

if( ! function_exists( 'construction_landing_page_post_author' ) ) :
/**
 * Author Bio
 * 
*/
function construction_landing_page_post_author(){
    if( get_the_author_meta( 'description' ) ){
    ?>
    <section class="author">
		<h2><?php esc_html_e( 'About Author', 'construction-landing-page' ); ?></h2>
		<div class="holder">
			<div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 161 ); ?></div>
			<div class="text-holder">
				<strong class="name"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></strong>				
				<?php echo wpautop( esc_html( get_the_author_meta( 'description' ) ) ); ?>
			</div>
		</div>
	</section>
    <?php  
    }  
}
endif;

if( ! function_exists( 'construction_landing_page_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
*/
function construction_landing_page_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );    
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'construction-landing-page' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'construction-landing-page' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'construction-landing-page' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'construction_landing_page_change_comment_form_default_fields' );

if( ! function_exists( 'construction_landing_page_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
*/
function construction_landing_page_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment*', 'construction-landing-page' ) . '" cols="45" rows="8" aria-required="true"></textarea></p>';
    $defaults['title_reply'] = esc_html__( 'Leave a Reply', 'construction-landing-page' );
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'construction_landing_page_change_comment_form_defaults' );

if ( ! function_exists( 'construction_landing_page_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function construction_landing_page_excerpt_more() {
	return ' &hellip; ';
}

endif;
add_filter( 'excerpt_more', 'construction_landing_page_excerpt_more' );

if ( ! function_exists( 'construction_landing_page_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function construction_landing_page_excerpt_length( $length ) {
	return 20;
}
endif;
add_filter( 'excerpt_length', 'construction_landing_page_excerpt_length', 999 );

if( ! function_exists( 'construction_landing_page_get_section_header') ):
/**
 * Returns Section header
*/
function construction_landing_page_get_section_header( $section_title ){
    
        $header_query = new WP_Query( array( 
                
                'p' => $section_title,
                'post_type' => 'page'

                ));
        
        if( $section_title && $header_query->have_posts() ){ 
            while( $header_query->have_posts() ){ $header_query->the_post();
    ?>
                <header class="header">
                    <?php 
                        echo '<h2 class="main-title">';
                         the_title();
                         echo '</h2>';
                        echo the_content(); 
                    ?>
                </header>
    <?php   }
        wp_reset_postdata();
        }
    
}
endif;

if( ! function_exists( 'construction_landing_page_get_clients') ):
/**
 * Helper function for listing sponsor 
*/
function construction_landing_page_get_clients( $logo, $url ){
    
    echo '<div class="col">';
    if( $url ) echo '<a href="' . esc_url( $url ) . '" target="_blank">'; 
    if( $logo ) echo '<img src="' . esc_url( $logo ) . '">';
    if( $url ) echo '</a>';
    echo '</div>';
     
}
endif;

/**
 * Query Contact Form 7
 */
function construction_landing_page_is_cf7_activated() {
	return class_exists( 'WPCF7' ) ? true : false;
}

/**
 * Query WooCommerce activation
 */
function construction_landing_page_is_woocommerce_activated() {
    return class_exists( 'woocommerce' ) ? true : false;
}

if( ! function_exists( 'construction_landing_page_home_section') ):
/**
 * Check if home page section are enable or not.
*/
    function construction_landing_page_home_section(){

        $construction_landing_page_sections = array( 'banner', 'about', 'promotional', 'portfolio', 'services', 'clients', 'testimonials', 'contactform' );       
        $enable_section = false;
        foreach( $construction_landing_page_sections as $section ){ 
            if( get_theme_mod( 'construction_landing_page_ed_' . $section . '_section' ) == 1 ){
                $enable_section = true;
            }
        }
        return $enable_section;
    }
endif;

if( ! function_exists( 'construction_landing_page_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function construction_landing_page_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'construction-landing-page-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = construction_landing_page_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( construction_landing_page_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => "http://schema.org",
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => get_permalink( $post->ID )
            ),
            "headline"  => get_the_title( $post->ID ),
            "image"     => array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            ),
            "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
            "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => construction_landing_page_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "publisher" => array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if ( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if ( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">' , PHP_EOL;
        if ( version_compare( PHP_VERSION, '5.4.0' , '>=' ) ) {
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
        } else {
            echo wp_json_encode( $args ) , PHP_EOL;
        }
        echo '</script>' , PHP_EOL;
    }
}
endif;
add_action( 'wp_head', 'construction_landing_page_single_post_schema' );

if( ! function_exists( 'construction_landing_page_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function construction_landing_page_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;