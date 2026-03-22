<?php
/**
 * Funzioni helper per i template
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stampa i link del menu di navigazione
 */
function e3pr0m_nav_links() {
    wp_nav_menu( [
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav__list',
        'fallback_cb'    => '__return_false',
    ] );
}

/**
 * Ritorna l'URL della thumbnail o un placeholder
 */
function e3pr0m_get_thumbnail_url( $size = 'e3pr0m-card' ) {
    if ( has_post_thumbnail() ) {
        return get_the_post_thumbnail_url( get_the_ID(), $size );
    }
    return E3PR0M_ASSETS . '/img/placeholder.svg';
}

/**
 * Stampa i tag di un post come badge
 */
function e3pr0m_post_tags() {
    $tags = get_the_tags();
    if ( ! $tags ) return;
    echo '<div class="tags">';
    foreach ( $tags as $tag ) {
        printf(
            '<a href="%s" class="tag">%s</a>',
            esc_url( get_tag_link( $tag->term_id ) ),
            esc_html( $tag->name )
        );
    }
    echo '</div>';
}

/**
 * Stampa la data di pubblicazione formattata
 */
function e3pr0m_post_date() {
    printf(
        '<time class="post-date" datetime="%s">%s</time>',
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date( 'd M Y' ) )
    );
}
