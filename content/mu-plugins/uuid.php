<?php
/**
 * RFC 4122 compliant UUIDs.
 *
 * The RFC 4122 specification defines a Uniform Resource Name namespace for
 * UUIDs (Universally Unique IDentifier), also known as GUIDs (Globally
 * Unique IDentifier).  A UUID is 128 bits long, and requires no central
 * registration process.
 *
 * @package UUID
 */

add_filter(
	'get_the_guid', function ( $guid ) {
		if ( preg_match( '/[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}/', $guid ) ) {
			$guid = 'urn:uuid:' . $guid;
		}
		return $guid;
	}
);

/**
 * RFC 4122 compliant UUID version 4.
 *
 * @return string A RFC 4122 compliant UUID version 4
 */
function uuid_v4() {
	/*
	 * Start with 128 random bits (16 bytes), and adjust specific bits later.
	 *
	 * RFC 4122 4.1:
	 * The UUID format is 16 octets; some bits of the eight octet variant
	 * field specified below determine finer structure.
	 *
	 * RFC 4122 4.4:
	 * Set all the other bits to randomly (or pseudo-randomly) chosen
	 * values.
	 */
	$octets = str_split( random_bytes( 16 ), 1 );

	/*
	 * Set version to 0100 (UUID version 4).
	 *
	 * RFC 4122 4.4 (UUID version 4):
	 * Set the four most significant bits (bits 12 through 15) of the
	 * time_hi_and_version field to the 4-bit version number from
	 * Section 4.1.3.
	 *
	 * RFC 4122 4.1.3:
	 * The version number is in the most significant 4 bits of the time
	 * stamp (bits 4 through 7 of the time_hi_and_version field).
	 * 0     1     0     0     (UUID version 4)
	 *
	 * According to RFC 4122 4.1.2, time_hi_and_version is octet 6-7.
	 */
	$octets[6] = chr( ord( $octets[6] ) & 0x0f | 0x40 );

	/*
	 * Set the UUID variant to the one defined by RFC 4122, according to RFC 4122 section 4.1.1.
	 *
	 * RFC 4122 4.4 (UUID version 4):
	 * Set the two most significant bits (bits 6 and 7) of the
	 * clock_seq_hi_and_reserved to zero and one, respectively.
	 *
	 * According to RFC 4122 4.1.2, clock_seq_hi_and_reserved is octet 8.
	 */
	$octets[8] = chr( ord( $octets[8] ) & 0x3f | 0x80 );

	// Hex encode the octets for string representation.
	$octets = array_map( 'bin2hex', $octets );

	// Return the octets in the format specified by the ABNF in RFC 4122 section 3.
	return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( implode( '', $octets ), 4 ) );
}

/**
 * RFC 4122 compliant UUID version 5.
 *
 * @param  string $name    The name to generate the UUID from.
 * @param  string $ns_uuid Namespace UUID. Default is for the NS when name string is a URL.
 * @return string          The UUID string.
 */
function uuid_v5( $name, $ns_uuid = '6ba7b811-9dad-11d1-80b4-00c04fd430c8' ) {

	// Compute the hash of the name space ID concatenated with the name.
	$hash = sha1( $ns_uuid . $name );

	// Intialize the octets with the 16 first octets of the hash, and adjust specific bits later.
	$octets = str_split( substr( $hash, 0, 16 ), 1 );

	/*
	 * Set version to 0101 (UUID version 5).
	 *
	 * Set the four most significant bits (bits 12 through 15) of the
	 * time_hi_and_version field to the appropriate 4-bit version number
	 * from Section 4.1.3.
	 *
	 * That is 0101 for version 5.
	 * time_hi_and_version is octets 6–7
	 */
	$octets[6] = chr( ord( $octets[6] ) & 0x0f | 0x50 );

	/*
	 * Set the UUID variant to the one defined by RFC 4122, according to RFC 4122 section 4.1.1.
	 *
	 * Set the two most significant bits (bits 6 and 7) of the
	 * clock_seq_hi_and_reserved to zero and one, respectively.
	 *
	 * clock_seq_hi_and_reserved is octet 8
	 */
	$octets[8] = chr( ord( $octets[8] ) & 0x3f | 0x80 );

	// Hex encode the octets for string representation.
	$octets = array_map( 'bin2hex', $octets );

	// Return the octets in the format specified by the ABNF in RFC 4122 section 3.
	return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( implode( '', $octets ), 4 ) );
}


add_action(
	'save_post', function( $post_id, $post = null, $update = null ) {
		/*
		* We’ll only update the GUIDs when inserting new posts.
		* A GUID should never be changed for an existing post.
		*/
		if ( ! $update ) {
			global $wpdb;

			$where = array(
				'ID' => $post_id,
			);

			$wpdb->update(
				$wpdb->posts, array(
					'guid' => uuid_v5( get_permalink( $post_id ) ),
				), $where
			);

			clean_post_cache( $post_id );
		}
	}
);
