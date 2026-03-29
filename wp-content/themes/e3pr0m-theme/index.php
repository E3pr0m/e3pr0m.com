<?php
/**
 * Template: Index (Homepage / Fallback)
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

get_header();
?>

<!-- ==============================
     HERO
     ============================== -->
<section class="hero">
  <div class="container">
    <p class="hero__label">// Hello World, I'm</p>
    <h1 class="hero__title">
      e3pr0m<em>.</em><br>
      Web Developer<span class="cursor"></span>
    </h1>
    <p class="hero__description">
      <?php bloginfo( 'description' ); ?>
    </p>
    <div class="hero__actions">
  
    <a href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>" class="btn btn--primary">
    &rarr; <?php esc_html_e( 'Vedi i progetti', 'e3pr0m-theme' ); ?>
      </a>
      <a href="<?php echo esc_url( get_page_link( get_page_by_path( 'contatti' ) ) ); ?>" class="btn btn--outline">
        <?php esc_html_e( 'Contattami', 'e3pr0m-theme' ); ?>
      </a>
            <a href="<?php echo esc_url( get_page_link( get_page_by_path( 'Devcard' ) ) ); ?>" class="btn btn--outline">
        <?php esc_html_e( 'DevCard', 'e3pr0m-theme' ); ?>
      </a>
    </div>
  </div>
</section>

<!-- ==============================
     ULTIMI PROGETTI
     ============================== -->
<section class="section">
  <div class="container">
    <h2 class="section__title">
      <span class="section__prefix">// </span>
      <?php esc_html_e( 'Ultimi progetti', 'e3pr0m-theme' ); ?>
    </h2>

    <?php
    $projects = new WP_Query( [
        'post_type'      => 'projects',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );

    if ( $projects->have_posts() ) :
    ?>
    <div class="projects-grid">
      <?php
      while ( $projects->have_posts() ) :
          $projects->the_post();
          get_template_part( 'template-parts/card', 'project' );
      endwhile;
      wp_reset_postdata();
      ?>
    </div>

    <div class="section__cta" style="text-align:center; margin-top: var(--space-xl);">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>" class="btn btn--outline">
        <?php esc_html_e( 'Tutti i progetti', 'e3pr0m-theme' ); ?> &rarr;
      </a>
    </div>

    <?php else : ?>
    <p class="no-content"><?php esc_html_e( 'Nessun progetto trovato.', 'e3pr0m-theme' ); ?></p>
    <?php endif; ?>

  </div>
</section>

<!-- ==============================
     ULTIMI POST BLOG
     ============================== -->
<section class="section" style="border-top: 1px solid var(--color-border);">
  <div class="container">
    <h2 class="section__title">
      <span class="section__prefix">// </span>
      <?php esc_html_e( 'Dal blog', 'e3pr0m-theme' ); ?>
    </h2>

    <?php
    $posts = new WP_Query( [
        'post_type'      => 'post',
        'posts_per_page' => 3,
    ] );

    if ( $posts->have_posts() ) :
    ?>
    <div class="posts-list">
      <?php
      while ( $posts->have_posts() ) :
          $posts->the_post();
          get_template_part( 'template-parts/card', 'post' );
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
    <?php else : ?>
    <p class="no-content"><?php esc_html_e( 'Nessun articolo ancora.', 'e3pr0m-theme' ); ?></p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
