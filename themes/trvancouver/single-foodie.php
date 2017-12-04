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
		
				<ul class="page-root">
				<a href="<?php echo get_home_url(); ?>"><li>Home <i class="fa fa-angle-right" aria-hidden="true"></i></li></a>
				<a href="<?php echo get_home_url(); ?>/foodie"><li>Foodie <i class="fa fa-angle-right" aria-hidden="true"></i></li></a>
				<a href=""><li><?php the_title( '<p class="entry-title">', '</p>' ); ?> </li></a>
			</ul>
			
			
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<div class="entry-meta">
					
				<?php red_starter_posted_by(); ?> <?php red_starter_posted_on(); ?> <?php red_starter_comment_count(); ?> 
					</div><!-- .entry-meta -->
					<?php echo do_shortcode('<div class="single-sns-top">[mashshare]</div>'); ?>
				
				<div class="feature-image"><?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large' ); ?>
					<?php endif; ?>
			</div>
			
					
					
				</header><!-- .entry-header -->
			
				<div class="entry-content">
					<?php the_content(); ?>
					<?php
						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html( 'Pages:' ),
							'after'  => '</div>',
						) );
					?>
				</div><!-- .entry-content -->
			
				<div>
				Tags:
				<?php
					// echo get_the_tag_list('',', ','');
				?> 
			<?php
			$posttags = get_the_tags();
			if ($posttags) {
			  foreach($posttags as $tag) {
				  echo "<a href='?slug=".$tag->slug."'>".$tag->name."</a>, ";
				//   var_dump($tag);
				// echo $tag->name . ' '; 
			  }
			}
			?>
				</div>
			
				<div class="rotatot-single-post-container">
				<div class="rotator rotator-single-post">
				  <?php echo adrotate_group(4); ?>
				</div>
				
				<div class="rotator rotator-single-post">
				  <?php echo adrotate_group(5); ?>
				</div>
				</div>
				
				<?php echo do_shortcode('<div class="single-sns-bottom">[mashshare]</div>'); ?>
			
			
				<div class="p-n-article-container">
				<div>
					<h3>Previous posts</h3>
					<?php previous_post_link('<strong>%link</strong>', '%title', ''); ?> 
						</div>   
				<div>
				<h3>Next posts</h3>
				<?php next_post_link('<strong>%link</strong>', '%title'); ?>
						</div>
						</div>
		
						</article><!-- #post-## -->    

			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->

		<?php get_sidebar(); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>
