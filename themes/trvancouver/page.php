<?php
/**
 * The template for displaying all pages.
 *
 * @package Trvancouver_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<div class="title">
							<?php the_title('<h3>', '</h3>'); ?>
						</div>

					<div class="posts">
						<div class="thumbnail-wrapper">
							<?php the_post_thumbnail( ); ?>
						</div>
						<?php the_content(); ?>

					
					</div>
				<?php endwhile; ?>
		

		</main><!-- #main -->
		<?php get_sidebar(); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>

