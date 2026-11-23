<?php
/** Editable Persian editorial starter. Existing edited content is never replaced. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function nue_design_section( $label, $title, $copy, $body, $class ) {
    return nue_group( nue_p( $label, 'section-label' ) . nue_h( $title ) . ( $copy ? nue_p( $copy, 'section-copy' ) : '' ) . $body, 'lune-section ' . $class );
}
function nue_page_content() {
    $pages = nue_previous_page_content();
    $hero = '<!-- wp:columns {"className":"lune-hero","verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center lune-hero">'
        . '<!-- wp:column {"verticalAlignment":"center","width":"40%","className":"lune-hero-copy"} --><div class="wp-block-column is-vertically-aligned-center lune-hero-copy" style="flex-basis:40%">'
        . nue_p( 'استودیو ناخن / زیبایی در جزئیات', 'section-label' )
        . nue_h( 'زیبایی،<br>با دقت و<br><em>آرامش.</em>', 1 )
        . nue_p( 'فضایی برای کمی مکث و رسیدگی به خودتان؛ با نگاهی دقیق به جزئیات و طراحی‌هایی که به سلیقه شما نزدیک‌اند.', 'hero-description' )
        . '<!-- wp:buttons {"className":"lune-hero-actions"} --><div class="wp-block-buttons lune-hero-actions"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( '/booking/' ) ) . '">رزرو نوبت ←</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( '/services/' ) ) . '">مشاهده خدمات ←</a></div><!-- /wp:button --></div><!-- /wp:buttons -->'
        . nue_p( 'رسیدگی به ناخن / طراحی با سلیقه شما', 'hero-caption' ) . '</div><!-- /wp:column -->'
        . '<!-- wp:column {"width":"60%","className":"lune-hero-media"} --><div class="wp-block-column lune-hero-media" style="flex-basis:60%">'
        . nue_image( 'hero.jpg', 'مانیکور سفید شیری با فرم بادامی', 'lune-hero-main' )
        . nue_image( 'studio.jpg', 'مانیکور صورتی با درخشش ظریف', 'lune-hero-secondary' )
        . '</div><!-- /wp:column --></div><!-- /wp:columns -->';
    $marquee = '<!-- wp:html --><div class="lune-marquee" aria-label="خدمات استودیو"><div class="lune-marquee-track"><span>مانیکور ژل <b>✦</b> طراحی ناخن <b>✦</b> پدیکور <b>✦</b> بیلدر ژل <b>✦</b> مراقبت ناخن <b>✦</b> </span><span aria-hidden="true">مانیکور ژل <b>✦</b> طراحی ناخن <b>✦</b> پدیکور <b>✦</b> بیلدر ژل <b>✦</b> مراقبت ناخن <b>✦</b> </span></div><button type="button" class="lune-marquee-toggle" hidden>توقف حرکت</button></div><!-- /wp:html -->';
    $services = nue_design_section( '۰۲ / خدمات', 'خدماتی برای <em>شما.</em>', 'از مراقبت ناخن تا طراحی دلخواه؛ جزئیات هر خدمت را ببینید و انتخابی متناسب با سلیقه خود داشته باشید.', nue_block( 'service-menu', '{"limit":8}' ) . nue_link( '/services/', 'همه خدمات' ), 'lune-services' );
    $gallery = nue_design_section( '۰۳ / گالری', 'جزئیاتی که <em>می‌مانند.</em>', 'مجموعه‌ای از طرح‌ها و رنگ‌ها برای الهام گرفتن.', nue_block( 'gallery', '{"limit":12}' ) . nue_link( '/nail-art/', 'مشاهده گالری' ), 'lune-gallery' );
    $process = '<!-- wp:columns {"className":"lune-steps"} --><div class="wp-block-columns lune-steps">';
    foreach ( array( array('۰۱','رزرو','خدمت و زمان مناسب خود را انتخاب کنید یا برای راهنمایی با ما در تماس باشید.'), array('۰۲','گفت‌وگو','درباره فرم، رنگ، سلیقه و ایده‌های خود با هنرمندتان صحبت کنید.'), array('۰۳','خلق','طرح انتخابی شما با توجه به جزئیات و شرایط ناخن اجرا می‌شود.'), array('۰۴','مراقبت','پیش از پایان نوبت، درباره مراقبت پس از خدمات از هنرمند خود بپرسید.') ) as $step ) {
        $process .= '<!-- wp:column --><div class="wp-block-column">' . nue_p( $step[0], 'step-number' ) . nue_h( $step[1], 3 ) . nue_p( $step[2] ) . '</div><!-- /wp:column -->';
    }
    $process .= '</div><!-- /wp:columns -->';
    $process = nue_design_section( '۰۴ / تجربه شما', 'از ابتدا تا <em>جزئیات.</em>', 'هر تجربه با شنیدن سلیقه شما آغاز می‌شود.', $process, 'lune-process' );
    $pricing = nue_design_section( '۰۵ / قیمت‌ها', 'قیمت‌گذاری <em>روشن.</em>', 'قیمت‌ها و مدت خدمات را بررسی کنید؛ موارد تکمیل‌نشده با استودیو هماهنگ می‌شوند.', nue_block( 'pricing-preview' ) . nue_link( '/pricing/', 'فهرست کامل قیمت‌ها' ), 'lune-pricing' );
    $team = nue_design_section( '۰۶ / هنرمندان', 'دستان <em>هنرمند.</em>', 'با تیمی که پشت جزئیات است آشنا شوید.', nue_block( 'team', '{"limit":3}' ) . nue_link( '/team/', 'آشنایی با تیم' ), 'lune-team' );
    $reviews = nue_design_section( '۰۷ / دیدگاه‌ها', 'حرف‌های <em>دلنشین.</em>', '', nue_block( 'review-carousel' ) . nue_link( '/testimonials/', 'همه دیدگاه‌ها' ), 'lune-reviews' );
    $faq_items = '';
    foreach ( array(
        array( 'چگونه نوبت رزرو کنم؟', 'از صفحه رزرو، خدمت و زمان مناسب را انتخاب کنید. اگر سامانه فعال نیست با استودیو تماس بگیرید.' ),
        array( 'ماندگاری مانیکور چقدر است؟', 'ماندگاری به نوع خدمت، شرایط ناخن و مراقبت پس از آن بستگی دارد. برای جزئیات با هنرمند خود گفت‌وگو کنید.' ),
        array( 'آیا خدمات ریموو ارائه می‌شود؟', 'برای اطلاع از خدمات ریموو، زمان مورد نیاز و هزینه آن فهرست خدمات را بررسی کنید یا با استودیو تماس بگیرید.' ),
        array( 'می‌توانم عکس ایده دلخواهم را بیاورم؟', 'بله، عکس یا ایده مورد علاقه‌تان را همراه داشته باشید تا درباره امکان اجرا گفت‌وگو کنیم.' ),
        array( 'شرایط لغو نوبت چیست؟', 'لطفاً پیش از رزرو، صفحه شرایط لغو نوبت را مطالعه کنید و برای جزئیات به‌روز با استودیو تماس بگیرید.' ),
        array( 'برای مناسبت‌ها و نوبت‌های گروهی چگونه هماهنگ کنم؟', 'برای بررسی امکان هماهنگی و دریافت جزئیات، تاریخ و تعداد نفرات را با استودیو در میان بگذارید.' ),
    ) as $item ) { $faq_items .= nue_details_item( $item[0], $item[1] ); }
    $faq_columns = '<!-- wp:columns {"className":"lune-faq-columns"} --><div class="wp-block-columns lune-faq-columns"><!-- wp:column --><div class="wp-block-column">' . nue_h( 'پیش از <em>مراجعه.</em>' ) . nue_p( 'پرسشی دارید؟ با استودیو در ارتباط باشید.', 'section-copy' ) . nue_link( '/contact/', 'تماس با ما' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . $faq_items . nue_link( '/faq/', 'همه پرسش‌ها' ) . '</div><!-- /wp:column --></div><!-- /wp:columns -->';
    $faq = nue_group( nue_p( '۰۹ / پرسش‌های متداول', 'section-label' ) . $faq_columns, 'lune-section lune-faq' );
    $pages['home'] = array( 'خانه', nue_group( $hero . $marquee . $services . $gallery . $process . $pricing . $team . $reviews . nue_cta() . $faq, 'lune-home' ) );
    return $pages;
}
