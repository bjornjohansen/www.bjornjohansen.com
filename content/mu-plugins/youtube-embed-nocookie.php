<?php
/**
 * Use tracking free domain for YouTube embeds.
 *
 * Found: https://rickylee.com/2018/03/26/youtube-nocookie-gdpr-wordpress/
 */
/**
 * Modify YouTube oEmbeds to use youtube-nocookie.com
 *
 * @param $cached_html
 * @param $url
 *
 * @return string
 */
function filter_youtube_embed( $cached_html, $url = null ) {
	// Search for youtu to return true for both youtube.com and youtu.be URLs
	if ( strpos( $url, 'youtu' ) ) {
		$cached_html = preg_replace( '/youtube\.com\/(v|embed)\//s', 'youtube-nocookie.com/$1/', $cached_html );
	}
	return $cached_html;
}
add_filter( 'embed_oembed_html', 'filter_youtube_embed', 10, 2 );
