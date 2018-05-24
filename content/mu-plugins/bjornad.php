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


/**
 * Add an advertisement right after the “read more” separator.
 */
add_filter(
	'the_content', function ( $content ) {
		if ( ! is_singular() ) {
			return $content;
		}

		$ad = '<div style="border-top: 1px solid #aaa; border-bottom: 1px solid #aaa;"><div style="font-size: 0.75em; color: #aaa;">Advertisement:</div><div style="text-align: center"><a href="https://servebolt.com/platforms/wordpress/?ref=bjornjohansen" rel="nofollow"><img src="https://bjornjohansen.no/content/uploads/2018/05/servebolt-ad.png" border="0"></a></div></div>';

		if ( false !== strpos( $content, '<!--more-->' ) ) {
			$content = str_replace( '<!--more-->', '<!--more-->' . $ad, $content );
		}

		return $content;
	}, 10, 1
);
