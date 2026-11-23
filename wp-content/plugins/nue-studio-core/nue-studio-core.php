<?php
/**
 * Plugin Name: Nue Studio Core
 * Description: Studio content types, editable details, booking and site components for Nue Studio.
 * Version: 1.3.0
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Text Domain: nue-studio-core
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'NUE_CORE_DIR', plugin_dir_path( __FILE__ ) );
require_once NUE_CORE_DIR . 'content.php';
require_once NUE_CORE_DIR . 'components.php';
require_once NUE_CORE_DIR . 'setup.php';
require_once NUE_CORE_DIR . 'persian.php';
if ( defined( 'WP_CLI' ) && WP_CLI ) { require_once NUE_CORE_DIR . 'cli.php'; }
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
        add_settings_error( 'nue_settings', 'acuity', 'لطفاً یک نشانی HTTPS متعلق به Acuity یا as.me وارد کنید.' );
        $out['acuity_url'] = '';
    }
    return $out;
}
function nue_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $settings = get_option( 'nue_settings', array() );
    $fields = array( 'acuity_url' => 'نشانی سامانه رزرو Acuity (https://…acuityscheduling.com یا https://…as.me)', 'address' => 'نشانی استودیو', 'phone' => 'تلفن', 'email' => 'ایمیل تماس', 'hours' => 'ساعات کاری', 'instagram' => 'نشانی اینستاگرام', 'facebook' => 'نشانی فیسبوک', 'gift_url' => 'نشانی خرید کارت هدیه', 'map_url' => 'نشانی نقشه' );
    echo '<div class="wrap"><h1>تنظیمات نیو استودیو</h1>';
    $result = get_transient( 'nue_persian_setup_result_' . get_current_user_id() );
    if ( is_array( $result ) ) {
        delete_transient( 'nue_persian_setup_result_' . get_current_user_id() );
        echo '<div class="notice notice-success"><p>' . esc_html( sprintf( '%d صفحه اولیه فارسی شد؛ %d صفحه ویرایش‌شده بدون تغییر باقی ماند.', $result['updated'], $result['skipped'] ) ) . '</p></div>';
        if ( empty( $result['locale_set'] ) ) { echo '<div class="notice notice-warning"><p>بسته زبان فارسی وردپرس نصب نیست. برای ترجمه بخش‌های پیش‌فرض وردپرس، از تنظیمات عمومی زبان فارسی را نصب و انتخاب کنید.</p></div>'; }
    }
    if ( current_user_can( 'switch_themes' ) && wp_get_theme( 'nue-studio' )->exists() ) {
        $setup_url = wp_nonce_url( admin_url( 'admin-post.php?action=nue_apply_persian' ), 'nue_apply_persian' );
        echo '<p><a class="button" href="' . esc_url( $setup_url ) . '">فعال‌سازی قالب و تنظیم محتوای فارسی</a> فقط محتوای اولیه ویرایش‌نشده جایگزین می‌شود.</p>';
    }
    echo '<p>اطلاعات تأییدشده استودیو را وارد کنید. برای سامانه رزرو نیازی به کلید API نیست.</p><form method="post" action="options.php">';
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
