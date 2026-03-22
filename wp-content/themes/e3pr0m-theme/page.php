<?php
/**
 * Template: Page
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

get_header();
?>

<section class="section">
  <div class="container container--narrow">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <article id="page-<?php the_ID(); ?>" <?php post_class( 'single-page' ); ?>>

      <header class="single-page__header">
        <h1 class="single-page__title"><?php the_title(); ?></h1>
      </header>

      <div class="single-page__content prose">
        <?php the_content(); ?>
      </div>

    </article>

    <?php endwhile; endif; ?>

  </div>
</section>

<?php get_footer(); ?>
