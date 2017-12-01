<?php
/**
 * The template for displaying archive for the News post type.
 *
 * @package Trvancouver_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php
        // global $post;
        $args = array(
        'post_type' => 'news',
        'order' => 'DSC',
        'posts_per_page' => 3);
        $product_posts = get_posts( $args ); // returns an array of posts
        ?>
        <?php foreach ( $product_posts as $post ) : setup_postdata( $post ); ?>
        <div class="news-single-container">
          <div class="p-content-div">
        <div class="content-top">
          <div class="content-title">
        <a class="" href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a> 
        </div> 
        <div class="archive-post-date">
        <p>Posted By: <?php red_starter_posted_by(); ?></p> 
        <p>On: <?php red_starter_posted_on(); ?></p> 
        <p>In: <span class="cat-news">News</span></p>
        <p><?php comments_number(); ?></p>
        </div>
        </div>
          <div class="content archive-content-th">
          <div class="archive-post-thumb">
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
