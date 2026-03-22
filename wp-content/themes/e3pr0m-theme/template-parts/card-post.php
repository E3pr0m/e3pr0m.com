<?php
/**
 * Template Part: Card Post Blog
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */
?>

<article class="card card--post">

  <div class="card__meta">
    <?php e3pr0m_post_date(); ?>
  </div>

  <h3 class="card__title">
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </h3>

  <p class="card__description"><?php the_excerpt(); ?></p>

  <a href="<?php the_permalink(); ?>" class="card__readmore">
    <?php esc_html_e( 'Leggi', 'e3pr0m-theme' ); ?> &rarr;
  </a>

</article>
