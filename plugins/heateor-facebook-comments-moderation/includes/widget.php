<?php 
defined('ABSPATH') or die("Cheating........Uh!!");
/**
 * Recent Facebook Comments Widget
 */
class HeateorFcmRecentFacebookCommentsWidget extends WP_Widget { 
	/** constructor */ 
	public function __construct() { 
		parent::__construct( 
			'HeateorFcmRecentFacebookComments', //unique id 
			__( 'Heateor - Recent Facebook Comments' ), //title displayed at admin panel
			array(  
				'description' => __( 'Display recent Facebook Comments from all over your website.', 'heateor-fcm-text' ) ) 
			); 
	}
	
	/** This is rendered widget content */ 
	public function widget( $args, $instance ) {
		extract( $args ); 
		
		echo $before_widget;
		if( !empty( $instance['before_widget_content'] ) ){ 
			echo '<div>' . $instance['before_widget_content'] . '</div>';
		}
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		global $wpdb;
		$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}heateor_facebook_comments WHERE is_deleted = '0' AND is_approved = '1' ORDER BY created_time DESC LIMIT %d", isset( $instance['num_comments'] ) && $instance['num_comments'] != '' ? intval( $instance['num_comments'] ) : 10 ) );

		$heateor_fcm_comment_li_classes = array( 'heateor_fcm_facebook_comment' );
			
		if ( isset( $instance['show_avatar'] ) ) {
			array_push( $heateor_fcm_comment_li_classes, 'heateor_fcm_with_avatar' );
		}
		
		if ( ! isset( $instance['show_message'] ) ) {
			array_push( $heateor_fcm_comment_li_classes, 'heateor_fcm_no_message' );
		}

		if ( ! isset( $instance['show_timestamp'] ) ) {
			array_push( $heateor_fcm_comment_li_classes, 'heateor_fcm_no_timestamp' );
		}

		if ( is_array( $comments ) && count( $comments ) > 0 ) {
			?>
			<ul class="heateor_fcm_facebook_comments">
			<?php
			foreach ( $comments as $comment ) {
				?>
				<li class="<?php echo implode( " ", $heateor_fcm_comment_li_classes ); ?>">
					<?php if ( isset( $instance['show_avatar'] ) ) { ?><a class="heateor_fcm_facebook_comment_avatar" target="_blank" href="https://www.facebook.com/<?php echo $comment->from_fb_id;?>" target="_blank"><img src="https://graph.facebook.com/<?php echo $comment->from_fb_id;?>/picture" border="0" height="50" width="50"></a><?php } ?> <<?php echo $instance['heading_tag'] ?>><a target="_blank" href="https://www.facebook.com/<?php echo $comment->from_fb_id;?>" target="_blank"><?php echo $comment->from_fb_name;?></a> <?php _e( 'on', 'heateor-fcm-text' ) ?> <a target="_blank" href="<?php echo get_permalink( $comment->wp_post_id );?>"><?php echo get_the_title( $comment->wp_post_id );?></<?php echo $instance['heading_tag'] ?>></a>
					<?php if ( isset( $instance['show_timestamp'] ) ) {
						?>
						<time datetime="<?php echo date("c", $comment->created_time); ?>"><?php echo date("d M Y", $comment->created_time); ?> </time>
						<?php
					}
					?>
					<?php if ( isset( $instance['show_message'] ) ) {
						$message = strip_tags( $comment->comment );
						if ( isset( $instance['message_characters'] ) && intval( $instance['message_characters'] ) > 0 ) {
							$message_characters = intval( $instance['message_characters'] );
							if ( strlen( $message ) > $message_characters ) {
								$message = substr( $message, 0, $message_characters ) . " ...";
							}
						}
						?>
						<div class="heateor_fcm_fb_message"><?php echo $message;?></div>
						<?php
					}
					?>
				</li>
				<?php
			}
			?>
			<ul>
			<?php
		}

		if( !empty( $instance['after_widget_content'] ) ){ 
			echo '<div>' . $instance['after_widget_content'] . '</div>';
		}
		echo $after_widget; 
	} 

	/** Everything which should happen when user edit widget at admin panel */ 
	public function update( $new_instance, $old_instance ) { 
		$instance = $old_instance; 
		$instance['title'] = strip_tags( $new_instance['title'] ); 
		$instance['num_comments'] = $new_instance['num_comments']; 
		$instance['heading_tag'] = $new_instance['heading_tag']; 
		$instance['show_message'] = $new_instance['show_message']; 
		$instance['message_characters'] = $new_instance['message_characters'];
		$instance['show_avatar'] = $new_instance['show_avatar']; 
		$instance['show_timestamp'] = $new_instance['show_timestamp']; 
		$instance['before_widget_content'] = $new_instance['before_widget_content']; 
		$instance['after_widget_content'] = $new_instance['after_widget_content']; 

		return $instance; 
	}  

	/** Widget options in admin panel */ 
	public function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array( 'title' => __( 'Recent Facebook Comments', 'heateor-fcm-text' ), 'num_comments' => 10, 'heading_tag' => 'h3', 'show_message' => '1', 'message_characters' => '', 'show_avatar' => '1', 'show_timestamp' => '1', 'before_widget_content' => '', 'after_widget_content' => '' );  

		foreach( $instance as $key => $value ) {  
			if ( is_string( $value ) ) {
				$instance[ $key ] = esc_attr( $value );  
			}
		}

		$instance = wp_parse_args( (array)$instance, $defaults ); 
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content', 'heateor-fcm-text' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'heateor-fcm-text' ); ?></label> 
			<input style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /> 
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_comments' ); ?>"><?php _e( 'Number of comments to display', 'heateor-fcm-text' ); ?></label> 
			<input style="width:12%" id="<?php echo $this->get_field_id( 'num_comments' ); ?>" name="<?php echo $this->get_field_name( 'num_comments' ); ?>" type="text" value="<?php echo $instance['num_comments']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'heading_tag' ); ?>"><?php _e( 'Heading Tag', 'heateor-fcm-text' ); ?></label> 
			<select id="<?php echo $this->get_field_id( 'heading_tag' ); ?>" name="<?php echo $this->get_field_name( 'heading_tag' ); ?>">
				<option value="h1" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'h1' ? 'selected' : '' ; ?>>H1</option>
				<option value="h2" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'h2' ? 'selected' : '' ; ?>>H2</option>
				<option value="h3" <?php echo !isset($instance['heading_tag']) || $instance['heading_tag'] == 'h3' ? 'selected' : '' ; ?>>H3</option>
				<option value="h4" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'h4' ? 'selected' : '' ; ?>>H4</option>
				<option value="h5" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'h5' ? 'selected' : '' ; ?>>H5</option>
				<option value="h6" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'h6' ? 'selected' : '' ; ?>>H6</option>
				<option value="div" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'div' ? 'selected' : '' ; ?>>Div</option>
				<option value="span" <?php echo isset($instance['heading_tag']) && $instance['heading_tag'] == 'span' ? 'selected' : '' ; ?>>Span</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_avatar' ); ?>"><?php _e( 'Display avatar', 'heateor-fcm-text' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_avatar' ); ?>" name="<?php echo $this->get_field_name( 'show_avatar' ); ?>" value="1" <?php if(isset($instance['show_avatar'])) echo 'checked="checked"'; ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_timestamp' ); ?>"><?php _e( 'Display timestamp', 'heateor-fcm-text' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_timestamp' ); ?>" name="<?php echo $this->get_field_name( 'show_timestamp' ); ?>" value="1" <?php if(isset($instance['show_timestamp'])) echo 'checked="checked"'; ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_message' ); ?>"><?php _e( 'Show Message', 'heateor-fcm-text' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_message' ); ?>" name="<?php echo $this->get_field_name( 'show_message' ); ?>" value="1" <?php if(isset($instance['show_message'])) echo 'checked="checked"'; ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'message_characters' ); ?>"><?php _e( 'Number of characters in message', 'heateor-fcm-text' ); ?></label> 
			<input style="width: 12%" id="<?php echo $this->get_field_id( 'message_characters' ); ?>" name="<?php echo $this->get_field_name( 'message_characters' ); ?>" type="text" value="<?php echo $instance['message_characters']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content', 'heateor-fcm-text' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" /> 
		</p> 
<?php 
  } 
} 
add_action( 'widgets_init', create_function( '', 'return register_widget( "HeateorFcmRecentFacebookCommentsWidget" );' )); 