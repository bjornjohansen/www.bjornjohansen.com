<?php
/**
 * The home template file
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wprig
 */

get_header(); ?>

	<main id="primary" class="site-main">

	<?php

	if ( have_posts() ) :

		/**
		 * Include the component stylesheet for the content.
		 * This call runs only once on index and archive pages.
		 * At some point, override functionality should be built in similar to the template part below.
		 */
		wp_print_styles( array( 'wprig-content' ) ); // Note: If this was already done it will be skipped.

		/* Display the appropriate header when required. */
		wprig_index_header();

		/* Start the Loop. */
		while ( have_posts() ) :
			the_post();

			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					?>
				</header>

				<div class="entry-content">
					<?php
					the_excerpt();
					?>
				</div>
			</article>

			<?php

		endwhile;

		if ( ! is_singular() ) :
			the_posts_navigation();
		endif;

		endif;
	?>

	</main><!-- #primary -->

<?php
get_sidebar();
get_footer();
