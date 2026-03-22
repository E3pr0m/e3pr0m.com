<?php
/**
 * Template Part: Card Progetto
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

$github = get_post_meta( get_the_ID(), '_project_github', true );
$demo   = get_post_meta( get_the_ID(), '_project_demo',   true );
$tech   = get_post_meta( get_the_ID(), '_project_tech',   true );
?>

<article class="card card--project">

  <?php if ( $tech ) : ?>
  <p class="card__tag"><?php echo esc_html( $tech ); ?></p>
  <?php endif; ?>

  <h3 class="card__title">
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </h3>

  <p class="card__description"><?php the_excerpt(); ?></p>

  <div class="card__footer">
    <?php if ( $github ) : ?>
    <a href="<?php echo esc_url( $github ); ?>" class="btn btn--outline btn--sm" target="_blank" rel="noopener noreferrer">
      GitHub
    </a>
    <?php endif; ?>

    <?php if ( $demo ) : ?>
    <a href="<?php echo esc_url( $demo ); ?>" class="btn btn--primary btn--sm" target="_blank" rel="noopener noreferrer">
      Demo &rarr;
    </a>
    <?php endif; ?>
  </div>

</article>
