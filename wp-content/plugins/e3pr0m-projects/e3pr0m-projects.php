<?php
/**
 * Plugin Name:  e3pr0m Projects
 * Plugin URI:   https://www.e3pr0m.com
 * Description:  Custom Post Type "Progetti" con campi meta per GitHub, demo e stack tecnologico.
 * Version:      1.0.0
 * Author:       e3pr0m
 * Author URI:   https://www.e3pr0m.com
 * License:      GPL-2.0+
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  e3pr0m-projects
 * Domain Path:  /languages
 *
 * @package e3pr0m-projects
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// COSTANTI
// =========================================

define( 'E3PR0M_PROJ_VERSION', '1.0.0' );
define( 'E3PR0M_PROJ_DIR',     plugin_dir_path( __FILE__ ) );
define( 'E3PR0M_PROJ_URI',     plugin_dir_url( __FILE__ ) );

// =========================================
// INCLUDE
// =========================================

require_once E3PR0M_PROJ_DIR . 'inc/cpt.php';
require_once E3PR0M_PROJ_DIR . 'inc/meta-boxes.php';

// =========================================
// LOAD TEXTDOMAIN
// =========================================

add_action( 'plugins_loaded', function () {
    load_plugin_textdomain(
        'e3pr0m-projects',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
} );
