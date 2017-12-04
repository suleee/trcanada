<?php
/**
 * The template for displaying archive for the life post type.
 *
 * @package Trvancouver_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<?php
			//change the name of header of the page
			function archive_best_title( $title) {
			if(is_post_type_archive('life')){
				$title = 'life';
				}
				return $title;
				}
				add_filter('get_the_archive_title', 'archive_life_title');
				the_archive_title( '<h1 class="">', '</h1>' );
			?>

			</header>

			<div class="arhive-posts-container life-posts">
				<?php while ( have_posts() ) : the_post(); ?>
				
					<div class="posts">
					<a href = "<?php the_permalink(); ?> " rel="bookmark" class="post-img-tb">
						<div class="thumbnail-wrapper">
							<?php the_post_thumbnail( ); ?>
						</div>

						<div class="title">
							<?php the_title('<h3>', '</h3>'); ?>
							<div><?php red_starter_posted_on(); ?> </div>
						
						</div>
			</a>
					</div>
				<?php endwhile; ?>
			</div>
			<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
		</main><!-- #main -->
		<?php get_sidebar(); ?>
	</div><!-- #primary -->


<?php get_footer(); ?>
