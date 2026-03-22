# DevForge — Portfolio WordPress by e3pr0m

Sito personale da sviluppatore costruito su WordPress con tema custom e plugin proprietari.

## Struttura

```
wp-content/
├── themes/
│   └── e3pr0m-theme/       # Tema custom dark minimal
└── plugins/
    ├── e3pr0m-projects/     # CPT Progetti con campi custom
    ├── e3pr0m-codesnippet/  # Snippet viewer con syntax highlighting
    └── e3pr0m-devcard/      # Blocco Gutenberg card sviluppatore
```

## Deploy

Il deploy è automatico via **GitHub Actions + FTP** su Aruba Linux Base.

Ad ogni `git push` sul branch `main`, GitHub Actions carica automaticamente
i file aggiornati sul server via FTP.

### Configurazione Secrets

Nel repository GitHub → Settings → Secrets and variables → Actions, aggiungere:

| Secret | Valore |
|---|---|
| `FTP_SERVER` | host FTP Aruba (es. ftp.tuodominio.it) |
| `FTP_USERNAME` | utente FTP |
| `FTP_PASSWORD` | password FTP |

## Sviluppo Locale

1. Installa [Local by Flywheel](https://localwp.com/)
2. Crea un nuovo sito WordPress locale
3. Clona il repo nella cartella `wp-content/` del sito locale
4. Attiva il tema `e3pr0m-theme` e i plugin dal pannello WordPress

## Tecnologie

- **CMS**: WordPress (latest)
- **PHP**: 8.2+
- **CSS**: Custom Properties + BEM + Grid/Flexbox (zero framework)
- **JS**: Vanilla JS
- **Deploy**: GitHub Actions + FTP Deploy Action
- **Hosting**: Aruba Linux Base

## Autore

**e3pr0m** — [tuosito.dev](https://tuosito.dev)
