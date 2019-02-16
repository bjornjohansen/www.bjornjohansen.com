<?php
/**
 * The home template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ursus_Arctos
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/* Start the Loop */
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
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php
						echo sprintf(
							'%1$s%2$s &rarr;',
							esc_html__( 'Continue reading', 'ursus-arctos' ),
							sprintf( '<span class="screen-reader-text"> &ldquo;%s&rdquo;</span>', esc_html( get_the_title() ) )
						);
						?>
					</a>
				</div>


				</article>


				<?php
			endwhile;

			the_posts_navigation();
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
