=== e3pr0m Code Snippet ===
Contributors:      e3pr0m
Tags:              code, snippet, syntax highlight, prism, gutenberg
Requires at least: 6.0
Tested up to:      6.5
Requires PHP:      8.2
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Visualizza snippet di codice con syntax highlighting, numeri di riga e copia con un click.

== Description ==

**e3pr0m Code Snippet** aggiunge un blocco Gutenberg e uno shortcode per inserire snippet di codice nei tuoi post e pagine WordPress, con:

* Syntax highlighting via [Prism.js](https://prismjs.com/) (vendor locale, nessuna CDN esterna)
* Numeri di riga opzionali
* Pulsante "Copia" con feedback visivo
* Barra superiore con nome del linguaggio e titolo opzionale
* Tema dark neon coerente col tema e3pr0m
* Asset caricati **solo** nelle pagine che contengono snippet

**Linguaggi supportati:**
plaintext, HTML, XML, SVG, CSS, SCSS, JavaScript, TypeScript, PHP, Python, Bash, SQL, JSON, YAML, Markdown.

= Shortcode =

`[codesnippet lang="php" title="functions.php" show_lines="true" copyable="true"]`
`echo "ciao";`
`[/codesnippet]`

Attributi disponibili:

* `lang` — linguaggio del codice (default: `plaintext`)
* `title` — titolo opzionale mostrato nella barra superiore
* `show_lines` — mostra i numeri di riga (default: `true`)
* `copyable` — mostra il pulsante copia (default: `true`)

= Blocco Gutenberg =

Cerca "Code Snippet" nell'inseritore di blocchi. Tutti gli attributi dello shortcode sono disponibili come controlli nell'inspector panel.

== Installation ==

1. Carica la cartella `e3pr0m-codesnippet` nella directory `/wp-content/plugins/`
2. Attiva il plugin dal pannello **Plugin** di WordPress
3. Copia i file Prism.js in `assets/vendor/prism/` (vedi sezione FAQ)
4. Usa il blocco Gutenberg o lo shortcode `[codesnippet]` nei tuoi contenuti

== Frequently Asked Questions ==

= Come installo Prism.js? =

Vai su [prismjs.com/download.html](https://prismjs.com/download.html), seleziona i linguaggi che ti servono (almeno quelli nell'allowlist del plugin), scarica e copia i file generati in:

`wp-content/plugins/e3pr0m-codesnippet/assets/vendor/prism/prism.min.js`
`wp-content/plugins/e3pr0m-codesnippet/assets/vendor/prism/prism.min.css`

= Il plugin rallenta il sito? =

No. CSS e JavaScript vengono accodati solo nelle pagine che contengono almeno uno snippet. Sulle pagine senza snippet non viene caricato nulla.

= Cosa succede se disattivo o elimino il plugin? =

Disattivando il plugin, i tuoi contenuti rimangono intatti: i tag `[codesnippet]` restano nel database come testo. Eliminando il plugin, viene rimossa solo l'opzione interna `e3cs_version` — nessun contenuto viene cancellato.

= Posso aggiungere altri linguaggi? =

Sì. Apri `inc/class-shortcode.php` e aggiungi la voce corrispondente nell'array restituito da `e3cs_allowed_languages()`. Assicurati di includere il grammar del linguaggio nel build di Prism.js.

== Screenshots ==

1. Snippet PHP con numeri di riga e pulsante copia
2. Blocco Gutenberg nell'editor con anteprima live
3. Inspector panel con i controlli del blocco

== Changelog ==

= 1.0.0 =
* Prima release pubblica
* Shortcode `[codesnippet]` con attributi lang, title, show_lines, copyable
* Blocco Gutenberg con render server-side
* Syntax highlighting via Prism.js (vendor locale)
* Pulsante copia con fallback per browser legacy
* Caricamento condizionale degli asset

== Upgrade Notice ==

= 1.0.0 =
Prima release. Nessuna azione richiesta.
