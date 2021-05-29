<?php
class Urban_Charity_Comment extends Walker_Comment {
	protected function html5_comment( $comment, $depth, $args ) {

		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li'; ?>

		<<?php echo esc_attr($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php
							$comment_author_link = get_comment_author_link( $comment );
							$comment_author_url  = get_comment_author_url( $comment );
							$comment_author      = get_comment_author( $comment );
							$avatar              = get_avatar( $comment, $args['avatar_size'] );
							if ( 0 != $args['avatar_size'] ) {
								if ( empty( $comment_author_url ) ) {
									echo wp_kses_post($avatar);
								} else {
									printf( '<a href="%s" rel="external nofollow" class="url skip-link">', esc_url($comment_author_url) );
									echo wp_kses_post($avatar);
								}
							}
							
							if ( ! empty( $comment_author_url ) ) {
								echo '</a>';
							}
						?>


					<div class="comment-metadata">

						<?php 
							printf(
								/* translators: %s: comment author link */
								'<h2 class="fn">' . get_comment_author_link( $comment ) . '</h2>'
							);


						 ?>
						<a class="skip-link" href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<?php
								/* translators: 1: comment date, 2: comment time */
								$comment_timestamp = sprintf( __( '%1$s at %2$s', 'urban-charity' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
							<time datetime="<?php comment_time( 'c' ); ?>" title="<?php echo esc_attr( $comment_timestamp ); ?>">
								<?php echo esc_attr( $comment_timestamp ); ?>
							</time>
						</a>
						<?php edit_comment_link( __( 'Edit', 'urban-charity' ), ' <span class="edit-link"></span>' ); ?>
						<?php
							comment_reply_link(
								array_merge(
									$args,
									array(
										'add_below' => 'div-comment',
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
										'before'    => '<div class="comment-reply">',
										'after'     => '</div>',
									)
								)
							);
						?>
					</div><!-- .comment-metadata -->



					</div><!-- .comment-author -->

					

					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'urban-charity' ); ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->

				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
			</article><!-- .comment-body -->

			
		<?php
	}
}
