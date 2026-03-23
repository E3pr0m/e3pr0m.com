<?php
/**
 * Plugin Name:       e3pr0m Projects
 * Plugin URI:        https://www.e3pr0m.com
 * Description:       Custom Post Type "Progetti" con campi meta (GitHub, Demo, Tech stack).
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.2
 * Author:            e3pr0m
 * Author URI:        https://www.e3pr0m.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       e3pr0m-projects
 * Domain Path:       /languages
 *
 * @package e3pr0m-projects
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// COSTANTI
// =========================================

define( 'E3PR0M_PROJECTS_VERSION', '1.0.0' );
define( 'E3PR0M_PROJECTS_DIR',     plugin_dir_path( __FILE__ ) );
define( 'E3PR0M_PROJECTS_URI',     plugin_dir_url( __FILE__ ) );

// =========================================
// INCLUDE
// =========================================

require_once E3PR0M_PROJECTS_DIR . 'includes/cpt.php';
require_once E3PR0M_PROJECTS_DIR . 'includes/meta-boxes.php';


// =========================================
// ASSETS ADMIN
// =========================================

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    // Carica solo nelle schermate di edit del CPT
    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'projects' ) {
        return;
    }

    wp_enqueue_style(
        'e3pr0m-projects-admin',
        E3PR0M_PROJECTS_URI . 'assets/css/admin.css',
        [],
        E3PR0M_PROJECTS_VERSION
    );
} );

// =========================================
// FLUSH REWRITE RULES — solo all'attivazione
// =========================================

register_activation_hook( __FILE__, function () {
    // Registra il CPT prima del flush
    e3pr0m_projects_register_cpt();
    flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function () {
    flush_rewrite_rules();
} );