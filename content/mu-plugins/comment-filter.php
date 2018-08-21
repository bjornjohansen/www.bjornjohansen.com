<?php
/**
 * Filter comments.
 *
 * @package BJ\Comment;
 */

namespace BJ\Comment;

/**
 * Filters a comment's approval status before it is set.
 *
 * Returning a WP_Error value from the filter will shortcircuit comment insertion and
 * allow skipping further processing.
 *
 * @param bool|string|WP_Error $approved    The approval status. Accepts 1, 0, 'spam' or WP_Error.
 * @param array                $commentdata Comment data.
 * @return bool|string|WP_Error             The approval status. Can be 1, 0, 'spam' or WP_Error.
 */
function comment_filter_cyrillic( $approved, $commentdata ) {

	// Remove all whitespaces.
	$input = preg_replace( '/\s/', '', $commentdata['comment_content'] );

	// Remove all cyrillic letters (U+0400..U+04FF).
	$filtered_input = mb_ereg_replace( '[Ѐ-ӿ]', '', $input );

	// Calculate the ratio of cyrillic in the input.
	$orgin_len      = mb_strlen( $input );
	$filtered_len   = mb_strlen( $filtered_input );
	$ratio_filtered = ( $orgin_len - $filtered_len ) / $orgin_len;

	// If more than 50% is cyrillic, it is spam.
	$is_spam = $ratio_filtered > 0.5;

	// Let’s keep it for moderation.
	return ( $is_spam ? 0 : 1 );
}
add_filter( 'pre_comment_approved', '\BJ\Comment\comment_filter_cyrillic', 10, 2 );
