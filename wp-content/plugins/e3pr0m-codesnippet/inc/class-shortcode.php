<?php
/**
 * Shortcode [codesnippet]
 *
 * Uso:
 *   [codesnippet lang="php" title="functions.php" show_lines="true" copyable="true"]
 *   echo "ciao";
 *   [/codesnippet]
 *
 * @package e3pr0m-codesnippet
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// LINGUE SUPPORTATE DA PRISM
// =========================================

/**
 * Allowlist delle lingue accettate.
 * Chiave  = valore dell'attributo lang="..."
 * Valore  = classe CSS passata a Prism (language-*)
 */
function e3cs_allowed_languages(): array {
	return [
		'plaintext'  => 'plaintext',
		'html'       => 'markup',
		'xml'        => 'markup',
		'svg'        => 'markup',
		'css'        => 'css',
		'scss'       => 'scss',
		'js'         => 'javascript',
		'javascript' => 'javascript',
		'typescript' => 'typescript',
		'ts'         => 'typescript',
		'php'        => 'php',
		'python'     => 'python',
		'py'         => 'python',
		'bash'       => 'bash',
		'sh'         => 'bash',
		'sql'        => 'sql',
		'json'       => 'json',
		'yaml'       => 'yaml',
		'yml'        => 'yaml',
		'markdown'   => 'markdown',
		'md'         => 'markdown',
	];
}

// =========================================
// REGISTRAZIONE SHORTCODE
// =========================================

add_shortcode( 'codesnippet', 'e3cs_shortcode_render' );

/**
 * Callback dello shortcode.
 *
 * @param array|string $atts    Attributi passati dall'utente.
 * @param string|null  $content Il codice racchiuso tra i tag.
 * @return string               HTML del blocco snippet.
 */
function e3cs_shortcode_render( array|string $atts, ?string $content = null ): string {

	// Segnala che questa pagina ha bisogno degli asset
	e3cs_mark_assets_needed();

	$atts = shortcode_atts(
		[
			'lang'       => 'plaintext',
			'title'      => '',
			'show_lines' => 'true',
			'copyable'   => 'true',
		],
		$atts,
		'codesnippet'
	);

	return e3cs_render_snippet(
		content:    $content ?? '',
		lang:       $atts['lang'],
		title:      $atts['title'],
		show_lines: filter_var( $atts['show_lines'], FILTER_VALIDATE_BOOLEAN ),
		copyable:   filter_var( $atts['copyable'],   FILTER_VALIDATE_BOOLEAN ),
	);
}

// =========================================
// RENDER CONDIVISO (usato anche dal blocco)
// =========================================

/**
 * Genera l'HTML dello snippet.
 * Funzione pura — nessun side effect, nessun output diretto.
 *
 * @param string $content    Codice sorgente grezzo.
 * @param string $lang       Linguaggio (es. "php", "js").
 * @param string $title      Titolo opzionale della barra.
 * @param bool   $show_lines Mostra i numeri di riga.
 * @param bool   $copyable   Mostra il pulsante copia.
 * @return string            HTML completo del widget.
 */
function e3cs_render_snippet(
	string $content,
	string $lang       = 'plaintext',
	string $title      = '',
	bool   $show_lines = true,
	bool   $copyable   = true,
): string {

	// Risolve il linguaggio nell'alias Prism corretto
	$langs      = e3cs_allowed_languages();
	$lang_key   = strtolower( trim( $lang ) );
	$prism_lang = $langs[ $lang_key ] ?? 'plaintext';

	// Classi CSS del wrapper
	$wrapper_classes = [ 'codesnippet' ];
	if ( $show_lines ) {
		$wrapper_classes[] = 'line-numbers';
	}

	// Pulisce il codice: rimuove indentazione comune e spazi iniziali/finali
	$code = e3cs_clean_content( $content );

	// Avvia il buffering — più leggibile di una stringa concatenata
	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">

		<?php if ( $title || $copyable ) : ?>
		<div class="codesnippet__bar">
			<span class="codesnippet__lang"><?php echo esc_html( $lang_key ); ?></span>

			<?php if ( $title ) : ?>
			<span class="codesnippet__title"><?php echo esc_html( $title ); ?></span>
			<?php endif; ?>

			<?php if ( $copyable ) : ?>
			<button
				class="codesnippet__copy"
				type="button"
				aria-label="<?php esc_attr_e( 'Copia il codice', 'e3pr0m-codesnippet' ); ?>"
				data-copy-target
			>
				<?php esc_html_e( 'Copia', 'e3pr0m-codesnippet' ); ?>
			</button>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<pre class="language-<?php echo esc_attr( $prism_lang ); ?>"><code class="language-<?php echo esc_attr( $prism_lang ); ?>"><?php echo esc_html( $code ); ?></code></pre>

	</div>
	<?php
	return ob_get_clean();
}

// =========================================
// UTILITY
// =========================================

/**
 * Pulisce il contenuto dello shortcode:
 * - Rimuove i tag <br> inseriti da wpautop
 * - Decodifica le entità HTML rimpiazzate da WordPress
 * - Toglie l'indentazione comune (dedent)
 * - Trim delle righe vuote iniziali e finali
 *
 * @param string $raw Contenuto grezzo dallo shortcode.
 * @return string     Codice pronto per esc_html().
 */
function e3cs_clean_content( string $raw ): string {

	// WordPress sostituisce alcune entità — le ripristiniamo
	$code = html_entity_decode( $raw, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

	// Rimuove i <br /> inseriti da wpautop
	$code = preg_replace( '/<br\s*\/?>/i', "\n", $code );

	// Rimuove eventuali tag residui (sicurezza extra)
	$code = wp_strip_all_tags( $code );

	// Divide in righe, rimuove righe vuote iniziali e finali
	$lines = explode( "\n", $code );
	while ( isset( $lines[0] ) && trim( $lines[0] ) === '' ) {
		array_shift( $lines );
	}
	while ( ! empty( $lines ) && trim( end( $lines ) ) === '' ) {
		array_pop( $lines );
	}

	if ( empty( $lines ) ) {
		return '';
	}

	// Calcola l'indentazione minima comune (dedent)
	$min_indent = PHP_INT_MAX;
	foreach ( $lines as $line ) {
		if ( trim( $line ) === '' ) continue;
		$indent = strlen( $line ) - strlen( ltrim( $line ) );
		$min_indent = min( $min_indent, $indent );
	}

	if ( $min_indent > 0 && $min_indent !== PHP_INT_MAX ) {
		$lines = array_map(
			fn( string $l ) => substr( $l, $min_indent ),
			$lines
		);
	}

	return implode( "\n", $lines );
}
