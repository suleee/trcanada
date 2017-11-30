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









            <div id="post-nav">
    <?php $prevPost = get_previous_post(true);
        if($prevPost) {
            $args = array(
                'post_type' => 'best',
                'posts_per_page' => 1,
                'include' => $prevPost->ID
            );
            $prevPost = get_posts($args);
            foreach ($prevPost as $post) {
                setup_postdata($post);
    ?>
        <div class="post-previous">
            <a class="previous" href="<?php the_permalink(); ?>">&laquo; Previous Story</a>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <small><?php the_date('F j, Y'); ?></small>
        </div>
    <?php
                wp_reset_postdata();
            } //end foreach
        } // end if
         
        $nextPost = get_next_post(true);
        if($nextPost) {
            $args = array(
                'post_type' => 'best',
                'posts_per_page' => 1,
                'include' => $nextPost->ID
            );
            $nextPost = get_posts($args);
            foreach ($nextPost as $post) {
                setup_postdata($post);
    ?>
        <div class="post-next">
            <a class="next" href="<?php the_permalink(); ?>">Next Story &raquo;</a>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <small><?php the_date('F j, Y'); ?></strong>
        </div>
    <?php
                wp_reset_postdata();
            } //end foreach
        } // end if
    ?>
</div>




















        <!-- realted article -->
	
			<div class="relatedposts">
    <h3>Related posts</h3>
    <?php
        $orig_post = $post;
        global $post;
        $tags = wp_get_post_tags($post->ID);
        
 
        if ($tags) {
            $tag_ids = array();
            foreach($tags as $individual_tag) {
                $tag_ids[] = $individual_tag->term_id;
            }
                $args=array(
                    'tag__id' => $tags,
                    'post_type' => 'best',
                    'post__not_in' => array($post->ID),
                    'posts_per_page'=>4, // Number of related posts to display.
                    'caller_get_posts'=>1
                );
    
            $my_query = new WP_Query( $args );
    
            while( $my_query->have_posts() ) {
                    $my_query->the_post();
                ?>
        
                <div class="relatedthumb">
                    <a rel="external" href="<? the_permalink()?>"><?php the_post_thumbnail(array(150,100)); ?><br />
                    <?php the_title(); ?>
                    </a>
                </div>
    
            <?php }
        }
        $post = $orig_post;
        wp_reset_query();
        ?>
	</div>
    
    
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
