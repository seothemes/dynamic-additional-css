<?php
/**
 * Plugin Name: Dynamic Additional CSS
 * Plugin URI:  https://github.com/seothemes/dynamic-additional-css
 * Description: Writes Customizer Additional CSS to dynamic stylesheet.
 * Version:     1.0.0
 * Author:      Lee Anthony
 * Author URI:  https://seothemes.com/
 * Copyright:   SEO Themes 2019
 * Text Domain: dynamic-additional-css
 * Domain Path: /languages
 * License:     GPL-3.0-or-later
 */

namespace SeoThemes\DynamicAdditionalCss;

// Bail if no Additional CSS.
if ( ! wp_get_custom_css() ) {
	return;
}

add_action('after_setup_theme', __NAMESPACE__ . '\remove_default_css');
/**
 * Remove default inline styles.
 *
 * Hooked to `after_setup_theme` to make Customizer check work.
 *
 * @since 1.0.0
 *
 * @return void
 */
function remove_default_css() {
	if ( ! is_customize_preview() ) {
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
	}
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_dynamic_css' );
/**
 * Enqueues dynamic CSS on frontend or adds inline styles if in Customizer.
 *
 * @since  1.0.0
 *
 * @return void
 */
function enqueue_dynamic_css() {
	$handle = 'dynamic-additional';

	if ( ! is_customize_preview() ) {
		wp_enqueue_style(
			$handle,
			\admin_url( 'admin-ajax.php' ) . '?action=load_css_ajax&wpnonce=' . wp_create_nonce( 'dynamic-css-nonce' ),
			[],
			'1.0.0',
			'all'
		);
	}
}

add_action( 'wp_ajax_load_css_ajax', __NAMESPACE__ . '\load_css_ajax' );
add_action( 'wp_ajax_nopriv_load_css_ajax', __NAMESPACE__ . '\load_css_ajax' );
/**
 * Load the dynamic CSS with ajax (if nonce is ok).
 *
 * @since 1.0.0
 *
 * @return void
 */
function load_css_ajax() {
	$nonce = $_REQUEST['wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'dynamic-css-nonce' ) ) {
		die( 1 );

	} else {
		header( 'Content-type: text/css; charset: UTF-8' );
		echo generate_css();
	}

	exit;
}

/**
 * Generates the CSS output.
 *
 * Includes quick and dirty way to mostly minify CSS with PHP.
 *
 * @since  1.0.0
 * @author Gary Jones
 *
 * @param string $css CSS to minify.
 *
 * @return string Minified CSS
 */
function generate_css() {

	// Get additional CSS.
	$css = strip_tags( wp_get_custom_css() );

	// Minify a bit.
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
	$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
	$css = preg_replace( '/;(?=\s*})/', '', $css );
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
	$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	$css = preg_replace( '/0 0 0 0/', '0', $css );
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

	return trim( $css );
}
