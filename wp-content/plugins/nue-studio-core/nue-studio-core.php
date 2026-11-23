<?php
/**
 * Plugin Name: Nue Studio Core
 * Description: Studio content types, editable details, booking and site components for Nue Studio.
 * Version: 1.1.0
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Text Domain: nue-studio-core
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'NUE_CORE_DIR', plugin_dir_path( __FILE__ ) );
require_once NUE_CORE_DIR . 'content.php';
require_once NUE_CORE_DIR . 'components.php';
require_once NUE_CORE_DIR . 'setup.php';
register_activation_hook( __FILE__, 'nue_activate' );
add_action( 'admin_menu', function () { add_options_page( 'Nue Studio Settings', 'Nue Studio', 'manage_options', 'nue-studio', 'nue_settings_page' ); } );
add_action( 'admin_init', function () {
    register_setting( 'nue_settings', 'nue_settings', array( 'sanitize_callback' => 'nue_sanitize_settings' ) );
} );
function nue_sanitize_settings( $input ) {
    $out = array();
    foreach ( array( 'address', 'phone', 'email', 'hours', 'instagram', 'facebook', 'acuity_url', 'gift_url', 'map_url' ) as $key ) {
        $value = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';
        $out[ $key ] = in_array( $key, array( 'instagram', 'facebook', 'acuity_url', 'gift_url', 'map_url' ), true ) ? esc_url_raw( $value, array( 'https' ) ) : ( 'email' === $key ? sanitize_email( $value ) : sanitize_textarea_field( $value ) );
    }
    if ( ! empty( $out['acuity_url'] ) && ! nue_is_acuity_url( $out['acuity_url'] ) ) {
        add_settings_error( 'nue_settings', 'acuity', 'Please use an HTTPS Acuity Scheduling or as.me URL.' );
        $out['acuity_url'] = '';
    }
    return $out;
}
function nue_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $settings = get_option( 'nue_settings', array() );
    $fields = array( 'acuity_url' => 'Acuity scheduler URL (https://…acuityscheduling.com or https://…as.me)', 'address' => 'Studio address', 'phone' => 'Phone', 'email' => 'Contact email', 'hours' => 'Opening hours', 'instagram' => 'Instagram URL', 'facebook' => 'Facebook URL', 'gift_url' => 'Gift card purchase URL', 'map_url' => 'Map link URL' );
    echo '<div class="wrap"><h1>Nue Studio settings</h1><p>Enter verified business information. Empty values are shown as configuration placeholders. Do not enter API credentials.</p><form method="post" action="options.php">';
    settings_fields( 'nue_settings' ); echo '<table class="form-table">';
    foreach ( $fields as $key => $label ) { echo '<tr><th scope="row"><label for="nue-' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td><input class="regular-text" id="nue-' . esc_attr( $key ) . '" name="nue_settings[' . esc_attr( $key ) . ']" value="' . esc_attr( $settings[ $key ] ?? '' ) . '"></td></tr>'; }
    echo '</table>'; submit_button(); echo '</form></div>';
}
function nue_is_acuity_url( $url ) {
    $parts = wp_parse_url( $url );
    if ( empty( $parts['scheme'] ) || 'https' !== strtolower( $parts['scheme'] ) || empty( $parts['host'] ) ) { return false; }
    $host = strtolower( $parts['host'] );
    return (bool) preg_match( '/(^|\.)(acuityscheduling\.com|as\.me)$/', $host );
}
function nue_setting( $key ) { $s = get_option( 'nue_settings', array() ); return isset( $s[ $key ] ) ? $s[ $key ] : ''; }
