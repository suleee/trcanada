<?php
/**
 * The template for displaying archive for the Best post type.
 *
 * @package RED_Starter_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

		<header class="page-header">

			</header>

			<?php /* Start the Loop */ ?>
				
				
			<div class="arhive-posts-container trtv-posts">
            <?php while ( have_posts() ) : the_post(); ?>
            
                <div class="posts">
				<div class="post-thumbnail-wrapper trtv-tb-w-bg">
				<div class="play-btn">
				  <img src="<?php echo get_template_directory_uri(); ?>/img/triangle.png"/>
				</div>
						<a href = "<?php the_permalink(); ?> " rel="bookmark">
						
						<?php the_post_thumbnail( ); ?></a>
                    </div>

                    <div class="title">
                        <?php the_title('<h3>', '</h3>'); ?>
                        <div><?php red_starter_posted_on(); ?> </div>
                    
                    </div>
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
