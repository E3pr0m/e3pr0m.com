<?php
/**
 * Template: Archive Projects
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

get_header();
?>

<section class="section">
  <div class="container">

    <!-- Intestazione archivio -->
    <header class="archive-header">
      <p class="hero__label">// portfolio</p>
      <h1 class="archive-header__title">
        <span class="section__prefix">// </span><?php esc_html_e( 'Tutti i progetti', 'e3pr0m-theme' ); ?>
      </h1>
      <?php if ( get_the_archive_description() ) : ?>
        <div class="archive-header__description">
          <?php the_archive_description(); ?>
        </div>
      <?php endif; ?>
    </header>

    <!-- Filtro per tipo progetto -->
    <?php
    $project_types = get_terms( [
        'taxonomy'   => 'project_type',
        'hide_empty' => true,
    ] );

    if ( ! is_wp_error( $project_types ) && ! empty( $project_types ) ) :
        $current_type = get_query_var( 'project_type' );
    ?>
    <div class="archive-filter">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>"
         class="archive-filter__btn <?php echo ! $current_type ? 'is-active' : ''; ?>">
        <?php esc_html_e( 'Tutti', 'e3pr0m-theme' ); ?>
      </a>
      <?php foreach ( $project_types as $type ) : ?>
        <a href="<?php echo esc_url( get_term_link( $type ) ); ?>"
           class="archive-filter__btn <?php echo $current_type === $type->slug ? 'is-active' : ''; ?>">
          <?php echo esc_html( $type->name ); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Griglia progetti -->
    <?php if ( have_posts() ) : ?>

      <div class="projects-grid">
        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/card', 'project' );
        endwhile;
        ?>
      </div>

      <!-- Paginazione -->
      <nav class="archive-pagination">
        <?php
        the_posts_pagination( [
            'mid_size'  => 2,
            'prev_text' => '&larr; ' . __( 'Precedenti', 'e3pr0m-theme' ),
            'next_text' => __( 'Successivi', 'e3pr0m-theme' ) . ' &rarr;',
        ] );
        ?>
      </nav>

    <?php else : ?>
      <p class="no-content"><?php esc_html_e( 'Nessun progetto trovato.', 'e3pr0m-theme' ); ?></p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
