=== MedSpa Starter ===

Tags: medical-spa, wellness, health, blog, custom-colors, custom-logo, custom-menu, featured-images, footer-widgets, full-width-template, rtl-language-support, theme-options, two-columns, block-patterns, dark-mode
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

MedSpa Starter is a modern WordPress theme purpose-built for medical spas, wellness clinics, and aesthetics studios. It uses TailwindCSS v4 for styling, Vite for the build system, and ships with 15 Gutenberg block patterns covering every section a medical spa website needs — hero, services, treatments, before/after, team, testimonials, pricing, FAQ, and booking CTAs.

Features:
- TailwindCSS v4 utility-first styling
- 15 med-spa-specific Gutenberg block patterns
- Teal + Gold luxury wellness colour palette
- Playfair Display headings + Inter body type
- Dark mode (class-based, localStorage persistent)
- WCAG 2.2 AA accessible
- Schema.org MedicalOrganization + Physician + Service structured data
- RTL support via Tailwind rtl: variants
- WooCommerce and Jetpack compatible
- No jQuery, no Bootstrap, no icon fonts

== Installation ==

1. Upload the theme folder to /wp-content/themes/
2. Activate via Appearance > Themes
3. For development: cd into the theme folder and run `npm install` then `npm run dev`

== Build ==

Requires Node 20+.

npm install       — install dev dependencies
npm run dev       — start Vite dev server
npm run build     — compile CSS + JS to build/

== Frequently Asked Questions ==

= How do I generate the translation file? =

Run `wp i18n make-pot . languages/medspastarter.pot --domain=medspastarter` from inside the theme directory.

= Does it work without npm/Vite? =

No. The `build/` directory must exist before activating the theme. Run `npm install && npm run build` once after downloading. The build output is not committed.

= How do I add my booking platform? =

Go to Customizer → Booking. Set your booking URL and CTA button text. The same URL is used in the header button and footer CTA patterns.

= Can I use this without WooCommerce or Jetpack? =

Yes. Both integrations are conditional and only load when the respective plugin is active.

== Changelog ==

= 1.0.0 =
* Phase 6 complete: RTL pass (logical CSS properties throughout), admin welcome page, .pot scaffold, breadcrumb rendering with Customizer control, sticky-header Customizer setting wired to PHP and JS, Colors section deduplicated in Customizer
* Phase 5 complete: Customizer (8 sections), Schema.org JSON-LD, WooCommerce and Jetpack compatibility, starter content
* Phase 4 complete: 15 Gutenberg block patterns for medical spa use cases
* Phase 3 complete: JS modules — dark mode, navigation, sticky header, back-to-top, scroll animations, search overlay
* Phase 2 complete: Full template hierarchy, sidebar, comments, search, template-parts, template tags and hooks
* Phase 1 complete: TailwindCSS v4 + Vite build system, theme.json, starter CSS/JS
