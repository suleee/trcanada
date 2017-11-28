<?php
/**
 * The template for displaying all single posts.
 *
 * @package Trvancouver_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>

			

			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // End of the loop. ?>


		<footer class="social-icon">
                        <div class="social-media-wrapper">
                            <a class="social-media"><i class="fa fa-facebook" aria-hidden="true"></i> Like</a>
                            <a class="social-media"><i class="fa fa-twitter" aria-hidden="true"></i> Tweet</a>
                            <a class="social-media"><i class="fa fa-pinterest" aria-hidden="true"></i> Pin</a>
                        </div>
                </footer>
			<?php if ( comments_open() || get_comments_number() ) :
    comments_template();
endif;
			?>
		</main><!-- #main -->

		<?php get_sidebar(); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>
