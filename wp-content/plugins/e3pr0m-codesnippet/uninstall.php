<?php
/**
 * Uninstall — e3pr0m Code Snippet
 *
 * Eseguito da WordPress solo quando l'utente clicca
 * "Elimina" nel pannello Plugin — MAI alla semplice disattivazione.
 *
 * Rimuove:
 *  - L'opzione e3cs_version da wp_options
 *
 * Non rimuove:
 *  - I post che contengono lo shortcode [codesnippet]
 *    (il contenuto dell'utente non viene mai cancellato)
 *
 * @package e3pr0m-codesnippet
 */

// Blocca l'accesso diretto — WordPress imposta questa costante
// prima di chiamare uninstall.php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'e3cs_version' );
