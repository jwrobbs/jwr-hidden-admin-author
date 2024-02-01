<?php
/**
 * Plugin Name: Hidden Admin Author
 * Main file for Hidden Admin Author plugin.
 *
 * @author Josh Robbs <josh@joshrobbs.com>
 * @since 2024-01-29
 * @package Hidden_Admin_Author
 */

namespace HiddenAdminAuthor;

use JWR\JWR_Control_Panel\PHP\JWR_Plugin_Options;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'jwr-control-panel/index.php';

/**
 * Add settings to the JWR Control Panel.
 *
 * Settings: Author ID to substitute for admin author.
 *
 * @return void
 */
function add_hidden_admin_author_settings() {
	$options = new JWR_Plugin_Options( 'Hidden Admin Author', 'haa', '1.0.1' );
	$options->add_number_field( 'Substitute Author ID', 'substitute_author_id', '1', '', '1', '1', 33 );
	$options->publish();
}
add_action( 'update_jwr_control_panel', __NAMESPACE__ . '\add_hidden_admin_author_settings' );

/**
 * Save post function to remove admin author.
 *
 * @param array $data    Slashed, sanitized, and processed post data.
 * @param array $postarr Sanitized (and slashed) but otherwise unmodified post data.
 * @return void
 */
function replace_admin_author( $data, $postarr ) {
	// If this is just a revision, don't do anything.
	if ( wp_is_post_revision( $postarr['ID'] ) ) {
		return;
	}

	// Get the author ID.
	$author_id = $data['post_author'];

	// Check if is admin.
	$user  = new \WP_User( $author_id );
	$roles = $user->roles;
	if ( ! is_array( $roles ) || ! in_array( 'administrator', $roles, true ) ) {
		return $data;
	}

	// Get the substitute author ID.
	$substitute_author_id = get_field( 'substitute_author_id', 'option' );

	if ( ! $substitute_author_id ) {
		return $data;
	}

	// Update the post author.
	$data['post_author'] = $substitute_author_id;
	return $data;
}
\add_filter( 'wp_insert_post_data', __NAMESPACE__ . '\replace_admin_author', 10, 2 );

/**
 * Disable Users portion of the REST API.
 *
 * Based on https://wordpress.stackexchange.com/a/254251
 *
 * @param array $endpoints The REST API endpoints.
 *
 * @return array
 */
function disable_users_rest_api( $endpoints ) {
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}

	return $endpoints;
}

add_filter( 'rest_endpoints', __NAMESPACE__ . '\disable_users_rest_api' );
