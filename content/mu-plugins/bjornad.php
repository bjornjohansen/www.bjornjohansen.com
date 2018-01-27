<?php
/**
 * The website ads.
 *
 * @package BJ\Ads
 */

namespace BJ\Ads;

/**
 * The bjornad shortcode callback handler.
 *
 * @param array|string $atts Shortcode attributes array or empty string.
 * @return string Rendered HTML for the shortcode.
 */
function bjornad_func( $atts ) {
	return '';
}
add_shortcode( 'bjornad', '\BJ\Ads\bjornad_func' );


/**
 * Add an ad to the start and end of each post.
 *
 * @param string $content The post content.
 * @return string The post content with ads.
 */
function display_ad( $content ) {

	if ( is_single() && 2124 !== get_the_ID() ) {

		$ad_content = '<hr><p>By the way: <a href="https://bjornjohansen.no/welcome-to-wordcamp-oslo-2018">Check out the WordPress conference <span style="white-space:nowrap">WordCamp Oslo 2018</span></a></p><hr>';

		$content = $content . $ad_content;
	}

	return $content;

}
add_filter( 'the_content', '\BJ\Ads\display_ad' );
