<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Ursus_Arctos
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

<style>
	.site {
		text-align: center;
	}
	.site-header {
		background: #069;
		color: #fff;
	}
	.site-title {
		font-size: 2em;
		font-weight: normal;
		line-height: 1.2em;
		margin: 0;
		padding: 1em;
	}
	.site-title a {
		color: #fff;
		text-decoration: none;
	}
	.main-navigation,
	.posts-navigation {
		background: #000;
		padding: 0.5em;
		display: flex;
		justify-content: center;
	}
	.posts-navigation {
		justify-content: space-evenly;
	}
	.main-navigation a,
	.posts-navigation a {
		color: #fff;
		padding: 0 1em;
		white-space: nowrap;
	}
	.main-navigation a:active,
	.main-navigation a:hover,
	.main-navigation a:focus,
	.posts-navigation a:active,
	.posts-navigation a:hover,
	.posts-navigation a:focus {
		color: #09a;
	}

	.site-content {

	}
	.entry-header,
	.entry-content,
	.entry-footer,
	.comments-area {
		max-width: 50em;
		margin: 0 auto;
		padding: 0 1em;
		text-align: left;
	}
	.post-thumbnail {
		background-color: #0e2231;
		width: 100%;
	}
	.post-thumbnail img {
		width: 100%;
		height: auto;
	}
	h2 a,
	h2 a:visited {
		color: #000;
		text-decoration: none;
		font-weight: normal;
	}
	h2 a:hover,
	h2 a:focus,
	h2 a:active {
		text-decoration: underline;
	}
	.post {
		padding-bottom: 4em;
	}
	.home .site-main {
		padding-top: 4em;
	}
</style>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ursus-arctos' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$ursus_arctos_description = get_bloginfo( 'description', 'display' );
			if ( $ursus_arctos_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $ursus_arctos_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'ursus-arctos' ); ?></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
