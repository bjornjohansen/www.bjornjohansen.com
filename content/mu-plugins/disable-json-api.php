<?php
/**
 * WP REST API customizations.
 *
 * @package bjornjohansen\bjornjohansen.no
 */

/**
 * Disable the WP REST API for non-authenticated users.
 *
 * @param WP_Error|null|bool $access Current auth status.
 * @return WP_Error|null|bool WP_Error if authentication error, null if authentication method wasn't used, true if authentication succeeded.
 */
function bj_disable_nonauth_rest( $access ) {
	if ( ! is_user_logged_in() ) {
		return new WP_Error( 'rest_authorization_required', 'You must be authenticated and authorized first.', array( 'status' => rest_authorization_required_code() ) );
	}

	return $access;
}
add_filter( 'rest_authentication_errors', 'bj_disable_nonauth_rest', 10, 1 );
