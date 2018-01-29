<?php
/**
 * Theme Name: Wp Rest Transient
 * Description: Prof of concept for caching RestApi calls
 * Author: Ivan Ruzevic
 * Author URI: https://mustra-designs.com/
 * Version: 1.0
 * Text Domain: test
 *
 * @package test
 */


  if ( ! function_exists( 'test_get_page_cache_name_by_slug' ) ) {
    /**
     * Get Page cache name for transient by post slug and type.
     *
     * @param string $post_slug Page Slug to save.
     * @param string $post_type Page Type to save.
     * @return string
     *
     * @since  1.0.0
     */
    function test_get_page_cache_name_by_slug( $post_slug = null, $post_type = null ) {
      if( ! $post_slug || ! $post_type ) {
        return false;
      }

      $post_slug = str_replace( '__trashed', '', $post_slug );

      return 'test_data_' . $post_type . '_' . $post_slug;
    }
  }

  if ( ! function_exists( 'test_get_page_data_by_slug' ) ) {
    /**
     * Get full post data by post slug and type.
     *
     * @param string $post_slug Page Slug to do Query by.
     * @param string $post_type Page Type to do Query by.
     * @return array
     *
     * @since  1.0.0
     */
    function test_get_page_data_by_slug( $post_slug = null, $post_type = null ) {
      if( ! $post_slug || ! $post_type ) {
        return false;
      }
      
      $page_output = '';
      $args = array(
        'name' => $post_slug,
        'post_type' => $post_type,
        'posts_per_page' => 1,
        'no_found_rows' => true
      );

      $the_query = new WP_Query( $args );

      if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
          $the_query->the_post();

          $page_output = $the_query->post;
        }
        wp_reset_postdata();
      }

      return $page_output;
    }
  }

  if ( ! function_exists( 'test_get_json_page' ) ) {
    /**
     * Return Page in JSON format
     *
     * @param string $post_slug Page Slug.
     * @param string $post_type Page Type.
     * @return json
     *
     * @since  1.0.0
     */
    function test_get_json_page( $post_slug = null, $post_type = null ) {
      if( ! $post_slug || ! $post_type ) {
        return false;
      }

      return json_encode( test_get_page_data_by_slug( $post_slug, $post_type ) );
    }
  }

  if ( ! function_exists( 'test_get_allowed_post_types' ) ) {
    /**
     * Get the array of allowed types to do operations on.
     *
     * @return array
     *
     * @since 1.0.0
     */
    function test_get_allowed_post_types() {
      return array( 'post', 'page' );
    }
  }

  if ( ! function_exists( 'test_is_post_type_allowed_to_save' ) ) {
    /**
     * Check if post type is allowed to be save in transient.
     *
     * @param string $post_type Get post type.
     * @return boolean
     *
     * @since 1.0.0
     */
    function test_is_post_type_allowed_to_save( $post_type = null ) {
      if( ! $post_type ) {
        return false;
      }

      $allowed_types = test_get_allowed_post_types();

      if ( in_array( $post_type, $allowed_types, true ) ) {
        return true;
      }

      return false;
    }
  }

  add_action( 'save_post', 'test_update_page_transient' );

  if ( ! function_exists( 'test_update_page_transient' ) ) {
    /**
     * Update Page to transient for caching on action hooks save_post.
     *
     * @param int $post_id Saved Post ID provided by action hook.
     *
     * @since 1.0.0
     */
    function test_update_page_transient( $post_id ) {
      $post_status = get_post_status( $post_id );
      $post = get_post( $post_id );
      $post_slug = $post->post_name;
      $post_type = $post->post_type;

      $cache_name = test_get_page_cache_name_by_slug( $post_slug, $post_type );
      if( ! $cache_name ) {
        return false;
      }

      if( $post_status === 'auto-draft' || $post_status === 'inherit' ) {
        return false;
      } else if( $post_status === 'trash' ) {
        delete_transient( $cache_name );
      } else  {
        if( test_is_post_type_allowed_to_save( $post_type ) ) {
          $cache = test_get_json_page( $post_slug, $post_type );
          set_transient( $cache_name, $cache, 0 );
        }
      }
    }
  }