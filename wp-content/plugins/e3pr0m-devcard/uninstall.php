<?php
/**
 * Uninstall — e3pr0m DevCard
 *
 * Eseguito da WordPress quando l'utente clicca "Elimina" nella
 * schermata dei plugin. Rimuove tutte le opzioni salvate in DB.
 *
 * @package e3pr0m-devcard
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'e3dc_version' );
