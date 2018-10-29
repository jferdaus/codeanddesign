<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fora
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php fora_entry_category(); ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' );
		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php fora_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
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

	<footer class="entry-footer">
		<?php fora_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
<div class="sepHentry"><i class="fa fa-circle-thin" aria-hidden="true"></i></div>
