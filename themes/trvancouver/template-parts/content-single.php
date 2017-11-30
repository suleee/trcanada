<?php
/**
 * Template part for displaying single posts.
 *
 * @package Trvancouver_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">


<p>home >> best >>	list</p>
<?php
$categories = get_terms( array(
	'taxonomy' => 'best_type',
	'orderby' => 'name',
	'hide_empty' => true,
));
$separator = ' ';
$output = '';
if ( ! empty( $categories ) ) {
    foreach( $categories as $category ) {
        $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
    }
    echo trim( $output, $separator );
}
?>
	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	<div class="entry-meta">
	<?php red_starter_posted_by(); ?> <?php red_starter_posted_on(); ?>/ <?php red_starter_comment_count(); ?> 
		</div><!-- .entry-meta -->
		<?php echo do_shortcode('[mashshare]'); ?>
	
	<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large' ); ?>
		<?php endif; ?>

		
		
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
      <?php echo adrotate_group(11); ?>
	</div>
	
	<div class="rotator rotator-single-post">
      <?php echo adrotate_group(10); ?>
	</div>
	</div>
	
	<?php echo do_shortcode('[mashshare]'); ?>


	<div class="p-n-article-container">
	<div>
		<?php previous_post_link('<strong>%link</strong>', 'Previous article'); ?> 
			</div>   
	<div>
	<?php next_post_link('<strong>%link</strong>', 'Next article'); ?>
			</div>
			</div>



		


</article><!-- #post-## -->
