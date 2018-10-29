<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fora
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="main_footer">
			<?php $socialFooter = get_theme_mod('fora_theme_options_socialfooter', ''); ?>
			<?php if($socialFooter == 1): ?>
				<div class="site-social-footer">
					<?php fora_social_buttons(); ?>
				</div><!-- .site-social -->
			<?php endif; ?>
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'fora' ) ); ?>">
				<?php
				/* translators: %s: WordPress name */
				printf( esc_html__( 'Proudly powered by %s', 'fora' ), 'WordPress' );
				?>
				</a>
				<span class="sep"> | </span>
				<?php
				/* translators: 1: theme name, 2: theme developer */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'fora' ), '<a target="_blank" href="https://crestaproject.com/downloads/fora/" rel="nofollow" title="Fora Theme">Fora Light</a>', 'CrestaProject WordPress Themes' );
				?>
			</div><!-- .site-info -->
		</div><!-- .main_footer -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<a href="#top" id="toTop"><i class="fa fa-angle-up fa-lg"></i></a>
<?php wp_footer(); ?>

</body>
</html>
