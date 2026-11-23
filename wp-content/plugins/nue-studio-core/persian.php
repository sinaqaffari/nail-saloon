<?php
/** Persian starter content and a safe, opt-in migration for existing installations. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function nue_fa_faq( $short = false ) {
    $items = array(
        'عمومی' => array( 'در اولین مراجعه چه اتفاقی می‌افتد؟' => 'پیش از شروع، درباره وضعیت ناخن‌ها و سلیقه شما گفت‌وگو می‌کنیم. برای جزئیات هر خدمت با استودیو تماس بگیرید.' ),
        'مانیکور' => array( 'کدام مانیکور برای من مناسب است؟' => 'هنرمند شما با توجه به ناخن طبیعی، سبک زندگی و نتیجه دلخواهتان در انتخاب خدمت مناسب راهنمایی‌تان می‌کند.' ),
        'پدیکور' => array( 'برای پدیکور چگونه آماده شوم؟' => 'با پاهای تمیز مراجعه کنید و پیش از شروع، هرگونه حساسیت یا نگرانی را با هنرمند خود در میان بگذارید.' ),
        'طراحی ناخن' => array( 'می‌توانم عکس ایده دلخواهم را بیاورم؟' => 'بله، عکس یا ایده مورد علاقه‌تان را همراه داشته باشید تا پیش از شروع درباره امکان اجرا گفت‌وگو کنیم.' ),
        'رزرو' => array( 'چطور وقت رزرو کنم؟' => 'از صفحه رزرو، زمان و خدمت مورد نظر را انتخاب کنید. اگر سامانه فعال نیست، با استودیو تماس بگیرید.' ),
        'پرداخت' => array( 'چه روش‌هایی برای پرداخت پذیرفته می‌شود؟' => 'لطفاً پیش از مراجعه برای اطلاع از روش‌های پرداخت با استودیو تماس بگیرید.' ),
        'لغو و تغییر نوبت' => array( 'اگر نتوانم در زمان رزرو حاضر شوم چه کنم؟' => 'در اولین فرصت با استودیو تماس بگیرید و شرایط لغو و تغییر نوبت را مطالعه کنید.' ),
        'مراقبت پس از خدمات' => array( 'چطور از مانیکورم مراقبت کنم؟' => 'به‌طور منظم از روغن کوتیکول استفاده کنید، هنگام نظافت دستکش بپوشید و از ناخن‌ها به‌عنوان ابزار استفاده نکنید.' ),
    );
    $html = ''; $i = 0;
    foreach ( $items as $category => $questions ) {
        if ( $short && $i++ >= 4 ) { break; }
        if ( ! $short ) { $html .= nue_h( esc_html( $category ), 3 ); }
        foreach ( $questions as $question => $answer ) { $html .= nue_details_item( $question, $answer ); }
    }
    return $html;
}
function nue_page_content() {
    $hero = '<!-- wp:group {"className":"hero","layout":{"type":"constrained"}} --><div class="wp-block-group hero"><!-- wp:columns {"className":"hero-columns","verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center hero-columns"><!-- wp:column {"verticalAlignment":"center","width":"48%"} --><div class="wp-block-column is-vertically-aligned-center" style="flex-basis:48%">' . nue_p( 'به نیو استودیو خوش آمدید ✳', 'eyebrow' ) . nue_h( 'زیبایی در <em>دستان شما</em>', 1 ) . nue_p( 'جایی برای رسیدگی به خودتان، زیبایی ناخن‌ها و تجربه لحظه‌ای آرام. مانیکور، پدیکور و طراحی ناخن، با توجه به سلیقه شما.', 'hero-copy' ) . nue_link( '/booking/', 'رزرو نوبت' ) . nue_p( 'ناخن‌های زیبا، حال خوب ✳', 'hero-note' ) . '</div><!-- /wp:column --><!-- wp:column {"width":"52%"} --><div class="wp-block-column" style="flex-basis:52%">' . nue_image( 'hero.jpg', 'ناخن‌های بادامی با لاک سفید شیری', 'hero-image' ) . '</div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->';
    $intro = '<!-- wp:columns {"className":"intro-split","verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center intro-split"><!-- wp:column --><div class="wp-block-column">' . nue_image( 'studio.jpg', 'مانیکور صورتی ملایم با درخشش ظریف', 'intro-image' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . nue_p( 'کمی بیشتر درباره ما ✳', 'eyebrow' ) . nue_h( 'لحظه‌ای زیبا، <em>فقط برای شما</em>' ) . nue_p( 'به فضایی خوش آمدید که زیبایی در آن با آرامش همراه است. در نیو استودیو باور داریم همین لحظه‌های کوچک می‌توانند حال روزمان را تغییر دهند.' ) . nue_p( 'از مراقبت ناخن تا ظریف‌ترین جزئیات طراحی، همه‌چیز با توجه به سلیقه و راحتی شما انجام می‌شود.' ) . nue_link( '/about/', 'آشنایی با استودیو' ) . '</div><!-- /wp:column --></div><!-- /wp:columns -->';
    $home = $hero . nue_group( $intro, 'content-section intro-section' )
        . nue_group( nue_p( 'زیبایی در جزئیات است ✳', 'eyebrow' ) . nue_h( 'ایده‌هایی برای <em>ناخن‌های شما</em>' ) . nue_p( 'رنگ‌ها، بافت‌ها و طرح‌هایی برای الهام گرفتن از زیبایی‌های کوچک.' ) . nue_block( 'gallery', '{"limit":4}' ) . nue_link( '/nail-art/', 'مشاهده گالری' ), 'content-section gallery-section' )
        . nue_group( nue_p( 'برای لحظه‌های شما ✳', 'eyebrow' ) . nue_h( 'مراقبتی به سبک <em>خودتان</em>' ) . nue_p( 'از رسیدگی‌های روزمره تا طراحی‌های خاص، خدمت مناسب خود را پیدا کنید.' ) . nue_block( 'services', '{"limit":3}' ) . nue_link( '/services/', 'مشاهده همه خدمات' ), 'content-section services-section' )
        . nue_group( nue_p( 'دستان هنرمند ما ✳', 'eyebrow' ) . nue_h( 'با تیم ما <em>آشنا شوید</em>' ) . nue_p( 'نگاه خلاق، دستان دقیق و علاقه‌ای مشترک به زیبایی جزئیات.' ) . nue_block( 'team', '{"limit":3}' ) . nue_link( '/team/', 'آشنایی با تیم' ), 'content-section team-section' )
        . nue_group( nue_p( 'تفاوت نیو استودیو ✳', 'eyebrow' ) . nue_h( 'زیبایی همراه با <em>حس خوب</em>' ) . '<!-- wp:columns {"className":"values-grid"} --><div class="wp-block-columns values-grid"><!-- wp:column --><div class="wp-block-column">' . nue_p( '۰۱ / ✳', 'eyebrow' ) . nue_h( 'کیفیت', 3 ) . nue_p( 'توجه به محصولات، سلامت ناخن و ظرافت در هر مرحله.' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . nue_p( '۰۲ / ✳', 'eyebrow' ) . nue_h( 'صمیمیت', 3 ) . nue_p( 'فضایی آرام و دوستانه برای اینکه احساس راحتی کنید.' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . nue_p( '۰۳ / ✳', 'eyebrow' ) . nue_h( 'دقت و سرعت', 3 ) . nue_p( 'خدماتی سنجیده، با احترام به وقت و سلیقه شما.' ) . '</div><!-- /wp:column --></div><!-- /wp:columns -->', 'content-section values-section' )
        . nue_group( nue_p( 'حرف‌های دلنشین ✳', 'eyebrow' ) . nue_h( 'از زبان <em>همراهان ما</em>' ) . nue_block( 'testimonials', '{"limit":3}' ) . nue_link( '/testimonials/', 'مشاهده دیدگاه‌ها' ), 'content-section review-section' )
        . nue_group( nue_p( 'هدیه‌ای برای یک عزیز ✳', 'eyebrow' ) . nue_h( 'هدیه‌ای از جنس <em>آرامش</em>' ) . nue_p( 'به کسی که دوستش دارید، فرصتی برای رسیدگی به خودش هدیه دهید.' ) . nue_link( '/gift-cards/', 'کارت هدیه' ), 'gift-promo' )
        . nue_group( nue_p( 'خوب است بدانید ✳', 'eyebrow' ) . nue_h( 'پرسش‌های <em>متداول</em>' ) . nue_fa_faq( true ) . nue_link( '/faq/', 'همه پرسش‌ها' ), 'content-section faq-section' ) . nue_cta();
    $pages = array(); $pages['home'] = array( 'خانه', $home );
    $pages['services'] = array( 'خدمات', nue_intro( 'خدمات استودیو', 'زیبایی در <em>تمام جزئیات</em>', 'خدماتی سنجیده برای مراقبت و زیبایی ناخن‌ها، متناسب با سلیقه شما.' ) . nue_group( nue_block( 'services' ) ) . nue_cta() );
    $pages['pricing'] = array( 'قیمت‌ها', nue_intro( 'منوی قیمت', 'مراقبتی زیبا، <em>متناسب با شما</em>', 'خدمات را بررسی کنید. قیمت و زمان هر خدمت هنگام رزرو توسط استودیو تأیید می‌شود.' ) . nue_group( nue_block( 'pricing' ) . nue_h( 'پکیج‌ها و خدمات تکمیلی' ) . nue_p( 'برای اطلاع از پکیج‌ها و خدمات تکمیلی موجود با استودیو تماس بگیرید. هزینه پیش از شروع خدمت اعلام می‌شود.' ) ) . nue_cta() );
    $pages['nail-art'] = array( 'طراحی ناخن', nue_intro( 'گالری طراحی ناخن', 'آثار کوچک <em>هنری</em>', 'مجموعه‌ای از رنگ‌ها و جزئیات برای الهام گرفتن از طرح بعدی شما.' ) . nue_group( nue_block( 'gallery' ) ) . nue_cta() );
    $pages['about'] = array( 'درباره ما', nue_intro( 'داستان ما', 'زیبایی در <em>لحظه‌های کوچک</em>', 'فضایی برای آرامش، رسیدگی به خود و تجربه زیبایی به سبک خودتان.' ) . nue_group( $intro . nue_h( 'فلسفه ما' ) . nue_p( 'باور داریم رسیدگی به ناخن فراتر از زیبایی ظاهری است؛ فرصتی کوچک برای آرامش و ابراز سلیقه شخصی است.' ) . nue_h( 'روش ما' ) . nue_p( 'هر خدمت با شنیدن خواسته شما آغاز می‌شود. سلامت ناخن طبیعی و جزئیاتی که برایتان مهم است، در انتخاب ما نقش دارند.' ) . nue_h( 'بهداشت و کیفیت' ) . nue_p( 'محیط تمیز و مراقبت دقیق، بخشی از تجربه شماست. برای اطلاع از روش‌های بهداشتی و محصولات مورد استفاده با استودیو گفت‌وگو کنید.' ) . nue_h( 'ارزش‌های ما' ) . nue_p( 'مهربانی، خلاقیت، مراقبت و توجه به جزئیات، اساس کار ماست.' ) . nue_h( 'تیم ما' ) . nue_block( 'team', '{"limit":3}' ) . nue_link( '/team/', 'آشنایی با تیم' ) ) . nue_cta() );
    $pages['team'] = array( 'تیم ما', nue_intro( 'هنرمندان نیو استودیو', 'دستانی هنرمند، <em>همراه شما</em>', 'با افرادی آشنا شوید که خلاقیت و توجه را به هر نوبت می‌آورند.' ) . nue_group( nue_block( 'team' ) ) . nue_cta() );
    $pages['booking'] = array( 'رزرو نوبت', nue_intro( 'وقتی برای خودتان', 'برای خودتان <em>زمان بگذارید</em>', 'خدمت دلخواهتان را انتخاب کنید و زمانی مناسب برای یک تجربه آرام رزرو کنید.' ) . nue_group( nue_h( 'رزرو آنلاین' ) . nue_p( 'در سامانه رزرو، خدمت و زمان مناسب را انتخاب کنید و مراحل ثبت نوبت را تکمیل کنید.' ) . nue_block( 'booking' ) . nue_h( 'پیش از رزرو' ) . nue_p( 'لطفاً پیش از ثبت نوبت، شرایط لغو و تغییر را مطالعه کنید. برای انتخاب خدمت یا تغییر نوبت می‌توانید با استودیو تماس بگیرید.' ) . nue_link( '/cancellation-policy/', 'شرایط لغو نوبت' ) . nue_h( 'پرسشی دارید؟' ) . nue_block( 'contact-info' ) ) );
    $pages['gift-cards'] = array( 'کارت هدیه', nue_intro( 'هدیه‌ای برای عزیزان', 'هدیه‌ای از جنس <em>حال خوب</em>', 'گاهی بهترین هدیه، فرصتی برای آرامش و رسیدگی به خود است.' ) . nue_group( nue_image( 'studio.jpg', 'مانیکور صورتی درخشان', 'wide-image' ) . nue_h( 'هدیه‌ای با سلیقه شما' ) . nue_p( 'برای اطلاع از مبلغ‌های قابل ارائه و جزئیات کارت هدیه با استودیو تماس بگیرید.' ) . nue_block( 'gift-link' ) . nue_h( 'روش استفاده' ) . nue_p( 'پس از فعال شدن امکان خرید، کارت هدیه را تهیه کنید و به عزیزتان بدهید. روش استفاده هنگام خرید اعلام می‌شود.' ) . nue_h( 'شرایط و قوانین' ) . nue_p( 'مدت اعتبار، شیوه استفاده و سایر شرایط کارت هدیه باید پیش از خرید توسط استودیو تأیید و اعلام شود.' ) ) . nue_cta() );
    $pages['contact'] = array( 'تماس با ما', nue_intro( 'با ما در ارتباط باشید', 'مشتاق شنیدن <em>صدای شما هستیم</em>', 'برای پرسش، پیشنهاد یا هماهنگی با استودیو، از راه‌های زیر با ما در تماس باشید.' ) . nue_group( nue_h( 'اطلاعات تماس' ) . nue_block( 'contact-info' ) . nue_p( 'نقشه و اطلاعات موقعیت پس از تأیید استودیو در این بخش قرار می‌گیرد.' ) . nue_h( 'برای ما پیام بگذارید' ) . nue_p( 'فرم زیر را تکمیل کنید تا در اولین فرصت پاسخ دهیم.' ) . nue_block( 'contact-form' ) ) . nue_cta() );
    $pages['faq'] = array( 'پرسش‌های متداول', nue_intro( 'خوب است بدانید', 'پرسش‌های شما، <em>پاسخ‌های ما</em>', 'آنچه بهتر است پیش از مراجعه و رزرو نوبت بدانید.' ) . nue_group( nue_fa_faq() . nue_p( 'پرسش دیگری دارید؟ با ما تماس بگیرید.' ) . nue_link( '/contact/', 'تماس با ما' ) ) . nue_cta() );
    $pages['testimonials'] = array( 'دیدگاه‌ها', nue_intro( 'حرف‌های دلنشین', 'از زبان <em>همراهان ما</em>', 'دیدگاه کسانی که همراهی‌شان برای ما ارزشمند است.' ) . nue_group( nue_h( 'دیدگاه‌های شما' ) . nue_block( 'testimonials' ) . nue_p( 'دیدگاه‌های واقعی پس از دریافت و تأیید در این بخش منتشر می‌شوند.' ) ) . nue_cta() );
    $legal = array( 'privacy-policy' => array( 'حریم خصوصی', 'اطلاعات مربوط به جمع‌آوری، استفاده، نگهداری و اشتراک داده‌ها، از جمله فرم تماس و سامانه رزرو، باید توسط استودیو بررسی و تکمیل شود.' ), 'terms-and-conditions' => array( 'شرایط و قوانین', 'شرایط خدمات، استفاده از وب‌سایت، پرداخت و سایر قوانین اختصاصی کسب‌وکار باید توسط استودیو بررسی و تکمیل شود.' ), 'cancellation-policy' => array( 'شرایط لغو نوبت', 'مهلت لغو، تغییر نوبت، تأخیر، عدم حضور و هزینه‌های احتمالی باید توسط استودیو تأیید و در این صفحه درج شود.' ), 'refund-policy' => array( 'شرایط بازگشت وجه', 'شرایط بازگشت وجه، مراحل درخواست، قوانین کارت هدیه و موارد استثنا باید توسط استودیو تکمیل شود.' ) );
    foreach ( $legal as $slug => $data ) { $pages[ $slug ] = array( $data[0], nue_intro( 'اطلاعات مهم', esc_html( $data[0] ), 'اطلاعاتی برای همراهان نیو استودیو.' ) . nue_group( nue_p( 'متن نمونه قابل ویرایش — مشاوره حقوقی نیست.', 'eyebrow' ) . nue_h( 'اطلاعات و شرایط' ) . nue_p( esc_html( $data[1] ) ) . nue_h( 'پرسشی درباره این شرایط دارید؟' ) . nue_p( 'برای دریافت اطلاعات به‌روز با استودیو تماس بگیرید.' ) . nue_link( '/contact/', 'تماس با ما' ) ) ); }
    $pages['blog'] = array( 'مجله', '' );
    return $pages;
}

/** Stable English slugs let old links and appointment links keep working. */
function nue_service_seed_data() {
    return array(
        array( 'slug'=>'classic-manicure','en'=>'Classic Manicure','fa'=>'مانیکور کلاسیک','category'=>'Manicure','description_en'=>'A considered essential for beautifully cared-for natural nails.','description_fa'=>'مراقبتی پایه و دقیق برای زیبایی ناخن‌های طبیعی.' ),
        array( 'slug'=>'gel-manicure','en'=>'Gel Manicure','fa'=>'مانیکور ژل','category'=>'Manicure','description_en'=>'A glossy, durable finish with meticulous nail care.','description_fa'=>'مانیکوری با ظاهری براق و ماندگار، همراه با رسیدگی دقیق به ناخن.' ),
        array( 'slug'=>'builder-gel','en'=>'Builder Gel','fa'=>'بیلدر ژل','category'=>'Extensions','description_en'=>'Added structure and strength with a natural-looking finish.','description_fa'=>'استحکام و فرم بیشتر با ظاهری طبیعی و ظریف.' ),
        array( 'slug'=>'hard-gel-extensions','en'=>'Hard Gel Extensions','fa'=>'کاشت با هارد ژل','category'=>'Extensions','description_en'=>'Custom length and shape, thoughtfully sculpted.','description_fa'=>'فرم و قد دلخواه ناخن با طراحی متناسب با سلیقه شما.' ),
        array( 'slug'=>'nail-art','en'=>'Nail Art','fa'=>'طراحی ناخن','category'=>'Nail Art','description_en'=>'A little self-expression, created just for you.','description_fa'=>'طرحی منحصربه‌فرد برای بیان سلیقه شخصی شما.' ),
        array( 'slug'=>'pedicure','en'=>'Pedicure','fa'=>'پدیکور','category'=>'Pedicure','description_en'=>'A restorative ritual for polished, cared-for feet.','description_fa'=>'مراقبتی آرامش‌بخش برای زیبایی و رسیدگی به پاها.' ),
        array( 'slug'=>'gel-pedicure','en'=>'Gel Pedicure','fa'=>'پدیکور ژل','category'=>'Pedicure','description_en'=>'A considered pedicure with a lasting gel finish.','description_fa'=>'پدیکور همراه با پوشش ژل زیبا و ماندگار.' ),
        array( 'slug'=>'nail-repair','en'=>'Nail Repair','fa'=>'ترمیم ناخن','category'=>'Add-ons','description_en'=>'A little extra care for a nail that needs attention.','description_fa'=>'رسیدگی بیشتر به ناخنی که به ترمیم نیاز دارد.' ),
        array( 'slug'=>'removal','en'=>'Removal','fa'=>'ریموو','category'=>'Removal','description_en'=>'Gentle removal with the health of your natural nails in mind.','description_fa'=>'برداشتن پوشش ناخن با توجه به سلامت ناخن طبیعی.' ),
    );
}
function nue_translate_starter_terms() {
    // Keep the default category ID and slug so existing post and category URLs survive.
    $default_category = get_term( (int) get_option( 'default_category' ), 'category' );
    if ( $default_category && ! is_wp_error( $default_category ) && $default_category->slug === 'uncategorized' && $default_category->name === 'Uncategorized' ) {
        wp_update_term( $default_category->term_id, 'category', array( 'name' => 'دسته‌بندی نشده', 'slug' => 'uncategorized' ) );
    }
    $taxonomies = array(
        'nue_art_category' => array( 'classic'=>array('Classic','کلاسیک'), 'chrome'=>array('Chrome','کروم'), 'abstract'=>array('Abstract','انتزاعی'), 'minimal'=>array('Minimal','مینیمال'), 'french'=>array('French','فرنچ'), 'sculptural'=>array('Sculptural','حجمی'), 'custom'=>array('Custom','سفارشی') ),
        'nue_service_category' => array( 'manicure'=>array('Manicure','مانیکور'), 'pedicure'=>array('Pedicure','پدیکور'), 'extensions'=>array('Extensions','کاشت و اکستنشن'), 'nail-art'=>array('Nail Art','طراحی ناخن'), 'add-ons'=>array('Add-ons','خدمات تکمیلی'), 'removal'=>array('Removal','ریموو'), 'packages'=>array('Packages','پکیج‌ها') ),
    );
    foreach ( $taxonomies as $taxonomy => $terms ) { foreach ( $terms as $slug => $names ) {
        $term = get_term_by( 'slug', $slug, $taxonomy );
        if ( $term && ! is_wp_error( $term ) && $term->name === $names[0] ) { wp_update_term( $term->term_id, $taxonomy, array( 'name' => $names[1], 'slug' => $slug ) ); }
    } }
}
function nue_starter_normalize( $content ) {
    // The first release used these image descriptions; accept either unedited version.
    return str_replace( array( 'A sheer nude manicure in warm natural light', 'A manicure in progress in a warm, serene studio', 'A thoughtful manicure ritual' ), array( 'Milky white almond-shaped nails', 'A soft pink shimmer manicure', 'Soft pink shimmering manicure' ), trim( $content ) );
}
/** Translate only the untouched single-site WordPress starter post. */
function nue_translate_wordpress_starter_post() {
    $post = get_page_by_path( 'hello-world', OBJECT, 'post' );
    $original = "<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->";
    if ( ! $post || $post->post_title !== 'Hello world!' || $post->post_content !== $original || $post->post_excerpt !== '' ) { return false; }
    $updated = wp_update_post( array( 'ID' => $post->ID, 'post_title' => 'به مجله نیو استودیو خوش آمدید', 'post_content' => '<!-- wp:paragraph --><p>این نخستین نوشته نمونه است. آن را ویرایش یا حذف کنید و داستان خودتان را بنویسید.</p><!-- /wp:paragraph -->' ), true );
    return ! is_wp_error( $updated );
}
/** Leave edited sample pages alone; only replace the exact English core default. */
function nue_translate_wordpress_starter_page() {
    $page = get_page_by_path( 'sample-page' );
    $original = '<!-- wp:paragraph -->' . "\n" . "<p>This is an example page. It's different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>" . "\n" . '<!-- /wp:paragraph -->' . "\n\n" . '<!-- wp:quote -->' . "\n" . "<blockquote class=\"wp-block-quote\"><p>Hi there! I'm a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin' caught in the rain.)</p></blockquote>" . "\n" . '<!-- /wp:quote -->' . "\n\n" . '<!-- wp:paragraph -->' . "\n" . '<p>...or something like this:</p>' . "\n" . '<!-- /wp:paragraph -->' . "\n\n" . '<!-- wp:quote -->' . "\n" . '<blockquote class="wp-block-quote"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>' . "\n" . '<!-- /wp:quote -->' . "\n\n" . '<!-- wp:paragraph -->' . "\n" . '<p>As a new WordPress user, you should go to <a href="' . admin_url() . '">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>' . "\n" . '<!-- /wp:paragraph -->';
    if ( ! $page || $page->post_title !== 'Sample Page' || $page->post_content !== $original || $page->post_excerpt !== '' ) { return false; }
    $updated = wp_update_post( array( 'ID' => $page->ID, 'post_title' => 'صفحه نمونه', 'post_content' => '<!-- wp:paragraph --><p>این یک صفحه نمونه وردپرس است. می‌توانید آن را ویرایش یا حذف کنید و محتوای دلخواه خود را بنویسید.</p><!-- /wp:paragraph -->' ), true );
    return ! is_wp_error( $updated );
}
function nue_migrate_persian_starters() {
    $updated = 0; $skipped = 0; $english = nue_english_page_content(); $persian = nue_page_content();
    foreach ( $persian as $slug => $data ) {
        $page = get_page_by_path( $slug );
        if ( ! $page || ! isset( $english[ $slug ] ) ) { continue; }
        if ( $page->post_title === $data[0] && trim( $page->post_content ) === trim( $data[1] ) ) { continue; }
        if ( $page->post_title !== $english[ $slug ][0] || nue_starter_normalize( $page->post_content ) !== nue_starter_normalize( $english[ $slug ][1] ) ) { $skipped++; continue; }
        // Preserve URLs, featured images, page IDs and existing revisions.
        $result = wp_update_post( array( 'ID' => $page->ID, 'post_title' => $data[0], 'post_content' => $data[1] ), true );
        if ( ! is_wp_error( $result ) ) { $updated++; }
    }
    foreach ( nue_service_seed_data() as $service ) {
        $post = get_page_by_path( $service['slug'], OBJECT, 'nue_service' );
        if ( ! $post || 'nue_service' !== $post->post_type ) { continue; }
        $old_content = '<!-- wp:paragraph --><p>' . esc_html( $service['description_en'] ) . ' Speak with the studio to find the right treatment for you.</p><!-- /wp:paragraph -->';
        if ( $post->post_title === $service['en'] && $post->post_content === $old_content ) {
            wp_update_post( array( 'ID' => $post->ID, 'post_title' => $service['fa'], 'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $service['description_fa'] ) . ' برای انتخاب خدمت مناسب با استودیو مشورت کنید.</p><!-- /wp:paragraph -->' ) );
            if ( get_post_meta( $post->ID, '_nue_short_description', true ) === $service['description_en'] ) { update_post_meta( $post->ID, '_nue_short_description', $service['description_fa'] ); }
        }
    }
    foreach ( array( 'milky-white-nails' => array( 'Milky white manicure', 'مانیکور سفید شیری', 'Milky white almond-shaped nails', 'ناخن‌های بادامی با لاک سفید شیری' ), 'soft-shimmer-nails' => array( 'Soft shimmer manicure', 'مانیکور صورتی درخشان', 'Soft pink almond-shaped nails with a subtle shimmer', 'ناخن‌های بادامی صورتی با درخشش ملایم' ) ) as $slug => $names ) {
        $projects = get_posts( array( 'post_type' => 'nue_project', 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1 ) );
        if ( ! $projects ) { continue; } $project = $projects[0];
        if ( $project->post_title === $names[0] && $project->post_content === '<!-- wp:paragraph --><p>' . $names[2] . '.</p><!-- /wp:paragraph -->' ) { wp_update_post( array( 'ID' => $project->ID, 'post_title' => $names[1], 'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $names[3] ) . '.</p><!-- /wp:paragraph -->' ) ); }
        $attachment = get_post_thumbnail_id( $project->ID );
        if ( $attachment && get_post_meta( $attachment, '_wp_attachment_image_alt', true ) === $names[2] ) { update_post_meta( $attachment, '_wp_attachment_image_alt', $names[3] ); }
    }
    $reviews = get_posts( array( 'post_type'=>'nue_testimonial', 'posts_per_page'=>-1, 'post_status'=>'any', 'meta_key'=>'_nue_sample', 'meta_value'=>'yes' ) );
    foreach ( $reviews as $review ) {
        if ( $review->post_title === 'Example review' && $review->post_content === 'This is an example of where a genuine client review could appear.' ) {
            wp_update_post( array( 'ID'=>$review->ID, 'post_title'=>'دیدگاه نمونه', 'post_content'=>'این یک متن نمونه است؛ دیدگاه واقعی مشتریان پس از دریافت در این بخش منتشر می‌شود.' ) );
            if ( get_post_meta( $review->ID, '_nue_customer_name', true ) === 'Sample attribution' ) { update_post_meta( $review->ID, '_nue_customer_name', 'نام نمونه' ); }
        }
    }
    nue_translate_starter_terms();
    nue_translate_wordpress_starter_post();
    nue_translate_wordpress_starter_page();
    return array( 'updated' => $updated, 'skipped' => $skipped );
}
/** Explicit setup shared by the dashboard button and WP-CLI. */
function nue_apply_persian_setup() {
    if ( get_template() !== 'nue-studio' ) { switch_theme( 'nue-studio' ); }
    update_option( 'WPLANG', 'fa_IR' );
    $result = nue_migrate_persian_starters();
    $result['locale_set'] = 'fa_IR' === get_option( 'WPLANG' );
    update_option( 'nue_persian_setup_done', 1 );
    return $result;
}
add_action( 'admin_post_nue_apply_persian', function () {
    if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'switch_themes' ) ) { wp_die( esc_html__( 'You are not allowed to do this.' ) ); }
    check_admin_referer( 'nue_apply_persian' );
    if ( ! wp_get_theme( 'nue-studio' )->exists() ) { wp_die( 'ابتدا قالب نیو استودیو را نصب کنید.' ); }
    $result = nue_apply_persian_setup();
    set_transient( 'nue_persian_setup_result_' . get_current_user_id(), $result, 120 );
    wp_safe_redirect( admin_url( 'options-general.php?page=nue-studio' ) ); exit;
} );
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'switch_themes' ) ) { return; }
    $wrong_theme = get_template() !== 'nue-studio';
    if ( ! $wrong_theme && get_option( 'nue_persian_setup_done' ) ) {
        if ( 'fa_IR' !== get_option( 'WPLANG' ) ) { echo '<div class="notice notice-warning"><p>بسته زبان فارسی وردپرس نصب یا فعال نیست. برای ترجمه متن‌های پیش‌فرض وردپرس، در تنظیمات عمومی زبان سایت را فارسی انتخاب کنید.</p></div>'; }
        return;
    }
    $url = wp_nonce_url( admin_url( 'admin-post.php?action=nue_apply_persian' ), 'nue_apply_persian' );
    echo '<div class="notice notice-warning"><p><strong>نیو استودیو:</strong> برای نمایش طراحی درست و متن فارسی، قالب نیو استودیو را فعال و محتوای اولیه ترجمه‌نشده را به‌روزرسانی کنید. صفحه‌های ویرایش‌شده، قیمت‌ها و تصاویر شما تغییر نمی‌کنند.</p>';
    if ( wp_get_theme( 'nue-studio' )->exists() ) { echo '<p><a class="button button-primary" href="' . esc_url( $url ) . '">فعال‌سازی قالب و تنظیم محتوای فارسی</a></p>'; }
    else { echo '<p>ابتدا قالب Nue Studio را در مسیر پوسته‌های وردپرس نصب کنید.</p>'; }
    echo '</div>';
} );
