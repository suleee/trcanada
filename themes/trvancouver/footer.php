<?php
/**
 * The template for displaying the footer.
 *
 * @package Trvancouver_Theme
 */

?>


			</div><!-- #content -->

			<footer id="colophon" class="site-footer" role="contentinfo">
			<div id="footer-widget" class="widget-area footer-widget-area" role="complementary">

<?php if ( function_exists( 'dynamic_sidebar' )|| dynamic_sidebar('footer')):?>
<?php endif; ?>
<?php dynamic_sidebar ('footer'); ?>
</div>
				<div class="instagram-container">
					<!-- <div class="instagram-api">instagram</div> -->
					<div class="footer-sns-contianer">
					<i class="fa fa-facebook" aria-hidden="true"></i> |
					<i class="fa fa-instagram" aria-hidden="true"></i>|
					<i class="fa fa-youtube-play" aria-hidden="true"></i>
				</div>
				</div>

				
					<div class="site-info-container">
					<div class="footer-first-section">
					<p><span> © 2017 TR Canada Vancouver.</span> All right reserved </p>
					<a href="">View available tickets <i class="fa fa-angle-right" aria-hidden="true"></i></a>
					</div>
					<ul class="footer-second-section">
					<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'contact us' ) ) ); ?>"><li>contact</li></a>
						<li> | </li>
						<a href="<?php echo esc_url( get_permalink( get_page_by_title( '광고 및 제휴 문의' ) ) ); ?>"><li>광고 및 제휴 문의</li></a>
						<li> | </li>
						<a href="http://kpopme.com/"><a href=""><li> Kpop me</li></a>
						<li> | </li>
						<li>©<span>TR</span> Canada</li>
					</ul>

				</div><!-- .site-info -->
			</footer><!-- #colophon -->
		</div><!-- #page -->

		<?php wp_footer(); ?>

	</body>
</html>
