<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'init', function () {
    wp_register_script( 'nue-block-editor', plugins_url( 'editor.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-components' ), '1.4.0', true );
    $blocks = array( 'services', 'pricing', 'gallery', 'team', 'testimonials', 'booking', 'contact-info', 'contact-form', 'detail', 'share', 'related-posts', 'featured-post', 'gift-link', 'service-menu', 'pricing-preview', 'review-carousel' );
    foreach ( $blocks as $block ) {
        register_block_type( 'nue/' . $block, array( 'api_version' => 2, 'editor_script' => 'nue-block-editor', 'render_callback' => 'nue_render_' . str_replace( '-', '_', $block ), 'attributes' => array( 'limit' => array( 'type' => 'number', 'default' => -1 ) ) ) );
    }
} );
function nue_query( $type, $limit = -1, $exclude = 0 ) { return get_posts( array( 'post_type' => $type, 'posts_per_page' => $limit, 'post_status' => 'publish', 'orderby' => 'menu_order title', 'order' => 'ASC', 'exclude' => $exclude ) ); }
function nue_photo( $id, $size = 'large' ) {
    if ( has_post_thumbnail( $id ) ) { return get_the_post_thumbnail( $id, $size, array( 'loading' => 'lazy' ) ); }
    if ( 'nue_artist' === get_post_type( $id ) ) { return '<div class="portrait-placeholder" aria-label="تصویر هنوز ثبت نشده است"><span aria-hidden="true">✳</span></div>'; }
    $white = absint( $id ) % 2 === 1;
    return '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/' . ( $white ? 'hero.jpg' : 'studio.jpg' ) ) . '" alt="' . esc_attr( $white ? 'ناخن‌های بادامی با لاک سفید شیری' : 'مانیکور صورتی با درخشش ملایم' ) . '" loading="lazy">';
}
function nue_button( $url, $text ) { return '<a class="nue-button" href="' . esc_url( $url ) . '">' . esc_html( $text ) . ' <span aria-hidden="true">↗</span></a>'; }
function nue_service_booking( $id ) { $url = nue_meta( $id, 'appointment_url' ); return $url && nue_is_acuity_url( $url ) ? $url : home_url( '/booking/' ); }
function nue_render_services( $attributes ) {
    $posts = nue_query( 'nue_service', $attributes['limit'] ?? -1 ); if ( ! $posts ) { return '<p>برای نمایش خدمات، آن‌ها را در پیشخوان وردپرس اضافه کنید.</p>'; }
    $html = '<div class="service-grid">';
    foreach ( $posts as $p ) { $starter_image = get_post_meta( get_post_thumbnail_id( $p->ID ), '_nue_supplied_photo', true ); $html .= '<article class="service-card' . ( $starter_image ? ' has-starter-image' : '' ) . '"><a class="service-image" href="' . esc_url( get_permalink( $p ) ) . '">' . nue_photo( $p->ID, 'medium_large' ) . '</a><div class="service-card-head"><h3><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a></h3><span>↗</span></div><p>' . esc_html( nue_meta( $p->ID, 'short_description', 'مراقبتی ویژه، متناسب با سلیقه شما.' ) ) . '</p><div class="service-meta"><span>' . esc_html( nue_meta( $p->ID, 'duration', 'مدت زمان با استودیو هماهنگ می‌شود' ) ) . '</span><span>' . esc_html( nue_meta( $p->ID, 'price', 'قیمت پس از هماهنگی' ) ) . '</span></div><a class="text-link" href="' . esc_url( nue_service_booking( $p->ID ) ) . '">رزرو نوبت ↗</a></article>'; }
    return $html . '</div>';
}
function nue_render_pricing() {
    $terms = get_terms( array( 'taxonomy' => 'nue_service_category', 'hide_empty' => false ) ); $html = '';
    if ( ! is_wp_error( $terms ) ) { foreach ( $terms as $term ) { $posts = get_posts( array( 'post_type' => 'nue_service', 'posts_per_page' => -1, 'post_status' => 'publish', 'tax_query' => array( array( 'taxonomy' => 'nue_service_category', 'field' => 'term_id', 'terms' => $term->term_id ) ) ) ); if ( ! $posts ) { continue; } $html .= '<section class="price-group"><h2>' . esc_html( $term->name ) . '</h2>'; foreach ( $posts as $p ) { $html .= '<div class="price-row"><div><a href="' . esc_url( get_permalink( $p ) ) . '"><strong>' . esc_html( get_the_title( $p ) ) . '</strong></a><p>' . esc_html( nue_meta( $p->ID, 'duration', 'مدت زمان با استودیو هماهنگ می‌شود' ) ) . '</p></div><span>' . esc_html( nue_meta( $p->ID, 'price', 'قیمت پس از هماهنگی' ) ) . '</span></div>'; } $html .= '</section>'; } }
    return $html ?: '<p>برای ساخت منوی قیمت، خدمات را به دسته‌های مربوط اختصاص دهید.</p>';
}
function nue_render_gallery( $attributes ) {
    $posts = nue_query( 'nue_project', $attributes['limit'] ?? -1 ); $terms = get_terms( array( 'taxonomy' => 'nue_art_category', 'hide_empty' => false ) );
    $concepts = is_front_page() && (int) ( $attributes['limit'] ?? -1 ) === 12 && nue_concept_images_enabled() ? max( 0, min( 10, 12 - count( $posts ) ) ) : 0;
    $html = ( $concepts ? '<p class="concept-disclaimer">تصاویر مفهومیِ برچسب‌خورده با هوش مصنوعی ساخته شده‌اند و نمونه‌کار واقعی استودیو نیستند.</p>' : '' ) . '<div class="gallery-filters" aria-label="فیلتر طرح‌های ناخن"><button class="is-active" type="button" data-filter="all" aria-pressed="true">همه</button>';
    if ( ! is_wp_error( $terms ) ) { foreach ( $terms as $term ) { $html .= '<button type="button" data-filter="' . esc_attr( $term->slug ) . '" aria-pressed="false">' . esc_html( $term->name ) . '</button>'; } } $html .= '</div><div class="gallery-grid">';
    foreach ( $posts as $p ) { $categories = wp_get_post_terms( $p->ID, 'nue_art_category', array( 'fields' => 'slugs' ) ); $src = get_the_post_thumbnail_url( $p->ID, 'full' ); $html .= '<article class="gallery-card" data-categories="' . esc_attr( is_wp_error( $categories ) ? '' : implode( ' ', $categories ) ) . '">'; if ( $src ) { $html .= '<button class="lightbox-trigger" type="button" data-image="' . esc_url( $src ) . '" data-alt="' . esc_attr( get_post_meta( get_post_thumbnail_id( $p->ID ), '_wp_attachment_image_alt', true ) ) . '" aria-label="نمایش بزرگ‌تر ' . esc_attr( get_the_title( $p ) ) . '">' . nue_photo( $p->ID ) . '</button>'; } else { $html .= nue_photo( $p->ID ); } $html .= '<a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . ' ↗</a></article>'; }
    $illustrations = array(
        array( 'مانیکور طبیعی', 'minimal classic' ), array( 'درخشش رز', 'chrome' ), array( 'خطوط آزاد', 'abstract' ), array( 'رنگ آلویی', 'classic' ), array( 'فرنچ ظریف', 'french minimal' ), array( 'طرح گیاهی', 'abstract' ), array( 'قرمز کلاسیک', 'classic' ), array( 'ابر و آبی', 'abstract' ), array( 'ابزار طراحی', 'custom' ), array( 'طرح لاک‌پشتی', 'abstract' ),
    );
    for ( $i = 0; $i < $concepts; $i++ ) {
        $src = get_template_directory_uri() . '/assets/images/concept-' . str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) . '.jpg';
        $title = $illustrations[$i][0]; $alt = 'تصویر مفهومی ساخته‌شده با هوش مصنوعی: ' . $title;
        $html .= '<article class="gallery-card concept-card" data-categories="' . esc_attr( $illustrations[$i][1] ) . '"><button class="lightbox-trigger" type="button" data-image="' . esc_url( $src ) . '" data-alt="' . esc_attr( $alt ) . '" aria-label="' . esc_attr( 'نمایش ' . $alt ) . '"><img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy"></button><span class="concept-badge">تصویر مفهومی · هوش مصنوعی</span></article>';
    }
    if ( ! $posts && ! $concepts ) { $html .= '<p>برای ساخت گالری، عکس‌ها و نمونه‌کارهای خود را اضافه کنید.</p>'; }
    return $html . '<p class="gallery-empty" role="status" hidden>هنوز نمونه‌کاری در این دسته ثبت نشده است. دسته دیگری را انتخاب کنید.</p></div><dialog class="nue-lightbox" aria-label="تصویر بزرگ‌شده طرح ناخن"><button type="button" class="lightbox-close" aria-label="بستن تصویر">×</button><img src="" alt=""></dialog>';
}
function nue_render_team( $attributes ) {
    $posts = nue_query( 'nue_artist', $attributes['limit'] ?? -1 );
    if ( ! $posts ) {
        if ( ! is_front_page() || ! nue_concept_images_enabled() ) { return '<p>معرفی هنرمندان استودیو به‌زودی در این بخش قرار می‌گیرد.</p>'; }
        $html = '<p class="concept-disclaimer">این کارت‌ها نمونه نمایشی هستند؛ تصاویر مفهومی اعضای واقعی تیم را نشان نمی‌دهند.</p><div class="team-grid">';
        foreach ( array( 'concept-09.jpg', 'concept-01.jpg', 'concept-03.jpg' ) as $i => $image ) {
            $url = get_template_directory_uri() . '/assets/images/' . $image;
            $html .= '<article class="team-card team-card--placeholder"><div class="team-placeholder-image"><img src="' . esc_url( $url ) . '" alt="تصویر مفهومی ساخته‌شده با هوش مصنوعی" loading="lazy"><span>تصویر مفهومی · هوش مصنوعی</span></div><p class="eyebrow">پروفایل نمونه</p><h3>معرفی هنرمند</h3><p>نام، تصویر و تخصص هنرمند استودیو را در پیشخوان اضافه کنید.</p><div class="team-chips"><span>تخصص</span><span>نمونه‌کار</span></div></article>';
        }
        return $html . '</div>';
    }
    $html = '<div class="team-grid">'; foreach ( $posts as $p ) { $html .= '<article class="team-card"><a href="' . esc_url( get_permalink( $p ) ) . '">' . nue_photo( $p->ID ) . '</a><p class="eyebrow">' . esc_html( nue_meta( $p->ID, 'position', 'هنرمند ناخن' ) ) . '</p><h3><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . ' ↗</a></h3><p>' . esc_html( get_the_excerpt( $p ) ) . '</p></article>'; } return $html . '</div>';
}
function nue_render_testimonials( $attributes ) {
    $posts = nue_query( 'nue_testimonial', $attributes['limit'] ?? -1 ); if ( ! $posts ) { return '<p>دیدگاه‌های واقعی پس از دریافت در این بخش منتشر می‌شوند.</p>'; }
    $html = '<div class="review-grid">'; foreach ( $posts as $p ) { $rating = max( 0, min( 5, (int) nue_meta( $p->ID, 'rating', '0' ) ) ); $html .= '<blockquote class="review-card">' . ( $rating ? '<span class="stars" aria-label="' . $rating . ' از ۵ ستاره">' . str_repeat( '★', $rating ) . '</span>' : '' ) . '<p>“' . esc_html( wp_strip_all_tags( $p->post_content ) ) . '”</p><cite>' . esc_html( nue_meta( $p->ID, 'customer_name', get_the_title( $p ) ) ) . '</cite>' . ( 'yes' === strtolower( nue_meta( $p->ID, 'sample' ) ) ? '<small>دیدگاه نمونه است و نظر واقعی مشتری نیست</small>' : '' ) . '</blockquote>'; } return $html . '</div>';
}
function nue_render_booking() {
    $url = nue_setting( 'acuity_url' ); if ( ! $url || ! nue_is_acuity_url( $url ) ) { return '<div class="notice-box"><strong>سامانه رزرو هنوز تنظیم نشده است.</strong><p>مدیر استودیو می‌تواند نشانی Acuity را در تنظیمات نیو استودیو وارد کند. برای هماهنگی نوبت با استودیو تماس بگیرید.</p><a class="text-link" href="' . esc_url( home_url( '/contact/' ) ) . '">تماس با ما ↗</a></div>'; }
    return '<div class="scheduler"><iframe src="' . esc_url( $url ) . '" title="سامانه رزرو نوبت Acuity" loading="lazy" width="100%" height="800"></iframe><p>اگر سامانه رزرو بارگذاری نشد، <a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">صفحه رزرو را در زبانه جدید باز کنید</a>.</p></div>';
}
function nue_render_contact_info() {
    $html = '<div class="contact-info">'; foreach ( array( 'address' => 'نشانی', 'phone' => 'تلفن', 'email' => 'ایمیل', 'hours' => 'ساعات کاری' ) as $key => $label ) { $v = nue_setting( $key ); if ( ! $v ) { continue; } $html .= '<p><strong>' . esc_html( $label ) . '</strong><br>'; if ( 'email' === $key ) { $html .= '<a href="mailto:' . esc_attr( antispambot( $v ) ) . '">' . esc_html( antispambot( $v ) ) . '</a>'; } elseif ( 'phone' === $key ) { $html .= '<a href="tel:' . esc_attr( preg_replace( '/[^+0-9]/', '', $v ) ) . '">' . esc_html( $v ) . '</a>'; } else { $html .= nl2br( esc_html( $v ) ); } $html .= '</p>'; }
    foreach ( array( 'instagram' => 'اینستاگرام', 'facebook' => 'فیسبوک', 'map_url' => 'مشاهده نقشه' ) as $key => $label ) { if ( nue_setting( $key ) ) { $html .= '<a class="social-link" href="' . esc_url( nue_setting( $key ) ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $label ) . ' ↗</a> '; } }
    if ( ! nue_setting( 'address' ) && ! nue_setting( 'email' ) && ! nue_setting( 'phone' ) ) { $html .= '<p>اطلاعات تماس به‌زودی اعلام می‌شود.</p>'; } return $html . '</div>';
}
function nue_render_gift_link() { $url = nue_setting( 'gift_url' ); return $url ? nue_button( $url, 'خرید کارت هدیه' ) : '<div class="notice-box">امکان خرید کارت هدیه به‌زودی فعال می‌شود. مدیر استودیو می‌تواند نشانی ارائه‌دهنده را در تنظیمات وارد کند.</div>'; }
function nue_render_contact_form() {
    if ( ! nue_setting( 'email' ) ) { return '<div class="notice-box">فرم تماس پس از ثبت ایمیل استودیو در تنظیمات فعال می‌شود.</div>'; }
    $status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : '';
    $notice = 'sent' === $status ? '<p role="status">پیام شما ارسال شد. از تماس شما سپاسگزاریم.</p>' : ( 'error' === $status ? '<p role="alert">پیام شما ارسال نشد. اطلاعات را بررسی کنید و دوباره تلاش کنید.</p>' : '' );
    return $notice . '<form class="contact-form" action="' . esc_url( set_url_scheme( admin_url( 'admin-post.php' ), wp_parse_url( home_url(), PHP_URL_SCHEME ) ) ) . '" method="post"><input type="hidden" name="action" value="nue_contact">' . wp_nonce_field( 'nue_contact', 'nue_nonce', true, false ) . '<label for="nue-name">نام</label><input id="nue-name" name="name" required maxlength="100"><label for="nue-email">ایمیل</label><input id="nue-email" name="email" type="email" required maxlength="254"><label for="nue-message">پیام</label><textarea id="nue-message" name="message" required maxlength="5000" rows="6"></textarea><div class="form-trap" aria-hidden="true"><label>این قسمت را خالی بگذارید<input name="website" tabindex="-1" autocomplete="off"></label></div><button class="nue-button" type="submit">ارسال پیام ↗</button></form>';
}
add_action( 'admin_post_nopriv_nue_contact', 'nue_handle_contact' ); add_action( 'admin_post_nue_contact', 'nue_handle_contact' );
function nue_handle_contact() {
    $ok = isset( $_POST['nue_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nue_nonce'] ) ), 'nue_contact' ) && empty( $_POST['website'] );
    $name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : ''; $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : ''; $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    $to = nue_setting( 'email' ); $ok = $ok && $name && is_email( $email ) && $message && is_email( $to ) && strlen( $message ) <= 5000;
    if ( $ok ) { $ok = wp_mail( $to, 'پیام وب‌سایت از طرف ' . $name, $message . "\n\nاز طرف: " . $name . ' <' . $email . '>', array( 'Reply-To: ' . $email ) ); }
    wp_safe_redirect( add_query_arg( 'contact', $ok ? 'sent' : 'error', home_url( '/contact/' ) ) ); exit;
}
function nue_list( $value ) { $lines = array_filter( array_map( 'trim', explode( "\n", $value ) ) ); if ( ! $lines ) { return '<p>جزئیات با استودیو هماهنگ می‌شود.</p>'; } return '<ul><li>' . implode( '</li><li>', array_map( 'esc_html', $lines ) ) . '</li></ul>'; }
function nue_gallery_ids( $ids ) { $html = '<div class="detail-gallery">'; foreach ( array_filter( array_map( 'absint', explode( ',', $ids ) ) ) as $id ) { $html .= wp_get_attachment_image( $id, 'large', false, array( 'loading' => 'lazy' ) ); } return $html . '</div>'; }
function nue_render_detail() {
    $id = get_the_ID(); $type = get_post_type( $id ); $back = 'nue_service' === $type ? '/services/' : ( 'nue_artist' === $type ? '/team/' : '/nail-art/' );
    $html = '<p class="breadcrumb"><a href="' . esc_url( home_url( '/' ) ) . '">خانه</a> / <a href="' . esc_url( home_url( $back ) ) . '">مشاهده همه</a> / ' . esc_html( get_the_title( $id ) ) . '</p><div class="detail-hero"><div><p class="eyebrow">تجربه نیو استودیو ✳</p><h1>' . esc_html( get_the_title( $id ) ) . '</h1><div class="detail-copy">' . apply_filters( 'the_content', get_post_field( 'post_content', $id ) ) . '</div>';
    if ( 'nue_service' === $type ) { $html .= '<p class="detail-meta">' . esc_html( nue_meta( $id, 'duration', 'مدت زمان با استودیو هماهنگ می‌شود' ) ) . ' &nbsp; / &nbsp; ' . esc_html( nue_meta( $id, 'price', 'قیمت پس از هماهنگی' ) ) . '</p>' . nue_button( nue_service_booking( $id ), 'رزرو این خدمت' ); }
    if ( 'nue_artist' === $type ) { $html .= '<p>' . esc_html( nue_meta( $id, 'position' ) ) . '</p>' . nue_button( home_url( '/booking/' ), 'رزرو نوبت' ); }
    $html .= '</div><div class="detail-image">' . nue_photo( $id ) . '</div></div>';
    if ( 'nue_service' === $type ) { $html .= '<div class="detail-sections">'; foreach ( array( 'benefits' => 'مزایا', 'included' => 'این خدمت شامل چیست', 'preparation' => 'پیش از مراجعه', 'aftercare' => 'مراقبت پس از خدمت' ) as $key => $label ) { $html .= '<section><h2>' . esc_html( $label ) . '</h2>' . ( in_array( $key, array( 'benefits', 'included' ), true ) ? nue_list( nue_meta( $id, $key ) ) : '<p>' . nl2br( esc_html( nue_meta( $id, $key, 'جزئیات با استودیو هماهنگ می‌شود.' ) ) ) . '</p>' ) . '</section>'; } $html .= '</div><section class="related"><h2>خدمات دیگر را ببینید.</h2>'; $others = nue_query( 'nue_service', 3, $id ); foreach ( $others as $p ) { $html .= '<p><a class="text-link" href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . ' ↗</a></p>'; } $html .= '</section>'; }
    if ( 'nue_artist' === $type ) { $html .= '<div class="detail-sections"><section><h2>تخصص‌ها</h2><p>' . esc_html( nue_meta( $id, 'specialties', 'اطلاعات به‌زودی تکمیل می‌شود.' ) ) . '</p></section><section><h2>سابقه</h2><p>' . esc_html( nue_meta( $id, 'experience', 'اطلاعات به‌زودی تکمیل می‌شود.' ) ) . '</p></section></div>'; foreach ( array( 'instagram', 'social_url' ) as $key ) { if ( nue_meta( $id, $key ) ) { $html .= '<a class="text-link" href="' . esc_url( nue_meta( $id, $key ) ) . '" rel="noopener noreferrer">صفحه هنرمند ↗</a>'; } } $html .= nue_gallery_ids( nue_meta( $id, 'gallery_ids' ) ); }
    if ( 'nue_project' === $type ) { $artist = absint( nue_meta( $id, 'artist_id' ) ); if ( $artist && 'nue_artist' === get_post_type( $artist ) && 'publish' === get_post_status( $artist ) ) { $html .= '<p>طراحی توسط <a href="' . esc_url( get_permalink( $artist ) ) . '">' . esc_html( get_the_title( $artist ) ) . '</a></p>'; } $html .= nue_gallery_ids( nue_meta( $id, 'gallery_ids' ) ); }
    return $html;
}
function nue_render_share() { $url = rawurlencode( get_permalink() ); $title = rawurlencode( get_the_title() ); return '<div class="share-links"><span>اشتراک‌گذاری مطلب</span> <a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_attr( $url ) . '" target="_blank" rel="noopener noreferrer">فیسبوک ↗</a> <a href="https://pinterest.com/pin/create/button/?url=' . esc_attr( $url ) . '&amp;description=' . esc_attr( $title ) . '" target="_blank" rel="noopener noreferrer">پینترست ↗</a></div>'; }
function nue_render_related_posts() { $posts = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'category__in' => wp_get_post_categories( get_the_ID() ) ) ); $html = '<div class="related-posts">'; foreach ( $posts as $p ) { $html .= '<article><a href="' . esc_url( get_permalink( $p ) ) . '">' . ( has_post_thumbnail( $p ) ? get_the_post_thumbnail( $p, 'medium_large' ) : '' ) . '<h3>' . esc_html( get_the_title( $p ) ) . '</h3></a></article>'; } return $html . ( $posts ? '' : '<p>مطالب بیشتر به‌زودی منتشر می‌شوند.</p>' ) . '</div>'; }
add_action( 'wp_head', function () { if ( ! is_front_page() ) { return; } $data = array( '@context' => 'https://schema.org', '@type' => 'NailSalon', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ) ); if ( nue_setting( 'phone' ) ) { $data['telephone'] = nue_setting( 'phone' ); } if ( nue_setting( 'address' ) ) { $data['address'] = array( '@type' => 'PostalAddress', 'streetAddress' => nue_setting( 'address' ) ); } echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . '</script>'; } );

function nue_render_featured_post() {
    $sticky = get_option( 'sticky_posts', array() );
    $args = array( 'post_type' => 'post', 'posts_per_page' => 1, 'post_status' => 'publish', 'ignore_sticky_posts' => true );
    if ( $sticky ) { $args['post__in'] = $sticky; }
    $posts = get_posts( $args );
    if ( ! $posts ) { return ''; }
    $p = $posts[0];
    return '<article class="featured-post"><div>' . ( has_post_thumbnail( $p ) ? get_the_post_thumbnail( $p, 'large', array( 'loading' => 'lazy' ) ) : '' ) . '</div><div><p class="eyebrow">مطلب برگزیده ✳</p><h2><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a></h2><p>' . esc_html( get_the_excerpt( $p ) ) . '</p><a class="text-link" href="' . esc_url( get_permalink( $p ) ) . '">خواندن مطلب ↗</a></div></article>';
}

/** A compact editorial menu rendered from the same editable service records. */
function nue_render_service_menu( $attributes ) {
    $posts = nue_query( 'nue_service', $attributes['limit'] ?? 8 );
    if ( ! $posts ) { return '<p>خدمات پس از ثبت در پیشخوان اینجا نمایش داده می‌شوند.</p>'; }
    $html = '<div class="lune-service-list">'; $index = 0;
    foreach ( $posts as $post ) {
        $index++;
        $html .= '<article class="lune-service-row"><span class="lune-row-number">' . esc_html( strtr( str_pad( (string) $index, 2, '0', STR_PAD_LEFT ), array( '0'=>'۰', '1'=>'۱', '2'=>'۲', '3'=>'۳', '4'=>'۴', '5'=>'۵', '6'=>'۶', '7'=>'۷', '8'=>'۸', '9'=>'۹' ) ) ) . '</span><div class="lune-row-main"><h3><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( get_the_title( $post ) ) . '</a></h3><p>' . esc_html( nue_meta( $post->ID, 'short_description', 'برای دریافت جزئیات با استودیو تماس بگیرید.' ) ) . '</p></div><div class="lune-row-meta"><span>' . esc_html( nue_meta( $post->ID, 'duration', 'مدت با هماهنگی' ) ) . '</span><span>' . esc_html( nue_meta( $post->ID, 'price', 'قیمت با هماهنگی' ) ) . '</span></div><a class="lune-row-arrow" href="' . esc_url( get_permalink( $post ) ) . '" aria-label="' . esc_attr( 'جزئیات ' . get_the_title( $post ) ) . '">←</a></article>';
    }
    return $html . '</div>';
}
/** Category cards use real services and prices, not invented pricing tiers. */
function nue_render_pricing_preview() {
    $terms = get_terms( array( 'taxonomy' => 'nue_service_category', 'hide_empty' => true ) );
    if ( is_wp_error( $terms ) || ! $terms ) { return '<p>قیمت‌ها پس از ثبت خدمات در این بخش نمایش داده می‌شوند.</p>'; }
    $html = '<div class="lune-price-cards">'; $shown = 0;
    foreach ( $terms as $term ) {
        if ( $shown >= 3 ) { break; }
        $posts = get_posts( array( 'post_type' => 'nue_service', 'posts_per_page' => 4, 'post_status' => 'publish', 'tax_query' => array( array( 'taxonomy' => 'nue_service_category', 'field' => 'term_id', 'terms' => $term->term_id ) ) ) );
        if ( ! $posts ) { continue; }
        $html .= '<section class="lune-price-card' . ( 1 === $shown ? ' is-featured' : '' ) . '"><p class="lune-price-label">' . ( 1 === $shown ? 'منتخب خدمات' : 'فهرست خدمات' ) . '</p><h3>' . esc_html( $term->name ) . '</h3><ul>';
        foreach ( $posts as $post ) { $html .= '<li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( get_the_title( $post ) ) . '</a><span>' . esc_html( nue_meta( $post->ID, 'price', 'با هماهنگی' ) ) . '</span></li>'; }
        $html .= '</ul><a class="lune-price-button" href="' . esc_url( home_url( '/booking/' ) ) . '">رزرو نوبت ←</a></section>'; $shown++;
    }
    return $html . '</div>';
}
/** Reviews remain visible without JavaScript; controls are enabled progressively. */
function nue_render_review_carousel() {
    $posts = nue_query( 'nue_testimonial', 8 );
    if ( ! $posts ) { return '<p>دیدگاه‌های واقعی پس از دریافت در این بخش منتشر می‌شوند.</p>'; }
    $html = '<div class="lune-review-carousel" aria-label="دیدگاه‌های همراهان"><div class="lune-review-slides">';
    foreach ( $posts as $post ) {
        $html .= '<blockquote class="lune-review-slide"><p>«' . esc_html( wp_strip_all_tags( $post->post_content ) ) . '»</p><cite>' . esc_html( nue_meta( $post->ID, 'customer_name', get_the_title( $post ) ) ) . '</cite>' . ( 'yes' === strtolower( nue_meta( $post->ID, 'sample' ) ) ? '<small>دیدگاه نمونه است و نظر واقعی مشتری نیست.</small>' : '' ) . '</blockquote>';
    }
    return $html . '</div><div class="lune-review-controls" hidden><button type="button" data-review-prev aria-label="دیدگاه قبلی">→</button><span class="lune-review-count" aria-live="polite"></span><button type="button" data-review-next aria-label="دیدگاه بعدی">←</button><button type="button" class="lune-review-pause" data-review-pause aria-pressed="false">توقف پخش</button></div></div>';
}
