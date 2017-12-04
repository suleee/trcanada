<?php
/**
 * Template part for displaying single posts.
 *
 * @package Trvancouver_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		



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
