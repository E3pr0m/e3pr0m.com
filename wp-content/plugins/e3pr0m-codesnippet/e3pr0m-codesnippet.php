<?php
/**
 * Plugin Name:       e3pr0m Code Snippet
 * Plugin URI:        https://www.e3pr0m.com
 * Description:       Visualizza snippet di codice con syntax highlighting, numeri di riga e copia con un click. Disponibile come shortcode e blocco Gutenberg.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.2
 * Author:            e3pr0m
 * Author URI:        https://www.e3pr0m.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       e3pr0m-codesnippet
 * Domain Path:       /languages
 *
 * @package e3pr0m-codesnippet
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// COSTANTI
// =========================================

define( 'E3CS_VERSION',  '1.0.0' );
define( 'E3CS_DIR',      plugin_dir_path( __FILE__ ) );
define( 'E3CS_URI',      plugin_dir_url( __FILE__ ) );
define( 'E3CS_ASSETS',   E3CS_URI  . 'assets/' );
define( 'E3CS_INC',      E3CS_DIR  . 'inc/' );

// =========================================
// CARICAMENTO TRADUZIONI
// =========================================

add_action( 'init', function () {
	load_plugin_textdomain(
		'e3pr0m-codesnippet',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
} );

// =========================================
// INCLUDE — ordine importante:
//   1. enqueue  (registra gli asset)
//   2. shortcode
//   3. block
// =========================================

require_once E3CS_INC . 'enqueue.php';
require_once E3CS_INC . 'class-shortcode.php';
require_once E3CS_INC . 'class-block.php';

// =========================================
// HOOK DI ATTIVAZIONE / DISATTIVAZIONE
// =========================================

register_activation_hook( __FILE__, 'e3cs_on_activate' );
register_deactivation_hook( __FILE__, 'e3cs_on_deactivate' );

/**
 * Azioni all'attivazione del plugin.
 * Per ora segna solo la versione in DB — utile per future migrazioni.
 */
function e3cs_on_activate(): void {
	update_option( 'e3cs_version', E3CS_VERSION );
}

/**
 * Azioni alla disattivazione del plugin.
 */
function e3cs_on_deactivate(): void {
	// Nessuna azione distruttiva: i contenuti (shortcode nei post) rimangono intatti.
	// La rimozione dei dati avviene solo tramite uninstall.php.
}
