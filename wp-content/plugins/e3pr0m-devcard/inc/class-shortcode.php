<?php
/**
 * Shortcode [devcard]
 *
 * Uso:
 *   [devcard
 *     name="Mario Rossi"
 *     role="Full Stack Developer"
 *     bio="Appassionato di WordPress e codice pulito."
 *     avatar="https://example.com/avatar.jpg"
 *     location="Roma, IT"
 *     available="true"
 *     github="mariorossi"
 *     linkedin="mariorossi"
 *     twitter="mariorossi"
 *     website="https://mariorossi.dev"
 *     email="mario@example.com"
 *     skills="PHP, WordPress, JavaScript, MySQL"
 *   ]
 *
 * @package e3pr0m-devcard
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// REGISTRAZIONE SHORTCODE
// =========================================

add_shortcode( 'devcard', 'e3dc_shortcode_render' );

/**
 * Pulisce il tag [devcard] dal contenuto DOPO wpautop (priority 10)
 * e PRIMA di do_shortcode (priority 11).
 *
 * wpautop converte le newline tra gli attributi in <br /> che il parser
 * degli shortcode non capisce. Questo filter:
 *  1. Rimuove <br /> e tag <p> inseriti dentro il tag [devcard ...]
 *  2. Normalizza curly/smart quotes e entità HTML → " dritte
 *
 * Deve essere registrato DOPO wpautop e shortcode_unautop (entrambi priority 10,
 * aggiunti da WP core prima di questo plugin) così gira per ultimo a priority 10.
 */
add_filter( 'the_content', 'e3dc_sanitize_shortcode_tag', 10 );

function e3dc_sanitize_shortcode_tag( string $content ): string {
	return preg_replace_callback(
		'/\[devcard\b[^\]]*\]/s',
		function ( array $m ): string {
			$tag = $m[0];

			// Rimuove <br />, <br>, </p>, <p> che wpautop ha inserito tra gli attributi
			$tag = preg_replace( '/<br\s*\/?>/', ' ', $tag );
			$tag = preg_replace( '/<\/?\s*p\s*>/', ' ', $tag );

			// Normalizza entità HTML delle virgolette tipografiche
			$tag = str_replace(
				[ '&#8220;', '&#8221;', '&#8216;', '&#8217;', '&#8222;', '&ldquo;', '&rdquo;', '&lsquo;', '&rsquo;', '&bdquo;', '&laquo;', '&raquo;' ],
				[ '"',       '"',       "'",       "'",       '"',       '"',       '"',       "'",       "'",       '"',       '"',       '"'       ],
				$tag
			);

			// Normalizza caratteri Unicode raw delle virgolette tipografiche
			return str_replace(
				[ "\u{201C}", "\u{201D}", "\u{2018}", "\u{2019}", "\u{201E}", "\u{00AB}", "\u{00BB}" ],
				[ '"',        '"',        "'",         "'",         '"',        '"',        '"'        ],
				$tag
			);
		},
		$content
	);
}

/**
 * Callback dello shortcode [devcard].
 * Usato come fallback se `pre_do_shortcode_tag` non intercetta
 * (es. WordPress < 4.7 o contesti che bypassano quel filter).
 *
 * @param array|string $atts Attributi passati dall'utente.
 * @return string            HTML della card.
 */
function e3dc_shortcode_render( array|string $atts ): string {

	e3dc_mark_assets_needed();

	$atts = shortcode_atts(
		[
			'name'      => '',
			'role'      => '',
			'bio'       => '',
			'avatar'    => '',
			'location'  => '',
			'available' => 'false',
			'github'    => '',
			'linkedin'  => '',
			'twitter'   => '',
			'website'   => '',
			'email'     => '',
			'skills'    => '',
		],
		$atts,
		'devcard'
	);

	$atts = e3dc_strip_smart_quotes( $atts );

	return e3dc_render_card(
		name:      $atts['name'],
		role:      $atts['role'],
		bio:       $atts['bio'],
		avatar:    $atts['avatar'],
		location:  $atts['location'],
		available: filter_var( $atts['available'], FILTER_VALIDATE_BOOLEAN ),
		github:    $atts['github'],
		linkedin:  $atts['linkedin'],
		twitter:   $atts['twitter'],
		website:   $atts['website'],
		email:     $atts['email'],
		skills:    $atts['skills'],
	);
}

// =========================================
// RENDER CONDIVISO (usato anche dal blocco)
// =========================================

/**
 * Genera l'HTML della dev card.
 * Funzione pura — nessun side effect, nessun output diretto.
 */
function e3dc_render_card(
	string $name      = '',
	string $role      = '',
	string $bio       = '',
	string $avatar    = '',
	string $location  = '',
	bool   $available = false,
	string $github    = '',
	string $linkedin  = '',
	string $twitter   = '',
	string $website   = '',
	string $email     = '',
	string $skills    = '',
): string {

	// Gutenberg converte le virgolette dritte " in tipografiche “ ”
	// prima del salvataggio (JS). Le puliamo sui valori già ricevuti.
	foreach ( [ &$name, &$role, &$bio, &$avatar, &$location, &$github, &$linkedin, &$twitter, &$website, &$email, &$skills ] as &$_val ) {
		$_val = e3dc_clean_attr( $_val );
	}
	unset( $_val );

	$wrapper_classes = [ 'devcard' ];
	if ( $available ) $wrapper_classes[] = 'devcard--available';
	if ( ! $avatar )  $wrapper_classes[] = 'devcard--no-avatar';

	// Skills: stringa CSV → array pulito
	$skill_list = array_filter(
		array_map( 'trim', explode( ',', $skills ) )
	);

	$social_links = e3dc_build_social_links( $github, $linkedin, $twitter, $website, $email );

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" aria-label="<?php echo esc_attr( $name ); ?>">

		<?php if ( $avatar ) : ?>
		<div class="devcard__avatar-wrap" aria-hidden="true">
			<img
				class="devcard__avatar"
				src="<?php echo esc_url( $avatar ); ?>"
				alt="<?php echo esc_attr( $name ); ?>"
				loading="lazy"
				decoding="async"
				width="80"
				height="80"
			/>
		</div>
		<?php endif; ?>

		<div class="devcard__body">

			<div class="devcard__header">
				<?php if ( $name ) : ?>
				<span class="devcard__name"><?php echo esc_html( $name ); ?></span>
				<?php endif; ?>

				<?php if ( $available ) : ?>
				<span class="devcard__badge" aria-label="<?php esc_attr_e( 'Disponibile per nuovi progetti', 'e3pr0m-devcard' ); ?>">
					<span class="devcard__badge-dot" aria-hidden="true"></span>
					<?php esc_html_e( 'Disponibile', 'e3pr0m-devcard' ); ?>
				</span>
				<?php endif; ?>
			</div>

			<?php if ( $role ) : ?>
			<p class="devcard__role"><?php echo esc_html( $role ); ?></p>
			<?php endif; ?>

			<?php if ( $location ) : ?>
			<p class="devcard__location" aria-label="<?php esc_attr_e( 'Posizione', 'e3pr0m-devcard' ); ?>">
				<span class="devcard__location-icon" aria-hidden="true"><?php echo e3dc_icon_location(); // phpcs:ignore ?></span>
				<?php echo esc_html( $location ); ?>
			</p>
			<?php endif; ?>

			<?php if ( $bio ) : ?>
			<p class="devcard__bio"><?php echo wp_kses( $bio, [ 'span' => [ 'style' => [] ] ] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $skill_list ) ) : ?>
			<ul class="devcard__skills" aria-label="<?php esc_attr_e( 'Skill', 'e3pr0m-devcard' ); ?>">
				<?php foreach ( $skill_list as $skill ) : ?>
				<li class="devcard__skill"><?php echo esc_html( $skill ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<?php if ( ! empty( $social_links ) ) : ?>
			<nav class="devcard__social" aria-label="<?php esc_attr_e( 'Profili social', 'e3pr0m-devcard' ); ?>">
				<?php foreach ( $social_links as $link ) : ?>
					<?php if ( $link['type'] === 'email' ) : ?>
					<button
						class="devcard__social-link devcard__social-link--email"
						type="button"
						data-email="<?php echo esc_attr( $link['href'] ); ?>"
						aria-label="<?php echo esc_attr( $link['label'] ); ?>"
					><?php echo $link['icon']; // phpcs:ignore ?></button>
					<?php else : ?>
					<a
						class="devcard__social-link"
						href="<?php echo esc_url( $link['href'] ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $link['label'] ); ?>"
					><?php echo $link['icon']; // phpcs:ignore ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</nav>
			<?php endif; ?>

		</div><!-- .devcard__body -->
	</div><!-- .devcard -->
	<?php
	return ob_get_clean();
}

// =========================================
// UTILITY
// =========================================

/**
 * Costruisce l'array dei link social da mostrare.
 *
 * @return array<int, array{href:string,label:string,icon:string,type:string}>
 */
function e3dc_build_social_links(
	string $github,
	string $linkedin,
	string $twitter,
	string $website,
	string $email,
): array {
	$links = [];

	if ( $github )   $links[] = [ 'type' => 'link',  'href' => 'https://github.com/'      . rawurlencode( $github ),   'label' => 'GitHub',                                             'icon' => e3dc_icon_github()   ];
	if ( $linkedin ) $links[] = [ 'type' => 'link',  'href' => 'https://linkedin.com/in/' . rawurlencode( $linkedin ), 'label' => 'LinkedIn',                                           'icon' => e3dc_icon_linkedin() ];
	if ( $twitter )  $links[] = [ 'type' => 'link',  'href' => 'https://x.com/'           . rawurlencode( $twitter ),  'label' => 'X / Twitter',                                        'icon' => e3dc_icon_twitter()  ];
	if ( $website )  $links[] = [ 'type' => 'link',  'href' => $website,                                               'label' => __( 'Sito web', 'e3pr0m-devcard' ),                   'icon' => e3dc_icon_website()  ];
	if ( $email )    $links[] = [ 'type' => 'email', 'href' => $email,                                                 'label' => __( 'Copia indirizzo email', 'e3pr0m-devcard' ),       'icon' => e3dc_icon_email()    ];

	return $links;
}

// =========================================
// ICONE SVG INLINE
// =========================================

function e3dc_icon_github(): string {
	return '<svg class="devcard__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" width="20" height="20"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.604-3.369-1.341-3.369-1.341-.454-1.154-1.11-1.461-1.11-1.461-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>';
}

function e3dc_icon_linkedin(): string {
	return '<svg class="devcard__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" width="20" height="20"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
}

function e3dc_icon_twitter(): string {
	return '<svg class="devcard__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" width="20" height="20"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.631 5.905-5.631zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';
}

function e3dc_icon_website(): string {
	return '<svg class="devcard__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" width="20" height="20"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>';
}

function e3dc_icon_email(): string {
	return '<svg class="devcard__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" width="20" height="20"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>';
}

function e3dc_icon_location(): string {
	return '<svg class="devcard__location-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" width="13" height="13"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>';
}

// =========================================
// UTILITY
// =========================================

/**
 * Pulisce un singolo valore attributo shortcode:
 * rimuove virgolette tipografiche (smart quotes) che Gutenberg
 * inserisce lato JS prima del salvataggio, poi fa trim().
 *
 * @param string $value Valore grezzo.
 * @return string       Valore pulito.
 */
function e3dc_clean_attr( string $value ): string {
	static $quotes = null;
	if ( null === $quotes ) {
		$quotes = [
			"\u{201C}", // “ apertura doppia
			"\u{201D}", // ” chiusura doppia
			"\u{2018}", // ‘ apertura singola
			"\u{2019}", // ’ chiusura singola
			"\u{201E}", // „ doppia bassa
			"\u{00AB}", // « caporali apertura
			"\u{00BB}", // » caporali chiusura
		];
	}
	return trim( str_replace( $quotes, '', $value ) );
}

/**
 * Rimuove le virgolette tipografiche (smart quotes) inserite
 * dall'editor visuale di WordPress dai valori degli attributi
 * shortcode.
 *
 * WordPress converte automaticamente " in " e " (U+201C / U+201D)
 * e ' in ' e ' (U+2018 / U+2019). Quando l'utente scrive
 *   [devcard name="Alessio"]
 * nell'editor classico o nel blocco HTML, i valori arrivano
 * come  "Alessio"  → il parser include il " come parte del valore.
 *
 * @param array $atts Array di attributi shortcode grezzi.
 * @return array      Array con i valori stringa ripuliti.
 */
function e3dc_strip_smart_quotes( array $atts ): array {
	// Caratteri da eliminare: virgolette tipografiche doppie e singole
	// sia in apertura che in chiusura, più le virgolette dritte standard.
	$smart_quotes = [
		"\u{201C}", // " apertura doppia
		"\u{201D}", // " chiusura doppia
		"\u{2018}", // ' apertura singola
		"\u{2019}", // ' chiusura singola
		"\u{201E}", // „ doppia bassa (DE/PL)
		"\u{00AB}", // « caporali apertura
		"\u{00BB}", // » caporali chiusura
	];

	// Entità HTML che Gutenberg inserisce nei blocchi rich-text
	$html_entities = [ '&#8220;', '&#8221;', '&#8216;', '&#8217;', '&#8222;', '&ldquo;', '&rdquo;', '&lsquo;', '&rsquo;', '&bdquo;', '&laquo;', '&raquo;' ];

	foreach ( $atts as $key => $value ) {
		if ( is_string( $value ) ) {
			$value = str_replace( $html_entities, '', $value );
			$atts[ $key ] = trim( str_replace( $smart_quotes, '', $value ) );
		}
	}

	return $atts;
}
