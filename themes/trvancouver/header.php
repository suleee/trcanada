<?php
/**
 * The header for our theme.
 *
 * @package Trvancouver_Theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	

	<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div id="page" class="hfeed site">
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html( 'Skip to content' ); ?></a>

			<header id="masthead" class="site-header" role="banner">
				<div class="small-menu-container">
				<div class="small-menu">
				<div class="icons">
					<a href="https://www.facebook.com/trcanadadotcom/about/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
					<a href="https://www.instagram.com/trcanadadotcom/?hl=en"><i class="fa fa-instagram" aria-hidden="true"></i></a>
					<a href=""><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
				</div>
				<ul>
				<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'contact us' ) ) ); ?>"><li>CONTACT</li></a>
				<a href="http://kpopme.com/"><li>KPOPME</li></a>
				</ul>
				</div>
				</div>
					<!-- <h1 class="site-title screen-reader-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p> -->
				</div><!-- .site-branding -->
				<div class="menu-container">			
				<div class="logo-search-field">
				<?php echo do_shortcode('[responsive_menu]'); ?>	
				<?php show_easylogo(); ?> 
						<span class="header-search"><?php get_search_form( )?></span>
					</div>

				<nav id="site-navigation" class="main-navigation" role="navigation">
		
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', ) ); ?>
				
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html( 'Primary Menu' ); ?></button>

					</nav><!-- #site-navigation -->
				
					</div>
			</header><!-- #masthead -->

			<div id="content" class="site-content">
