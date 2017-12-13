<?php
/**
 * Create immutable asset URLs.
 *
 * @package Immutable_Assets
 * @author bjornjohansen
 * @version 0.1.0
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
 */

/**
 * Immutable assets class
 */
class Immutable_Assets {

	/**
	 * Holds our base URL.
	 *
	 * @var string
	 */
	private $_base_url = null;

	/**
	 * Holds our base URL hostname.
	 *
	 * @var string
	 */
	private $_base_host = null;

	/**
	 * Init dynamic configuration.
	 *
	 * @return bool Whether initialization was OK.
	 */
	private function init_config() {
		$result = false;

		$base_url = site_url();
		if ( ! $base_url || ! strlen( $base_url ) ) {
			$base_url = wp_guess_url();
		}

		// We have to know who we are, before we try to be clever.
		if ( strlen( $base_url ) ) {
			$base_urlparts = wp_parse_url( $base_url );
			if ( isset( $base_urlparts['host'] ) ) {
				$this->_base_url  = $base_url;
				$this->_base_host = $base_urlparts['host'];
				$result           = true;
			}
		}

		return $result;
	}

	/**
	 * Setup WP filters
	 */
	public function wp_setup() {
		if ( $this->init_config() ) {
			add_filter( 'script_loader_src', [ $this, 'modify_url' ], 10, 2 );
			add_filter( 'style_loader_src', [ $this, 'modify_url' ], 10, 2 );
		}
	}

	/**
	 * Create an immutable asset URL
	 *
	 * Takes the version string, normalizes it with base64 encoding if needed, and
	 * injects it into the asset URL if the source points to our installation.
	 * The new URLs must be handled with rewrite rules in the web server (Apache/Nginx).
	 *
	 * @param string $src    The source URL.
	 * @param string $handle The asset’s handle.
	 * @return string The possibly modified URL.
	 */
	public function modify_url( $src, $handle ) {

		if ( is_null( $this->_base_url ) ) {
			if ( ! $this->init_config() ) {
				return $src;
			}
		}

		// Parse the URL so we can understand it a little better.
		$urlparts = wp_parse_url( $src );

		if ( ! isset( $urlparts['path'] ) ) {
			// We could obviously not parse this URL.
			return $src;
		}

		// Check if the asset URL points to this installation.
		if ( ! isset( $urlparts['host'] ) || $urlparts['host'] === $this->_base_host ) {

			$ver = null;
			if ( isset( $urlparts['query'] ) ) {
				// Get the version string from the query vars.
				$queryvars = [];
				parse_str( $urlparts['query'], $queryvars );
				if ( isset( $queryvars['ver'] ) ) {
					$ver = $queryvars['ver'];
				}
			} else {
				// Try to get the filemtime as version.
				if ( defined( 'WP_CONTENT_URL' ) && defined( 'WP_CONTENT_DIR' ) && false !== strpos( $src, WP_CONTENT_URL ) ) {
					$path      = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src );
					$filemtime = false;
					if ( is_file( $path ) ) {
						$filemtime = filemtime( $path );
					}
					if ( false !== $filemtime ) {
						$var = $filemtime;
					}
				}
			}

			// Normalize the version string if needed.
			if ( strlen( $ver ) && ! preg_match( '/^[a-zA-Z0-9]+$/', $ver ) ) {
				$ver = rtrim( base64_encode( $ver ), '=' );
			}

			// Check if we actually has a version string.
			if ( strlen( $ver ) ) {

				// Get the filename part and inject our version string.
				$pathparts   = explode( '/', $urlparts['path'] );
				$file        = array_pop( $pathparts );
				$file        = preg_replace( '/\.(css|js)$/', sprintf( '.%s.$1', $ver ), $file );
				$pathparts[] = $file;

				// Set src to the modified path.
				$src = implode( '/', $pathparts );

				// If a hostname was given in the original asset URL we’ll prepend the hostname.
				if ( isset( $urlparts['host'] ) && strlen( $urlparts['host'] ) ) {
					$src = '//' . $urlparts['host'] . $src;
				}

				// If a scheme was given in the original asset uRL, we’ll prepend the scheme.
				if ( isset( $urlparts['scheme'] ) && strlen( $urlparts['scheme'] ) ) {
					$src = $urlparts['scheme'] . ':' . $src;
				}
			}
		}

		return $src;

	}

}
