<?php
/** Theme setup and assets. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'after_setup_theme', function () {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor.css' );
} );
add_action( 'wp_enqueue_scripts', function () {
    $version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'nue-studio', get_template_directory_uri() . '/assets/css/site.css', array(), $version );
    wp_enqueue_style( 'nue-editorial', get_template_directory_uri() . '/assets/css/editorial.css', array( 'nue-studio' ), $version );
    wp_enqueue_script( 'nue-studio', get_template_directory_uri() . '/assets/js/site.js', array(), $version, true );
} );
add_action( 'init', function () { register_block_pattern_category( 'nue-studio', array( 'label' => __( 'Nue Studio', 'nue-studio' ) ) ); } );

// Stable page classes allow editorial treatments without relying on database IDs.
add_filter( 'body_class', function ( $classes ) {
    if ( is_page() ) {
        $page = get_queried_object();
        if ( $page && ! empty( $page->post_name ) ) { $classes[] = 'nue-page-' . sanitize_html_class( $page->post_name ); }
    }
    return $classes;
} );

// Persian is the site's frontend language even if the WordPress language pack
// has not yet been downloaded. Admin language remains a WordPress setting.
add_filter( 'language_attributes', function ( $attributes ) {
    if ( is_admin() ) { return $attributes; }
    $attributes = preg_replace( '/\s*(?:lang|dir)=["\'][^"\']*["\']/', '', $attributes );
    return trim( $attributes . ' lang="fa-IR" dir="rtl"' );
} );

// A minimal frontend fallback when WordPress has not downloaded fa_IR yet.
// Content entered by the owner and the admin language are not altered.
add_filter( 'gettext', function ( $translated, $text, $domain ) {
    if ( is_admin() || 'default' !== $domain ) { return $translated; }
    $labels = array( 'Open menu' => 'باز کردن فهرست', 'Close menu' => 'بستن فهرست', 'Previous Page' => 'صفحه قبل', 'Next Page' => 'صفحه بعد', 'Read more' => 'ادامه مطلب', 'Search' => 'جست‌وجو' );
    return isset( $labels[ $text ] ) ? $labels[ $text ] : $translated;
}, 10, 3 );
