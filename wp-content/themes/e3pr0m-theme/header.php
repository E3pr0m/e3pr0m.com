<?php
/**
 * Template: Header
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); 
  
  ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">
  <?php esc_html_e( 'Vai al contenuto', 'e3pr0m-theme' ); ?>
</a>

<header class="site-header" role="banner">
  <div class="container">
    <div class="site-header__inner">

      <!-- Logo -->
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
        <span>&gt;</span><?php bloginfo( 'name' ); ?><span class="cursor"></span>
      </a>

      <!-- Navigazione -->
      <nav class="nav" role="navigation" aria-label="<?php esc_attr_e( 'Navigazione principale', 'e3pr0m-theme' ); ?>">
        <?php e3pr0m_nav_links(); ?>
      </nav>

    </div>
  </div>
</header>

<div id="page" class="site">
<main id="main" class="site-main" role="main">
