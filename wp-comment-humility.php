<?php

/**
 * Plugin Name: WP Comment Humility
 * Plugin URI:  https://wordpress.org/plugins/wp-comment-humility/
 * Description: Move the "Comments" menu underneath "Posts"
 * Author:      John James Jacoby
 * Version:     0.1.0
 * Author URI:  https://profiles.wordpress.org/johnjamesjacoby/
 * License:     GPL v2 or later
 * Text Domain: wp-comment-humility
 * Domain Path: /languages
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Actions
add_action( 'admin_menu',             '_wp_comment_humility' );
add_action( 'admin_head-comment.php', '_wp_comment_humility_modify_admin_menu_highlight' );
add_action( 'plugins_loaded', '_wp_comment_humility_i18n' ) );

/**
 * Unset top level "Comments" menu and related submenus, then recreate it under
 * the "Posts" top level menu item.
 *
 * @since 0.1.0
 */
function _wp_comment_humility() {

	// Look for
	$comments_menu = _wp_comment_humility_get_menu_index_by_slug( 'edit-comments.php' );

	// No comments
	if ( false !== $comments_menu ) {

		// Unset top level menu
		unset( $GLOBALS['menu'][ $comments_menu ], $GLOBALS['submenu'][ 'edit-comments.php' ] );

		// Move comments to underneath "Posts"
		$awaiting_mod = wp_count_comments();
		$awaiting_mod = $awaiting_mod->moderated;
		$GLOBALS['submenu']['edit.php'][9] = array( sprintf( __( 'Comments %s', 'wp-comment-humility' ), "<span class='awaiting-mod count-{$awaiting_mod}'><span class='pending-count'>" . number_format_i18n( $awaiting_mod ) . '</span></span>' ), 'edit_posts', 'edit-comments.php' );
	}
}

/**
 * Get a global menu index
 *
 * @since 0.1.0
 *
 * @param type $location
 *
 * @return mixed
 */
function _wp_comment_humility_get_menu_index_by_slug( $location = '' ) {
	foreach ( $GLOBALS['menu'] as $index => $menu_item ) {
		if ( $location === $menu_item[2] ) {
			return $index;
		}
	}
	return false;
}

/**
 * This tells WP to highlight the Posts > Comments menu when editing a comment.
 *
 * @since 0.1.0
 */
function _wp_comment_humility_modify_admin_menu_highlight() {
	$GLOBALS['plugin_page']  = 'edit.php';
}

/**
 * Loads the translation file.
 *
 * @since 0.1.0
 */
function _wp_comment_humility_i18n() {

	/* Load the translation of the plugin. */
	load_plugin_textdomain( 'wp-comment-humility', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
