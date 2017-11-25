<?php
/*
Plugin Name: Heateor Social Comments
Plugin URI: https://www.heateor.com
Description: Enable Facebook Comments, Google Plus Comments, Disqus Comments along with default WordPress Comments.
Version: 1.4.11
Author: Team Heateor
Author URI: https://www.heateor.com
Text Domain: heateor-social-comments
Domain Path: /languages
License: GPL2+
*/
defined( 'ABSPATH' ) or die( "Cheating........Uh!!" );
define( 'HEATEOR_SOCIAL_COMMENTS_VERSION', '1.4.11' );

$heateor_sc_options = get_option( 'heateor_sc' );

//include shortcode
require 'inc/shortcode.php';

/**
 * Hook the plugin function on 'init' event.
 */
function heateor_sc_init() {
	add_action( 'wp_enqueue_scripts', 'heateor_sc_frontend_styles' );
	global $heateor_sc_options;
	if( isset( $heateor_sc_options['enable_facebookcomments'] ) || isset( $heateor_sc_options['enable_googlepluscomments'] ) || isset( $heateor_sc_options['enable_disquscomments'] ) ) {
		add_filter( 'comments_template', 'heateor_sc_social_commenting' );
	}
}
add_action( 'init', 'heateor_sc_init' );

/**
 * Render Social Commenting
 */
function heateor_sc_social_commenting( $file ) {
	if ( ( is_single() || is_page() || is_singular() ) && comments_open() ) {
		// if password is required, return
		if ( post_password_required() ) {
			echo '<p>'.__( 'This is password protected.', 'heateor-social-comments' ).'</p>';
			return plugin_dir_path( __FILE__ ) . '/inc/comments.php';
		}

		// check if social comments are enabled at this post type
		global $post, $heateor_sc_options;
		
		$comments_meta = '';
		if ( ! is_front_page() || ( is_front_page() && 'page' == get_option( 'show_on_front' ) ) ) {
			$comments_meta = get_post_meta( $post->ID, '_heateor_sc_meta', true );
			if ( isset( $comments_meta['disable_comments'] ) ) {
				return $file;
			}
		}

		$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
		if ( count( $post_types ) > 0 && isset( $post->post_type ) && ! isset( $heateor_sc_options['enable_' . $post->post_type] ) ) {
			return $file;
		}

		global $heateor_sc_options;
		$commentsOrder = $heateor_sc_options['commenting_order'];
		$commentsOrder = explode( ',', $commentsOrder );
		
		$tabs = '';
		$divs = '';
		foreach( $commentsOrder as $key => $order ) {
			$commentsOrder[$key] = trim( $order );
			if ( ! isset( $heateor_sc_options['enable_' .$order. 'comments'] ) ) { unset($commentsOrder[$key]); }
		}
		$orderCount = 0;
		foreach( $commentsOrder as $order ) {
			
			$comment_div = '';
			if ( $order == 'wordpress' ) {
				if ( isset( $heateor_sc_options['counts'] ) ) {
					$comments_count = heateor_sc_get_wp_comments_count();
				}
				$comment_div = '<div style="clear:both"></div>' . heateor_sc_render_wp_comments( $file ) . '<div style="clear:both"></div>';
			} elseif ( $order == 'facebook' ) {
				if ( isset( $heateor_sc_options['counts'] ) ) {
					$comments_count = heateor_sc_get_fb_comments_count();
				}
				$comment_div = heateor_sc_render_fb_comments();
			} elseif ( $order == 'googleplus' ) {
				if ( isset( $heateor_sc_options['counts'] ) ) {
					$comments_count = heateor_sc_get_gp_comments_count();
				}
				$comment_div = '<div style="clear:both"></div>' . heateor_sc_render_gp_comments() . '<div style="clear:both"></div>';
			} else {
				if ( isset( $heateor_sc_options['counts'] ) ) {
					$comments_count = heateor_sc_get_dq_comments_count();
				}
				$comment_div = heateor_sc_render_dq_comments();
			}

			$divs .= '<div ' . ( $orderCount != 0 ? 'style="display:none"' : '' ) . ' id="heateor_sc_' . $order . '_comments">' . ( isset( $heateor_sc_options['commenting_layout'] ) && $heateor_sc_options['commenting_layout'] == 'stacked' && isset( $heateor_sc_options['label_' . $order . '_comments'] ) ? '<h3 class="comment-reply-title">' . $heateor_sc_options['label_' . $order . '_comments'] . ( isset( $comments_count ) ? ' (' . intval( $comments_count ) . ')' : '' ) . '</h3>' : '' );
			$divs .= $comment_div;
			$divs .= '</div>';

			if ( ! isset( $heateor_sc_options['commenting_layout'] ) || $heateor_sc_options['commenting_layout'] == 'tabbed' ) {
				$tabs .= '<li><a ' . ( $orderCount == 0 ? 'class="heateor-sc-ui-tabs-active"' : '' ) . ' id="heateor_sc_' . $order . '_comments_a" href="javascript:void(0)" onclick="this.setAttribute(\'class\', \'heateor-sc-ui-tabs-active\');document.getElementById(\'heateor_sc_' . $order . '_comments\').style.display = \'block\';';
				foreach ($commentsOrder as $commenting) {
					if($commenting != $order){
						$tabs .= 'document.getElementById(\'heateor_sc_' . $commenting . '_comments_a\').setAttribute(\'class\', \'\');document.getElementById(\'heateor_sc_' . $commenting . '_comments\').style.display = \'none\';';
					}
				}
				$tabs .= '">';
				// icon
				if ( isset( $heateor_sc_options['enable_' . $order . 'icon'] ) || ( ! isset( $heateor_sc_options['enable_' . $order . 'icon'] ) && ! isset( $heateor_sc_options['label_' . $order . '_comments'] ) ) ) {
					$alt = isset( $heateor_sc_options['label_' . $order . '_comments'] ) ? $heateor_sc_options['label_' . $order . '_comments'] : ucfirst( $order ) . ' Comments';
					$tabs .= '<div title="'. $alt .'" class="heateor_sc_' . $order . '_background"><i class="heateor_sc_' . $order . '_svg"></i></div>';
				}
				// label
				if ( isset( $heateor_sc_options['label_' . $order . '_comments'] ) ) {
					$tabs .= '<span class="heateor_sc_comments_label">' . $heateor_sc_options['label_' . $order . '_comments'] . '</span>';
				}
				$tabs .= ( isset( $comments_count ) ? ' (' . $comments_count . ')' : '' ) . '</a></li>';
				
				$orderCount++;
			}
			
		}
		$commentingHtml = '<div class="heateor_sc_social_comments">';
		if ( $tabs ) {
			$commentingHtml .= ( isset( $heateor_sc_options['commenting_label'] ) ? '<div style="clear:both"></div><h3 class="comment-reply-title">' . $heateor_sc_options['commenting_label'] . '</h3><div style="clear:both"></div>' : '' ) . '<ul class="heateor_sc_comments_tabs">' . $tabs . '</ul>';
		}
		$commentingHtml .= $divs;
		$commentingHtml .= '</div>';
		echo $commentingHtml;
		// hack to return empty string
		return plugin_dir_path( __FILE__ ) . '/inc/comments.php';
	}
	return $file;
}

/**
 * Get WordPress Comments count
 */
function heateor_sc_get_wp_comments_count() {
	global $post;
	$comments_count = wp_count_comments( $post->ID );
	return ( $comments_count && isset( $comments_count -> approved ) ? $comments_count -> approved : 0 );
}

/**
 * Get Facebook Comments count
 */
function heateor_sc_get_fb_comments_count() {
	global $heateor_sc_options;
	if ( isset( $heateor_sc_options['urlToComment'] ) && $heateor_sc_options['urlToComment'] != '' ) {
		$url = $heateor_sc_options['urlToComment'];
	} else {
		$url = heateor_sc_get_current_page_url();
	}
	return '<fb:comments-count href='. $url .'></fb:comments-count>';
}

/**
 * Get Google Plus Comments count
 */
function heateor_sc_get_gp_comments_count() {
	global $heateor_sc_options;
	$webpage_url = isset( $heateor_sc_options['gpcomments_url'] ) && $heateor_sc_options['gpcomments_url'] ? $heateor_sc_options['gpcomments_url'] : heateor_sc_get_current_page_url();
	$response = wp_remote_get( 'https://apis.google.com/_/widget/render/commentcount?bsv&usegapi=1&href=' . urlencode( $webpage_url ) );
	if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) { 
		return '0';
	}
	$body = $response['body'];
	$count = explode( "<span>", $body );
	$count = $count[1];
	$count = explode( " ", trim( $count ) );
	return $count[0];
}

/**
 * Get Disqus Comments count
 */
function heateor_sc_get_dq_comments_count(){
	global $heateor_sc_options;
	if ( ! $heateor_sc_options['dq_key'] || ! $heateor_sc_options['dq_shortname'] ) {
		return 0;
	}
	$response = wp_remote_get( 'https://disqus.com/api/3.0/threads/set.json?api_key=' . $heateor_sc_options['dq_key'] . '&forum=' . $heateor_sc_options["dq_shortname"] . '&thread=link:' . urlencode( heateor_sc_get_current_page_url() ) );
	if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
		return '0';
	}
	$json = json_decode( $response['body'] );
	return isset( $json->response[0] ) && isset( $json->response[0]->posts ) ? $json->response[0]->posts : 0;	
}

/**
 * Get current page url
 */
function heateor_sc_get_current_page_url() {
	global $post;
	if ( isset( $post -> ID ) && $post -> ID ) {
		return get_permalink( $post -> ID );
	} else {
		return html_entity_decode( esc_url( heateor_sc_get_http().$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
	}
}

/**
 * Get http/https protocol at the website
 */
function heateor_sc_get_http() {
	if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
		return "https://";
	} else {
		return "http://";
	}
}

/**
 * Render Disqus Comments
 */
function heateor_sc_render_dq_comments() {
	global $heateor_sc_options;
	$shortname = isset( $heateor_sc_options['dq_shortname'] ) && $heateor_sc_options['dq_shortname'] != '' ? $heateor_sc_options['dq_shortname'] : '';
	return '<div class="embed-container clearfix" id="disqus_thread">' . ( $shortname != '' ? $shortname : '<div style="font-size: 14px;clear: both;">' . __( 'Specify a Disqus shortname in Super Socializer &gt; Social Commenting section in admin panel', 'heateor-social-comments' ) . '</div>' ) . '</div><script type="text/javascript">var disqus_shortname = "' . $shortname . '";(function(d) {var dsq = d.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js"; (d.getElementsByTagName("head")[0] || d.getElementsByTagName("body")[0]).appendChild(dsq); })(document);</script>';
}

/**
 * Render Google Plus Comments
 */
function heateor_sc_render_gp_comments() {
	global $heateor_sc_options;
	if ( isset( $heateor_sc_options['gpcomments_url'] ) && $heateor_sc_options['gpcomments_url'] != '' ) {
		$url = $heateor_sc_options['gpcomments_url'];
	} else {
		$url = heateor_sc_get_current_page_url();
	}
	return '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><div id="heateor_sc_gpcomments"></div><script type="text/javascript">window.setTimeout(function(){var e="heateor_sc_gpcomments",r="",o="FILTERED_POSTMOD";gapi.comments.render(e,{href:"'. $url .'",first_party_property:"BLOGGER",legacy_comment_moderation_url:r,view_type:o})},10);</script>';
}

/**
 * Render Facebook Comments
 */
function heateor_sc_render_fb_comments() {
	global $heateor_sc_options;
	if ( isset( $heateor_sc_options['urlToComment'] ) && $heateor_sc_options['urlToComment'] != '' ) {
		$url = $heateor_sc_options['urlToComment'];
	} else {
		$url = heateor_sc_get_current_page_url();
	}
	$commentingHtml = '<style type="text/css">.fb-comments,.fb-comments span,.fb-comments span iframe[style]{min-width:100%!important;width:100%!important}</style><div id="fb-root"></div><script type="text/javascript">';
	if ( ( defined( 'HEATEOR_FB_COM_NOT_VERSION' ) && version_compare( '1.1.4', HEATEOR_FB_COM_NOT_VERSION ) > 0 ) || ( defined( 'HEATEOR_FB_COM_MOD_VERSION' ) && HEATEOR_FB_COM_MOD_VERSION == '1.1.4' ) ) {
		$commentingHtml .= 'window.fbAsyncInit=function(){FB.init({appId:"'. ( $heateor_sc_options['fb_app_id'] != '' ? $heateor_sc_options["fb_app_id"] : '' ) .'",channelUrl:"'. site_url() .'//channel.html",status:!0,cookie:!0,xfbml:!0,version:"v2.8"}),FB.Event.subscribe("comment.create",function(e){if(typeof e.commentID != "undefined" && e.commentID){';
		if ( defined( 'HEATEOR_FB_COM_NOT_VERSION' ) && version_compare( '1.1.4', HEATEOR_FB_COM_NOT_VERSION ) > 0 ) {
			$commentingHtml .= 'jQuery.ajax({
	            type: "POST",
	            dataType: "json",
	            url: "' . site_url() . '/index.php",
	            data: {
	                action: "heateor_sc_moderate_fb_comments",
	                data: e
	            },
	            success: function(a,b,c) {}
	        });';
		}
    	if ( defined( 'HEATEOR_FB_COM_MOD_VERSION' ) && HEATEOR_FB_COM_MOD_VERSION == '1.1.4' ) {
    		$commentingHtml .= 'jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: "' . site_url() . '/index.php",
                data: {
                    action: "heateor_fcm_save_fb_comment",
                    data: e
                },
                success: function(a,b,c) {}
            });';
    	}
        $commentingHtml .= '}})};';
	}
	$commentingHtml .= '!function(e,n,t){var o,c=e.getElementsByTagName(n)[0];e.getElementById(t)||(o=e.createElement(n),o.id=t,o.src="//connect.facebook.net/' . ( isset($heateor_sc_options['comment_lang']) && $heateor_sc_options['comment_lang'] != '' ? $heateor_sc_options["comment_lang"] : 'en_US' ) . '/sdk.js#xfbml=1&version=v2.8' . ( $heateor_sc_options['fb_app_id'] != '' ? '&appId=' . $heateor_sc_options["fb_app_id"] : '' ) . '",c.parentNode.insertBefore(o,c))}(document,"script","facebook-jssdk");</script><div class="fb-comments" data-href="' . $url . '" data-colorscheme="' . ( isset($heateor_sc_options['comment_color']) && $heateor_sc_options['comment_color'] != '' ? $heateor_sc_options["comment_color"] : '' ) . '" data-numposts="' . ( isset($heateor_sc_options['comment_numposts']) && $heateor_sc_options['comment_numposts'] != '' ? $heateor_sc_options["comment_numposts"] : '' ) . '" data-width="' . ( isset( $heateor_sc_options['comment_width'] ) && $heateor_sc_options['comment_width'] != '' ? $heateor_sc_options["comment_width"] : '100%' ) . '" data-order-by="' . ( isset($heateor_sc_options['comment_orderby']) && $heateor_sc_options['comment_orderby'] != '' ? $heateor_sc_options["comment_orderby"] : '' ) . '" ></div>';
	return $commentingHtml;
}

/**
 * Render WordPress Comments
 */
function heateor_sc_render_wp_comments( $file ) {
	ob_start();
	if ( file_exists( $file ) ) {
		require $file;
	} elseif ( file_exists( TEMPLATEPATH . $file ) ) {
		require( TEMPLATEPATH . $file );
	} elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/comments.php' ) ) {
		require( ABSPATH . WPINC . '/theme-compat/comments.php');
	}
	return ob_get_clean();
}

/**
 * Stylesheets to load at front end.
 */
function heateor_sc_frontend_styles() {
	global $heateor_sc_options;
	if(isset($heateor_sc_options['css']) && $heateor_sc_options['css']){
		?>
		<style type="text/css"><?php echo $heateor_sc_options['css'] ?></style>
		<?php
	}
	wp_enqueue_style( 'heateor-sc-frontend-css', plugins_url( 'css/front.css', __FILE__ ), false, HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Create plugin menu in admin.
 */	
function heateor_sc_create_admin_menu() {
	$options_page = add_menu_page( 'Heateor - Social Comments', '<b>Social Comments</b>', 'manage_options', 'heateor-sc', 'heateor_sc_option_page', plugins_url( 'images/logo.png', __FILE__ ) );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_sc_admin_scripts' );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_sc_admin_style' );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_sc_fb_sdk_script' );
}
add_action( 'admin_menu', 'heateor_sc_create_admin_menu' );

/**
 * Include javascript files in admin.
 */	
function heateor_sc_admin_scripts(){
	?>
	<script>var heateorScWebsiteUrl = '<?php echo site_url() ?>', heateorScHelpBubbleTitle = "<?php echo __( 'Click to show help', 'heateor-social-comments' ) ?>", heateorScHelpBubbleCollapseTitle = "<?php echo __( 'Click to hide help', 'heateor-social-comments' ) ?>"; </script>
	<?php
	wp_enqueue_script( 'heateor_sc_admin_scripts', plugins_url( 'js/admin/admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable' ) );
}

/**
 * Include CSS files in admin.
 */	
function heateor_sc_admin_style(){
	wp_enqueue_style( 'heateor_sc_admin_style', plugins_url( 'css/admin.css', __FILE__ ), false, HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Include Javascript SDK in admin.
 */	
function heateor_sc_fb_sdk_script(){
	wp_enqueue_script( 'heateor_sc_fb_sdk_script', plugins_url( 'js/admin/fb_sdk.js', __FILE__ ), false, HEATEOR_SOCIAL_COMMENTS_VERSION );
}

function heateor_sc_plugin_settings_fields() {
	register_setting( 'heateor_sc_options', 'heateor_sc', 'heateor_sc_validate_options' );
	// show option to disable sharing on particular page/post
	$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
	if ( count( $post_types ) ) {
		foreach( $post_types as $type ) {
			add_meta_box( 'heateor_sc_meta', 'Heateor Social Comments', 'heateor_sc_comments_meta_setup', $type );
		}
		// save sharing meta on post/page save
		add_action( 'save_post', 'heateor_sc_save_comments_meta' );
	}
}
add_action( 'admin_init', 'heateor_sc_plugin_settings_fields' );

/**
 * Show social comments meta options
 */
function heateor_sc_comments_meta_setup(){
	global $post;
	$post_type = $post->post_type;
	$comments_meta = get_post_meta( $post->ID, '_heateor_sc_meta', true );
	?>
	<p>
		<label for="heateor_sc_comments">
			<input type="checkbox" name="_heateor_sc_meta[disable_comments]" id="heateor_sc_comments" value="1" <?php @checked( '1', $comments_meta['disable_comments'] ); ?> />
			<?php _e( 'Disable Social Comments on this '.$post_type, 'heateor-social-comments' ) ?>
		</label>
	</p>
	<?php
    echo '<input type="hidden" name="heateor_sc_meta_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
}

/**
 * Save social comments meta fields.
 */
function heateor_sc_save_comments_meta( $post_id ) {
    // make sure data came from our meta box
    if ( ! isset( $_POST['heateor_sc_meta_nonce'] ) || ! wp_verify_nonce( $_POST['heateor_sc_meta_nonce'], __FILE__ ) ) {
		return $post_id;
 	}
    // Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	// Return if it's a post revision
	if ( false !== wp_is_post_revision( $post_id ) ) return;
    
    // check user permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

    if ( isset( $_POST['_heateor_sc_meta'] ) ) {
		$options = $_POST['_heateor_sc_meta'];
	} else {
		$options = array();
	}
	update_post_meta( $post_id, '_heateor_sc_meta', $options );

    return $post_id;
}

/**
 * Display notification message when plugin options are saved
 */
function heateor_sc_settings_saved_notification(){
	if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		return '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible below-h2"> 
<p><strong>' . __('Settings saved', 'heateor-social-comments') . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __('Dismiss this notice', 'heateor-social-comments') . '</span></button></div>';
	}
}

/**
 * Plugin options page.
 */	
function heateor_sc_option_page() {
	global $heateor_sc_options;
	echo heateor_sc_settings_saved_notification();
	require 'admin/plugin-options.php';
}

/** 
 * Validate plugin options
 */ 
function heateor_sc_validate_options( $options ) {
	foreach( $options as $k => $v ) {
		if( is_string( $v ) ) {
			$options[$k] = trim( esc_attr( $v ) );
		} elseif( trim( $v ) == '' ) {
			unset( $options[$k] );
		}
	}
	return $options;
}

/**
 * When plugin is activated
 */
function heateor_sc_save_default_options() {
	// counter options
	add_option( 'heateor_sc', array(
	   'commenting_layout' => 'tabbed',
	   'commenting_label' => 'Leave a Reply',
	   'commenting_order' => 'wordpress,facebook,googleplus,disqus',
	   'enable_post' => '1',
	   'enable_page' => '1',
	   'enable_wordpresscomments' => '1',
	   'label_wordpress_comments' => 'Default Comments',
	   'enable_wordpressicon' => '1',
	   'enable_facebookcomments' => '1',
	   'fb_app_id' => '',
	   'label_facebook_comments' => 'Facebook Comments',
	   'enable_facebookicon' => '1',
	   'comment_lang' => get_locale(),
	   'label_googleplus_comments' => 'G+ Comments',
	   'enable_googleplusicon' => '1',
	   'label_disqus_comments' => 'Disqus Comments',
	   'enable_disqusicon' => '1',
	) );

	add_option( 'heateor_sc_version', HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Plugin activation function
 */
function heateor_sc_activate_plugin($network_wide){
	global $wpdb;
	if(function_exists('is_multisite') && is_multisite()){
		//check if it is network activation if so run the activation function for each id
		if($network_wide){
			$old_blog =  $wpdb->blogid;
			//Get all blog ids
			$blog_ids =  $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

			foreach($blog_ids as $blog_id){
				switch_to_blog($blog_id);
				heateor_sc_save_default_options();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	heateor_sc_save_default_options();
}
register_activation_hook(__FILE__, 'heateor_sc_activate_plugin');

/**
 * Save default options for the new subsite created
 */
function heateor_sc_new_subsite_default_options($blog_id, $user_id, $domain, $path, $site_id, $meta){
    if(is_plugin_active_for_network('heateor-social-comments/heateor-social-comments.php')){ 
        switch_to_blog($blog_id);
        heateor_sc_save_default_options();
        restore_current_blog();
    }
}
add_action('wpmu_new_blog', 'heateor_sc_new_subsite_default_options', 10, 6);

/**
 * Upgate database and plugin version based on version check
 */
function heateor_sc_update_db_check(){
	$currentVersion = get_option('heateor_sc_version');

	if($currentVersion && $currentVersion != HEATEOR_SOCIAL_COMMENTS_VERSION){
		if(version_compare('1.4.4', $currentVersion) > 0){
			$pluginOptions = get_option('heateor_sc');
			$pluginOptions['fb_app_id'] = '';
			update_option('heateor_sc', $pluginOptions);
		}

		update_option('heateor_sc_version', HEATEOR_SOCIAL_COMMENTS_VERSION);
	}
}
add_action('plugins_loaded', 'heateor_sc_update_db_check');

/**
 * Ask reason to deactivate the plugin
 */
function heateor_sc_ask_reason_to_deactivate(){
	global $pagenow;
	if(!get_option('heateor_sc_feedback_submitted') && 'plugins.php' === $pagenow){
		?>
		<style type="text/css">
		#heateor_sc_sharing_more_providers{position:fixed;top:45%;left:47%;background:#FAFAFA;width:650px;margin:-180px 0 0 -300px;z-index:10000000;text-shadow:none!important;height:394px}#heateor_sc_popup_bg{background:url(<?php echo plugins_url('images/transparent_bg.png', __FILE__) ?>);bottom:0;display:block;left:0;position:fixed;right:0;top:0;z-index:10000}#heateor_sc_sharing_more_providers .title{font-size:14px!important;height:auto!important;background:#EC1B23!important;border-bottom:1px solid #D7D7D7!important;color:#fff;font-weight:700;letter-spacing:inherit;line-height:34px!important;padding:0!important;text-align:center;text-transform:none;margin:0!important;text-shadow:none!important;width:100%}#heateor_sc_sharing_more_providers *{font-family:Arial,Helvetica,sans-serif}#heateor_sc_sharing_more_content .form-table td{padding:4px 0;}#heateor_sc_sharing_more_providers #heateor_sc_sharing_more_content{background:#FAFAFA;border-radius:4px;color:#555;height:100%;width:100%}#heateor_sc_sharing_more_providers .filter{margin:0;padding:10px 0 0;position:relative;width:100%}#heateor_sc_sharing_more_providers .all-services{clear:both;height:388px;overflow:auto}#heateor_sc_sharing_more_content .all-services ul{margin:10px!important;overflow:hidden;list-style:none;padding-left:0!important;position:static!important;width:auto!important}#heateor_sc_sharing_more_content .all-services ul li{margin:0;background:0 0!important;float:left;width:33.3333%!important;text-align:left!important}#heateor_sc_sharing_more_providers .close-button img{margin:0;}#heateor_sc_sharing_more_providers .close-button.separated{background:0 0!important;border:none!important;box-shadow:none!important;width:auto!important;height:auto!important;z-index:1000}#heateor_sc_sharing_more_providers .close-button{height:auto!important;width:auto!important;left:auto!important;display:block!important;color:#555!important;cursor:pointer!important;font-size:29px!important;line-height:29px!important;margin:0!important;padding:0!important;position:absolute;right:-13px;top:-11px}#heateor_sc_sharing_more_providers .filter input.search{width:94%;display:block;float:none;font-family:"open sans","helvetica neue",helvetica,arial,sans-serif;font-weight:300;height:auto;line-height:inherit;margin:0 auto;padding:5px 8px 5px 10px;border:1px solid #ccc!important;color:#000;background:#FFF!important;font-size:16px!important;text-align:left!important}#heateor_sc_sharing_more_providers .footer-panel{background:#EC1B23!important;border-top:1px solid #D7D7D7;padding:6px 0;width:100%;color:#fff}#heateor_sc_sharing_more_providers .footer-panel p{background-color:transparent;top:0;text-align:left!important;color:#000;font-family:'helvetica neue',arial,helvetica,sans-serif;font-size:12px;line-height:1.2;margin:0!important;padding:0 6px!important;text-indent:0!important}#heateor_sc_sharing_more_providers .footer-panel a{color:#fff;text-decoration:none;font-weight:700;text-indent:0!important}#heateor_sc_sharing_more_providers .all-services ul li a{border-radius:3px;color:#666!important;display:block;font-size:18px;height:auto;line-height:28px;overflow:hidden;padding:8px;text-decoration:none!important;text-overflow:ellipsis;white-space:nowrap;border:none!important;text-indent:0!important;background:0 0!important;text-shadow:none;box-shadow:none!important}#heateor_sc_feedback_skip{background-color: #777;color:#fff;border: none;padding: 4px 28px;border-radius: 5px;cursor: pointer;}#heateor_sc_feedback_submit{color:#fff;background-color: #EC1B23; margin-right: 20px;border: none;padding: 4px 28px;border-radius: 5px;font-weight: bold;cursor: pointer}
			@media screen and (max-width:783px){#heateor_sc_sharing_more_providers{width:80%;left:60%;margin-left:-50%;text-shadow:none!important}}
		</style>
		<script type="text/javascript">
		if(typeof String.prototype.trim!=="function"){String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"")}}

		jQuery(function(){
			jQuery(document).on('click', 'tr#heateor-social-comments span.deactivate a', function(event){
				var deactivateUrl = jQuery(this).attr('href');
				event.preventDefault();
				
				var heateorScMoreSharingServicesHtml = '<h3 class="title ui-drag-handle"><?php _e('Please help us make the plugin better', 'heateor-social-comments') ?></h3><button id="heateor_sc_sharing_popup_close" class="close-button separated"><img src="<?php echo plugins_url('images/close.png', __FILE__) ?>" /></button><div id="heateor_sc_sharing_more_content"><div class="all-services">';
				heateorScMoreSharingServicesHtml += '<div class="metabox-holder columns-2" id="post-body" style="width:100%"><div class="stuffbox" style="margin-bottom:0"><h3><label><?php _e('I am deactivating the plugin because', 'heateor-social-comments');?></label></h3><div class="inside"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table"><tr><td colspan="2"><label for="heateor_sc_reason_1"><input id="heateor_sc_reason_1" name="heateor_sc_deactivate_reason" type="radio" value="1" /><?php _e("I no longer need the plugin", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><label for="heateor_sc_reason_2"><input id="heateor_sc_reason_2" name="heateor_sc_deactivate_reason" type="radio" value="2" /><?php _e("I found a better plugin", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><label for="heateor_sc_reason_3"><input id="heateor_sc_reason_3" name="heateor_sc_deactivate_reason" type="radio" value="3" /><?php _e("I only needed the plugin for a short period", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><input id="heateor_sc_reason_4" name="heateor_sc_deactivate_reason" type="radio" value="4" /><label for="heateor_sc_reason_4"><?php _e("The plugin broke my site", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><input id="heateor_sc_reason_5" name="heateor_sc_deactivate_reason" type="radio" value="5" /><label for="heateor_sc_reason_5"><?php _e("The plugin suddenly stopped working", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><label for="heateor_sc_reason_6"><input id="heateor_sc_reason_6" name="heateor_sc_deactivate_reason" type="radio" value="6" /><?php _e("I couldn\'t understand how to make it work", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><label for="heateor_sc_reason_7"><input id="heateor_sc_reason_7" name="heateor_sc_deactivate_reason" type="radio" value="7" /><?php _e("The plugin is great, but I need specific feature that you don\'t support", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><input id="heateor_sc_reason_8" name="heateor_sc_deactivate_reason" type="radio" value="8" /><label for="heateor_sc_reason_8"><?php _e("The plugin is not working", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><label for="heateor_sc_reason_9"><input id="heateor_sc_reason_9" name="heateor_sc_deactivate_reason" type="radio" value="9" /><?php _e("It\'s not what I was looking for", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><label for="heateor_sc_reason_10"><input id="heateor_sc_reason_10" name="heateor_sc_deactivate_reason" type="radio" value="10" /><?php _e("The plugin didn\'t work as expected", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><input id="heateor_sc_reason_11" name="heateor_sc_deactivate_reason" type="radio" value="11" /><label for="heateor_sc_reason_11"><?php _e("Other", 'heateor-social-comments'); ?></label></td></tr><tr><td colspan="2"><input type="button" id="heateor_sc_feedback_submit" value="Submit" /><input type="button" id="heateor_sc_feedback_skip" value="Skip" /></td><td class="heateor_sc_loading"></td></tr></table></div></div></div>';
				var mainDiv = document.createElement('div');
				mainDiv.innerHTML = heateorScMoreSharingServicesHtml + '</div><div class="footer-panel"><p></p></div></div>';
				mainDiv.setAttribute('id', 'heateor_sc_sharing_more_providers');
				var bgDiv = document.createElement('div');
				bgDiv.setAttribute('id', 'heateor_sc_popup_bg');
				jQuery('body').append(mainDiv).append(bgDiv);
				jQuery('input[name=heateor_sc_deactivate_reason]').click(function(){
					jQuery('div#heateor_sc_reason_details').remove();
					if(jQuery(this).val() == 2){
						var label = 'Plugin name and download link';
					}else{
						var label = 'Details (Optional)';
					}
					jQuery(this).parent().append('<div id="heateor_sc_reason_details"><label>'+ label +'</label><div style="clear:both"></div><textarea id="heateor_sc_reason_details_textarea" rows="5" cols="50"></textarea></div>');
				});
				jQuery('input#heateor_sc_feedback_skip').click(function(){
					location.href = deactivateUrl;
				});
				jQuery('input#heateor_sc_feedback_submit').click(function(){
					var reason = jQuery('input[name=heateor_sc_deactivate_reason]:checked');
					var details = typeof jQuery('#heateor_sc_reason_details_textarea').val() != 'undefined' ? jQuery('#heateor_sc_reason_details_textarea').val().trim() : '';
					if(reason.length == 0){
						alert('<?php _e("Please specify a vaild reason", "Super-Socializer") ?>');
						return false;
					}
					reason = reason.val().trim();
					jQuery("#heateor_sc_feedback_submit").after('<img style="margin-right:20px" src="<?php echo plugins_url('images/ajax_loader.gif', __FILE__) ?>" />')
					jQuery.ajax({
				        type: "GET",
				        dataType: "json",
				        url: '<?php echo get_admin_url() ?>admin-ajax.php',
				        data: {
				            action: "heateor_sc_send_feedback",
				            reason: reason,
				            details: details
				        },
				        success: function(e) {
				            location.href = deactivateUrl;
				        },
				        error: function(e) {
				            location.href = deactivateUrl;
				        }
				    });
				});
				document.getElementById('heateor_sc_sharing_popup_close').onclick = function(){
					mainDiv.parentNode.removeChild(mainDiv);
					bgDiv.parentNode.removeChild(bgDiv);
				}
			});
		});
		</script>
		<?php
	}
}
add_action('admin_footer', 'heateor_sc_ask_reason_to_deactivate');

/**
 * Send feedback to heateor server
 */
function heateor_sc_send_feedback(){
	if(isset($_GET['reason']) && isset($_GET['details'])){
		$reason = trim(esc_attr($_GET['reason']));
		$details = trim(esc_attr($_GET['details']));
		$querystring = array(
			'pid' => 3,
			'r' => $reason,
			'd' => $details
		);
		wp_remote_get('https://www.heateor.com/api/analytics/v1/save?' . http_build_query($querystring), array('timeout' => 15));
		add_option('heateor_sc_feedback_submitted', '1');
	}
	die;
}
add_action('wp_ajax_heateor_sc_send_feedback', 'heateor_sc_send_feedback');

/**
 * Show "Settings" link below plugin name at Plugins page
 */
function heateor_sc_place_settings_link($links){	
	$addons_link = '<a href="https://www.heateor.com/add-ons" target="_blank">' . __('Add-Ons', 'heateor-social-comments') . '</a>';
    $support_link = '<a href="http://support.heateor.com" target="_blank">' . __('Support Documentation', 'heateor-social-comments') . '</a>';
	$settings_link = '<a href="admin.php?page=heateor-sc">' . __('Settings', 'heateor-social-comments') . '</a>';
	// place it before other links
	array_unshift($links, $settings_link);
	$links[] = $addons_link;
	$links[] = $support_link;

	return $links;
}
add_filter('plugin_action_links_heateor-social-comments/heateor-social-comments.php', 'heateor_sc_place_settings_link');

/**
 * Set flag in database if browser message notification has been read
 */
function heateor_sc_plugin_notification_read() {
	update_option( 'heateor_sc_plugin_notification_read', '1' );
	die;
}
add_action( 'wp_ajax_heateor_sc_plugin_notification_read', 'heateor_sc_plugin_notification_read' );

/**
 * Show notification in admin area
 */
function heateor_sc_plugin_notification() {
	if ( current_user_can( 'manage_options' ) ) {
		global $heateor_sc_options;
		if ( ! get_option( 'heateor_sc_plugin_notification_read') && ! isset( $heateor_sc_options['enable_wordpresscomments'] ) && isset( $heateor_sc_options['enable_facebookcomments'] ) && ! isset( $heateor_sc_options['enable_googlepluscomments'] ) && ! isset( $heateor_sc_options['enable_disquscomments'] ) ) {
			?>
			<script type="text/javascript">
			function heateorScBrowserNotificationRead(){
				jQuery.ajax({
					type: 'GET',
					url: '<?php echo get_admin_url() ?>admin-ajax.php',
					data: {
						action: 'heateor_sc_plugin_notification_read'
					},
					success: function(data, textStatus, XMLHttpRequest){
						jQuery('#heateor_sc_plugin_notification').fadeOut();
					}
				});
			}
			</script>
			<div id="heateor_sc_plugin_notification" class="update-nag">
				<h3>Heateor Social Comments</h3>
				<p><?php echo sprintf( __( 'As you are using only Facebook Comments feature of this plugin, you should switch to <a href="%s" target="_blank">Fancy Facebook Comments</a> for better performance', 'heateor-social-comments' ), 'https://wordpress.org/plugins/fancy-facebook-comments' ); ?><a href="https://wordpress.org/plugins/fancy-facebook-comments" target="_blank"><input type="button" style="margin-left: 5px;" class="button button-primary" value="<?php _e( 'Okay', 'heateor-social-comments' ) ?>" /></a><input type="button" onclick="heateorScBrowserNotificationRead()" style="margin-left: 5px;" class="button" value="<?php _e( 'Later', 'heateor-social-comments' ) ?>" /></p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'heateor_sc_plugin_notification' );