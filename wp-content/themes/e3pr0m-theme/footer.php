<?php
/**
 * Template: Footer
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */
?>

</main><!-- #main -->
</div><!-- #page -->

<footer class="site-footer" role="contentinfo">
  <div class="container">
    <p class="site-footer__text">
      &copy; <?php echo esc_html( date( 'Y' ) ); ?> 
      <span><?php bloginfo( 'name' ); ?></span> — 
      <?php esc_html_e( 'Sviluppato con', 'e3pr0m-theme' ); ?> 
      <span>&#9829;</span> 
      <?php esc_html_e( 'da', 'e3pr0m-theme' ); ?> 
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">e3pr0m</a>
    </p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
