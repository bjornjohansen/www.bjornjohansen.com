<?php
/**
 * Create immutable asset URLs.
 *
 * @package Immutable_Assets
 * @author bjornjohansen
 * @version 0.1.0
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
 */

/*
You want something like this in your Nginx config server context:
location ~* (.+)\.(?:[a-zA-Z0-9]+)\.(js|css)$ {
	try_files $uri $1.$2;
	expires max;
	add_header Cache-Control "public, immutable";
}

or something like this in your Apache VirtualHost section (or .htaccess):
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule (.+)\.(?:[a-zA-Z0-9]+)\.(js|css)$ $1.$2 [L]
</IfModule>
<IfModule mod_headers.c>
	<FilesMatch "\.(js|css)$">
		Header set Expires "Thu, 31 Dec 2037 23:55:55 GMT"
		Header set Cache-Control "max-age=315360000, public, immutable"
	</FilesMatch>
</IfModule>
*/

require __DIR__ . '/class-immutable-assets.php';

add_action(
	'init', function() {
		$ia = new Immutable_Assets();
		$ia->wp_setup();
	}
);
