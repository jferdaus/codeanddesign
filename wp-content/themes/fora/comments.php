<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fora
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$comments_number = get_comments_number();
				if ( 1 === $comments_number ) {
					esc_html_e( '1 Comment', 'fora' );
				} else {
					/* translators: %s: number of comments */
					printf( esc_html( _nx( '%d Comment', '%d Comments', $comments_number, 'comments title', 'fora' ) ), number_format_i18n( $comments_number ));
				}
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => '70',
					'reply_text'        =>  '<span>' .esc_html__( 'Reply'  , 'fora' ) . '<i class="fa fa-reply spaceLeft"></i></span>',
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'fora' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'fora' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'fora' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'fora' ); ?></p>
	<?php
	endif;

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields =  array(
			'author' => '<p class="comment-form-author"><label for="author"><span class="screen-reader-text">' . esc_html__( 'Name *'  , 'fora' ) . '</span></label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' placeholder="' . esc_attr__( 'Name *'  , 'fora' ) . '"/></p>',
			'email'  => '<p class="comment-form-email"><label for="email"><span class="screen-reader-text">' . esc_html__( 'Email *'  , 'fora' ) . '</span></label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' placeholder="' . esc_attr__( 'Email *'  , 'fora' ) . '"/></p>',
			'url'    => '<p class="comment-form-url"><label for="url"><span class="screen-reader-text">' . esc_html__( 'Website'  , 'fora' ) . '</span></label><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . esc_attr__( 'Website'  , 'fora' ) . '"/></p>',
		);
		$required_text = esc_html__('Required fields are marked ', 'fora').' <span class="required">*</span>';
		?>
		<?php comment_form( array(
			'fields' => apply_filters( 'comment_form_default_fields', $fields ),
			/* translators: %s: wordpress login url */
			'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' , 'fora' ), wp_login_url( apply_filters( 'the_permalink', esc_url(get_permalink( ) ) ) ) ) . '</p>',
			/* translators: 1: profile user link, 2: username, 3: logout link */
			'logged_in_as' => '<p class="logged-in-as smallPart">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'  , 'fora' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', esc_url(get_permalink( ) ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="comment-notes smallPart">' . esc_html__( 'Your email address will not be published.'  , 'fora' ) . ( $req ? $required_text : '' ) . '</p>',
			'title_reply' => esc_html__( 'Leave a Reply'  , 'fora' ),
			/* translators: %s: name of person to reply */
			'title_reply_to' => esc_html__( 'Leave a Reply to %s'  , 'fora' ),
			'cancel_reply_link' => esc_html__( 'Cancel reply'  , 'fora' ) . '<i class="fa fa-times spaceLeft"></i>',
			'label_submit' => esc_html__( 'Post Comment'  , 'fora' ),
			'comment_field' => '<p class="comment-form-comment"><label for="comment"><span class="screen-reader-text">' . esc_html__( 'Comment *'  , 'fora' ) . '</span></label><textarea id="comment" name="comment" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Comment *'  , 'fora' ) . '"></textarea></p>',
		));
	?>

</div><!-- #comments -->
