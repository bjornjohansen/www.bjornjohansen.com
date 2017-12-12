<?php
/**
 * The WordPress configuration file.
 *
 * @package bjornjohansen\bjornjohansen.no
 */

define( 'DB_NAME', getenv( 'WP_DB_NAME' ) );
define( 'DB_USER', getenv( 'WP_DB_USER' ) );
define( 'DB_PASSWORD', getenv( 'WP_DB_PASSWORD' ) );
define( 'DB_HOST', getenv( 'WP_DB_HOST' ) );

define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY', getenv( 'WP_AUTH_KEY' ) );
define( 'SECURE_AUTH_KEY', getenv( 'WP_SECURE_AUTH_KEY' ) );
define( 'LOGGED_IN_KEY', getenv( 'WP_LOGGED_IN_KEY' ) );
define( 'NONCE_KEY', getenv( 'WP_NONCE_KEY' ) );
define( 'AUTH_SALT', getenv( 'WP_AUTH_SALT' ) );
define( 'SECURE_AUTH_SALT', getenv( 'WP_SECURE_AUTH_SALT' ) );
define( 'LOGGED_IN_SALT', getenv( 'WP_LOGGED_IN_SALT' ) );
define( 'NONCE_SALT', getenv( 'WP_NONCE_SALT' ) );

$table_prefix = 'bj_';

define( 'WP_CONTENT_DIR', __DIR__ . '/content' );
define( 'WP_CONTENT_URL', '/content' );

if ( ! defined( 'WP_SITEURL' ) ) {
	define( 'WP_SITEURL', '/wp' );
}

if ( ! defined( 'WP_HOME' ) ) {
	define( 'WP_HOME', '' );
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
