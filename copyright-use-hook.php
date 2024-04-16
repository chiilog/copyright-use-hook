<?php
/**
 * Plugin Name:       Copyright Use Hook
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            mel_cha
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chiilog-copyright-use-hook
 *
 * @package ChiilogCopyrightUseHook
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function chiilog_copyright_use_hook_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'chiilog_copyright_use_hook_block_init' );

function add_copyright_block_after_post_title_block( $hooked_block_types, $relative_position, $anchor_block_type, $context ) {

	// Only hook the block on Single templates (posts).
	if ( ! $context instanceof WP_Block_Template || ! property_exists( $context, 'slug' ) || 'single' !== $context->slug ) {
		return $hooked_block_types;
	}

	// Hook the block after the Post Content block.
	if ( 'after' === $relative_position && 'core/post-title' === $anchor_block_type ) {
		$hooked_block_types[] = 'chiilog-blocks/copyright-use-hook';
	}

	return $hooked_block_types;
}
add_filter( 'hooked_block_types', 'add_copyright_block_after_post_title_block', 10, 4 );

function remove_hooked_like_button_block_after_post_content( $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context  ) {

	// Has the hooked block been suppressed by a previous filter?
	if ( is_null( $parsed_hooked_block ) ) {
		return $parsed_hooked_block;
	}

	// Remove any Like Button blocks hooked after Post Content.
	if ( 'core/post-content' === $parsed_anchor_block['blockName'] ) {
		return null;
	}

	return $parsed_hooked_block;
}
// Priority is set to 15.
add_filter( 'hooked_block_chiilog-blocks/copyright-use-hook', 'remove_hooked_like_button_block_after_post_content', 15, 5 );
