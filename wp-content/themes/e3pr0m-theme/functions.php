<?php
/**
 * e3pr0m Theme — functions.php
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 * @link      https://tuosito.dev
 * @version   1.0.2
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// COSTANTI
// =========================================

define( 'E3PR0M_VERSION',   '1.0.0' );
define( 'E3PR0M_DIR',       get_template_directory() );
define( 'E3PR0M_URI',       get_template_directory_uri() );
define( 'E3PR0M_ASSETS',    E3PR0M_URI . '/assets' );

// =========================================
// SETUP TEMA
// =========================================

function e3pr0m_theme_setup() {

    // Traduzioni
    load_theme_textdomain( 'e3pr0m-theme', E3PR0M_DIR . '/languages' );

    // Supporto WordPress
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );

    // Dimensioni immagini custom
    add_image_size( 'e3pr0m-card',   600, 400, true );
    add_image_size( 'e3pr0m-hero',  1200, 600, true );

    // Menu
    register_nav_menus( [
        'primary' => __( 'Menu Principale', 'e3pr0m-theme' ),
        'footer'  => __( 'Menu Footer',     'e3pr0m-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'e3pr0m_theme_setup' );

// =========================================
// ENQUEUE STILI E SCRIPT
// =========================================

function e3pr0m_enqueue_assets() {

    // Google Fonts — JetBrains Mono + Inter
    wp_enqueue_style(
        'e3pr0m-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@400;600;700&display=swap',
        [],
        null
    );

    // Stylesheet principale (style.css)
    wp_enqueue_style(
        'e3pr0m-style',
        get_stylesheet_uri(),
        [ 'e3pr0m-fonts' ],
        E3PR0M_VERSION
    );

    // CSS aggiuntivo
    wp_enqueue_style(
        'e3pr0m-main',
        E3PR0M_ASSETS . '/css/main.css',
        [ 'e3pr0m-style' ],
        E3PR0M_VERSION
    );

    // JavaScript principale
    wp_enqueue_script(
        'e3pr0m-main',
        E3PR0M_ASSETS . '/js/main.js',
        [],
        E3PR0M_VERSION,
        true   // in footer
    );

    // Passa variabili PHP → JS
    wp_localize_script( 'e3pr0m-main', 'e3pr0mData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'e3pr0m_nonce' ),
        'siteUrl' => get_site_url(),
    ] );
}
add_action( 'wp_enqueue_scripts', 'e3pr0m_enqueue_assets' );

// =========================================
// WIDGET AREAS
// =========================================

function e3pr0m_register_sidebars() {
    register_sidebar( [
        'name'          => __( 'Sidebar Blog', 'e3pr0m-theme' ),
        'id'            => 'sidebar-blog',
        'description'   => __( 'Widget area per il blog', 'e3pr0m-theme' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'widgets_init', 'e3pr0m_register_sidebars' );

// =========================================
// BODY CLASSES
// =========================================

function e3pr0m_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'is-singular';
    }
    if ( is_front_page() ) {
        $classes[] = 'is-home';
    }
    return $classes;
}
add_filter( 'body_class', 'e3pr0m_body_classes' );

// =========================================
// EXCERPT
// =========================================

function e3pr0m_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'e3pr0m_excerpt_length' );

function e3pr0m_excerpt_more( $more ) {
    return '…';
}
add_filter( 'excerpt_more', 'e3pr0m_excerpt_more' );

// =========================================
// RIMUOVI JUNK DAL <HEAD>
// =========================================

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// =========================================
// INCLUDE FILE AGGIUNTIVI
// =========================================

require_once E3PR0M_DIR . '/inc/template-functions.php';
