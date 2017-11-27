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
			<?php
			//change the name of header of the page
			function archive_best_title( $title) {
			if(is_post_type_archive('news')){
				$title = '티알이 뽑은 베스트 랭킹';
				}
				return $title;
				}
				add_filter('get_the_archive_title', 'archive_best_title');
				the_archive_title( '<h1 class="">', '</h1>' );


				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
	
			<ul class=" post-cat-type-list best-type-list">
                    <?php    
                        $terms = get_terms( array(
                                            'taxonomy' => 'news_type',
                                            'orderby' => 'name',
                                        ));

                        foreach ($terms as $term) :
                            $url = get_term_link ($term->slug , 'news_type');              
						?>    
						<li class="post-cat-list">                   
                        <a href='<?php echo $url?>' class='button'>
						
						<p><?php echo $term->name; ?></p></a>
						</li>
                    <?php
                        endforeach;
					?>
					
			</ul>

			</header>

			<?php /* Start the Loop */ ?>
				
				
			<div class="arhive-posts-container best-posts">
				<?php while ( have_posts() ) : the_post(); ?>
				
					<div class="posts">
						<div class="thumbnail-wrapper">
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
