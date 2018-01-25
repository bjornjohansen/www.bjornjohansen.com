<?php
/**
 * The website ads.
 *
 * @package BJ\Ads
 */

/**
 * The bjornad shortcode callback handler.
 *
 * @param array|string $atts Shortcode attributes array or empty string.
 * @return string Rendered HTML for the shortcode.
 */
function bjornad_func( $atts ) {
	return '';
}
add_shortcode( 'bjornad', 'bjornad_func' );
