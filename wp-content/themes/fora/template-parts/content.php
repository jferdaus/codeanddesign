<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fora
 */

?>
<?php 
	$showPost = get_theme_mod('fora_theme_options_postshow', 'excerpt'); 
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php fora_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php
			if ( '' != get_the_post_thumbnail() ) {
				echo '<div class="entry-featuredImg"><a href="' .esc_url(get_permalink()). '">';
				the_post_thumbnail('fora-little-post');
				echo '</a></div>';
			}
		?>
		<?php if($showPost == 'excerpt'): ?>
			<?php the_excerpt(); ?>
		<?php else: ?>
			<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'fora' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'fora' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span class="page-links-number">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'fora' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
			?>
		<?php endif; ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php if($showPost == 'excerpt'): ?>
			<span class="read-more"><a href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Continue Reading', 'fora') ?><i class="fa spaceLeft fa-caret-right"></i></a></span>
		<?php endif; ?>
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

