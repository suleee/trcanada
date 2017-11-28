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
				$title = 'news';
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

			<?php
        // global $post;
        $args = array(
        'post_type' => 'news',
        'order' => 'DSC',
        'posts_per_page' => 3);
        $product_posts = get_posts( $args ); // returns an array of posts
        ?>
        <?php foreach ( $product_posts as $post ) : setup_postdata( $post ); ?>
        <div class="best-single-container">
          <div class="p-content-div">
        <div class="content-top">
          <div class="content-title">
        <a class="" href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a> 
        </div> 
        <div class="front-best-post-date">
        <p>Posted By: <?php red_starter_posted_by(); ?></p> 
        <p>On: <?php red_starter_posted_on(); ?></p> 
        <p>In: <span class="cat-best">Best</span></p>
        <p><?php comments_number(); ?></p>
        </div>
        </div>
          <div class="contetnt firstpage-content-th">
          <div class="best-thumb">
          <?php the_post_thumbnail( 'large' ); ?></div>
          
          
          <div class="content-p-container">
            <p class="content-p">
          <?php
            $content = get_the_content();
            $content = strip_tags($content);
            echo substr($content, 0, 280) . "...";
          ?>
          <a class="read-more" href="<?php the_permalink(); ?>"> Read More <i class="fa fa-angle-double-right" aria-hidden="true"></i> </a>
          </p>
        </div>
        </div>  
        </div> 
        <?php echo do_shortcode('[mashshare]'); ?>
        </div>
        <?php endforeach; wp_reset_postdata(); ?>
		  </div>
		  
		<?php endif; ?>
		</main><!-- #main -->
		<?php get_sidebar(); ?>
	</div><!-- #primary -->


<?php get_footer(); ?>
