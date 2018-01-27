<?php
/**
 * Generate rest doute
 * 
 * Route location: /wp-content/themes/test/test.php?slug=sample-page&type=page
 *
 * @since   1.0.0
 * @package eightshift
 */

require_once( '../../../wp-config-simple.php' );
require_once('functions.php');

// Check input and protect it.
if ( ( isset( $_GET['slug'] ) || ! empty( $_GET['slug'] ) ) && ( isset( $_GET['type'] ) || ! empty( $_GET['type'] ) ) ) {
  $post_slug = htmlentities( trim ( $_GET['slug'] ), ENT_QUOTES );
  $post_type = htmlentities( trim ( $_GET['type'] ), ENT_QUOTES );
} else {
  wp_send_json( 'Error, page slug or type is missing!' );
}

$cache = get_transient( test_get_page_cache_name_by_slug( $post_slug, $post_type ) );

if ( $cache === false ) {
  wp_send_json( 'Error, the page does not exist or it is not cached correctly. Please try rebuilding cache and try again!' );
}

wp_send_json( json_decode( $cache ) );
