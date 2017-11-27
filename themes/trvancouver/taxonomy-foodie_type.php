
<?php
/**
* The template for displaying archive for the ..
*
* @package RED_Starter_Theme
*/

get_header(); ?>

    <div id="primary" class="content-area tax-product">
        <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

        <header class="page-header">
        
                <?php $term = get_queried_object(); ?>
                    <h1><?php echo $term -> name; ?> </h1>
                        <?php the_archive_description( '<div class="taxonomy-description">', '</div>' );?>

        


                        <ul class="post-cat-type-list best-type-list">
                    <?php    
                        $terms = get_terms( array(
                                            'taxonomy' => 'foodie_type',
                                            'orderby' => 'name',
                                        ));

                        foreach ($terms as $term) :
                            $url = get_term_link ($term->slug , 'foodie_type');              
                    	?>    
						<li class="post-cat-list">                   
                        <a href='<?php echo $url?>' class='button'>
						
						<p><?php echo $term->name; ?></p></a>
						</li>
                    <?php
                        endforeach;
					?>
					
			</ul>

            </header><!-- .page-header -->
            
            <!--<?php /* Start the Loop */ ?>-->
                
                
            <div class="best-posts">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="posts">
                        <div class="thumbnail-wrapper">
                            <a href = "<?php the_permalink(); ?> " rel="bookmark"><?php the_post_thumbnail( 'large' ); ?></a>
                        </div>

                        <div class="title">
                            <?php the_title(); ?>
                            <?php echo CFS()->get( 'cost' ); ?>
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
