<?php
/**
 * Metabox: campi custom per il CPT "projects"
 *
 * Campi gestiti:
 *  - _project_github  → URL repository GitHub
 *  - _project_demo    → URL demo live
 *  - _project_tech    → Stack tecnologico (testo libero, es. "PHP, WordPress, JS")
 *  - _project_status  → Stato del progetto (select)
 *  - _project_year    → Anno di realizzazione
 *
 * @package e3pr0m-projects
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// REGISTRA METABOX
// =========================================

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'e3pr0m_project_details',
        __( '🔧 Dettagli Progetto', 'e3pr0m-projects' ),
        'e3pr0m_projects_meta_box_render',
        'projects',
        'normal',
        'high'
    );
} );

// =========================================
// RENDER METABOX
// =========================================

function e3pr0m_projects_meta_box_render( WP_Post $post ): void {

    // Nonce per sicurezza
    wp_nonce_field( 'e3pr0m_projects_save_meta', 'e3pr0m_projects_nonce' );

    // Leggi valori salvati
    $github = get_post_meta( $post->ID, '_project_github', true );
    $demo   = get_post_meta( $post->ID, '_project_demo',   true );
    $tech   = get_post_meta( $post->ID, '_project_tech',   true );
    $status = get_post_meta( $post->ID, '_project_status', true );
    $year   = get_post_meta( $post->ID, '_project_year',   true );

    $status_options = [
        ''            => __( '— Seleziona —',    'e3pr0m-projects' ),
        'completed'   => __( 'Completato',        'e3pr0m-projects' ),
        'in-progress' => __( 'In corso',          'e3pr0m-projects' ),
        'archived'    => __( 'Archiviato',         'e3pr0m-projects' ),
        'concept'     => __( 'Concept / Idea',    'e3pr0m-projects' ),
    ];

    ?>
    <div class="e3pr0m-meta-box">

        <div class="e3pr0m-meta-row">
            <label for="project_github">
                <span class="dashicons dashicons-github"></span>
                <?php esc_html_e( 'GitHub URL', 'e3pr0m-projects' ); ?>
            </label>
            <input
                type="url"
                id="project_github"
                name="project_github"
                value="<?php echo esc_attr( $github ); ?>"
                placeholder="https://github.com/username/repo"
                class="e3pr0m-meta-input"
            />
        </div>

        <div class="e3pr0m-meta-row">
            <label for="project_demo">
                <span class="dashicons dashicons-external"></span>
                <?php esc_html_e( 'Demo URL', 'e3pr0m-projects' ); ?>
            </label>
            <input
                type="url"
                id="project_demo"
                name="project_demo"
                value="<?php echo esc_attr( $demo ); ?>"
                placeholder="https://demo.tuosito.dev"
                class="e3pr0m-meta-input"
            />
        </div>

        <div class="e3pr0m-meta-row">
            <label for="project_tech">
                <span class="dashicons dashicons-editor-code"></span>
                <?php esc_html_e( 'Stack tecnologico', 'e3pr0m-projects' ); ?>
                <small><?php esc_html_e( '(es: PHP, WordPress, JavaScript)', 'e3pr0m-projects' ); ?></small>
            </label>
            <input
                type="text"
                id="project_tech"
                name="project_tech"
                value="<?php echo esc_attr( $tech ); ?>"
                placeholder="PHP, WordPress, JS, SCSS…"
                class="e3pr0m-meta-input"
            />
        </div>

        <div class="e3pr0m-meta-row e3pr0m-meta-row--half">

            <div>
                <label for="project_status">
                    <span class="dashicons dashicons-flag"></span>
                    <?php esc_html_e( 'Stato', 'e3pr0m-projects' ); ?>
                </label>
                <select id="project_status" name="project_status" class="e3pr0m-meta-select">
                    <?php foreach ( $status_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="project_year">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    <?php esc_html_e( 'Anno', 'e3pr0m-projects' ); ?>
                </label>
                <input
                    type="number"
                    id="project_year"
                    name="project_year"
                    value="<?php echo esc_attr( $year ); ?>"
                    placeholder="<?php echo esc_attr( date( 'Y' ) ); ?>"
                    min="2000"
                    max="<?php echo esc_attr( date( 'Y' ) + 1 ); ?>"
                    class="e3pr0m-meta-input"
                />
            </div>

        </div>

    </div>
    <?php
}

// =========================================
// SALVA META
// =========================================

add_action( 'save_post_projects', function ( int $post_id ): void {

    // Verifica nonce
    if (
        ! isset( $_POST['e3pr0m_projects_nonce'] ) ||
        ! wp_verify_nonce( $_POST['e3pr0m_projects_nonce'], 'e3pr0m_projects_save_meta' )
    ) {
        return;
    }

    // Non salvare su autosave o revision
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;

    // Permessi
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // ---- GitHub ----
    $github = isset( $_POST['project_github'] )
        ? esc_url_raw( $_POST['project_github'] )
        : '';
    update_post_meta( $post_id, '_project_github', $github );

    // ---- Demo ----
    $demo = isset( $_POST['project_demo'] )
        ? esc_url_raw( $_POST['project_demo'] )
        : '';
    update_post_meta( $post_id, '_project_demo', $demo );

    // ---- Tech ----
    $tech = isset( $_POST['project_tech'] )
        ? sanitize_text_field( $_POST['project_tech'] )
        : '';
    update_post_meta( $post_id, '_project_tech', $tech );

    // ---- Status ----
    $allowed_statuses = [ 'completed', 'in-progress', 'archived', 'concept' ];
    $status = isset( $_POST['project_status'] ) && in_array( $_POST['project_status'], $allowed_statuses, true )
        ? $_POST['project_status']
        : '';
    update_post_meta( $post_id, '_project_status', $status );

    // ---- Year ----
    $year = isset( $_POST['project_year'] )
        ? absint( $_POST['project_year'] )
        : 0;
    update_post_meta( $post_id, '_project_year', $year ?: '' );

} );

// =========================================
// COLONNE PERSONALIZZATE NELLA LIST TABLE
// =========================================

add_filter( 'manage_projects_posts_columns', function ( array $columns ): array {
    // Inserisci dopo "title"
    $new = [];
    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['project_tech']   = __( 'Stack',  'e3pr0m-projects' );
            $new['project_status'] = __( 'Stato',  'e3pr0m-projects' );
            $new['project_year']   = __( 'Anno',   'e3pr0m-projects' );
        }
    }
    return $new;
} );

add_action( 'manage_projects_posts_custom_column', function ( string $column, int $post_id ): void {
    switch ( $column ) {
        case 'project_tech':
            $tech = get_post_meta( $post_id, '_project_tech', true );
            echo $tech ? '<code style="font-size:11px;">' . esc_html( $tech ) . '</code>' : '—';
            break;

        case 'project_status':
            $status = get_post_meta( $post_id, '_project_status', true );
            $labels = [
                'completed'   => [ '✅', 'Completato'  ],
                'in-progress' => [ '🔄', 'In corso'    ],
                'archived'    => [ '📦', 'Archiviato'   ],
                'concept'     => [ '💡', 'Concept'      ],
            ];
            if ( $status && isset( $labels[ $status ] ) ) {
                [ $icon, $label ] = $labels[ $status ];
                echo esc_html( "$icon $label" );
            } else {
                echo '—';
            }
            break;

        case 'project_year':
            $year = get_post_meta( $post_id, '_project_year', true );
            echo $year ? esc_html( $year ) : '—';
            break;
    }
}, 10, 2 );
