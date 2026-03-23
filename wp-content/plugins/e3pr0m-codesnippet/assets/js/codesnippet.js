/**
 * e3pr0m Code Snippet — codesnippet.js
 *
 * Responsabilità:
 *  1. Inizializza Prism su tutti gli snippet della pagina
 *  2. Gestisce il pulsante "Copia" con feedback visivo
 *
 * Dipende da:
 *  - prism.min.js (caricato prima, stesso footer)
 *  - e3csData  { copyLabel, copiedLabel }  (wp_localize_script)
 *
 * @package e3pr0m-codesnippet
 */

( function () {

  'use strict';

  // =========================================
  // INIT
  // =========================================

  document.addEventListener( 'DOMContentLoaded', init );

  function init() {
    // Prism si auto-esegue al caricamento, ma lo richiamiamo
    // esplicitamente nel caso di contenuto caricato via AJAX.
    if ( typeof Prism !== 'undefined' ) {
      Prism.highlightAll();
    }

    initCopyButtons();
  }

  // =========================================
  // COPY TO CLIPBOARD
  // =========================================

  function initCopyButtons() {
    const wrappers = document.querySelectorAll( '.codesnippet' );

    wrappers.forEach( function ( wrapper ) {
      const btn = wrapper.querySelector( '[data-copy-target]' );
      if ( ! btn ) return;

      btn.addEventListener( 'click', function () {
        copySnippet( wrapper, btn );
      } );
    } );
  }

  /**
   * Copia il testo dell'elemento <code> nel clipboard.
   * Usa l'API moderna (navigator.clipboard) con fallback
   * al metodo execCommand per browser più vecchi.
   *
   * @param {HTMLElement} wrapper Il .codesnippet contenitore.
   * @param {HTMLElement} btn     Il pulsante premuto.
   */
  function copySnippet( wrapper, btn ) {
    const codeEl = wrapper.querySelector( 'code' );
    if ( ! codeEl ) return;

    const text = codeEl.textContent ?? '';

    if ( navigator.clipboard && window.isSecureContext ) {
      navigator.clipboard.writeText( text )
        .then( function () { showCopiedFeedback( btn ); } )
        .catch( function () { fallbackCopy( text, btn ); } );
    } else {
      fallbackCopy( text, btn );
    }
  }

  /**
   * Fallback execCommand per HTTP o browser senza Clipboard API.
   *
   * @param {string}      text Testo da copiare.
   * @param {HTMLElement} btn  Pulsante per il feedback.
   */
  function fallbackCopy( text, btn ) {
    const ta = document.createElement( 'textarea' );
    ta.value = text;
    ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
    document.body.appendChild( ta );
    ta.focus();
    ta.select();

    try {
      document.execCommand( 'copy' );
      showCopiedFeedback( btn );
    } catch ( err ) {
      // Silenziamo: l'utente può ancora copiare manualmente.
      console.warn( '[e3cs] Copia non riuscita:', err );
    } finally {
      document.body.removeChild( ta );
    }
  }

  /**
   * Mostra il feedback "Copiato!" sul pulsante per 2s.
   *
   * @param {HTMLElement} btn Pulsante su cui mostrare il feedback.
   */
  function showCopiedFeedback( btn ) {
    const labels = window.e3csData ?? {};
    const originalLabel = btn.textContent;

    btn.textContent = labels.copiedLabel ?? 'Copiato!';
    btn.classList.add( 'is-copied' );
    btn.setAttribute( 'disabled', 'disabled' );

    setTimeout( function () {
      btn.textContent = labels.copyLabel ?? originalLabel;
      btn.classList.remove( 'is-copied' );
      btn.removeAttribute( 'disabled' );
    }, 2000 );
  }

} )();
