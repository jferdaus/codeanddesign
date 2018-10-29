<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fora
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			if ( '' != get_the_post_thumbnail() ) {
				echo '<div class="entry-featuredImg">';
				the_post_thumbnail('fora-standard');
				echo '</div>';
			}
		?>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'fora' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span class="page-links-number">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'fora' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->
	<span class="updated notdisplay"><?php the_time(get_option('date_format')); ?></span>
	<div class="vcard author notdisplay"><a class="url fn" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></div>

	<footer class="entry-footer">
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'fora' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link center"><i class="fa fa-wrench spaceRight"></i>',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
<div class="sepHentry"><i class="fa fa-circle-thin" aria-hidden="true"></i></div>
