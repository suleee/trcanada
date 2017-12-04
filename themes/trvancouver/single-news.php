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
                <a href="<?php echo get_home_url(); ?>/news"><li>News <i class="fa fa-angle-right" aria-hidden="true"></i></li></a>
                <a href=""><li><?php the_title( '<p class="entry-title">', '</p>' ); ?> </li></a>
            </ul>
            
            
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                <div class="entry-meta">
                    
                <?php red_starter_posted_by(); ?> <?php red_starter_posted_on(); ?>/ <?php red_starter_comment_count(); ?> 
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
        
        

            <div id="post-nav">
    <?php $prevPost = get_previous_post(true);
        if($prevPost) {
            $args = array(
                'post_type' => 'news',
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
                'post_type' => 'news',
                'posts_per_page' => 1,
                'include' => $nextPost->ID
            );
            $nextPost = get_posts($args);
            foreach ($nextPost as $post) {
                setup_postdata($post);
    ?>
        <div class="post-next">
            <a class="next" href="<?php the_permalink(); ?>">Next Story &raquo;</a>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('array( 500, 600'); ?></a>
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
        <h3>Related posts</h3>
			<div class="relatedposts">
    <?php
        $orig_post = $post;
        global $post;
  
        if ($post) {    
            $args=array(
                'post_type' => 'news',
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
