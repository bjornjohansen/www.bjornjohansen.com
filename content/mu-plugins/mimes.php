<?php
/**
 * Populate the upload mimes with some useful data.
 *
 * @package bjornjohansen\www.bjornjohansen.com
 */

/**
 * Populate the upload mimes with some useful data.
 *
 * @param  array $mimes Current mimes.
 * @return array        Modified mimes.
 */
function bj_populate_mimes( $mimes ) {
		$mime_types = array(
			'ac3' => 'audio/ac3',
			'mpa' => 'audio/MPA',
			'flv' => 'video/x-flv',
			'ai'  => 'application/postscript',
			'eps' => 'application/postscript',
			'ppt' => 'application/vnd.ms-powerpoint',
			'pps' => 'application/vnd.ms-powerpoint',
			'svg' => 'image/svg+xml',
		);
		return array_merge( $mimes, $mime_types );
}
add_filter( 'upload_mimes', 'bj_populate_mimes' );
