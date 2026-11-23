<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function nue_register_content() {
    $types = array(
        'nue_service' => array( 'خدمات', 'خدمت', 'services', 'dashicons-heart' ),
        'nue_project' => array( 'نمونه‌کارهای ناخن', 'نمونه‌کار ناخن', 'nail-art-project', 'dashicons-art' ),
        'nue_artist' => array( 'اعضای تیم', 'عضو تیم', 'artist', 'dashicons-groups' ),
        'nue_testimonial' => array( 'دیدگاه‌ها', 'دیدگاه', 'review', 'dashicons-format-quote' ),
    );
    foreach ( $types as $key => $type ) {
        register_post_type( $key, array( 'labels' => array( 'name' => $type[0], 'singular_name' => $type[1], 'add_new_item' => 'افزودن ' . $type[1], 'edit_item' => 'ویرایش ' . $type[1] ), 'public' => true, 'show_in_rest' => true, 'has_archive' => false, 'rewrite' => array( 'slug' => $type[2], 'with_front' => false ), 'menu_icon' => $type[3], 'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ) ) );
    }
    register_taxonomy( 'nue_service_category', 'nue_service', array( 'label' => 'دسته‌های خدمات', 'public' => true, 'hierarchical' => true, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'service-category' ) ) );
    register_taxonomy( 'nue_art_category', 'nue_project', array( 'label' => 'دسته‌های طراحی ناخن', 'public' => true, 'hierarchical' => true, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'art-category' ) ) );
    foreach ( nue_fields() as $type => $fields ) {
        foreach ( $fields as $key => $label ) {
            register_post_meta( $type, '_nue_' . $key, array( 'show_in_rest' => true, 'single' => true, 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field', 'auth_callback' => function () { return current_user_can( 'edit_posts' ); } ) );
        }
    }
}
add_action( 'init', 'nue_register_content' );
function nue_fields() {
    return array(
        'nue_service' => array( 'price' => 'قیمت شروع (با واحد پول)', 'duration' => 'مدت زمان (برای مثال ۴۵ دقیقه)', 'short_description' => 'توضیح کوتاه', 'benefits' => 'مزایا (هر مورد در یک خط)', 'included' => 'موارد شامل خدمت (هر مورد در یک خط)', 'preparation' => 'راهنمای پیش از مراجعه', 'aftercare' => 'راهنمای مراقبت پس از خدمت', 'appointment_url' => 'نشانی نوبت در Acuity' ),
        'nue_project' => array( 'gallery_ids' => 'شناسه تصویرهای گالری (جداشده با ویرگول)', 'artist_id' => 'شناسه عضو مرتبط تیم' ),
        'nue_artist' => array( 'position' => 'سمت', 'specialties' => 'تخصص‌ها', 'experience' => 'سابقه', 'gallery_ids' => 'شناسه تصویرهای نمونه‌کار (جداشده با ویرگول)', 'instagram' => 'نشانی اینستاگرام', 'social_url' => 'نشانی شبکه اجتماعی دیگر' ),
        'nue_testimonial' => array( 'customer_name' => 'نام مشتری یا نام منتسب', 'rating' => 'امتیاز (۱ تا ۵)', 'sample' => 'متن نمونه است؟ (yes/no)' ),
    );
}
add_action( 'add_meta_boxes', function () { foreach ( array_keys( nue_fields() ) as $type ) { add_meta_box( 'nue_details', 'جزئیات نیو استودیو', 'nue_meta_box', $type, 'normal', 'high' ); } } );
function nue_meta_box( $post ) {
    wp_nonce_field( 'nue_save_details', 'nue_details_nonce' );
    echo '<p>توضیحات کامل یا معرفی را در ویرایشگر اصلی و تصویر اصلی را در بخش تصویر شاخص وارد کنید. شناسه تصویرها در کتابخانه رسانه قابل مشاهده است.</p>';
    foreach ( nue_fields()[ $post->post_type ] as $key => $label ) {
        $value = get_post_meta( $post->ID, '_nue_' . $key, true );
        echo '<p><label for="nue-' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
        if ( in_array( $key, array( 'benefits', 'included', 'preparation', 'aftercare', 'short_description' ), true ) ) { echo '<textarea class="widefat" rows="3" id="nue-' . esc_attr( $key ) . '" name="nue_meta[' . esc_attr( $key ) . ']">' . esc_textarea( $value ) . '</textarea>'; }
        else { echo '<input class="widefat" id="nue-' . esc_attr( $key ) . '" name="nue_meta[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '">'; }
        echo '</p>';
    }
}
add_action( 'save_post', function ( $id, $post ) {
    if ( ! isset( $_POST['nue_details_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nue_details_nonce'] ) ), 'nue_save_details' ) || ! current_user_can( 'edit_post', $id ) || wp_is_post_revision( $id ) || ! isset( nue_fields()[ $post->post_type ] ) ) { return; }
    $values = isset( $_POST['nue_meta'] ) && is_array( $_POST['nue_meta'] ) ? wp_unslash( $_POST['nue_meta'] ) : array();
    foreach ( nue_fields()[ $post->post_type ] as $key => $label ) {
        $value = isset( $values[ $key ] ) ? sanitize_textarea_field( $values[ $key ] ) : '';
        if ( in_array( $key, array( 'appointment_url', 'instagram', 'social_url' ), true ) ) { $value = esc_url_raw( $value, array( 'https' ) ); }
        if ( 'appointment_url' === $key && $value && ! nue_is_acuity_url( $value ) ) { $value = ''; }
        update_post_meta( $id, '_nue_' . $key, $value );
    }
}, 10, 2 );
function nue_meta( $id, $key, $fallback = '' ) { $v = get_post_meta( $id, '_nue_' . $key, true ); return '' !== $v ? $v : $fallback; }
