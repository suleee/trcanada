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
				
				
			<div class="arhive-posts-container best-posts">
            <?php while ( have_posts() ) : the_post(); ?>
            
                <div class="posts">
                    <div class="thumbnail-wrapper">
					<div class="play-btn"><i class="fa fa-caret-right" aria-hidden="true"></i></div>
         			 <div class="black-box"></div>
                        <a href = "<?php the_permalink(); ?> " rel="bookmark"><?php the_post_thumbnail( ); ?></a>
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
