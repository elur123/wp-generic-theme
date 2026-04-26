<?php
declare(strict_types=1);
/**
 * Demo Content Importer — admin tool page
 *
 * Accessible via Tools → Import Demo Content.
 *
 * @package MedSpaStarter
 */

add_action( 'admin_menu', 'medspastarter_demo_importer_menu' );

function medspastarter_demo_importer_menu(): void {
	add_management_page(
		esc_html__( 'Import Demo Content', 'medspastarter' ),
		esc_html__( 'Import Demo Content', 'medspastarter' ),
		'manage_options',
		'medspastarter-demo-importer',
		'medspastarter_demo_importer_page'
	);
}

function medspastarter_demo_importer_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$message = '';
	$error   = '';

	if (
		isset( $_POST['medspastarter_import_nonce'] ) &&
		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['medspastarter_import_nonce'] ) ), 'medspastarter_import_demo' )
	) {
		$result = medspastarter_run_demo_import();
		if ( is_wp_error( $result ) ) {
			$error = $result->get_error_message();
		} else {
			$message = $result;
		}
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import Demo Content', 'medspastarter' ); ?></h1>

		<?php if ( $message ) : ?>
			<div class="notice notice-success"><p><?php echo wp_kses_post( $message ); ?></p></div>
		<?php endif; ?>
		<?php if ( $error ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $error ); ?></p></div>
		<?php endif; ?>

		<div style="max-width:620px;margin-top:24px;background:#fff;padding:28px 32px;border:1px solid #ddd;border-radius:4px;">
			<h2 style="margin-top:0;"><?php esc_html_e( 'MedspBloom Starter — Demo Site', 'medspastarter' ); ?></h2>
			<p><?php esc_html_e( 'Imports a fully built medical spa demo: rich page content, navigation menus, and default theme settings.', 'medspastarter' ); ?></p>
			<ul style="list-style:disc;padding-left:20px;line-height:1.9;">
				<li><strong><?php esc_html_e( 'Home', 'medspastarter' ); ?></strong> — <?php esc_html_e( 'Hero, services overview, about snippet, stats, testimonials, booking CTA', 'medspastarter' ); ?></li>
				<li><strong><?php esc_html_e( 'About Us', 'medspastarter' ); ?></strong> — <?php esc_html_e( 'Clinic story, doctor bio, team, philosophy, certifications', 'medspastarter' ); ?></li>
				<li><strong><?php esc_html_e( 'Services', 'medspastarter' ); ?></strong> — <?php esc_html_e( 'Six treatment categories with descriptions and what\'s included', 'medspastarter' ); ?></li>
				<li><strong><?php esc_html_e( 'Contact', 'medspastarter' ); ?></strong> — <?php esc_html_e( 'Address, hours, phone, email, consultation process', 'medspastarter' ); ?></li>
				<li><strong><?php esc_html_e( 'Blog', 'medspastarter' ); ?></strong> — <?php esc_html_e( 'Posts archive page', 'medspastarter' ); ?></li>
				<li><?php esc_html_e( 'Primary + Footer navigation menus', 'medspastarter' ); ?></li>
				<li><?php esc_html_e( 'Default theme settings (booking CTA, dark mode, columns, etc.)', 'medspastarter' ); ?></li>
			</ul>
			<p style="color:#d63638;margin-bottom:20px;">
				<?php esc_html_e( 'Pages with the same titles will be skipped. Safe to run multiple times.', 'medspastarter' ); ?>
			</p>
			<form method="post">
				<?php wp_nonce_field( 'medspastarter_import_demo', 'medspastarter_import_nonce' ); ?>
				<button type="submit" class="button button-primary button-large">
					<?php esc_html_e( 'Import Demo Content', 'medspastarter' ); ?>
				</button>
			</form>
		</div>
	</div>
	<?php
}

// ═══════════════════════════════════════════════════════════════════════════
// Import runner
// ═══════════════════════════════════════════════════════════════════════════

/**
 * @return string|WP_Error  Success message or error.
 */
function medspastarter_run_demo_import(): string|WP_Error {
	$created  = [];
	$page_ids = [];

	$pages = medspastarter_demo_pages();

	foreach ( $pages as $key => $page ) {
		$existing = get_page_by_title( $page['post_title'], OBJECT, 'page' );

		if ( $existing ) {
			$page_ids[ $key ] = $existing->ID;
			continue;
		}

		$id = wp_insert_post( [
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
			'post_title'   => $page['post_title'],
			'post_content' => $page['post_content'],
		], true );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		$page_ids[ $key ] = $id;
		$created[]        = $page['post_title'];
	}

	// Reading settings
	update_option( 'show_on_front',  'page' );
	update_option( 'page_on_front',  $page_ids['home'] );
	update_option( 'page_for_posts', $page_ids['blog'] );

	// Nav menus
	$primary_id = medspastarter_get_or_create_menu(
		__( 'Primary Navigation', 'medspastarter' ),
		[
			[ 'title' => __( 'Home',     'medspastarter' ), 'url' => home_url( '/' ), 'type' => 'custom' ],
			[ 'title' => __( 'About',    'medspastarter' ), 'id'  => $page_ids['about'] ],
			[ 'title' => __( 'Services', 'medspastarter' ), 'id'  => $page_ids['services'] ],
			[ 'title' => __( 'Blog',     'medspastarter' ), 'id'  => $page_ids['blog'] ],
			[ 'title' => __( 'Contact',  'medspastarter' ), 'id'  => $page_ids['contact'] ],
		]
	);

	$footer_id = medspastarter_get_or_create_menu(
		__( 'Footer Navigation', 'medspastarter' ),
		[
			[ 'title' => __( 'About Us', 'medspastarter' ), 'id' => $page_ids['about'] ],
			[ 'title' => __( 'Services', 'medspastarter' ), 'id' => $page_ids['services'] ],
			[ 'title' => __( 'Contact',  'medspastarter' ), 'id' => $page_ids['contact'] ],
			[ 'title' => __( 'Blog',     'medspastarter' ), 'id' => $page_ids['blog'] ],
		]
	);

	set_theme_mod( 'nav_menu_locations', [ 'menu-1' => $primary_id, 'menu-3' => $footer_id ] );

	// Theme mods
	foreach ( [
		'booking_cta_text' 		=> __( 'Book Consultation', 'medspastarter' ),
		'booking_url'      		=> '#book',
		'phone_number'     		=> '(310) 555-0192',
		'email_address'    		=> 'contact@medspabloom.com',
		'clinic_location'  		=> 'Beverly Hills, CA',
		'social_links_topbar' 	=> 'https://facebook.com/medspabloom,https://instagram.com/medspabloom,https://linkedin.com/company/medspabloom',
		'enable_dark_mode' 		=> true,
		'has_back_to_top'  		=> true,
		'has_search'       		=> true,
		'blog_columns'     		=> 3,
		'sidebar_posts'    		=> true,
		'sidebar_pages'    		=> false,
		'excerpt_length'   		=> 28,
	] as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	if ( $created ) {
		return sprintf(
			/* translators: %s: list of page titles */
			__( 'Done! Created: %s. Front page → <strong>Home</strong>, posts page → <strong>Blog</strong>. Menus assigned.', 'medspastarter' ),
			'<strong>' . implode( ', ', array_map( 'esc_html', $created ) ) . '</strong>'
		);
	}

	return __( 'All demo pages already existed — menus and theme settings have been refreshed.', 'medspastarter' );
}

// ═══════════════════════════════════════════════════════════════════════════
// Page content definitions
// ═══════════════════════════════════════════════════════════════════════════

/** @return array<string,array{post_title:string,post_content:string}> */
function medspastarter_demo_pages(): array {
	return [
		'home'     => [ 'post_title' => __( 'Home', 'medspastarter' ),     'post_content' => medspastarter_content_home() ],
		'about'    => [ 'post_title' => __( 'About Us', 'medspastarter' ), 'post_content' => medspastarter_content_about() ],
		'services' => [ 'post_title' => __( 'Services', 'medspastarter' ), 'post_content' => medspastarter_content_services() ],
		'contact'  => [ 'post_title' => __( 'Contact', 'medspastarter' ),  'post_content' => medspastarter_content_contact() ],
		'blog'     => [ 'post_title' => __( 'Blog', 'medspastarter' ),     'post_content' => '' ],
	];
}

// ── Home ─────────────────────────────────────────────────────────────────────

function medspastarter_content_home(): string {
	return <<<'BLOCKS'
<!-- wp:cover {"dimRatio":80,"overlayColor":"neutral-900","minHeight":680,"minHeightUnit":"px","isDark":true,"align":"full"} -->
<div class="wp-block-cover alignfull is-dark" style="min-height:680px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-80 has-background-dim"></span><div class="wp-block-cover__inner-container">

<!-- wp:heading {"level":1,"textAlign":"center","style":{"typography":{"fontSize":"clamp(2.2rem,5vw,3.5rem)","fontWeight":"700","lineHeight":"1.15"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:clamp(2.2rem,5vw,3.5rem);font-weight:700;line-height:1.15;margin-bottom:24px">Redefine Your Beauty.<br>Restore Your Confidence.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.2rem"},"spacing":{"margin":{"bottom":"40px"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.2rem;margin-bottom:40px">Beverly Hills' premier medical aesthetics clinic — where board-certified expertise meets a deeply personal approach to beauty.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"16px"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"32px","right":"32px"}}},"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" href="#book" style="border-radius:50px;padding-top:14px;padding-right:32px;padding-bottom:14px;padding-left:32px">Book Free Consultation</a></div>
<!-- /wp:button -->

<!-- wp:button {"style":{"border":{"radius":"50px","width":"2px","color":"#ffffff"},"spacing":{"padding":{"top":"12px","bottom":"12px","left":"32px","right":"32px"}}},"textColor":"white","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/services" style="border-radius:50px;border-width:2px;border-color:#ffffff;padding-top:12px;padding-right:32px;padding-bottom:12px;padding-left:32px">Explore Treatments</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.875rem"},"spacing":{"margin":{"top":"24px"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:0.875rem;margin-top:24px">✦ Free 30-minute consultation &nbsp;·&nbsp; No obligation &nbsp;·&nbsp; Same-week appointments available</p>
<!-- /wp:paragraph -->

</div></div>
<!-- /wp:cover -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-group has-neutral-50-background-color has-background" style="padding-top:80px;padding-bottom:80px">

<!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"clamp(1.6rem,3vw,2.2rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-text-align-center has-neutral-900-color has-text-color" style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;margin-bottom:12px">Our Signature Treatments</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"56px"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="margin-bottom:56px">Science-backed aesthetics tailored to your unique beauty goals.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"32px","padding":{"right":"24px","left":"24px"}}}} -->
<div class="wp-block-columns" style="padding-right:24px;padding-left:24px">
<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding-top:40px;padding-right:32px;padding-bottom:40px;padding-left:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"2rem"},"spacing":{"margin":{"bottom":"16px"}}}} -->
<p style="font-size:2rem;margin-bottom:16px">💉</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.25rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.25rem;font-weight:700;margin-bottom:12px">Injectables &amp; Fillers</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.95rem">Botox, Dysport, and premium dermal fillers for natural-looking volume, smoothing, and facial rejuvenation — administered by board-certified physicians.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"20px"}}}} -->
<p style="margin-top:20px"><a href="/services" style="color:#f25f5a;font-weight:600;text-decoration:none;">Learn more →</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding-top:40px;padding-right:32px;padding-bottom:40px;padding-left:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"2rem"},"spacing":{"margin":{"bottom":"16px"}}}} -->
<p style="font-size:2rem;margin-bottom:16px">✨</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.25rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.25rem;font-weight:700;margin-bottom:12px">Laser &amp; Skin Resurfacing</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.95rem">Advanced laser platforms targeting fine lines, hyperpigmentation, acne scars, and sun damage — delivering measurable, lasting improvements to skin texture and tone.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"20px"}}}} -->
<p style="margin-top:20px"><a href="/services" style="color:#f25f5a;font-weight:600;text-decoration:none;">Learn more →</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding-top:40px;padding-right:32px;padding-bottom:40px;padding-left:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"2rem"},"spacing":{"margin":{"bottom":"16px"}}}} -->
<p style="font-size:2rem;margin-bottom:16px">🌿</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.25rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.25rem;font-weight:700;margin-bottom:12px">Body Contouring</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.95rem">Non-surgical fat reduction and skin-tightening with CoolSculpting, Emsculpt NEO, and RF microneedling — sculpt and tone without downtime.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"20px"}}}} -->
<p style="margin-top:20px"><a href="/services" style="color:#f25f5a;font-weight:600;text-decoration:none;">Learn more →</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"white"} -->
<div class="wp-block-group has-white-background-color has-background" style="padding-top:80px;padding-bottom:80px">

<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":"64px","padding":{"right":"48px","left":"48px"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center" style="padding-right:48px;padding-left:48px">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">OUR STORY</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"clamp(1.6rem,3vw,2.2rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;margin-bottom:24px">Board-Certified Excellence in Aesthetic Medicine</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Founded in 2009 by Dr. Elena Vasquez, MD, FACS, MedspBloom Starter was built on a single conviction: every patient deserves the same level of care, artistry, and results that were once available only to a privileged few.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"32px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:32px">Today our team of board-certified physicians and licensed aestheticians sees patients from across Southern California, combining cutting-edge technology with a deeply personal, consultative approach.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"12px","bottom":"12px","left":"28px","right":"28px"}}},"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" href="/about" style="border-radius:50px;padding:12px 28px">Meet Our Team</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:columns {"style":{"spacing":{"blockGap":"16px"}}} -->
<div class="wp-block-columns">
<!-- wp:column {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"28px","bottom":"28px","left":"24px","right":"24px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-column has-neutral-50-background-color has-background" style="border-radius:12px;padding:28px 24px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.2rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color" style="font-size:2.2rem;font-weight:700;margin-bottom:4px">2,500+</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">Happy patients</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"28px","bottom":"28px","left":"24px","right":"24px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-column has-neutral-50-background-color has-background" style="border-radius:12px;padding:28px 24px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.2rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color" style="font-size:2.2rem;font-weight:700;margin-bottom:4px">15+</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">Years of expertise</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"16px"}}} -->
<div class="wp-block-columns">
<!-- wp:column {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"28px","bottom":"28px","left":"24px","right":"24px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-column has-neutral-50-background-color has-background" style="border-radius:12px;padding:28px 24px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.2rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color" style="font-size:2.2rem;font-weight:700;margin-bottom:4px">98%</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">Patient satisfaction</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"28px","bottom":"28px","left":"24px","right":"24px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-column has-neutral-50-background-color has-background" style="border-radius:12px;padding:28px 24px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.2rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color" style="font-size:2.2rem;font-weight:700;margin-bottom:4px">25+</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">Treatments offered</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-group has-neutral-50-background-color has-background" style="padding-top:80px;padding-bottom:80px">

<!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"clamp(1.6rem,3vw,2.2rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-text-align-center has-neutral-900-color has-text-color" style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;margin-bottom:12px">What Our Patients Say</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"52px"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="margin-bottom:52px">Real results. Real stories.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"28px","padding":{"right":"32px","left":"32px"}}}} -->
<div class="wp-block-columns" style="padding-right:32px;padding-left:32px">
<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"36px","bottom":"36px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding:36px 32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem"},"spacing":{"margin":{"bottom":"20px"}}},"textColor":"secondary"} -->
<p class="has-secondary-color has-text-color" style="font-size:1.1rem;margin-bottom:20px">★★★★★</p>
<!-- /wp:paragraph -->
<!-- wp:quote {"style":{"typography":{"fontSize":"1rem","fontStyle":"italic"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"neutral-700"} -->
<blockquote class="wp-block-quote has-neutral-700-color has-text-color" style="font-size:1rem;font-style:italic;margin-bottom:24px"><p>"I've tried other med spas, but Serenova is on a completely different level. Dr. Vasquez listened to every concern, explained every step, and the results were beyond what I imagined. I look rested and refreshed — not 'done'."</p></blockquote>
<!-- /wp:quote -->
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","fontSize":"0.9rem"}}} -->
<p style="font-weight:600;font-size:0.9rem">— Amanda R., Beverly Hills</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"36px","bottom":"36px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding:36px 32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem"},"spacing":{"margin":{"bottom":"20px"}}},"textColor":"secondary"} -->
<p class="has-secondary-color has-text-color" style="font-size:1.1rem;margin-bottom:20px">★★★★★</p>
<!-- /wp:paragraph -->
<!-- wp:quote {"style":{"typography":{"fontSize":"1rem","fontStyle":"italic"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"neutral-700"} -->
<blockquote class="wp-block-quote has-neutral-700-color has-text-color" style="font-size:1rem;font-style:italic;margin-bottom:24px"><p>"The laser treatment for my sun damage was a game-changer. Three sessions in, my skin looks clearer and more even than it has in years. The staff makes you feel like a VIP from the moment you walk in."</p></blockquote>
<!-- /wp:quote -->
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","fontSize":"0.9rem"}}} -->
<p style="font-weight:600;font-size:0.9rem">— James T., Santa Monica</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"36px","bottom":"36px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding:36px 32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem"},"spacing":{"margin":{"bottom":"20px"}}},"textColor":"secondary"} -->
<p class="has-secondary-color has-text-color" style="font-size:1.1rem;margin-bottom:20px">★★★★★</p>
<!-- /wp:paragraph -->
<!-- wp:quote {"style":{"typography":{"fontSize":"1rem","fontStyle":"italic"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"neutral-700"} -->
<blockquote class="wp-block-quote has-neutral-700-color has-text-color" style="font-size:1rem;font-style:italic;margin-bottom:24px"><p>"I was nervous about body contouring, but the team's thoroughness and warmth put me at ease. Three months later the results are incredible. Worth every penny — I wish I'd come sooner."</p></blockquote>
<!-- /wp:quote -->
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","fontSize":"0.9rem"}}} -->
<p style="font-weight:600;font-size:0.9rem">— Priya M., Los Angeles</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"primary","align":"full"} -->
<div class="wp-block-group alignfull has-primary-background-color has-background" style="padding-top:80px;padding-bottom:80px">

<!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"clamp(1.6rem,3vw,2.4rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:700;margin-bottom:16px">Your Transformation Begins Here</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.1rem"},"spacing":{"margin":{"bottom":"40px"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.1rem;margin-bottom:40px">Schedule your complimentary 30-minute consultation. No obligation. Same-week appointments available.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"36px","right":"36px"}}},"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="#book" style="border-radius:50px;padding:14px 36px">Book Your Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.875rem"},"spacing":{"margin":{"top":"20px"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:0.875rem;margin-top:20px">Or call us: <a href="tel:+13105550192" style="color:#fff;font-weight:600;">(310) 555-0192</a></p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
BLOCKS;
}

// ── About Us ─────────────────────────────────────────────────────────────────

function medspastarter_content_about(): string {
	return <<<'BLOCKS'
<!-- wp:group {"style":{"spacing":{"padding":{"top":"72px","bottom":"72px"}}},"backgroundColor":"neutral-50","align":"full"} -->
<div class="wp-block-group alignfull has-neutral-50-background-color has-background" style="padding-top:72px;padding-bottom:72px">
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"clamp(2rem,4vw,3rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"neutral-900"} -->
<h1 class="wp-block-heading has-text-align-center has-neutral-900-color has-text-color" style="font-size:clamp(2rem,4vw,3rem);font-weight:700;margin-bottom:16px">About MedspBloom Starter</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.15rem"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="font-size:1.15rem">Where board-certified expertise meets a deeply personal approach to beauty.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"white"} -->
<div class="wp-block-group has-white-background-color has-background" style="padding-top:80px;padding-bottom:80px">
<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":"64px","padding":{"right":"48px","left":"48px"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center" style="padding-right:48px;padding-left:48px">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">OUR STORY</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"clamp(1.5rem,3vw,2.1rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:clamp(1.5rem,3vw,2.1rem);font-weight:700;margin-bottom:24px">Founded on a Belief That Everyone Deserves to Feel Beautiful</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">In 2009, Dr. Elena Vasquez stepped away from a prestigious academic surgical practice with one goal: to create a clinic where advanced aesthetic medicine was accessible, compassionate, and consistently exceptional.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">What began as a boutique single-physician practice in Beverly Hills has grown into a multi-disciplinary team of 12 specialists — united by an unwavering commitment to safety, artistry, and patient experience.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color">Every treatment plan starts with listening. We spend time understanding your goals, your concerns, and your lifestyle before recommending anything — because the best result is the one that looks and feels like you, only better.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"48px","bottom":"48px","left":"40px","right":"40px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-column is-vertically-aligned-center has-neutral-50-background-color has-background" style="border-radius:20px;padding:48px 40px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.1rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"24px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.1rem;font-weight:700;margin-bottom:24px">Our Credentials</h3>
<!-- /wp:heading -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.95rem"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.95rem">
<!-- wp:list-item --><li>Board-Certified Plastic Surgeons (ABPS)</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>American Society of Plastic Surgeons (ASPS)</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>American Med Spa Association (AmSpa)</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Allergan Medical Institute — Diamond Provider</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Galderma Aspire — Platinum Practice</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>JCAHO Accredited Facility</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>California Medical Board Licensed</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-group has-neutral-50-background-color has-background" style="padding-top:80px;padding-bottom:80px">
<!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"clamp(1.6rem,3vw,2.2rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-text-align-center has-neutral-900-color has-text-color" style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;margin-bottom:12px">Meet Our Team</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"52px"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="margin-bottom:52px">Board-certified physicians and licensed specialists, united by a passion for excellence.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"28px","padding":{"right":"32px","left":"32px"}}}} -->
<div class="wp-block-columns" style="padding-right:32px;padding-left:32px">
<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding:40px 32px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.15rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.15rem;font-weight:700;margin-bottom:4px">Dr. Elena Vasquez, MD, FACS</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.85rem","fontWeight":"600"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.85rem;font-weight:600;margin-bottom:16px">Founder &amp; Medical Director</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.95rem">Double board-certified in Plastic and Reconstructive Surgery. Harvard Medical School graduate with fellowship training in aesthetic surgery at NYU Langone. Dr. Vasquez is internationally recognized for her natural-looking facial rejuvenation results.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding:40px 32px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.15rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.15rem;font-weight:700;margin-bottom:4px">Dr. Michael Chen, MD</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.85rem","fontWeight":"600"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.85rem;font-weight:600;margin-bottom:16px">Lead Aesthetic Physician</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.95rem">Dermatology specialist with 12 years focused on laser medicine and energy-based treatments. Dr. Chen holds advanced certifications in PicoSure, Fraxel, and Clear + Brilliant and is sought after for treating complex pigmentation concerns.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}},"backgroundColor":"white"} -->
<div class="wp-block-column has-white-background-color has-background" style="border-radius:16px;padding:40px 32px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.15rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"4px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.15rem;font-weight:700;margin-bottom:4px">Sarah Mitchell, RN, BSN</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.85rem","fontWeight":"600"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.85rem;font-weight:600;margin-bottom:16px">Senior Aesthetic Nurse Injector</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.95rem">With over 3,000 injection sessions performed, Sarah is celebrated for her precision, her keen eye for facial anatomy, and her ability to put even the most anxious patients completely at ease.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"primary","align":"full"} -->
<div class="wp-block-group alignfull has-primary-background-color has-background" style="padding-top:80px;padding-bottom:80px">
<!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"clamp(1.5rem,3vw,2.2rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:clamp(1.5rem,3vw,2.2rem);font-weight:700;margin-bottom:16px">Ready to Meet the Team in Person?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"36px"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="margin-bottom:36px">Your first consultation is complimentary and completely obligation-free.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"36px","right":"36px"}}},"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="#book" style="border-radius:50px;padding:14px 36px">Book a Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
BLOCKS;
}

// ── Services ─────────────────────────────────────────────────────────────────

function medspastarter_content_services(): string {
	return <<<'BLOCKS'
<!-- wp:group {"style":{"spacing":{"padding":{"top":"72px","bottom":"72px"}}},"backgroundColor":"neutral-50","align":"full"} -->
<div class="wp-block-group alignfull has-neutral-50-background-color has-background" style="padding-top:72px;padding-bottom:72px">
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"clamp(2rem,4vw,3rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"neutral-900"} -->
<h1 class="wp-block-heading has-text-align-center has-neutral-900-color has-text-color" style="font-size:clamp(2rem,4vw,3rem);font-weight:700;margin-bottom:16px">Our Treatment Menu</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.15rem"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="font-size:1.15rem">Every treatment is performed or directly supervised by a board-certified physician.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"72px","bottom":"72px"}}},"backgroundColor":"white"} -->
<div class="wp-block-group has-white-background-color has-background" style="padding-top:72px;padding-bottom:72px">
<!-- wp:group {"style":{"spacing":{"padding":{"right":"48px","left":"48px"}}}} -->
<div class="wp-block-group" style="padding-right:48px;padding-left:48px">

<!-- wp:columns {"style":{"spacing":{"blockGap":"48px","margin":{"bottom":"64px"}}}} -->
<div class="wp-block-columns" style="margin-bottom:64px">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">01</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">Injectables &amp; Dermal Fillers</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Neurotoxins and premium hyaluronic acid fillers, expertly placed to soften lines, restore facial volume, and create harmonious, natural-looking results.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><!-- wp:list-item --><li>Botox &amp; Dysport (forehead, crow's feet, brow lift)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Juvederm &amp; Restylane filler collections</li><!-- /wp:list-item --><!-- wp:list-item --><li>Lip augmentation &amp; definition</li><!-- /wp:list-item --><!-- wp:list-item --><li>Tear trough correction</li><!-- /wp:list-item --><!-- wp:list-item --><li>Jawline &amp; chin contouring</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">02</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">Laser Skin Resurfacing</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Medical-grade laser platforms targeting pigmentation, texture, and laxity — with treatment intensities ranging from zero-downtime maintenance to transformative full-field resurfacing.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><!-- wp:list-item --><li>PicoSure Pro — pigment &amp; tone correction</li><!-- /wp:list-item --><!-- wp:list-item --><li>Fraxel Dual — texture &amp; scar revision</li><!-- /wp:list-item --><!-- wp:list-item --><li>Clear + Brilliant — glow &amp; maintenance</li><!-- /wp:list-item --><!-- wp:list-item --><li>IPL Photofacial — redness &amp; sun damage</li><!-- /wp:list-item --><!-- wp:list-item --><li>CO₂ fractional resurfacing</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"48px","margin":{"bottom":"64px"}}}} -->
<div class="wp-block-columns" style="margin-bottom:64px">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">03</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">Body Contouring &amp; Sculpting</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Non-surgical and minimally invasive body treatments to reduce stubborn fat, tighten skin, and build lean muscle — without surgery, without scars, and with minimal downtime.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><!-- wp:list-item --><li>CoolSculpting Elite — cryolipolysis</li><!-- /wp:list-item --><!-- wp:list-item --><li>Emsculpt NEO — fat reduction + muscle toning</li><!-- /wp:list-item --><!-- wp:list-item --><li>Morpheus8 — RF microneedling body</li><!-- /wp:list-item --><!-- wp:list-item --><li>Kybella — submental fat dissolving</li><!-- /wp:list-item --><!-- wp:list-item --><li>Cellulite treatment &amp; skin tightening</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">04</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">Skin Rejuvenation &amp; Facials</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Medical-grade facials and skin-health treatments that go beyond surface-level results — targeting the cellular mechanisms of aging, hydration, and radiance.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><!-- wp:list-item --><li>HydraFacial MD — deep cleanse &amp; hydration</li><!-- /wp:list-item --><!-- wp:list-item --><li>Microneedling with PRP (Vampire Facial)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Chemical peels (superficial to deep)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Dermaplaning &amp; exfoliation</li><!-- /wp:list-item --><!-- wp:list-item --><li>Medical-grade skincare consultation</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"48px"}}} -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">05</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">Hair Restoration</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Non-surgical hair restoration solutions for men and women experiencing thinning or early-stage hair loss, using your body's own regenerative biology.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><!-- wp:list-item --><li>PRP scalp injections</li><!-- /wp:list-item --><!-- wp:list-item --><li>Exosome hair therapy</li><!-- /wp:list-item --><!-- wp:list-item --><li>Low-level laser therapy (LLLT)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Personalized hair health protocol</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"600","letterSpacing":"0.1em"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-size:0.8rem;font-weight:600;letter-spacing:0.1em">06</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">Wellness &amp; IV Therapy</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"16px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:16px">Physician-formulated intravenous nutrient therapies delivering vitamins, minerals, and antioxidants directly to your bloodstream for immediate, measurable energy and recovery.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<ul class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><!-- wp:list-item --><li>Myers' Cocktail — energy &amp; immunity</li><!-- /wp:list-item --><!-- wp:list-item --><li>Glutathione — skin brightening &amp; detox</li><!-- /wp:list-item --><!-- wp:list-item --><li>NAD+ — cellular anti-aging</li><!-- /wp:list-item --><!-- wp:list-item --><li>Custom formulations</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"56px","bottom":"56px"}}},"backgroundColor":"neutral-50","align":"full"} -->
<div class="wp-block-group alignfull has-neutral-50-background-color has-background" style="padding-top:56px;padding-bottom:56px">
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.95rem"},"spacing":{"margin":{"bottom":"8px"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="font-size:0.95rem;margin-bottom:8px">Pricing varies by treatment area and individual goals. A personalised quote is provided at your complimentary consultation.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"13px","bottom":"13px","left":"32px","right":"32px"}}},"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" href="#book" style="border-radius:50px;padding:13px 32px">Book Your Free Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
BLOCKS;
}

// ── Contact ───────────────────────────────────────────────────────────────────

function medspastarter_content_contact(): string {
	return <<<'BLOCKS'
<!-- wp:group {"style":{"spacing":{"padding":{"top":"72px","bottom":"72px"}}},"backgroundColor":"neutral-50","align":"full"} -->
<div class="wp-block-group alignfull has-neutral-50-background-color has-background" style="padding-top:72px;padding-bottom:72px">
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"clamp(2rem,4vw,3rem)","fontWeight":"700"},"spacing":{"margin":{"bottom":"16px"}}},"textColor":"neutral-900"} -->
<h1 class="wp-block-heading has-text-align-center has-neutral-900-color has-text-color" style="font-size:clamp(2rem,4vw,3rem);font-weight:700;margin-bottom:16px">Get In Touch</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.15rem"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color" style="font-size:1.15rem">We'd love to hear from you. Reach out by phone, email, or book directly online.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"white","align":"full"} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:80px;padding-bottom:80px">
<!-- wp:columns {"style":{"spacing":{"blockGap":"64px","padding":{"right":"48px","left":"48px"}}}} -->
<div class="wp-block-columns" style="padding-right:48px;padding-left:48px">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"32px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:32px">Contact Information</h2>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"24px"}}}} -->
<div class="wp-block-group" style="margin-bottom:24px">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","fontSize":"0.85rem"}},"textColor":"neutral-900"} -->
<p class="has-neutral-900-color has-text-color" style="font-weight:600;font-size:0.85rem">ADDRESS</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color">400 N. Roxbury Drive, Suite 500<br>Beverly Hills, CA 90210</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"24px"}}}} -->
<div class="wp-block-group" style="margin-bottom:24px">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","fontSize":"0.85rem"}},"textColor":"neutral-900"} -->
<p class="has-neutral-900-color has-text-color" style="font-weight:600;font-size:0.85rem">PHONE</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color"><a href="tel:+13105550192" style="color:#f25f5a;">(310) 555-0192</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"24px"}}}} -->
<div class="wp-block-group" style="margin-bottom:24px">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","fontSize":"0.85rem"}},"textColor":"neutral-900"} -->
<p class="has-neutral-900-color has-text-color" style="font-weight:600;font-size:0.85rem">EMAIL</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color"><a href="mailto:contact@medspabloom.com" style="color:#f25f5a;">contact@medspabloom.com</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.1rem","fontWeight":"700"},"spacing":{"margin":{"top":"40px","bottom":"16px"}}},"textColor":"neutral-900"} -->
<h3 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.1rem;font-weight:700;margin-top:40px;margin-bottom:16px">Hours of Operation</h3>
<!-- /wp:heading -->
<!-- wp:table {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700","className":"is-style-stripes"} -->
<figure class="wp-block-table is-style-stripes"><table class="has-neutral-700-color has-text-color" style="font-size:0.9rem"><tbody><tr><td>Monday – Friday</td><td>9:00 AM – 6:00 PM</td></tr><tr><td>Saturday</td><td>10:00 AM – 4:00 PM</td></tr><tr><td>Sunday</td><td>Closed</td></tr></tbody></table></figure>
<!-- /wp:table -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"48px","bottom":"48px","left":"44px","right":"44px"}}},"backgroundColor":"neutral-50"} -->
<div class="wp-block-column has-neutral-50-background-color has-background" style="border-radius:20px;padding:48px 44px">
<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"12px"}}},"textColor":"neutral-900"} -->
<h2 class="wp-block-heading has-neutral-900-color has-text-color" style="font-size:1.4rem;font-weight:700;margin-bottom:12px">What to Expect</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"neutral-700","style":{"spacing":{"margin":{"bottom":"28px"}}}} -->
<p class="has-neutral-700-color has-text-color" style="margin-bottom:28px">Your first visit is designed to be relaxed, informative, and completely pressure-free.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"20px"}}}} -->
<div class="wp-block-group" style="margin-bottom:20px">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","fontSize":"0.95rem"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-weight:700;font-size:0.95rem">① Complimentary Consultation (30 min)</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">We listen to your goals, review your medical history, and examine your areas of concern — no selling, just honest conversation.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"20px"}}}} -->
<div class="wp-block-group" style="margin-bottom:20px">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","fontSize":"0.95rem"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-weight:700;font-size:0.95rem">② Personalised Treatment Plan</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">Dr. Vasquez or a specialist maps a tailored protocol — combining treatments, sequencing them strategically, and providing transparent pricing.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"32px"}}}} -->
<div class="wp-block-group" style="margin-bottom:32px">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","fontSize":"0.95rem"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-weight:700;font-size:0.95rem">③ Your First Treatment (if ready)</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9rem"}},"textColor":"neutral-700"} -->
<p class="has-neutral-700-color has-text-color" style="font-size:0.9rem">Many patients choose to begin the same day. There is never any pressure to proceed before you feel completely comfortable.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"13px","bottom":"13px","left":"28px","right":"28px"}}},"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" href="#book" style="border-radius:50px;padding:13px 28px">Book My Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
BLOCKS;
}

// ═══════════════════════════════════════════════════════════════════════════
// Menu helper
// ═══════════════════════════════════════════════════════════════════════════

/**
 * @param string                         $name
 * @param array<int,array<string,mixed>> $items
 * @return int  Menu term ID.
 */
function medspastarter_get_or_create_menu( string $name, array $items ): int {
	$existing = get_term_by( 'name', $name, 'nav_menu' );
	$menu_id  = $existing ? (int) $existing->term_id : (int) wp_create_nav_menu( $name );

	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( $existing_items ) {
		foreach ( $existing_items as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}

	foreach ( $items as $item ) {
		if ( isset( $item['url'] ) ) {
			wp_update_nav_menu_item( $menu_id, 0, [
				'menu-item-title'  => $item['title'],
				'menu-item-url'    => $item['url'],
				'menu-item-type'   => 'custom',
				'menu-item-status' => 'publish',
			] );
		} else {
			wp_update_nav_menu_item( $menu_id, 0, [
				'menu-item-title'     => $item['title'],
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $item['id'],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			] );
		}
	}

	return $menu_id;
}
