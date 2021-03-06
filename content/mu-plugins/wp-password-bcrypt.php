<?php
/**
 * Replaces wp_hash_password and wp_check_password with PHP 5.5's password_hash and password_verify.
 *
 * @package bjornjohansen\www.bjornjohansen.com
 */

/**
 * Used to check hash version.
 *
 * @var string Old wp hash prefix.
 */
const WP_OLD_HASH_PREFIX = '$P$';

/**
 * Check if user has entered correct password, supports bcrypt and pHash.
 *
 * @param string     $password Plaintext password.
 * @param string     $hash Hash of password.
 * @param int|string $user_id ID of user to whom password belongs.
 * @return mixed|void
 */
function wp_check_password( $password, $hash, $user_id = '' ) {
	if ( strpos( $hash, WP_OLD_HASH_PREFIX ) === 0 ) {
		global $wp_hasher;

		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';

			$wp_hasher = new PasswordHash( 8, true ); // WPCS: override ok.
		}

		$check = $wp_hasher->CheckPassword( $password, $hash );

		if ( $check && $user_id ) {
			$hash = wp_set_password( $password, $user_id );
		}
	}

	$check = password_verify( $password, $hash );
	return apply_filters( 'check_password', $check, $password, $hash, $user_id );
}

/**
 * Hash password using bcrypt
 *
 * @param string $password Plaintext password.
 * @return bool|string
 */
function wp_hash_password( $password ) {
	$options = apply_filters( 'wp_hash_password_options', [] );
	return password_hash( $password, PASSWORD_DEFAULT, $options );
}

/**
 * Set password using bcrypt
 *
 * @param string $password Plaintext password.
 * @param int    $user_id ID of user to whom password belongs.
 * @return bool|string
 */
function wp_set_password( $password, $user_id ) {
	/**
	 * The WPDB object.
	 *
	 * @var \wpdb $wpdb
	 */
	global $wpdb;

	$hash = wp_hash_password( $password );

	// phpcs:disable WordPress.VIP.RestrictedVariables.user_meta__wpdb__users

	$wpdb->update(
		$wpdb->users, [
			'user_pass'           => $hash,
			'user_activation_key' => '',
		], [ 'ID' => $user_id ]
	); // WPCS: db call ok.
	wp_cache_delete( $user_id, 'users' );

	return $hash;
}
