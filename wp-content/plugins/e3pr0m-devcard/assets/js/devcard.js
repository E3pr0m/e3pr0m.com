/**
 * e3pr0m DevCard — devcard.js
 *
 * Responsabilità:
 *  1. Fallback avatar: se l'immagine non si carica,
 *     mostra un placeholder con le iniziali del nome.
 *
 * Non dipende da librerie esterne.
 *
 * @package e3pr0m-devcard
 */

( function () {

  'use strict';

  // =========================================
  // INIT
  // =========================================

  document.addEventListener( 'DOMContentLoaded', init );

  function init() {
    initAvatarFallback();
  }

  // =========================================
  // AVATAR FALLBACK
  // =========================================

  /**
   * Per ogni img.devcard__avatar:
   *  - se il src è vuoto o la richiesta fallisce,
   *    sostituisce l'img con un div .devcard__avatar-placeholder
   *    che mostra le iniziali memorizzate in data-initials.
   */
  function initAvatarFallback() {
    const avatars = document.querySelectorAll( '.devcard__avatar' );

    avatars.forEach( function ( img ) {

      // Img già in errore (es. src vuoto, caricamento sincrono fallito)
      if ( ! img.src || img.naturalWidth === 0 && img.complete ) {
        swapToPlaceholder( img );
        return;
      }

      img.addEventListener( 'error', function () {
        swapToPlaceholder( img );
      } );
    } );
  }

  /**
   * Sostituisce l'img con un div placeholder iniziali.
   *
   * @param {HTMLImageElement} img L'img da sostituire.
   */
  function swapToPlaceholder( img ) {
    const wrap     = img.closest( '.devcard__avatar-wrap' );
    if ( ! wrap || wrap.querySelector( '.devcard__avatar-placeholder' ) ) return;

    const initials = img.dataset.initials || '?';

    const placeholder = document.createElement( 'div' );
    placeholder.className   = 'devcard__avatar-placeholder';
    placeholder.textContent = initials;
    placeholder.setAttribute( 'aria-hidden', 'true' );

    img.replaceWith( placeholder );
  }

} )();
