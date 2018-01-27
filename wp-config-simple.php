<?php
/**
 * Create simple wp configuration for Rest API routes
 *
 * @since 1.0.0
 * @package eightshift
 */

define( 'SHORTINIT', true );
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );