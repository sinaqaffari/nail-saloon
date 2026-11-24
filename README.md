# Nue Studio WordPress site

This repository contains an installable WordPress block theme and its companion plugin. It includes an optional Docker Compose configuration for local WordPress and MariaDB; it does not vendor WordPress or provide production hosting. No visual references were present in the repository; the implementation follows the written design brief. The hero and pink manicure photographs were supplied by the user. Two older, clearly labeled sample gallery assets are AI-generated; new installations use the supplied photographs as the starter gallery instead.

## Local development with Docker Compose

Requirements: Docker Engine and Docker Compose v2. This stack is intended for local development, not a production deployment. Existing WordPress files live in a volume, so changing the image tag alone does not upgrade an already-installed WordPress site; use the WordPress upgrade process after taking a backup.

1. Copy the example configuration and create **two different strong database passwords** in `.env` (do not commit it):
   ```sh
   cp .env.example .env
   # Edit .env and replace both password values.
   chmod 600 .env
   docker compose up -d
   ```
   The default image tracks the current official WordPress PHP 8.3 Apache tag; set `WORDPRESS_IMAGE` to a specific compatible tag if you want to pin it. Test upgrades before updating an existing site. The database must become healthy and WordPress must finish initializing before the site is ready; inspect `docker compose logs -f wordpress db` if needed. The default address is **http://localhost:8080**. Change `WORDPRESS_PORT` if that port is in use. The published port binds to `127.0.0.1` by default; to make a development instance reachable from other machines, explicitly set `WORDPRESS_BIND_ADDRESS=0.0.0.0` and protect access appropriately.
2. Open the address in your browser and complete the WordPress installation wizard, choosing your own administrator username, password, and email. Log in, then activate **Nue Studio** under Appearance → Themes and **Nue Studio Core** under Plugins, in that order. The plugin creates the initial site content on first activation. Alternatively, after the wizard and initialization, activate them with:
   ```sh
   docker compose --profile tools run --rm wpcli theme activate nue-studio
   docker compose --profile tools run --rm wpcli plugin activate nue-studio-core
   ```
3. Visit **Settings → Permalinks** and save if pretty URLs do not work, then configure the studio under **Settings → Nue Studio**. The WordPress admin, media uploads, pages and edits use the named `wordpress_data` volume; MariaDB uses `db_data`. Theme and plugin source directories are mounted read-only from this checkout. Gutenberg and the Site Editor save edits in the database; edit theme/plugin files in the repository rather than via the WordPress file editor.

`docker compose down` stops the containers and preserves the named volumes. **`docker compose down -v` deletes the database and uploaded files**, so use it only when intentionally resetting the site. Back up both volumes before destructive operations. MariaDB uses the `.env` database credentials when the database volume is first initialized: changing them later in `.env` does not change existing MariaDB accounts. Update credentials inside MariaDB as well, or reset the volume only if its data may be discarded.

For production, provision HTTPS, backups, proper mail delivery, monitoring and secret management, and review hosting/security configuration. This Compose file does not configure TLS, a mail server, or real booking/payment credentials.

## Installation

1. Install WordPress 6.4 or newer with PHP 7.4+ and a supported database, HTTPS, and pretty permalinks. Back up an existing site before activating the plugin: first activation creates content; on a fresh installation it selects the new Home and Journal as the front and posts pages. Existing sites retain their Reading settings. It sets `/blog/%postname%/` when no permalink structure is configured or when a fresh installation has only the default post/page and a standard default permalink structure. On an existing site, review **Settings → Reading** and **Settings → Permalinks** after activation. For `/blog/{post-slug}/`, use `/blog/%postname%/` (changing an existing structure requires redirects).
2. Copy `wp-content/themes/nue-studio` and `wp-content/plugins/nue-studio-core` into the corresponding directories of the WordPress installation. Activate **Nue Studio** under Appearance → Themes, then **Nue Studio Core** under Plugins. Activation creates the pages, the nine example service entries, categories, two gallery projects using the supplied photographs, and a labeled sample review. Existing pages with matching slugs are not overwritten, except that an untouched WordPress-generated privacy-policy draft is replaced with the labeled policy placeholder and published; edited drafts are preserved and need publishing manually. It only seeds once; deactivation does not delete content.
3. Set the site title and logo under Appearance → Editor, and customize the Navigation, Header and Footer template parts there. Edit page content using Pages and Gutenberg. The theme assumes a root-domain installation for the default navigation and footer links; update those links in the Site Editor for a subdirectory install.
4. In **Settings → Nue Studio**, supply verified business address, phone, email, opening hours, social links and map link. The contact form remains unavailable until a valid contact email is configured. WordPress `wp_mail` must be configured and tested on the host; a submission reports success only when `wp_mail` accepts it, not when delivery is guaranteed. Add a reputable mail delivery or anti-spam plugin if appropriate for production.
5. Edit the service entries to add actual prices, durations, details, images and appointment-specific Acuity URLs. Add the real team, portfolio and approved reviews; remove the explicitly labeled examples. Replace sample imagery via the Media Library or page editor. Review every FAQ and legal policy with the business and qualified counsel before launch. No prices, business details, credentials or real reviews are asserted by the starter content.

## Acuity and gift cards

In **Settings → Nue Studio**, paste the real HTTPS Acuity Scheduling URL (an `acuityscheduling.com` or `as.me` host). The Booking block embeds that URL and provides a fallback link. A service may optionally have its own Acuity appointment URL in its details; otherwise its button goes to the booking page. The plugin does not require or accept API credentials. Verify the scheduler and its third-party cookies/embedding behavior on the live domain and on mobile. A gift-card provider URL can also be added in settings; until then the CTA explicitly says purchasing is not configured. Cancellation, rescheduling, and gift-card terms must be filled in by the studio.

## Content and editing

- **Pages:** Home, Services, Nail Art, About, Team, Booking, Pricing, Gift Cards, Journal, Contact, FAQ, Testimonials, Privacy Policy, Terms and Conditions, Cancellation Policy and Refund Policy. Individual services, projects, artists and native blog posts have detail pages; WordPress uses the 404 template for missing URLs.
- **Templates:** `front-page`, `page`, `home` (blog), `index`, `archive`, `search`, `single`, `single-nue_service`, `single-nue_project`, `single-nue_artist`, `404`; reusable header and footer parts.
- **Content types:** Services, Nail Art Projects, Team Members, Testimonials. The main Gutenberg editor stores full descriptions/biographies/review text and featured images; the details meta box stores service price, duration, short description, benefits, included items, preparation, aftercare and appointment URL; project gallery image IDs and associated artist ID; artist position, specialties, experience, gallery image IDs and social links; review attribution, rating and sample flag. Service and nail art categories are native taxonomies. Find attachment IDs in the Media Library and artist IDs in the Team Members admin URLs. Projects and team profiles accept comma-separated media IDs.
- **Patterns:** Booking call to action, Editorial section heading, FAQ accordion item. Dynamic Gutenberg blocks render service cards, an editorial service list and category-based pricing cards from the same data, gallery and filters, team, reviews and an accessible review carousel, booking, contact information and form, gift link, detail pages, article sharing, related and featured posts. Native Details blocks provide accessible FAQ disclosures. Site Editor and Gutenberg can edit the surrounding content, style settings, navigation and reusable parts.
- **Dependencies:** WordPress only; Noto Naskh Arabic, Vazirmatn, Cormorant Garamond and DM Sans fonts are self-hosted under their included OFL licenses. No page builder, frontend framework, font CDN, or scheduling replacement. A mail transport plugin may be needed on the host. Use a dedicated SEO plugin if Open Graph tags and richer SEO controls are desired; the theme avoids duplicating plugin metadata and the core plugin emits basic NailSalon JSON-LD on the homepage.

## Validation and launch checklist

The theme and plugin were activated on a disposable WordPress Playground installation (WordPress 6.8.3, PHP 8.3, SQLite). The HTTP smoke test checked 19 page/detail routes, root and service 404s, and 29 linked internal paths. The disposable Playground preview needs a local-only routing shim for unmatched pretty URLs; it is not part of the plugin or theme. Seeded pages parsed without invalid blocks in Gutenberg; the Site Editor opened. Browser smoke tests at 360, 390, 768, 1024 and 1440px found no horizontal overflow on the tested pages or frontend console errors. Mobile navigation, gallery filtering and the lightbox were exercised. A REST edit of service price and duration was reflected in both pricing and service detail pages. A test-only Acuity URL appeared in the embed and fallback link, and an unconfigured mail transport returned an error rather than a false success. PHP files were linted with PHP WASM, JavaScript was syntax-checked with Node, and `theme.json` was parsed as JSON. These checks do not establish production readiness: verify the real Acuity account, email delivery, deployment routing (including root-level 404s), accessibility, SEO, and real content on the intended host. No actual scheduling or payment credentials were supplied. The sample review is not a real customer review.

A dependency-free interaction smoke test can be run with `node tests/interactions.js`; it checks gallery filtering and lightbox open/close/focus behavior with simulated DOM objects, not an actual browser.

With an installed, seeded WordPress site, run `python3 tests/wordpress-smoke.py https://your-site.example` for route/link smoke checks. This expects the starter service, supplied-photo art project and Hello World post to still exist.

## Persian / RTL refresh on an existing local installation

The screenshot of a narrow, unstyled-looking page is consistent with a different theme being active; confirm that **both** Nue Studio (Appearance → Themes) and Nue Studio Core (Plugins) are active. The theme loads `assets/css/site.css` and `assets/css/editorial.css` directly (there is no separate `site-rtl.css` to install), and its page language/direction and Persian typography are part of the theme. Noto Naskh Arabic (display headings) and Vazirmatn (body text) are self-hosted with their OFL licenses. Your site title remains editable under Settings → General.

To update the files in an existing Docker checkout, first back up the database and uploads, then obtain the revision containing this refresh (it must be committed and published by the repository owner before `git pull` can retrieve it). With that revision available on the current branch:

```sh
cd /path/to/nail-saloon
git pull --ff-only
docker compose up -d
```

Do **not** use `docker compose down -v`; that deletes the database and uploads. Open the WordPress dashboard, activate **Nue Studio Core** if needed, then choose **«فعال‌سازی قالب و تنظیم محتوای فارسی»** in its dashboard notice or Settings → Nue Studio. An administrator with permission to switch themes must run this once. It activates the Nue Studio theme, tries to set the site language to Persian (WordPress requires the Persian language pack to be installed), and updates only recognizable, unedited English starter pages, services, sample projects, sample review, the untouched WordPress Hello World post and Sample Page, and starter categories. It keeps existing URLs, prices, custom content and images. Pages that were edited (including even minor edits) are skipped and should be translated manually in Pages; the result displays a count. WordPress revisions/backups are recommended before migration. WordPress may need to download the Persian language pack for its own admin controls and built-in labels to be translated. Edited WordPress sample content and user-created posts are not changed; edit, translate, or remove those manually.

The Site Editor stores customized templates, template parts, navigation and global styles in the database, which can override the updated theme files. Existing customizations are **never automatically deleted**. If the old header/footer or styles persist, review Appearance → Editor → Patterns / Template Parts and Styles. Export or back up your customizations first, then deliberately reset only the relevant customized part or global styles to the theme default, or translate them manually. On an existing site also check Settings → Reading (select the intended Home and Journal pages) and save Settings → Permalinks if links fail. Clear caches and hard-refresh the browser after updating.

The Persian text, terms, contact details, legal placeholders, services, prices, Acuity URL and gift-card provider still need the owner's review before launch. Existing original-language custom pages and posts cannot safely be translated automatically without overwriting edits.


### Diagnose or apply the design from Docker

If the site still looks like the old English layout, back up the database and uploads first. From the exact checkout mounted by Compose, check the running installation (these commands do not delete volumes):

```sh
cd /path/to/nail-saloon
git log -1 --oneline
docker compose up -d
docker compose --profile tools run --rm wpcli plugin activate nue-studio-core
docker compose --profile tools run --rm wpcli nue status
```

`nue status` reports the active theme, actual WordPress URL, selected front page, stylesheet availability, and saved Site Editor template/part overrides. If needed, this explicit command activates the theme and translates only recognizable, unchanged starter content; it also **changes the Reading settings** to use the Nue Home and Journal pages:

```sh
docker compose --profile tools run --rm wpcli nue apply --set-reading-pages
```

Omit `--set-reading-pages` to preserve your current Reading settings. Existing templates, parts, global styles and content edits are never reset automatically. If status reports saved Site Editor overrides, export or back them up and review them in Appearance → Editor; only reset a customization if you intend to replace it. If the WordPress Persian language pack is not installed, WordPress rejects setting `fa_IR` and the setup command warns you. To install it (requires network access):

```sh
docker compose --profile tools run --rm wpcli language core install fa_IR --activate
```

Hard-refresh the site URL reported by `nue status` and clear any cache. If the result is still wrong, share the status output and a screenshot; avoid sharing passwords, keys, or `.env` contents. The Compose/WP-CLI workflow was not runtime-tested in this sandbox because Docker is unavailable.

Design direction: the Persian editorial layout uses soft white, dusty rose, fine rules, an asymmetric split hero, Noto Naskh Arabic display headings and Vazirmatn body text. The two supplied photographs remain in use; see version 1.5 below for explicitly labelled concept-image fallbacks. Owner-supplied service photos remain visible. We reviewed English-language nail-studio design references such as [Paintbox in Colorlib’s roundup](https://colorlib.com/wp/nail-salon-website-examples/) for editorial hierarchy, but did not copy a site or import its business details.


## Persian editorial homepage (version 1.4)

The new homepage contains an editable two-image hero, a pausable services marquee, service rows from existing service entries, a filterable/lightbox gallery, four editable process steps, category-based price cards from real service metadata, team profiles, a review carousel, the Acuity booking call to action, and six editable FAQ disclosures. Other pages retain their existing Gutenberg structure and adopt the shared light/rose design. The marquee and multi-review carousel pause on request and respect reduced-motion settings; content remains available without JavaScript.

No external example names, prices, ratings, guarantees, addresses, hours, artist biographies or fabricated customer reviews were copied from the design brief. The carousel displays existing reviews (including the visibly labelled sample until you remove it); pricing cards display configured prices or a placeholder. Add the actual studio artists, approved reviews, services, prices and gallery photographs in WordPress. The two supplied photographs remain in use; see version 1.5 below for labelled illustrative gallery fallbacks.

For an existing installation, back up first and use the **فعال‌سازی قالب و تنظیم محتوای فارسی** button or `wp nue apply` as above. The homepage is replaced with this new starter layout **only if it exactly matches a recognized, unedited original English or previous Persian starter** (apart from harmless self-closing-tag formatting). Edited pages, prices, media, saved Site Editor templates and global styles are not reset. If the homepage was edited, use the Gutenberg editor to adapt it manually; compare or restore a revision only after backing up. Verify the Reading settings and the active theme; a saved Site Editor template or part can override the updated theme. Do not use `docker compose down -v`.

## Image-led editorial refresh (version 1.5)

The homepage now fills available gallery slots (up to 12 total projects) with ten **AI-generated concept images**, each clearly labelled as illustrative, not actual studio work. The supplied photographs and real published projects come first. When there are no published artists, clearly marked example profile cards use illustrative nail imagery, not fictional people. Once real artist entries are added, they replace those example cards. All prices continue to come from the editable service records; no prices or real reviews are invented.

To remove the concept images and profile placeholders, uncheck **تصاویر مفهومی نمونه** in Settings → Nue Studio and save. Add real work under Nail Art / Projects with a featured image and category; add actual artists with their own featured images, biographies and details under Artists. The images are bundled in the theme, not imported into the Media Library; they are temporary presentation fallbacks, not editable project records. All surrounding sections remain editable in Gutenberg. The gallery uses responsive varied-size tiles; filter, lightbox, no-JavaScript content and reduced-motion behavior remain supported.

Existing installations need the new theme **and** plugin files. Back up the database and uploads, run `git pull --ff-only` on this branch and `docker compose up -d`, then clear caches and hard-refresh. Because these changes are mostly dynamic block output and CSS, an unchanged 1.4 homepage receives them without a content migration; a different/custom homepage, saved Site Editor overrides, or a disabled core plugin may need manual attention. See `wp nue status` and the troubleshooting steps above. Do not remove Docker volumes.
