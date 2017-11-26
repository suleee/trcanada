<?php
/**
* The template for displaying front-page.
*
* @package RED_Starter_Theme
*/

get_header(); ?>

<section class="contnet-continaer front-page">
    <div class="rotator">
      <div class="skipper">
        <i class="fa fa-caret-left prev" aria-hidden="true"></i>
        <i class="fa fa-caret-right next" aria-hidden="true"></i>
      </div>
      <?php echo adrotate_group(1); ?>
    </div>
    <div class="best-posts-container">
          <?php
        // global $post;
        $args = array(
        'post_type' => 'best',
        'order' => 'DSC',
        'posts_per_page' => 3);
        $product_posts = get_posts( $args ); // returns an array of posts
        ?>
        <?php foreach ( $product_posts as $post ) : setup_postdata( $post ); ?>
        <div class="best-single-container">
        <div class="content-top">
          <div class="content-title">
        <a class="" href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a> 
        </div> 
        <div><?php red_starter_posted_on(); ?> / <?php comments_number(); ?>
        </div>
        </div>
          <div class="contetnt firstpage-content-th">
          <div class="best-thumb">
          <?php the_post_thumbnail( 'large' ); ?></div>
          <div class="content-p">
          <?php
            $content = get_the_content();
            $content = strip_tags($content);
            echo substr($content, 0, 400) . "...";
          ?>
          <br/>
          <a class="read-more" href="<?php the_permalink(); ?>"> Read Entry </a>
          </div>
        </div>
        </div>
        <?php endforeach; wp_reset_postdata(); ?>
          </div>

<div class="viewmore-btn"><a href>Show More Posts <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>



  <div class="front-post-contianer fpc-food">
    <h2><span> 맛집 </span></h2>
      <div class="front-posts front-foodposts">
        <?php
        // global $post;
        $args = array(
        'post_type' => 'foodie',
        'order' => 'DSC',
        'posts_per_page' => 4);
        $product_posts = get_posts( $args ); // returns an array of posts
        ?>
        <?php foreach ( $product_posts as $post ) : setup_postdata( $post ); ?>
  <!--grab from content-sigle.php-->

        <div class="front-post-single">
        
        <div class="post-thumbnail-wrapper">
        <?php the_post_thumbnail( 'large' ); ?>
        </div>
        <div class="content-title post-th-title">
        <a class="" href="<?php the_permalink(); ?>"><?php the_title( '<h3">', '</h3>' ); ?></a>
        </div>
        </div>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
  </div>


  <div class="front-post-contianer">
    <h2>News</h2>
      <div class="front-posts front-newsposts">
        <?php
        // global $post;
        $args = array(
        'post_type' => 'news',
        'order' => 'DSC',
        'posts_per_page' => 4);
        $product_posts = get_posts( $args ); // returns an array of posts
        ?>
        <?php foreach ( $product_posts as $post ) : setup_postdata( $post ); ?>
  <!--grab from content-sigle.php-->

        <div class="front-post-single">
        
        <div class="post-thumbnail-wrapper">
        <?php the_post_thumbnail( 'large' ); ?>
        </div>
        <div class="content-title post-th-title">
        <a class="" href="<?php the_permalink(); ?>"><?php the_title( '<h3">', '</h3>' ); ?></a>
        </div>
        </div>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
  </div>

 
</section>
<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
