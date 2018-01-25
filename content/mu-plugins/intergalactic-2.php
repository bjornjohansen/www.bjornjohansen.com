<?php
/**
 * Intergalactic 2 customizations.
 *
 * @package BJ\Intergalactic2
 */

/**
 * Replace the intergalactic-large image with a medium image on all non-single pages (blog, archives etc).
 *
 * @param array|false  $image         Either array with src, width & height, icon src, or false.
 * @param int          $attachment_id Image attachment ID.
 * @param string|array $size          Size of image. Image size or array of width and height values
 *                                    (in that order). Default 'thumbnail'.
 * @param bool         $icon          Whether the image should be treated as an icon. Default false.
 * @return array|false Either array with src, width & height, icon src, or false.
 */
add_filter( 'wp_get_attachment_image_src', function( $image, $attachment_id, $size = 'thumbnail', $icon = false ) {

	if ( is_string( $size ) && 'intergalactic-large' === $size && ! is_single() ) {
		$size = 'medium';
		$image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	}

	return $image;
}, 10, 4 );
