<?php
/**
 * Template: Single Post
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

get_header();
?>

<section class="section">
  <div class="container container--narrow">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>

      <header class="single-post__header">
        <?php e3pr0m_post_tags(); ?>
        <h1 class="single-post__title"><?php the_title(); ?></h1>
        <div class="single-post__meta">
          <?php e3pr0m_post_date(); ?>
        </div>
      </header>

      <?php if ( has_post_thumbnail() ) : ?>
      <figure class="single-post__thumbnail">
        <?php the_post_thumbnail( 'e3pr0m-hero' ); ?>
      </figure>
      <?php endif; ?>

      <div class="single-post__content prose">
        <?php the_content(); ?>
      </div>

    </article>

    <!-- Navigazione post precedente/successivo -->
    <nav class="post-navigation">
      <?php
      the_post_navigation( [
          'prev_text' => '&larr; %title',
          'next_text' => '%title &rarr;',
      ] );
      ?>
    </nav>

    <?php endwhile; endif; ?>

  </div>
</section>

<?php get_footer(); ?>
