<?php
/** Diagnostics and explicitly requested setup for local WP-CLI users. */
if ( ! defined( 'ABSPATH' ) || ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit; }
class Nue_CLI_Command {
    /**
     * Report the active theme, front page and Site Editor overrides.
     *
     * ## EXAMPLES
     *     wp nue status
     */
    public function status( $args, $assoc_args ) {
        $theme = wp_get_theme();
        $home_id = (int) get_option( 'page_on_front' );
        $home = $home_id ? get_post( $home_id ) : null;
        WP_CLI::line( 'Site URL: ' . home_url( '/' ) );
        WP_CLI::line( 'Active theme: ' . get_stylesheet() . ' (' . $theme->get( 'Version' ) . ')' );
        WP_CLI::line( 'Nue Studio Core: active' );
        WP_CLI::line( 'Site language: ' . ( get_option( 'WPLANG' ) ?: 'en_US' ) );
        WP_CLI::line( 'Front page setting: ' . get_option( 'show_on_front' ) . ( $home ? ' — ' . $home->post_title . ' (ID ' . $home_id . ', slug ' . $home->post_name . ')' : '' ) );
        WP_CLI::line( 'Nue starter home page: ' . ( ( $starter = get_page_by_path( 'home' ) ) ? 'ID ' . $starter->ID : 'not found' ) );
        if ( get_template() !== 'nue-studio' ) { WP_CLI::warning( 'Nue Studio is not the active theme; the design cannot appear until it is activated.' ); }
        if ( ! $home || ! $starter || $home->ID !== $starter->ID || 'page' !== get_option( 'show_on_front' ) ) { WP_CLI::warning( 'The Nue starter page is not selected as the front page. Review Settings > Reading.' ); }
        if ( ! is_readable( get_theme_root() . '/nue-studio/assets/css/editorial.css' ) ) { WP_CLI::warning( 'The new stylesheet is missing from the mounted theme directory.' ); }
        $overrides = get_posts( array( 'post_type' => array( 'wp_template', 'wp_template_part' ), 'post_status' => 'publish', 'posts_per_page' => -1, 'tax_query' => array( array( 'taxonomy' => 'wp_theme', 'field' => 'name', 'terms' => 'nue-studio' ) ) ) );
        if ( $overrides ) {
            WP_CLI::warning( 'Saved Site Editor templates or parts can override updated theme files. Review them before resetting anything:' );
            foreach ( $overrides as $post ) { WP_CLI::line( '  ' . $post->post_type . ': ' . $post->post_name ); }
        } else { WP_CLI::line( 'Saved Nue templates/parts: none detected' ); }
        $styles = get_page_by_path( 'wp-global-styles-nue-studio', OBJECT, 'wp_global_styles' );
        if ( $styles ) { WP_CLI::warning( 'Saved global styles can override theme.json; review Appearance > Editor > Styles.' ); }
        WP_CLI::line( 'No content or settings were changed.' );
    }
    /**
     * Activate Nue Studio and translate recognizable, unedited starter content.
     *
     * ## OPTIONS
     * [--set-reading-pages]
     * : Explicitly set the Nue Home and Journal pages as WordPress reading pages.
     *
     * ## EXAMPLES
     *     wp nue apply
     *     wp nue apply --set-reading-pages
     */
    public function apply( $args, $assoc_args ) {
        if ( ! wp_get_theme( 'nue-studio' )->exists() ) { WP_CLI::error( 'Nue Studio theme is not installed in this WordPress installation.' ); }
        if ( isset( $assoc_args['set-reading-pages'] ) ) {
            $home = get_page_by_path( 'home' ); $blog = get_page_by_path( 'blog' );
            if ( ! $home || ! $blog || 'publish' !== $home->post_status || 'publish' !== $blog->post_status || $home->ID === $blog->ID ) { WP_CLI::error( 'Published Home and Journal starter pages are required before changing Reading settings.' ); }
        }
        $result = nue_apply_persian_setup();
        if ( isset( $assoc_args['set-reading-pages'] ) ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $home->ID );
            update_option( 'page_for_posts', $blog->ID );
            WP_CLI::line( 'Selected Nue Home and Journal as the reading pages.' );
        }
        if ( empty( $result['locale_set'] ) ) { WP_CLI::warning( 'WordPress could not set fa_IR because the language pack is not installed. Run: wp language core install fa_IR --activate' ); }
        WP_CLI::success( sprintf( 'Theme and Persian starter setup applied. %d starter pages updated; %d modified pages left unchanged.', $result['updated'], $result['skipped'] ) );
        WP_CLI::line( 'Your edited content, media, prices and Site Editor customizations were not reset.' );
    }
}
WP_CLI::add_command( 'nue', 'Nue_CLI_Command' );
