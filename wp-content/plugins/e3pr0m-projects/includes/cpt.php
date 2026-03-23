<?php
/**
 * Registrazione Custom Post Type: projects
 *
 * @package e3pr0m-projects
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registra il CPT "projects".
 * Chiamata sia da init che dall'activation hook.
 */
function e3pr0m_projects_register_cpt() {

    $labels = [
        'name'                  => __( 'Progetti',             'e3pr0m-projects' ),
        'singular_name'         => __( 'Progetto',             'e3pr0m-projects' ),
        'menu_name'             => __( 'Progetti',             'e3pr0m-projects' ),
        'add_new'               => __( 'Nuovo progetto',       'e3pr0m-projects' ),
        'add_new_item'          => __( 'Aggiungi progetto',    'e3pr0m-projects' ),
        'edit_item'             => __( 'Modifica progetto',    'e3pr0m-projects' ),
        'new_item'              => __( 'Nuovo progetto',       'e3pr0m-projects' ),
        'view_item'             => __( 'Vedi progetto',        'e3pr0m-projects' ),
        'search_items'          => __( 'Cerca progetti',       'e3pr0m-projects' ),
        'not_found'             => __( 'Nessun progetto.',     'e3pr0m-projects' ),
        'not_found_in_trash'    => __( 'Nessun progetto nel cestino.', 'e3pr0m-projects' ),
        'all_items'             => __( 'Tutti i progetti',     'e3pr0m-projects' ),
        'archives'              => __( 'Archivio progetti',    'e3pr0m-projects' ),
        'featured_image'        => __( 'Immagine progetto',    'e3pr0m-projects' ),
        'set_featured_image'    => __( 'Imposta immagine',     'e3pr0m-projects' ),
        'remove_featured_image' => __( 'Rimuovi immagine',     'e3pr0m-projects' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'progetti' ],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'revisions',
        ],
        'show_in_rest'       => true,   // compatibilità Gutenberg
    ];

    register_post_type( 'projects', $args );
}
add_action( 'init', 'e3pr0m_projects_register_cpt' );

// =========================================
// TAXONOMY: Tipo progetto (es. Web, Plugin, App)
// =========================================

function e3pr0m_projects_register_taxonomy() {

    $labels = [
        'name'              => __( 'Tipi progetto',      'e3pr0m-projects' ),
        'singular_name'     => __( 'Tipo progetto',      'e3pr0m-projects' ),
        'search_items'      => __( 'Cerca tipi',         'e3pr0m-projects' ),
        'all_items'         => __( 'Tutti i tipi',       'e3pr0m-projects' ),
        'edit_item'         => __( 'Modifica tipo',      'e3pr0m-projects' ),
        'update_item'       => __( 'Aggiorna tipo',      'e3pr0m-projects' ),
        'add_new_item'      => __( 'Aggiungi tipo',      'e3pr0m-projects' ),
        'new_item_name'     => __( 'Nuovo tipo',         'e3pr0m-projects' ),
        'menu_name'         => __( 'Tipi',               'e3pr0m-projects' ),
    ];

    register_taxonomy( 'project_type', 'projects', [
        'labels'            => $labels,
        'hierarchical'      => true,    // si comporta come categoria
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'tipo-progetto' ],
    ] );
}
add_action( 'init', 'e3pr0m_projects_register_taxonomy' );
