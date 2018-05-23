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

	if ( is_single() ) {

		$ad_content = '<hr><p>By the way: If you want hassle-free, amazingly fast web hosting, you should check out <a href="https://servebolt.com/?ref=bjornjohansen">Servebolt</a> (affiliate link). They’ll even transfer your site for free.</p><hr>';

		$content = $content . $ad_content;
	}

	return $content;

}
add_filter( 'the_content', '\BJ\Ads\display_ad' );
