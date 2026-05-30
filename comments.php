<?php
/**
 * Comments template
 *
 * @package GenericStarter
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area mt-12 pt-10 border-t border-neutral-200 dark:border-neutral-700">

	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title text-xl font-bold mb-8">
		<?php
		$comment_count = get_comments_number();
		if ( '1' === $comment_count ) {
			printf(
				/* translators: %s: post title */
				esc_html__( 'One thought on &ldquo;%s&rdquo;', 'genericstarter' ),
				'<span>' . wp_kses_post( get_the_title() ) . '</span>'
			);
		} else {
			printf(
				/* translators: 1: comment count, 2: post title */
				esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'genericstarter' ) ),
				number_format_i18n( $comment_count ),
				'<span>' . wp_kses_post( get_the_title() ) . '</span>'
			);
		}
		?>
	</h2>

	<ol class="comment-list space-y-8 mb-10">
		<?php
		wp_list_comments( [
			'style'       => 'ol',
			'short_ping'  => true,
			'avatar_size' => 48,
			'callback'    => 'genericstarter_comment_template',
		] );
		?>
	</ol>

	<?php
	the_comments_navigation( [
		'prev_text' => esc_html__( '&larr; Older comments', 'genericstarter' ),
		'next_text' => esc_html__( 'Newer comments &rarr;', 'genericstarter' ),
	] );
	?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<p class="no-comments text-sm text-neutral-700/60 dark:text-neutral-500">
		<?php esc_html_e( 'Comments are closed.', 'genericstarter' ); ?>
	</p>
	<?php endif; ?>

	<?php
	comment_form( [
		'title_reply'          => esc_html__( 'Leave a comment', 'genericstarter' ),
		'title_reply_to'       => esc_html__( 'Reply to %s', 'genericstarter' ),
		'title_reply_before'   => '<h2 class="comment-reply-title text-xl font-bold mb-6">',
		'title_reply_after'    => '</h2>',
		'cancel_reply_before'  => ' <span class="text-sm font-normal">',
		'cancel_reply_after'   => '</span>',
		'comment_notes_before' => '<p class="comment-notes text-sm text-neutral-700/60 dark:text-neutral-500 mb-4">'
			. esc_html__( 'Your email address will not be published.', 'genericstarter' )
			. '</p>',
		'fields'               => [
			'author' => '<div class="grid sm:grid-cols-2 gap-4 mb-4"><p class="comment-form-author">
				<label for="author">' . esc_html__( 'Name', 'genericstarter' ) . ' <span aria-hidden="true">*</span></label>
				<input id="author" name="author" type="text" value="' . esc_attr( isset( $commenter ) ? $commenter['comment_author'] : '' ) . '" size="30" maxlength="245" autocomplete="name" required>
			</p>',
			'email'  => '<p class="comment-form-email">
				<label for="email">' . esc_html__( 'Email', 'genericstarter' ) . ' <span aria-hidden="true">*</span></label>
				<input id="email" name="email" type="email" value="' . esc_attr( isset( $commenter ) ? $commenter['comment_author_email'] : '' ) . '" size="30" maxlength="100" autocomplete="email" required>
			</p></div>',
		],
		'comment_field'        => '<p class="comment-form-comment mb-4">
			<label for="comment">' . esc_html__( 'Comment', 'genericstarter' ) . ' <span aria-hidden="true">*</span></label>
			<textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required></textarea>
		</p>',
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn-primary" value="%4$s">%4$s</button>',
		'class_submit'         => '',
		'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
		'class_form'           => 'comment-form',
	] );
	?>

</div><!-- #comments -->

