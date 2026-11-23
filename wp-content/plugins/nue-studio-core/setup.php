<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function nue_group( $html, $class = 'content-section' ) { return '<!-- wp:group {"className":"' . esc_attr( $class ) . '","layout":{"type":"constrained"}} --><div class="wp-block-group ' . esc_attr( $class ) . '">' . $html . '</div><!-- /wp:group -->'; }
function nue_p( $text, $class = '' ) { return '<!-- wp:paragraph' . ( $class ? ' {"className":"' . esc_attr( $class ) . '"}' : '' ) . ' --><p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . $text . '</p><!-- /wp:paragraph -->'; }
function nue_h( $text, $level = 2 ) { return '<!-- wp:heading {"level":' . (int) $level . '} --><h' . (int) $level . ' class="wp-block-heading">' . $text . '</h' . (int) $level . '><!-- /wp:heading -->'; }
function nue_block( $name, $attrs = '' ) { return '<!-- wp:nue/' . $name . ( $attrs ? ' ' . $attrs : '' ) . ' /-->'; }
function nue_cta() { return '<!-- wp:pattern {"slug":"nue-studio/booking-cta"} /-->'; }
function nue_link( $url, $label ) { return '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $label ) . ' ↗</a></div><!-- /wp:button --></div><!-- /wp:buttons -->'; }
function nue_image( $name, $alt, $class = '' ) { $url = set_url_scheme( get_theme_root_uri(), wp_parse_url( home_url(), PHP_URL_SCHEME ) ) . '/nue-studio/assets/images/' . $name; return '<!-- wp:image {"sizeSlug":"large","className":"' . esc_attr( $class ) . '"} --><figure class="wp-block-image size-large ' . esc_attr( $class ) . '"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"/></figure><!-- /wp:image -->'; }
function nue_intro( $eyebrow, $title, $description ) { return nue_group( nue_p( $eyebrow . ' ✳', 'eyebrow' ) . nue_h( $title, 1 ) . nue_p( $description, 'intro-copy' ), 'page-intro' ); }
function nue_details_item( $question, $answer ) { return '<!-- wp:details --><details class="wp-block-details"><summary>' . esc_html( $question ) . '</summary>' . nue_p( esc_html( $answer ) ) . '</details><!-- /wp:details -->'; }
function nue_faq_content( $short = false ) {
    $items = array(
        'General' => array( 'What should I expect at my appointment?' => 'Every appointment begins with a conversation about your nails and the look you have in mind. Please contact the studio for current service details.' ),
        'Manicure' => array( 'Which manicure is right for me?' => 'Your artist can help you choose based on your natural nails, lifestyle, and desired finish.' ),
        'Pedicure' => array( 'How should I prepare for a pedicure?' => 'Arrive with clean feet and let your artist know about any concerns before your service.' ),
        'Nail Art' => array( 'Can I bring nail art inspiration?' => 'Absolutely. Bring your ideas or reference photos so your artist can discuss what is possible.' ),
        'Booking' => array( 'How do I book an appointment?' => 'Use our booking page when scheduling is available, or contact the studio for help.' ),
        'Payments' => array( 'Which payment methods are accepted?' => 'Please contact the studio to confirm current payment options before your visit.' ),
        'Cancellation' => array( 'What if I need to change my appointment?' => 'Please review our cancellation policy and contact the studio as soon as possible if your plans change.' ),
        'Aftercare' => array( 'How can I care for my manicure?' => 'Use cuticle oil regularly, wear gloves for household cleaning, and avoid using your nails as tools.' ),
    ); $html = ''; $i = 0; foreach ( $items as $cat => $questions ) { if ( $short && $i++ >= 4 ) { break; } if ( ! $short ) { $html .= nue_h( esc_html( $cat ), 3 ); } foreach ( $questions as $q => $a ) { $html .= nue_details_item( $q, $a ); } } return $html;
}
function nue_page_content() {
    $hero = '<!-- wp:group {"className":"hero","layout":{"type":"constrained"}} --><div class="wp-block-group hero"><!-- wp:columns {"className":"hero-columns","verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center hero-columns"><!-- wp:column {"verticalAlignment":"center","width":"48%"} --><div class="wp-block-column is-vertically-aligned-center" style="flex-basis:48%">' . nue_p( 'WELCOME TO NUE STUDIO ✳', 'eyebrow' ) . nue_h( 'Manicure And Pedicure <em>Nail Salon</em>', 1 ) . nue_p( 'A little time for yourself, a lot of love for your nails. Discover thoughtful nail care and artistry, made just for you.', 'hero-copy' ) . nue_link( '/booking/', 'Book your appointment' ) . '<!-- wp:paragraph {"className":"hero-note"} --><p class="hero-note">GOOD NAILS. GOOD MOOD. &nbsp; ✳</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"width":"52%"} --><div class="wp-block-column" style="flex-basis:52%">' . nue_image( 'hero.jpg', 'Milky white almond-shaped nails' , 'hero-image' ) . '</div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->';
    $intro = '<!-- wp:columns {"className":"intro-split","verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center intro-split"><!-- wp:column --><div class="wp-block-column">' . nue_image( 'studio.jpg', 'A soft pink shimmer manicure' , 'intro-image' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . nue_p( 'A LITTLE ABOUT US ✳', 'eyebrow' ) . nue_h( 'A beautiful pause, <em>just for you.</em>' ) . nue_p( 'Welcome to a space where beauty feels unhurried. At Nue Studio, we believe the smallest rituals can make the biggest difference.' ) . nue_p( 'From thoughtful nail care to the finishing details, everything we do is about helping you feel like yourself — only a little more polished.' ) . nue_link( '/about/', 'Get to know us' ) . '</div><!-- /wp:column --></div><!-- /wp:columns -->';
    $home = $hero . nue_group( $intro, 'content-section intro-section' ) . nue_group( nue_p( 'THE DETAILS MAKE THE DIFFERENCE ✳', 'eyebrow' ) . nue_h( 'A little nail <em>inspiration.</em>' ) . nue_p( 'A canvas for self-expression, right at your fingertips.' ) . nue_block( 'gallery', '{"limit":4}' ) . nue_link( '/nail-art/', 'Explore the gallery' ), 'content-section gallery-section' ) . nue_group( nue_p( 'MADE FOR YOUR MOMENT ✳', 'eyebrow' ) . nue_h( 'The art of <em>being you.</em>' ) . nue_p( 'From the essentials to something extraordinary, find your perfect treatment.' ) . nue_block( 'services', '{"limit":3}' ) . nue_link( '/services/', 'Explore all services' ), 'content-section services-section' ) . nue_group( nue_p( 'THE PEOPLE BEHIND THE POLISH ✳', 'eyebrow' ) . nue_h( 'Meet your <em>artists.</em>' ) . nue_p( 'Thoughtful hands, creative minds, and a shared love for the little details.' ) . nue_block( 'team', '{"limit":3}' ) . nue_link( '/team/', 'Meet the team' ), 'content-section team-section' ) . nue_group( nue_p( 'THE NUE DIFFERENCE ✳', 'eyebrow' ) . nue_h( 'Good nails, <em>good energy.</em>' ) . '<!-- wp:columns {"className":"values-grid"} --><div class="wp-block-columns values-grid"><!-- wp:column --><div class="wp-block-column">' . nue_p( '01 / ✳', 'eyebrow' ) . nue_h( 'Quality', 3 ) . nue_p( 'Considered products and careful attention to every detail.' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . nue_p( '02 / ✳', 'eyebrow' ) . nue_h( 'Friendly', 3 ) . nue_p( 'A welcoming space to relax, connect, and feel at home.' ) . '</div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column">' . nue_p( '03 / ✳', 'eyebrow' ) . nue_h( 'Fast', 3 ) . nue_p( 'Considered service that respects your time without overlooking the details.' ) . '</div><!-- /wp:column --></div><!-- /wp:columns -->', 'content-section values-section' ) . nue_group( nue_p( 'KIND WORDS ✳', 'eyebrow' ) . nue_h( 'The love is <em>mutual.</em>' ) . nue_block( 'testimonials', '{"limit":3}' ) . nue_link( '/testimonials/', 'Read more' ), 'content-section review-section' ) . nue_group( nue_p( 'A LITTLE SOMETHING FOR SOMEONE ✳', 'eyebrow' ) . nue_h( 'Give the gift of <em>me time.</em>' ) . nue_p( 'Because a little time for yourself is always a beautiful gift.' ) . nue_link( '/gift-cards/', 'Explore gift cards' ), 'gift-promo' ) . nue_group( nue_p( 'GOOD TO KNOW ✳', 'eyebrow' ) . nue_h( 'Frequently asked <em>questions.</em>' ) . nue_faq_content( true ) . nue_link( '/faq/', 'View all questions' ), 'content-section faq-section' ) . nue_cta();
    $pages = array(); $pages['home'] = array( 'Home', $home );
    $pages['services'] = array( 'Services', nue_intro( 'THE SERVICE MENU', 'Care, down to <em>the details.</em>', 'Considered treatments for healthy, beautiful nails. Find the service that feels like you.' ) . nue_group( nue_block( 'services' ) ) . nue_cta() );
    $pages['pricing'] = array( 'Pricing', nue_intro( 'THE MENU', 'A little care, <em>beautifully priced.</em>', 'Explore our service menu. Prices and timing are confirmed by the studio when you book.' ) . nue_group( nue_block( 'pricing' ) . nue_h( 'Packages &amp; add-ons' ) . nue_p( 'Ask the studio about available packages and personalized add-ons. Pricing will be confirmed before your appointment.' ) ) . nue_cta() );
    $pages['nail-art'] = array( 'Nail Art', nue_intro( 'THE NAIL ART EDIT', 'Little works <em>of art.</em>', 'A collection of details, textures, and ideas to inspire your next appointment.' ) . nue_group( nue_block( 'gallery' ) ) . nue_cta() );
    $pages['about'] = array( 'About Us', nue_intro( 'OUR STORY', 'Beauty in the <em>little things.</em>', 'A space to slow down, feel good, and leave a little more like yourself.' ) . nue_group( $intro . nue_h( 'Our philosophy' ) . nue_p( 'We believe nail care is more than a finishing touch. It is a small ritual of self-expression and a moment to reconnect with yourself.' ) . nue_h( 'Our approach' ) . nue_p( 'Every set begins with listening. We make space for your ideas, your natural nails, and the details that matter to you.' ) . nue_h( 'Care, quality &amp; hygiene' ) . nue_p( 'Thoughtful care includes a clean environment and a careful approach to every service. Ask the studio about its current hygiene practices and products.' ) . nue_h( 'Our values' ) . nue_p( 'Kindness, creativity, care, and attention to the details. Always.' ) . nue_h( 'Meet the team' ) . nue_block( 'team', '{"limit":3}' ) . nue_link( '/team/', 'Our artists' ) ) . nue_cta() );
    $pages['team'] = array( 'Our Team', nue_intro( 'THE PEOPLE BEHIND THE POLISH', 'Good hands, <em>good hearts.</em>', 'Meet the artists bringing care and creativity to every appointment.' ) . nue_group( nue_block( 'team' ) ) . nue_cta() );
    $pages['booking'] = array( 'Booking', nue_intro( 'YOUR MOMENT AWAITS', 'Make time <em>for you.</em>', 'Choose your service, find a time that works, and let us take care of the rest.' ) . nue_group( nue_h( 'Book an appointment' ) . nue_p( 'Select a service in the scheduler, choose an available time, and follow the prompts to confirm your appointment.' ) . nue_block( 'booking' ) . nue_h( 'Before you book' ) . nue_p( 'Please review the current cancellation and rescheduling policy before booking. Contact the studio if you need help choosing a service or changing an appointment.' ) . nue_link( '/cancellation-policy/', 'Cancellation policy' ) . nue_h( 'Questions?' ) . nue_block( 'contact-info' ) ) );
    $pages['gift-cards'] = array( 'Gift Cards', nue_intro( 'A LITTLE SOMETHING FOR SOMEONE', 'Give a little <em>me time.</em>', 'The most thoughtful gift? A moment to slow down and feel cared for.' ) . nue_group( nue_image( 'studio.jpg', 'Soft pink shimmering manicure' , 'wide-image' ) . nue_h( 'A gift that feels good.' ) . nue_p( 'Treat someone special to a little ritual of their own. Contact the studio for available denominations and details.' ) . nue_block( 'gift-link' ) . nue_h( 'How it works' ) . nue_p( 'Choose a gift card when purchasing is available, share it with someone you love, and let them choose their moment. The studio will provide redemption instructions.' ) . nue_h( 'Terms &amp; conditions' ) . nue_p( 'Gift card terms, expiration, and redemption conditions are to be confirmed by the studio before purchase.' ) ) . nue_cta() );
    $pages['contact'] = array( 'Contact', nue_intro( 'SAY HELLO', 'We would love to <em>hear from you.</em>', 'Questions, ideas, or just want to say hello? Get in touch with the studio.' ) . nue_group( nue_h( 'Find us' ) . nue_block( 'contact-info' ) . nue_p( 'Map and location details will be added once confirmed by the studio.' ) . nue_h( 'Send us a note' ) . nue_p( 'Fill in the form below and we will get back to you as soon as we can.' ) . nue_block( 'contact-form' ) ) . nue_cta() );
    $pages['faq'] = array( 'FAQ', nue_intro( 'GOOD TO KNOW', 'Your questions, <em>answered.</em>', 'Everything you might like to know before settling into your appointment.' ) . nue_group( nue_faq_content() . nue_p( 'Still have a question? We are here to help.' ) . nue_link( '/contact/', 'Contact us' ) ) . nue_cta() );
    $pages['testimonials'] = array( 'Testimonials', nue_intro( 'KIND WORDS', 'The love is <em>mutual.</em>', 'A little appreciation for the people who make this studio special.' ) . nue_group( nue_h( 'Words from our community' ) . nue_block( 'testimonials' ) . nue_p( 'Real client reviews will be shared here when supplied and approved.' ) ) . nue_cta() );
    $legal = array( 'privacy-policy' => array( 'Privacy Policy', 'How information is collected, used, stored, and shared, including contact form submissions and third-party booking, must be reviewed and completed by the studio.' ), 'terms-and-conditions' => array( 'Terms and Conditions', 'Service terms, acceptable website use, payment conditions, and other business-specific terms must be reviewed and completed by the studio.' ), 'cancellation-policy' => array( 'Cancellation Policy', 'Cancellation windows, rescheduling rules, late arrivals, no-shows, and any applicable fees must be confirmed and added by the studio.' ), 'refund-policy' => array( 'Refund Policy', 'Refund eligibility, procedures, gift card terms, and applicable exceptions must be confirmed and added by the studio.' ) );
    foreach ( $legal as $slug => $data ) { $pages[ $slug ] = array( $data[0], nue_intro( 'THE DETAILS', esc_html( $data[0] ), 'Important information for our community.' ) . nue_group( nue_p( 'EDITABLE PLACEHOLDER — not legal advice.', 'eyebrow' ) . nue_h( 'Policy information' ) . nue_p( esc_html( $data[1] ) ) . nue_h( 'Questions about this policy?' ) . nue_p( 'Please contact the studio for current information.' ) . nue_link( '/contact/', 'Contact us' ) ) ); }
    $pages['blog'] = array( 'Journal', '' ); return $pages;
}
/** Import the supplied photographs once; preserve any existing projects and edits. */
function nue_import_supplied_photos() {
    $photos = array(
        array( 'hero.jpg', 'Milky white manicure', 'Milky white almond-shaped nails', 'Minimal', 'milky-white-nails' ),
        array( 'studio.jpg', 'Soft shimmer manicure', 'Soft pink almond-shaped nails with a subtle shimmer', 'Classic', 'soft-shimmer-nails' ),
    );
    $ids = array();
    foreach ( $photos as $index => $photo ) {
        $matches = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_nue_supplied_photo', 'meta_value' => $photo[0], 'fields' => 'ids' ) );
        $attachment = $matches ? (int) $matches[0] : 0;
        if ( ! $attachment ) {
            $file = get_theme_root() . '/nue-studio/assets/images/' . $photo[0];
            if ( ! is_readable( $file ) ) { continue; }
            $upload = wp_upload_bits( 'nue-' . $photo[0], null, file_get_contents( $file ) );
            if ( ! empty( $upload['error'] ) ) { continue; }
            $attachment = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => $photo[1], 'post_status' => 'inherit' ), $upload['file'] );
            if ( ! $attachment || is_wp_error( $attachment ) ) { continue; }
            require_once ABSPATH . 'wp-admin/includes/image.php';
            wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $upload['file'] ) );
            update_post_meta( $attachment, '_wp_attachment_image_alt', $photo[2] );
            update_post_meta( $attachment, '_nue_supplied_photo', $photo[0] );
        }
        $ids[$index] = $attachment;
        $slug = $photo[4];
        $existing_projects = get_posts( array( 'post_type' => 'nue_project', 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
        if ( ! $existing_projects ) {
            $project = wp_insert_post( array( 'post_type' => 'nue_project', 'post_name' => $slug, 'post_title' => $photo[1], 'post_status' => 'publish', 'menu_order' => -20 + $index, 'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $photo[2] ) . '.</p><!-- /wp:paragraph -->' ) );
            if ( $project && ! is_wp_error( $project ) ) { set_post_thumbnail( $project, $attachment ); wp_set_object_terms( $project, $photo[3], 'nue_art_category' ); } else { unset( $ids[$index] ); }
        }
    }
    if ( count( $ids ) === count( $photos ) ) { update_option( 'nue_supplied_photos_v2', 1 ); }
    return $ids;
}
// Existing starter installations receive new gallery items without changing
// previously created posts, photos, service prices or owner-edited pages.
add_action( 'init', function () {
    if ( get_option( 'nue_seeded_v1' ) && ! get_option( 'nue_supplied_photos_v2' ) ) { nue_import_supplied_photos(); }
}, 100 );
function nue_activate() {
    nue_register_content();
    // Only configure the front page and default permalinks automatically for a
    // genuinely fresh site, not an existing site that happens to show posts.
    $initial_posts = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'posts_per_page' => 2 ) );
    $initial_pages = get_posts( array( 'post_type' => 'page', 'post_status' => array( 'publish', 'private', 'future' ), 'posts_per_page' => 2 ) );
    $fresh_posts = ! $initial_posts || ( count( $initial_posts ) === 1 && 'hello-world' === $initial_posts[0]->post_name );
    $fresh_pages = ! $initial_pages || ( count( $initial_pages ) === 1 && 'sample-page' === $initial_pages[0]->post_name );
    $fresh_site = $fresh_posts && $fresh_pages;
    if ( ! get_option( 'nue_seeded_v1' ) ) {
        foreach ( nue_page_content() as $slug => $data ) {
            $existing = get_page_by_path( $slug );
            if ( ! $existing ) {
                $id = wp_insert_post( array( 'post_type' => 'page', 'post_title' => $data[0], 'post_name' => $slug, 'post_content' => $data[1], 'post_status' => 'publish' ) );
            } else {
                $id = $existing->ID;
                // A new WordPress installation includes a draft privacy policy. Replace only the
                // untouched core-generated draft, never an owner's edited policy.
                if ( 'privacy-policy' === $slug && 'draft' === $existing->post_status && (int) get_option( 'wp_page_for_privacy_policy' ) === $id ) {
                    require_once ABSPATH . 'wp-admin/includes/class-wp-privacy-policy-content.php';
                    if ( $existing->post_content === WP_Privacy_Policy_Content::get_default_content() ) {
                        wp_update_post( array( 'ID' => $id, 'post_content' => $data[1], 'post_status' => 'publish' ) );
                    }
                }
            }
            if ( is_wp_error( $id ) || ! $id ) { continue; }
            if ( 'home' === $slug && $fresh_site && 'page' !== get_option( 'show_on_front' ) ) { update_option( 'show_on_front', 'page' ); update_option( 'page_on_front', $id ); }
            if ( 'blog' === $slug && $fresh_site && ! get_option( 'page_for_posts' ) ) { update_option( 'page_for_posts', $id ); }
        }
        foreach ( array( 'Classic', 'Chrome', 'Abstract', 'Minimal', 'French', 'Sculptural', 'Custom' ) as $term ) { if ( ! term_exists( $term, 'nue_art_category' ) ) { wp_insert_term( $term, 'nue_art_category' ); } }
        $service_categories = array( 'Manicure', 'Pedicure', 'Extensions', 'Nail Art', 'Add-ons', 'Removal', 'Packages' ); foreach ( $service_categories as $term ) { if ( ! term_exists( $term, 'nue_service_category' ) ) { wp_insert_term( $term, 'nue_service_category' ); } }
        $photo_ids = nue_import_supplied_photos();
        $services = array( array( 'Classic Manicure', 'Manicure', 'A considered essential for beautifully cared-for natural nails.' ), array( 'Gel Manicure', 'Manicure', 'A glossy, durable finish with meticulous nail care.' ), array( 'Builder Gel', 'Extensions', 'Added structure and strength with a natural-looking finish.' ), array( 'Hard Gel Extensions', 'Extensions', 'Custom length and shape, thoughtfully sculpted.' ), array( 'Nail Art', 'Nail Art', 'A little self-expression, created just for you.' ), array( 'Pedicure', 'Pedicure', 'A restorative ritual for polished, cared-for feet.' ), array( 'Gel Pedicure', 'Pedicure', 'A considered pedicure with a lasting gel finish.' ), array( 'Nail Repair', 'Add-ons', 'A little extra care for a nail that needs attention.' ), array( 'Removal', 'Removal', 'Gentle removal with the health of your natural nails in mind.' ) );
        foreach ( $services as $index => $s ) { if ( get_page_by_path( sanitize_title( $s[0] ), OBJECT, 'nue_service' ) ) { continue; } $id = wp_insert_post( array( 'post_type' => 'nue_service', 'post_title' => $s[0], 'post_status' => 'publish', 'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $s[2] ) . ' Speak with the studio to find the right treatment for you.</p><!-- /wp:paragraph -->' ) ); if ( $id && ! is_wp_error( $id ) ) { update_post_meta( $id, '_nue_short_description', $s[2] ); wp_set_object_terms( $id, $s[1], 'nue_service_category' ); if ( ! empty( $photo_ids[ $index % 2 ] ) ) { set_post_thumbnail( $id, $photo_ids[ $index % 2 ] ); } } }
        $review = wp_insert_post( array( 'post_type' => 'nue_testimonial', 'post_title' => 'Example review', 'post_status' => 'publish', 'post_content' => 'This is an example of where a genuine client review could appear.' ) );
        if ( $review && ! is_wp_error( $review ) ) { update_post_meta( $review, '_nue_customer_name', 'Sample attribution' ); update_post_meta( $review, '_nue_sample', 'yes' ); }
        update_option( 'nue_seeded_v1', 1 );
    }
    // Use the requested journal URLs on an untouched installation, without changing
    // established sites' permalinks (which would require redirects).
    $structure = get_option( 'permalink_structure' );
    if ( ! $structure || ( $fresh_site && in_array( $structure, array( '/%postname%/', '/%year%/%monthnum%/%day%/%postname%/' ), true ) ) ) {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/blog/%postname%/' );
    }
    // Core taxonomies were registered before the structure changed in this request.
    // Flush again on the next request after all taxonomy permastructs are rebuilt.
    update_option( 'nue_flush_rewrite_pending', 1 );
    flush_rewrite_rules();
}
add_action( 'init', function () {
    if ( get_option( 'nue_flush_rewrite_pending' ) ) {
        delete_option( 'nue_flush_rewrite_pending' );
        flush_rewrite_rules( false );
    }
}, 99 );
register_deactivation_hook( __FILE__, function () { flush_rewrite_rules(); } );
