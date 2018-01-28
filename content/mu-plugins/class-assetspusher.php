<?php
/**
 * Record assets so we can push them HTTP/2 style.
 *
 * @package BJ\AssetsPusher
 * @author bjornjohansen
 * @version 0.1.0
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
 */

// phpcs:disable WordPress.VIP.RestrictedVariables.cache_constraints___COOKIE -- Because it’s only used to check if it is set.

namespace BJ;

/**
 * Class for handling asset pushing.
 */
class AssetsPusher {

	/**
	 * The stack of local asset URLs.
	 *
	 * @var string[] Array with URLs.
	 */
	private $_stack = [];

	/**
	 * The base URL: scheme and hostname.
	 *
	 * @var string The base URL.
	 */
	private $_base_url = '';

	/**
	 * The length of the base URL.
	 *
	 * @var integer The length of the base URL.
	 */
	private $_base_url_len = 0;

	/**
	 * Get an instance.
	 *
	 * @return AssetsPusher
	 */
	public static function instance() {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new AssetsPusher();
		}
		return $instance;
	}

	/**
	 * No outside constructions.
	 */
	private function __construct() {
		$this->_stack = [];

		$home_url_parsed     = wp_parse_url( home_url( '/' ) );
		$this->_base_url     = $home_url_parsed['scheme'] . '://' . $home_url_parsed['host'];
		$this->_base_url_len = strlen( $this->_base_url );
	}

	/**
	 * Use the script_loader_src filters to add the enqueued asset to our stack.
	 *
	 * @param string $src    The source URL of the enqueued asset.
	 * @param string $handle The asset's registered handle.
	 * @return string The source URL of the enqueued asset.
	 */
	public static function script_loader( $src, $handle ) {

		$assets_pusher = AssetsPusher::instance();
		$assets_pusher->add( $src, 'script' );

		return $src;
	}

	/**
	 * Use the style_loader_src filters to add the enqueued asset to our stack.
	 *
	 * @param string $src    The source URL of the enqueued asset.
	 * @param string $handle The asset's registered handle.
	 * @return string The source URL of the enqueued asset.
	 */
	public static function style_loader( $src, $handle ) {

		$assets_pusher = AssetsPusher::instance();
		$assets_pusher->add( $src, 'style' );

		return $src;
	}

	/**
	 * Add the asset src to our stack if it is a local URL.
	 *
	 * @param string $src  Asset URL.
	 * @param string $type Asset type.
	 */
	public function add( $src, $type ) {
		if ( substr( $src, 0, $this->_base_url_len ) === $this->_base_url ) {
			$src = substr( $src, $this->_base_url_len );

			if ( ! isset( $this->_stack[ $type ] ) || ! is_array( $this->_stack[ $type ] ) ) {
				$this->_stack[ $type ] = [];
			}

			if ( ! in_array( $src, $this->_stack[ $type ], true ) ) {
				$this->_stack[ $type ][] = $src;
			}
		}
	}

	/**
	 * Create the transient key for this request.
	 *
	 * @return string|false The transient key. False if it could not be created for this request.
	 */
	private function get_transient_key() {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) { // WPCS: input var ok.
			return false;
		}

		return 'assets-' . md5( $_SERVER['REQUEST_URI'] ); // WPCS: sanitization ok, input var ok.
	}

	/**
	 * Save all the enqueued local asset URLs.
	 */
	public function save_assets() {

		// We won’t push assets to logged in users, as they likely have the assets cached already,
		// so there’s no need to save the assets for logged in users (which are likely to be a longer stack
		// than for first time visitors).
		if ( is_user_logged_in() ) {
			return;
		}

		if ( count( $this->_stack ) ) {
			$transient_key = $this->get_transient_key();

			// Not a regular HTTP request.
			if ( ! $transient_key ) {
				return;
			}

			// We don’t need to re-save this on every request.
			// We’ll only re-save the asset list at max every 10 minutes.
			$save_transient    = true;
			$existing_obj_json = get_transient( $transient_key );
			if ( false !== $existing_obj_json ) {
				$existing_obj = json_decode( $existing_obj_json );
				if ( isset( $existing_obj->created ) && time() - 600 < $existing_obj->created ) {
					$save_transient = false;
				}
			}

			if ( $transient_key && $save_transient ) {
				$obj          = new \stdClass();
				$obj->created = time();
				$obj->assets  = $this->_stack;
				set_transient( $transient_key, wp_json_encode( $obj ), 86400 );
			}
		}
	}

	/**
	 * Get all the stored asset URLs for this request.
	 *
	 * @return string[] An array of all the local URLs.
	 */
	public function get_assets() {
		$assets = [];

		$transient_key = $this->get_transient_key();
		if ( $transient_key ) {

			$obj_json = get_transient( $transient_key );
			if ( false !== $obj_json ) {
				$obj = json_decode( $obj_json );
				if ( isset( $obj->assets ) ) {
					$assets = (array) $obj->assets;
				}
			}
		}

		return $assets;
	}

	/**
	 * Send the push headers.
	 */
	public function send_headers() {
		$assets = $this->get_assets();
		foreach ( $assets as $type => $urls ) {
			foreach ( $urls as $url ) {
				$header = sprintf( 'Link: <%s>; rel=preload; as=%s', esc_url( $url ), $type );
				header( $header, false );
			}
		}
	}
}

/*
 * Hook our loader into the script and style loaders. They will take care of enqueing dependencies for us,
 * and filtering out inline stuff, generating RTL src URLs and whatnot.
 */
add_filter( 'script_loader_src', [ '\BJ\AssetsPusher', 'script_loader' ], 99, 2 );
add_filter( 'style_loader_src', [ '\BJ\AssetsPusher', 'style_loader' ], 99, 2 );

/**
 * Store the local asset URLs for this request in a transient.
 */
add_action(
	'shutdown', function() {
		$assets_pusher = AssetsPusher::instance();
		$assets_pusher->save_assets();
	}
);

/**
 * Send out the push headers for our assets.
 */
add_action(
	'send_headers', function( & $wp ) {

		// If the user didn’t have any cookies, the user likely don’t have any assets cached either.
		if ( empty( $_COOKIE ) ) { // WPCS: input var ok.
			$assets_pusher = AssetsPusher::instance();
			$assets_pusher->send_headers();

			// Flush the output buffer to trigger the web server’s PUSH mechanism ASAP.
			flush();
		}

	}, 10, 1
);
