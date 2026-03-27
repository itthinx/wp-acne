<?php
/**
 * Plugin Name: WP ACNE - Admin Cookie Nonce Expose
 * Plugin URI: https://github.com/itthinx/xmlrpc-disable-extreme
 * Description: WordPress Admin Cookie Nonce Expose. Debug tool. Developers Only. DO NOT use on Production Sites!
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: itthinx
 * Author URI: https://www.itthinx.com
 * Donate-Link: https://www.itthinx.com/shop/
 * License: GPLv3
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class.
 */
class WP_ACNE {

	/**
	 * Register the init action.
	 */
	public static function boot() {
		add_action( 'init', array( __CLASS__, 'init' ) );
	}

	/**
	 * Register admin_head action and prepare admin notice.
	 */
	public static function init() {

		if ( wp_doing_ajax() ) {
			return;
		}

		add_action( 'admin_head', array( __CLASS__, 'admin_head' ) );

		$cookies = array(
			'USER_COOKIE' => USER_COOKIE,
			'PASS_COOKIE' => PASS_COOKIE,
			'AUTH_COOKIE' => AUTH_COOKIE,
			'SECURE_AUTH_COOKIE' => SECURE_AUTH_COOKIE,
			'LOGGED_IN_COOKIE' => LOGGED_IN_COOKIE,
			'TEST_COOKIE' => TEST_COOKIE,
			'RECOVERY_MODE_COOKIE' => RECOVERY_MODE_COOKIE
		);

		$entries = array();
		foreach ( $cookies as $name => $id ) {
			$value = $_COOKIE[$id] ?? null;
			if ( $value !== null ) {
				$entries[] =
					sprintf(
						'<tr><td>%s</td><td>%s</td><td>%s</td></tr>',
						esc_html( $name ),
						esc_html( $id ),
						esc_html( $value )
					);
			}
		}

		wp_admin_notice(
			'<div class="wp-acne">' .
			'<h2>WP ACNE | Admin Cookie Nonce Expose</h2>' .
			'<h3>Nonce</h3>' .
			'<p>Default -1 action ' . esc_html( wp_create_nonce() ) . '</p>' .
			'<h3>Cookies</h3>' .
			'<table>' .
			'<thead>' .
			'<tr>' .
			'<th>Defined</th>' .
			'<th>Constant</th>' .
			'<th>Cookie</th>' .
			'</tr>' .
			'</thead>' .
			'<tbody>' .
			implode( '', $entries ) .
			'</tbody>' .
			'</table>' .
			'</div>'
		);

	}

	/**
	 * Render styles.
	 */
	public static function admin_head() {
		echo '<style type="text/css">' .
			'.wp-acne { font-family: monospace; }' .
			'.wp-acne tr { margin: 4px; }' .
			'.wp-acne td { padding: 4px; }' .
			'.wp-acne table thead tr th { border-bottom: 2px solid #eee; }' .
			'.wp-acne table tbody tr td { border-right: 1px solid #ccc; border-radius: 0 0 4px 0; }' .
			'.wp-acne table tbody tr td { border-bottom: 1px solid #ccc; border-radius: 0 0 4px 0; }' .
			'</style>';
	}
}

WP_ACNE::boot();
