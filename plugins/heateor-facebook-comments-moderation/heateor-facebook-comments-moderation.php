<?php
/*
Plugin Name: Facebook Comments Moderation by Heateor
Plugin URI: https://www.heateor.com
Description: Lets you moderate Facebook Comments
Version: 1.2.1
Author: Team Heateor
Author URI: https://www.heateor.com
License: GPL2+
*/
defined( 'ABSPATH' ) or die( "Cheating........Uh!!" );
define( 'HEATEOR_FB_COM_MOD_VERSION', '1.2.1' );
define( 'HEATEOR_FB_COM_MOD_DB_VERSION', '1.1' );
define( 'HEATEOR_FB_COM_MOD_SLUG', 'heateor-facebook-comments-moderation' );

$heateor_fcm_options = get_option( 'heateor_fcm' );
$heateor_fcm_license_options = get_option( 'heateor_fcm_license' );

if ( is_admin() && isset( $heateor_fcm_license_options['license_key'] ) && $heateor_fcm_license_options['license_key'] != '' ) {
	require 'library/plugin-update-checker/plugin-update-checker.php';
	$heateor_fcm_update_checker = PucFactory::buildUpdateChecker(
	    'https://www.heateor.com/api/license-manager/v1/info?l=' . $heateor_fcm_license_options['license_key'] . '&w=' . urlencode( str_replace( array( 'http://', 'https://' ), '', site_url() ) ),
	    __FILE__,
	    HEATEOR_FB_COM_MOD_SLUG
	);
}

// include widget class
require 'includes/widget.php';

$heateor_fcm_access_token = '';
$heateor_fcm_page_fb_id = '';

/**
 * Get Facebook access token
 */
function heateor_fcm_get_access_token() {
    global $heateor_fcm_options, $heateor_fcm_access_token;
	if ( $heateor_fcm_access_token ) {
    	return $heateor_fcm_access_token;
    }
    if ( $heateor_fcm_options['app_id'] && $heateor_fcm_options['app_secret'] ) {
    	$response = wp_remote_get( 'https://graph.facebook.com/oauth/access_token?%20client_id=' . $heateor_fcm_options['app_id'] . '&client_secret=' . $heateor_fcm_options['app_secret'] . '&grant_type=client_credentials', array( 'timeout' => 15 ) );
	    if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			$heateor_fcm_access_token = $access_token = isset( $body->access_token ) ? $body->access_token : '';
			return $access_token;
		}
    }
    return '';
}

/**
 * Make call to Facebook Graph API
 */
function heateor_fcm_graph_api_call( $call_to, $args = array(), $access_token ) {
	$graph_api_version = "https://graph.facebook.com/v2.8/";
	$graph_api_url = $graph_api_version . $call_to . '?access_token=' . $access_token . '&' . http_build_query( $args );
	$response = wp_remote_get( $graph_api_url, array( 'timeout' => 15 ) );
	if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
		return wp_remote_retrieve_body( $response );
	}
	return false;
}

/**
 * Get Facebook page ID of the passed url
 */
function heateor_fcm_get_page_fb_id( $page_url ) {
	$page_id = '';
	$access_token = heateor_fcm_get_access_token();
	if ( $access_token ) {
		$response = wp_remote_get( 'https://graph.facebook.com/?id=' . $page_url, array( 'timeout' => 15 ) );	
		if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
			$response = wp_remote_retrieve_body( $response );
			if ( $response !== false ) {
				$response = json_decode( $response );
				$page_id = isset( $response->og_object ) && isset( $response->og_object->id ) ? $response->og_object->id : '';
			}
		} else if ( isset( $response['response']['code'] ) && 403 === $response['response']['code'] ) {
			$response = wp_remote_retrieve_body( $response );
			if ( $response !== false ) {
				$response = json_decode( $response );
				if ( isset( $response->error ) && isset( $response->error->code ) && $response->error->code == 4 ) {
					update_option( 'heateor_fcm_app_limit', '1' );
				}
			}
		}
	}
	return $page_id;
}

/**
 * Get Facebook Comments made on the passed url
 */
function heateor_fcm_get_fb_comments( $page_url, $page_id ) {
	if ( ! $page_id ) {
		$page_id = heateor_fcm_get_page_fb_id( $page_url );
	}
	if ( $page_id ) {
		global $heateor_fcm_page_fb_id;
		$heateor_fcm_page_fb_id = $page_id;
		$args = array(
			'pretty' => 0,
			'filter' => 'stream',
			'limit' => '500',
			'order' => 'reverse_chronological',
			'fields' => 'message,from,created_time,parent.fields(id)'
		);
		$access_token = heateor_fcm_get_access_token();
		if ( $access_token ) {
			$response = json_decode( heateor_fcm_graph_api_call( $page_id . '/comments', $args, $access_token ) );
			$comments = $response->data;
			if ( is_array( $comments ) && isset( $comments[0]->id ) ) {
				return $comments;
			}
		}
	}

	return false;
}

/**
 * Handle GET, POST requests
 */
function heateor_fcm_handle_request() {
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'heateor_fcm_save_fb_comment' && isset( $_POST['data']['href'] ) && isset( $_POST['data']['message'] ) ) {
		$link = esc_url( trim( $_POST['data']['href'] ) );
		$page_id = isset( $_POST['data']['page_id'] ) ? trim( $_POST['data']['page_id'] ) : '';
		$comments = heateor_fcm_get_fb_comments( $link, $page_id );
		global $heateor_fcm_options, $heateor_fcm_page_fb_id;
		if ( isset( $heateor_fcm_options['recover_comments'] ) ) {
			$recovered_comments = heateor_fcm_get_fb_comments( str_replace( 'https://', 'http://', $link ), $page_id );
			if ( strpos( $link, '?' ) === false && strpos( $link, '&' ) === false ) {
				if ( $link[strlen( $link )-1] == '/' ) {
					$link_variant = substr( $link, 0, -1 );
				} else {
					$link_variant = $link . '/';
				}
				$recovered_comments_for_variant = heateor_fcm_get_fb_comments( str_replace( 'https://', 'http://', $link_variant ), $page_id );
			}
		}
		$placeholders = array();
		$values = array();
		if ( $comments !== false ) {
			foreach ( $comments as $fb_comment ) {
				$placeholders[] = '(%d, %s, %d, %s, %s, %d, %s, %s, %s, "0", %s, "0")';
				$values[] = NULL;
				$values[] = sanitize_text_field( $fb_comment->id );
				$values[] = url_to_postid( $link );
				$values[] = $heateor_fcm_page_fb_id;
				$values[] = sanitize_text_field( $fb_comment->message );
				$values[] = strtotime( sanitize_text_field( $fb_comment->created_time ) );
				$values[] = isset( $fb_comment->from ) && isset( $fb_comment->from->id ) ? sanitize_text_field( $fb_comment->from->id ) : '';
				$values[] = isset( $fb_comment->from ) && isset( $fb_comment->from->name ) ? sanitize_text_field( $fb_comment->from->name ) : '';
				$values[] = $heateor_fcm_options['comment_status'] == 'approved' ? '1' : '0';
				$values[] = isset( $fb_comment->parent ) && isset( $fb_comment->parent->id ) ? sanitize_text_field( $fb_comment->parent->id ) : '';
			}
		}
		if ( isset( $recovered_comments ) && $recovered_comments !== false ) {
			foreach ( $recovered_comments as $fb_comment ) {
				$placeholders[] = '(%d, %s, %d, %s, %s, %d, %s, %s, %s, "0", %s, "1")';
				$values[] = NULL;
				$values[] = sanitize_text_field( $fb_comment->id );
				$values[] = url_to_postid( $link );
				$values[] = $heateor_fcm_page_fb_id;
				$values[] = sanitize_text_field( $fb_comment->message );
				$values[] = strtotime( sanitize_text_field( $fb_comment->created_time ) );
				$values[] = isset( $fb_comment->from ) && isset( $fb_comment->from->id ) ? sanitize_text_field( $fb_comment->from->id ) : '';
				$values[] = isset( $fb_comment->from ) && isset( $fb_comment->from->name ) ? sanitize_text_field( $fb_comment->from->name ) : '';
				$values[] = $heateor_fcm_options['comment_status'] == 'approved' ? '1' : '0';
				$values[] = isset( $fb_comment->parent ) && isset( $fb_comment->parent->id ) ? sanitize_text_field( $fb_comment->parent->id ) : '';
			}
		}
		if ( isset( $recovered_comments_for_variant ) && $recovered_comments_for_variant !== false ) {
			foreach ( $recovered_comments_for_variant as $fb_comment ) {
				$placeholders[] = '(%d, %s, %d, %s, %s, %d, %s, %s, %s, "0", %s, "1")';
				$values[] = NULL;
				$values[] = sanitize_text_field( $fb_comment->id );
				$values[] = url_to_postid( $link );
				$values[] = $heateor_fcm_page_fb_id;
				$values[] = sanitize_text_field( $fb_comment->message );
				$values[] = strtotime( sanitize_text_field( $fb_comment->created_time ) );
				$values[] = isset( $fb_comment->from ) && isset( $fb_comment->from->id ) ? sanitize_text_field( $fb_comment->from->id ) : '';
				$values[] = isset( $fb_comment->from ) && isset( $fb_comment->from->name ) ? sanitize_text_field( $fb_comment->from->name ) : '';
				$values[] = $heateor_fcm_options['comment_status'] == 'approved' ? '1' : '0';
				$values[] = isset( $fb_comment->parent ) && isset( $fb_comment->parent->id ) ? sanitize_text_field( $fb_comment->parent->id ) : '';
			}
		}
		if ( count( $placeholders ) > 0 ) {
			global $wpdb;
			$query = "INSERT INTO {$wpdb->prefix}heateor_facebook_comments VALUES ";
			$query .= implode( ',', $placeholders );
			$query .= " ON DUPLICATE KEY UPDATE from_fb_name = VALUES(from_fb_name)";
			$sql = $wpdb->prepare( $query, $values );
			$wpdb->query( $sql );
		}
		die;
	}
}
add_action( 'parse_request', 'heateor_fcm_handle_request' );

/**
 * Create plugin menu in admin.
 */	
function heateor_fcm_create_admin_menu() {
	$options_page = add_menu_page( 'Heateor - Facebook Comments Moderation', 'FB Comments Moderation', 'manage_options', 'heateor-fcm', 'heateor_fcm_option_page', plugins_url( 'images/logo.png', __FILE__ ) );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_fcm_admin_scripts' );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_fcm_admin_style' );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_fcm_fb_sdk_script' );
	if ( ( is_multisite() && is_main_site() ) || ! is_multisite() ) {
		$license_page = add_submenu_page( 'heateor-fcm', 'License Settings', 'License Settings', 'manage_options', 'heateor-fcm-license-settings', 'heateor_fcm_license_settings_page' );
		add_action( 'admin_print_scripts-' . $license_page, 'heateor_fcm_admin_scripts' );
		add_action( 'admin_print_scripts-' . $license_page, 'heateor_fcm_fb_sdk_script' );
		add_action( 'admin_print_styles-' . $license_page, 'heateor_fcm_admin_style' );
	}
}
add_action( 'admin_menu', 'heateor_fcm_create_admin_menu' );

/**
 * Include javascript files in admin.
 */	
function heateor_fcm_admin_scripts() {
	?>
	<script>var heateorFcmWebsiteUrl = '<?php echo site_url() ?>', heateorFcmHelpBubbleTitle = "<?php echo __( 'Click to show help', 'heateor-fcm-text' ) ?>", heateorFcmHelpBubbleCollapseTitle = "<?php echo __( 'Click to hide help', 'heateor-fcm-text' ) ?>"; </script>
	<?php
	wp_enqueue_script( 'heateor_fcm_admin_scripts', plugins_url( 'js/admin/admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-tabs' ) );
}

/**
 * Include CSS files in admin.
 */	
function heateor_fcm_admin_style() {
	wp_enqueue_style( 'heateor_fcm_admin_style', plugins_url( 'css/admin.css', __FILE__ ), false, HEATEOR_FB_COM_MOD_VERSION );
}

/**
 * Include Javascript SDK in admin.
 */	
function heateor_fcm_fb_sdk_script() {
	wp_enqueue_script( 'heateor_fcm_fb_sdk_script', plugins_url( 'js/admin/fb_sdk.js', __FILE__ ), false, HEATEOR_FB_COM_MOD_VERSION );
}

/**
 * Display notification message when plugin options are saved
 */
function heateor_fcm_plugin_settings_fields() {
	register_setting( 'heateor_fcm_options', 'heateor_fcm', 'heateor_fcm_validate_options' );
	register_setting( 'heateor_fcm_license_options', 'heateor_fcm_license', 'heateor_fcm_validate_options' );
	if( isset( $_POST['heateor_fcm_delete_comment_id'] ) && $_POST['heateor_fcm_delete_comment_id'] != '' && current_user_can( 'manage_options' ) ) {
		global $wpdb;
		$wpdb->update(
			$wpdb->prefix . 'heateor_facebook_comments',
			array( 'is_deleted' => '1' ),
			array( 'id' => trim( intval( $_POST['heateor_fcm_delete_comment_id'] ) ) ),
			array( '%s' ), 
			array( '%d' )
		);
		exit( 0 );
	} elseif( isset( $_POST['heateor_fcm_approve_comment_id'] ) && $_POST['heateor_fcm_approve_comment_id'] != '' && isset( $_POST['heateor_fcm_approve_comment_status'] ) && $_POST['heateor_fcm_approve_comment_status'] != '' && current_user_can( 'manage_options' ) ) {
		global $wpdb;
		$wpdb->update(
			$wpdb->prefix . 'heateor_facebook_comments',
			array( 'is_approved' => $_POST['heateor_fcm_approve_comment_status'] ),
			array( 'id' => trim( intval( $_POST['heateor_fcm_approve_comment_id'] ) ) ),
			array( '%s' ), 
			array( '%d' )
		);
		die( $_POST['heateor_fcm_approve_comment_status'] == '1' ? '0' : '1' );
	}
}
add_action( 'admin_init', 'heateor_fcm_plugin_settings_fields' );

/**
 * Hook the plugin function on 'init' event
 */
function heateor_fcm_init() {
	global $heateor_fcm_options;
	add_action( 'wp_enqueue_scripts', 'heateor_fcm_frontend_styles' );
	add_action( 'wp_enqueue_scripts', 'heateor_fcm_frontend_scripts' );
	add_filter( 'the_content', 'heateor_fcm_display_recovered_comments', $heateor_fcm_options['recovered_comments_priority'] ? intval( $heateor_fcm_options['recovered_comments_priority'] ) : 99 );
}
add_action( 'init', 'heateor_fcm_init' );

/**
 * Display recovered Facebook Comments at the webpages
 */
function heateor_fcm_display_recovered_comments( $content ) {
	global $heateor_fcm_options;
	if ( isset( $heateor_fcm_options['show_recovered_comments'] ) ) {
		global $post, $wpdb;
		if ( isset( $post->post_type ) && isset( $heateor_fcm_options['show_recovered_comments_' . $post->post_type] ) ) {
			$number_of_comments_to_fetch = $heateor_fcm_options['num_recovered_front_comments'] != '' ? intval( $heateor_fcm_options['num_recovered_front_comments'] ) : 50;
			$comments = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'heateor_facebook_comments WHERE wp_post_id = %d AND is_deleted = "0" AND is_approved = "1" AND is_recovered = "1" ORDER BY created_time DESC LIMIT %d', $post->ID, $number_of_comments_to_fetch ) );
			if ( is_array( $comments ) && count( $comments ) > 0 ) {
				$number_of_comments = count( $comments );
				$formatted_comments = array();
				foreach ( $comments as $comment ) {
					$formatted_comment = array();
					$formatted_comment['comment_ID'] = $comment->comment_id;
					$formatted_comment['comment_post_ID'] = $comment->wp_post_id;
					$formatted_comment['comment_author'] = $comment->from_fb_name;
					$formatted_comment['comment_author_url'] = 'https://www.facebook.com/' . $comment->from_fb_id;
					$formatted_comment['comment_date'] = $comment->created_time;
					$formatted_comment['comment_content'] = $comment->comment;
					$formatted_comment['comment_approved'] = 1;
					$formatted_comment['comment_from_fb_id'] = $comment->from_fb_id;
					$formatted_comment['comment_parent'] = $comment->parent_comment_id != '' ? $comment->parent_comment_id : 0;

					$formatted_comments[] = (object) $formatted_comment;
				}
				$facebook_comments = '<div id="heateor_fcm_fb_comments_container">' . ( $heateor_fcm_options['recovered_comments_title'] != '' ? '<div class="heateor_fcm_fb_comments_title">' . str_replace( '%number_of_comments%', $number_of_comments, $heateor_fcm_options['recovered_comments_title'] ) . '</div>' : '' ) . '<ul class="commentlist">' . wp_list_comments( array( 'style' => 'div', 'echo' => false, 'callback' => 'heateor_fcm_format_facebook_comments' ), $formatted_comments ) . '</ul></div>';
				$content .= $facebook_comments;
			}
		}
	}
	return $content;
}

/**
 * Format the recovered Facebook Comments to display at the webpages
 */
function heateor_fcm_format_facebook_comments( $comment, $args, $depth ) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
        <?php if ( $args['avatar_size'] != 0 ) echo '<img src="https://graph.facebook.com/' . $comment->comment_from_fb_id . '/picture" border="0" height="' . $args['avatar_size'] . '" width="' . $args['avatar_size'] . '">'; ?>
        <?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ); ?>
    </div>

    <div class="comment-meta commentmetadata"><a href="javascript:void(0)">
        <time datetime="<?php echo date("c", $comment->comment_date); ?>"><?php echo date("d M Y H:i a", $comment->comment_date); ?> </time>
        <?php
        /* translators: 1: date, 2: time */
        //printf( __( '%1$s at %2$s' ), get_comment_date(),  get_comment_time() );
        ?>
    </a>
    </div>

    <?php comment_text(); ?>

    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
    <?php
}

/**
 * Stylesheets to load at front end.
 */
function heateor_fcm_frontend_styles() {
	global $heateor_fcm_options;
	wp_enqueue_style( 'heateor_fcm_frontend_css', plugins_url( 'css/front.css', __FILE__ ), false, HEATEOR_FB_COM_MOD_VERSION );
	if ( $heateor_fcm_options['custom_css'] ) {
		?>
		<style type="text/css">
		<?php echo $heateor_fcm_options['custom_css']; ?>
		</style>
		<?php
	}
}

/**
 * Javascript to load at front end.
 */
function heateor_fcm_frontend_scripts() {
	global $heateor_fcm_options;
	$in_footer = isset( $heateor_fcm_options['footer_script'] ) ? true : false;
	$app_limit_reached = get_option( 'heateor_fcm_app_limit' ) == 1 ? true : false;
	?>
	<script>var heateorFcmWebsiteUrl = '<?php echo site_url() ?>'; <?php echo $app_limit_reached ? "var heateorFcmAppLimit = 1;" : ""; ?></script>
	<?php
	wp_enqueue_script( 'heateor_fcm_front_js', plugins_url( 'js/front/front.js', __FILE__ ), array( 'jquery' ), HEATEOR_FB_COM_MOD_VERSION, $in_footer );
}

/**
 * Display notification message when plugin options are saved
 */
function heateor_fcm_settings_saved_notification() {
	if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		return '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible below-h2"> 
<p><strong>' . __( 'Settings saved', 'heateor-fcm-text' ) . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice', 'heateor-fcm-text' ) . '</span></button></div>';
	}
}

/**
 * License Options page
 */
function heateor_fcm_license_settings_page() {
	global $heateor_fcm_license_options;
	echo heateor_fcm_settings_saved_notification();
	require 'admin/license-options.php';
}

/**
 * Plugin options page.
 */	
function heateor_fcm_option_page() {
	global $heateor_fcm_options;
	echo heateor_fcm_settings_saved_notification();
	require 'admin/plugin-options.php';
}

/** 
 * Validate plugin options
 */ 
function heateor_fcm_validate_options( $options) {
	foreach ( $options as $k => $v) {
		if ( is_string( $v ) ) {
			$options[$k] = trim( esc_attr( $v ) );
		}
	}
	return $options;
}

/**
 * Default options when plugin is activated
 */
function heateor_fcm_default_options() {
	// plugin defaul options
	add_option( 'heateor_fcm', array(
	   'app_id' => '',
	   'app_secret' => '',
	   'admin_ids' => '',
	   'comment_status' => 'approved',
	   'num_comments' => '50',
	   'custom_css' => '',
	   'recovered_comments_title' => '',
	   'recovered_comments_priority' => '99',
	   'num_recovered_comments' => '100',
	   'num_recovered_front_comments' => '30',
	   'footer_script' => '1',
	) );

	// plugin version
	add_option( 'heateor_fcm_version', HEATEOR_FB_COM_MOD_VERSION );

	// install database
	heateor_fcm_install_database();
	add_option( 'heateor_fcm_db_version', HEATEOR_FB_COM_MOD_DB_VERSION );
}
register_activation_hook( __FILE__, 'heateor_fcm_default_options' );

/**
 * Make changes based on version check
 */
function heateor_fcm_update_db_check() {
	$current_version = get_option( 'heateor_fcm_version' );
	$current_db_version = get_option( 'heateor_fcm_db_version' );
	if ( $current_version && $current_version != HEATEOR_FB_COM_MOD_VERSION ) {
		if ( version_compare( "1.1.8", $current_version ) > 0 ) {
			global $heateor_fcm_options;
			$heateor_fcm_options['recovered_comments_priority'] = '99';
			update_option( 'heateor_fcm', $heateor_fcm_options );
		}

		if ( version_compare( "1.1.7", $current_version ) > 0 ) {
			global $heateor_fcm_options;
			$heateor_fcm_options['footer_script'] = '1';
			update_option( 'heateor_fcm', $heateor_fcm_options );
		}

		if ( version_compare( "1.1.5", $current_version ) > 0 ) {
			global $heateor_fcm_options;
			$heateor_fcm_options['custom_css'] = 'div#heateor_fcm_fb_comments_container .depth-2{margin-left:20px}.heateor_fcm_fb_comments_title{font-weight: bold;margin-bottom: 10px;}';
			$heateor_fcm_options['recovered_comments_title'] = '';
			$heateor_fcm_options['num_recovered_comments'] = '50';
			$heateor_fcm_options['num_recovered_front_comments'] = '50';
			update_option( 'heateor_fcm', $heateor_fcm_options );
		}

		if ( version_compare( "1.1.4", $current_version ) > 0 ) {
			global $heateor_fcm_options;
			$heateor_fcm_options['app_secret'] = '';
			$heateor_fcm_options['comment_status'] = 'approved';
			$heateor_fcm_options['num_comments'] = '50';
			update_option( 'heateor_fcm', $heateor_fcm_options );
		}

		update_option( 'heateor_fcm_version', HEATEOR_FB_COM_MOD_VERSION );
		
		if ( $current_db_version && $current_db_version != HEATEOR_FB_COM_MOD_DB_VERSION ) {
			// install/upgrade add-on database
			heateor_fcm_install_database();
			update_option( 'heateor_fcm_db_version', HEATEOR_FB_COM_MOD_DB_VERSION );
		}
	}
}
add_action( 'plugins_loaded', 'heateor_fcm_update_db_check' );

/**
 * Perform database installation operations
 */
function heateor_fcm_install_database() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'heateor_facebook_comments';
	$charset_collate = $wpdb->get_charset_collate();

	$query = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		comment_id varchar(50) NOT NULL,
		wp_post_id int(11) NOT NULL DEFAULT '0',
		page_fb_id varchar(40) NOT NULL,
		comment text NOT NULL,
		created_time int(11) NOT NULL,
		from_fb_id varchar(30) NOT NULL,
		from_fb_name varchar(30) NOT NULL,
		is_approved enum('0','1') NOT NULL DEFAULT '1',
		is_deleted enum('0','1') NOT NULL DEFAULT '0',
		parent_comment_id varchar(50) NOT NULL,
		is_recovered enum('0','1') NOT NULL DEFAULT '0',
		PRIMARY KEY  (id),
		UNIQUE KEY comment_id (comment_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $query );
}

/**
 * Show notifications related to license in admin area
 */
function heateor_fcm_license_notification() {
	if ( ( is_multisite() && is_main_site() ) || ( ! is_multisite() && current_user_can( 'manage_options' ) ) ) {
		global $heateor_fcm_license_options;
		if ( ( ! isset( $heateor_fcm_license_options['license_key'] ) || $heateor_fcm_license_options['license_key'] == '' ) ) {
			?>
			<div class="error">
				<h3>FB Comments Moderation</h3>
				<p><?php _e( 'Save license key at <strong>FB Comments Moderation > License Settings</strong> page to enable plugin updates', 'heateor-fcm-text' ) ?><a style="margin-left: 8px" href="<?php echo admin_url() . 'admin.php?page=heateor-fcm-license-settings' ?>"><input type="button" class="button button-primary" value="<?php _e( 'Let\'s do it', 'heateor-fcm-text' ) ?>" /></a></p>
			</div>
			<?php
		}

		if ( defined( 'THE_CHAMP_SS_VERSION' ) && version_compare( '7.8.17', THE_CHAMP_SS_VERSION ) > 0 ) {
			?>
			<div class="error notice">
				<h3>Facebook Comments Moderation</h3>
				<p>Update "Super Socializer" plugin to version 7.8.17 OR above for "Recent Facebook Comments" feature to work</p>
			</div>
			<?php
		}

		if ( defined( 'HEATEOR_SOCIAL_COMMENTS_VERSION' ) && version_compare( '1.4.8', HEATEOR_SOCIAL_COMMENTS_VERSION ) > 0 ) {
			?>
			<div class="error notice">
				<h3>Facebook Comments Moderation</h3>
				<p>Update "Heateor Social Comments" plugin to version 1.4.8 OR above for "Recent Facebook Comments" feature to work</p>
			</div>
			<?php
		}

		$heateor_fcm_last_update_check = get_option( 'external_updates-' . HEATEOR_FB_COM_MOD_SLUG );
		if ( $heateor_fcm_last_update_check && ! empty( $heateor_fcm_last_update_check->update->upgrade_notice ) ) {
			?>
			<div class="error">
				<h3>FB Comments Moderation</h3>
				<p><?php echo $heateor_fcm_last_update_check->update->upgrade_notice ?></p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'heateor_fcm_license_notification' );

/**
 * Insert meta tags in head
 */
function heateor_fcm_fb_meta_tags() {
	global $heateor_fcm_options;
	$main_site_options = is_multisite() ? get_blog_option( BLOG_ID_CURRENT_SITE, 'heateor_fcm' ) : '';
	$fb_app_id = isset( $heateor_fcm_options['app_id'] ) && $heateor_fcm_options['app_id'] != '' ? $heateor_fcm_options['app_id'] : '';
	$fb_admin_ids = isset( $heateor_fcm_options['admin_ids'] ) && $heateor_fcm_options['admin_ids'] != '' ? $heateor_fcm_options['admin_ids'] : '';
	if ( $main_site_options ) {
		if ( isset( $main_site_options['subsite_config'] ) && $main_site_options['subsite_config'] == 1 ) {
			if ( ! $fb_app_id ) {
				$fb_app_id = $main_site_options['app_id'];
			}
			if ( ! $fb_admin_ids ) {
				$fb_admin_ids = $main_site_options['admin_ids'];
			}
		} elseif ( isset( $main_site_options['subsite_config'] ) && $main_site_options['subsite_config'] == 2 ) {
			$fb_app_id = $main_site_options['app_id'];
			$fb_admin_ids = $main_site_options['admin_ids'];
		}
	}
	if ( $fb_app_id ) {
		?>
		<meta property="fb:app_id" content="<?php echo $fb_app_id ?>" />
		<?php
	}
	if ( $fb_admin_ids ) {
		$admin_ids = explode( ',', $fb_admin_ids );
		foreach ( $admin_ids as $id ) {
			?>
			<meta property="fb:admins" content="<?php echo trim( $id ) ?>" />
			<?php
		}
	}
}
add_action( 'wp_head', 'heateor_fcm_fb_meta_tags' );

/**
 * Use target="_blank" in the link of comment author
 */
function heateor_fcm_customize_comment_author_anchor( $author_link ) {
    return str_replace( "<a", "<a target='_blank'", $author_link );
}
add_filter( 'get_comment_author_link', 'heateor_fcm_customize_comment_author_anchor' );