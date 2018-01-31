<?php
/**
 * Generate rest doute
 * 
 * Route location: /wp-content/themes/wordpress-rest-transient/test.php?slug=sample-page&type=page
 *
 * @since   1.0.0
 * @package wordpress-rest-transient
 */

 // Load simple version of WordPress, this file can be located anywhere.
require_once( 'wp-config-simple.php' );

// Load function to be able to call some functions.
require_once('functions.php');

// Check input and protect it.
if ( ( isset( $_GET['slug'] ) || ! empty( $_GET['slug'] ) ) && ( isset( $_GET['type'] ) || ! empty( $_GET['type'] ) ) ) {
  $post_slug = htmlentities( trim ( $_GET['slug'] ), ENT_QUOTES );
  $post_type = htmlentities( trim ( $_GET['type'] ), ENT_QUOTES );
} else {
  wp_send_json( 'Error, page slug or type is missing!' );
}

// Get transient by name.
$cache = get_transient( wrt_get_page_cache_name_by_slug( $post_slug, $post_type ) );

// Return error on false.
if ( $cache === false ) {
  wp_send_json( 'Error, the page does not exist or it is not cached correctly. Please try rebuilding cache and try again!' );
}

// Decode json for output.
wp_send_json( json_decode( $cache ) );
