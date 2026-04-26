<?php
declare(strict_types=1);
/**
 * Template tag functions — output helpers for templates
 *
 * @package MedSpaStarter
 */

if ( ! function_exists( 'medspastarter_posted_on' ) ) :
	function medspastarter_posted_on(): void {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		echo '<span class="posted-on flex items-center gap-1">';
		medspastarter_icon( 'calendar', 'w-4 h-4 shrink-0' );
		echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
		echo '</span>';
	}
endif;

if ( ! function_exists( 'medspastarter_posted_by' ) ) :
	function medspastarter_posted_by(): void {
		echo '<span class="byline flex items-center gap-1">';
		medspastarter_icon( 'user', 'w-4 h-4 shrink-0' );
		echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">'
			. esc_html( get_the_author() ) . '</a>';
		echo '</span>';
	}
endif;

if ( ! function_exists( 'medspastarter_posted_in' ) ) :
	function medspastarter_posted_in(): void {
		if ( 'post' !== get_post_type() ) {
			return;
		}
		$categories_list = get_the_category_list( esc_html__( ', ', 'medspastarter' ) );
		if ( $categories_list ) {
			echo '<span class="cat-links flex items-center gap-1">';
			medspastarter_icon( 'folder', 'w-4 h-4 shrink-0' );
			echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'medspastarter_tagged_in' ) ) :
	function medspastarter_tagged_in(): void {
		if ( 'post' !== get_post_type() ) {
			return;
		}
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'medspastarter' ) );
		if ( $tags_list ) {
			echo '<div class="tag-links flex flex-wrap items-center gap-2 mt-4">';
			medspastarter_icon( 'tag', 'w-4 h-4 shrink-0 text-neutral-700/50 dark:text-neutral-500' );
			echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		}
	}
endif;

if ( ! function_exists( 'medspastarter_post_thumbnail' ) ) :
	function medspastarter_post_thumbnail( string $size = 'large' ): void {
		if ( post_password_required() || is_attachment() ) {
			return;
		}

		if ( ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) {
			echo '<div class="post-thumbnail mb-8 rounded-2xl overflow-hidden">';
			the_post_thumbnail( $size, [ 'class' => 'w-full h-auto object-cover' ] );
			echo '</div>';
		} else {
			echo '<a class="post-thumbnail block overflow-hidden rounded-t-2xl aspect-video" href="' . esc_url( get_permalink() ) . '" tabindex="-1" aria-hidden="true">';
			the_post_thumbnail( 'medspastarter-card', [
				'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
				'alt'   => the_title_attribute( [ 'echo' => false ] ),
			] );
			echo '</a>';
		}
	}
endif;

if ( ! function_exists( 'medspastarter_edit_link' ) ) :
	function medspastarter_edit_link(): void {
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'medspastarter' ),
					[ 'span' => [ 'class' => [] ] ]
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link inline-flex items-center gap-1 text-sm text-neutral-700/50 hover:text-primary dark:text-neutral-500 dark:hover:text-primary-light mt-2">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'medspastarter_reading_time' ) ) :
	function medspastarter_reading_time(): void {
		if ( 'post' !== get_post_type() ) {
			return;
		}
		global $post;
		$word_count  = (int) str_word_count( wp_strip_all_tags( $post->post_content ) );
		$minutes     = max( 1, (int) ceil( $word_count / 225 ) );
		echo '<span class="reading-time flex items-center gap-1">';
		medspastarter_icon( 'clock', 'w-4 h-4 shrink-0' );
		/* translators: %d: minutes to read */
		printf( esc_html( _n( '%d min read', '%d min read', $minutes, 'medspastarter' ) ), $minutes );
		echo '</span>';
	}
endif;

if ( ! function_exists( 'medspastarter_category_badge' ) ) :
	function medspastarter_category_badge(): void {
		if ( 'post' !== get_post_type() ) {
			return;
		}
		$category = get_the_category();
		if ( $category ) {
			echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" class="cat-badge">'
				. esc_html( $category[0]->name ) . '</a>';
		}
	}
endif;

if ( ! function_exists( 'medspastarter_breadcrumbs' ) ) :
	function medspastarter_breadcrumbs(): void {
		if ( ! get_theme_mod( 'has_breadcrumbs', true ) ) {
			return;
		}
		if ( is_front_page() || is_home() ) {
			return;
		}

		$items   = [];
		$items[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'medspastarter' ) . '</a>';

		if ( is_singular() ) {
			if ( 'post' === get_post_type() ) {
				$cats = get_the_category();
				if ( $cats ) {
					$items[] = '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
				}
			}
			$items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
		} elseif ( is_category() ) {
			$items[] = '<span aria-current="page">' . esc_html( single_cat_title( '', false ) ) . '</span>';
		} elseif ( is_tag() ) {
			$items[] = '<span aria-current="page">' . esc_html( single_tag_title( '', false ) ) . '</span>';
		} elseif ( is_archive() ) {
			$items[] = '<span aria-current="page">' . esc_html( get_the_archive_title() ) . '</span>';
		} elseif ( is_search() ) {
			$items[] = '<span aria-current="page">' . esc_html__( 'Search results', 'medspastarter' ) . '</span>';
		} elseif ( is_404() ) {
			$items[] = '<span aria-current="page">' . esc_html__( 'Page not found', 'medspastarter' ) . '</span>';
		}

		$sep   = medspastarter_get_icon( 'chevron-right', 'w-3 h-3 rtl:rotate-180 shrink-0' );
		$count = count( $items );

		echo '<nav class="breadcrumbs mb-4" aria-label="' . esc_attr__( 'Breadcrumb', 'medspastarter' ) . '">';
		echo '<ol class="flex flex-wrap items-center gap-1.5 text-sm text-neutral-500 dark:text-neutral-400">';
		foreach ( $items as $i => $item ) {
			echo '<li class="flex items-center gap-1.5">';
			echo $item; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( $i < $count - 1 ) {
				echo $sep; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '</li>';
		}
		echo '</ol></nav>';
	}
endif;

if ( ! function_exists( 'medspastarter_comment_template' ) ) :
	function medspastarter_comment_template( WP_Comment $comment, array $args, int $depth ): void {
		$GLOBALS['comment'] = $comment;
		?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item flex gap-4', $comment ); ?>>

			<div class="comment-avatar shrink-0 mt-1">
				<?php echo get_avatar( $comment, 48, '', '', [ 'class' => 'rounded-full' ] ); ?>
			</div>

			<div class="comment-body flex-1">
				<div class="comment-meta flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-2">
					<span class="comment-author font-semibold text-neutral-900 dark:text-neutral-100">
						<?php comment_author(); ?>
					</span>
					<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>" class="comment-date text-sm text-neutral-700/50 hover:text-primary dark:text-neutral-500 dark:hover:text-primary-light no-underline">
						<time datetime="<?php comment_time( 'c' ); ?>"><?php comment_date(); ?></time>
					</a>
					<?php if ( '0' === $comment->comment_approved ) : ?>
					<em class="text-sm text-secondary"><?php esc_html_e( 'Awaiting moderation.', 'medspastarter' ); ?></em>
					<?php endif; ?>
				</div>

				<div class="comment-content text-sm leading-relaxed text-neutral-700 dark:text-neutral-300">
					<?php comment_text(); ?>
				</div>

				<div class="reply-link mt-2">
					<?php
					comment_reply_link( array_merge( $args, [
						'add_below' => 'comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<span class="text-sm text-primary hover:underline">',
						'after'     => '</span>',
					] ) );
					?>
				</div>
			</div>
		</li>
		<?php
	}
endif;

if ( ! function_exists( 'medspastarter_post_navigation' ) ) :
	function medspastarter_post_navigation(): void {
		the_post_navigation( [
			'prev_text' => '<span class="flex items-center gap-2 text-sm font-medium text-neutral-700 dark:text-neutral-400 group-hover:text-primary dark:group-hover:text-primary-light transition-colors">'
				. medspastarter_get_icon( 'arrow-left', 'w-4 h-4 rtl:rotate-180' )
				. '<span><span class="block text-xs text-neutral-700/50 dark:text-neutral-500 uppercase tracking-wide">' . esc_html__( 'Previous', 'medspastarter' ) . '</span>%title</span></span>',
			'next_text' => '<span class="flex items-center gap-2 text-sm font-medium text-neutral-700 dark:text-neutral-400 group-hover:text-primary dark:group-hover:text-primary-light transition-colors">'
				. '<span><span class="block text-xs text-neutral-700/50 dark:text-neutral-500 uppercase tracking-wide">' . esc_html__( 'Next', 'medspastarter' ) . '</span>%title</span>'
				. medspastarter_get_icon( 'arrow-right', 'w-4 h-4 rtl:rotate-180' ) . '</span>',
			'class'     => 'post-navigation flex items-stretch justify-between gap-4 py-8 border-t border-neutral-200 dark:border-neutral-700',
		] );
	}
endif;
