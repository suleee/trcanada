<?php defined('ABSPATH') or die("Cheating........Uh!!"); ?>
<h2>Facebook Comments Moderation</h2>
<div class="metabox-holder columns-2" id="post-body">
<form action="options.php" method="post">
<?php settings_fields('heateor_fcm_options'); ?>
<div class="menu_div" id="tabs">
	<h2 class="nav-tab-wrapper" style="height:34px">
	<ul>
		<li style="margin-left:9px"><a style="margin:0; line-height:auto !important; height:23px" class="nav-tab" href="#tabs-1"><?php _e('Settings', 'heateor-fcm-text') ?></a></li>
		<li style="margin-left:9px"><a style="margin:0; line-height:auto !important; height:23px" class="nav-tab" href="#tabs-2"><?php _e('Recent Facebook Comments', 'heateor-fcm-text') ?></a></li>
		<li style="margin-left:9px"><a style="margin:0; line-height:auto !important; height:23px" class="nav-tab" href="#tabs-3"><?php _e('Recovered Facebook Comments', 'heateor-fcm-text') ?></a></li>
	</ul>
	</h2>
	<div class="menu_containt_div" id="tabs-1">
	<div class="clear"></div>
	<div class="heateor_left_column">
		<div class="stuffbox">
			<h3><label><?php _e('Configuration', 'heateor-fcm-text');?></label><a href="http://support.heateor.com/recover-facebook-comments-wordpress-moving-to-https-ssl/" target="_blank" style="float: right;"><?php _e('Configuration Steps', 'heateor-fcm-text');?></a></h3>
			<div class="inside">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
					<tr>
						<th>
						<img id="heateor_fcm_app_id_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_app_id"><?php _e("Facebook App ID", 'heateor-fcm-text'); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_app_id" name="heateor_fcm[app_id]" type="text" value="<?php echo isset($heateor_fcm_options['app_id']) ? $heateor_fcm_options['app_id'] : '' ?>" />
						</td>
					</tr>

					<tr class="heateor_help_content" id="heateor_fcm_app_id_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'Specify Facebook App ID for Recent Facebook Comments widget to work or if you want to moderate comments at all of the webpages from one moderation dashboard. Follow the documentation at <a href="http://support.heateor.com/how-to-get-facebook-app-id/" target="_blank">this link</a> to get Facebook App ID', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<?php
					if( isset( $heateor_fcm_options['app_id'] ) && $heateor_fcm_options['app_id'] != '' ) {
						?>
						<tr>
							<td colspan="2"><a target="_blank" href="https://developers.facebook.com/tools/comments/<?php echo $heateor_fcm_options['app_id'] ?>"><?php _e( 'Go to Moderation Dashboard', 'heateor-fcm-text' ) ?></a><br/><?php _e( '(You must be logged into your Facebook account to access moderation dashboard)', 'heateor-fcm-text' ) ?></td>
						</tr>
						<?php
					}
					?>

					<tr>
						<th>
						<img id="heateor_fcm_app_secret_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_app_secret"><?php _e("Facebook App Secret", 'heateor-fcm-text'); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_app_secret" name="heateor_fcm[app_secret]" type="text" value="<?php echo isset($heateor_fcm_options['app_secret']) ? $heateor_fcm_options['app_secret'] : '' ?>" />
						</td>
					</tr>

					<tr class="heateor_help_content" id="heateor_fcm_app_secret_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'Required for Recent Facebook Comments widget to work. You can get it by clicking the "Show" button in "App Secret" section at "Dashboard" page in Facebook developer account. See step 11, 12 at <a href="http://support.heateor.com/how-to-get-facebook-app-id/" target="_blank">this link</a>', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_admin_ids_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_admin_ids"><?php _e("Moderator FB ID(s)", 'heateor-fcm-text'); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_admin_ids" name="heateor_fcm[admin_ids]" type="text" value="<?php echo isset($heateor_fcm_options['admin_ids']) ? $heateor_fcm_options['admin_ids'] : '' ?>" />
						</td>
					</tr>

					<tr class="heateor_help_content" id="heateor_fcm_admin_ids_help_cont">
						<td colspan="2">
						<div>
						<?php _e('Specify Facebook account ID(s) of moderator(s) to enable them to moderate comments at the webpage where comments are enabled. Multiple IDs can be specified comma separated. You can use <a target="_blank" href="http://findmyfbid.in/">this link</a> to get Facebook account ID', 'heateor-fcm-text') ?>
						</div>
						<img src="<?php echo plugins_url('../images/snaps/heateor_fcm_moderation_tool.png', __FILE__); ?>" />
						</td>
					</tr>

					<tr>
						<td colspan="2">
						<?php _e( '<strong>Note:</strong> If comments are not appearing in moderation dashboard or moderators are not able to see "Moderation Tool" option even after specifying moderator FB ID(s), navigate to <a target="_blank" href="https://developers.facebook.com/tools/debug/og/object/">this link</a>, paste the url of webpage where you are facing the problem, click "Fetch new scrape information" button and refresh the problematic page', 'heateor-fcm-text' ) ?>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_default_status_recent_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_default_status_recent_comments"><?php _e("Default status of the comments to be displayed in Recent Facebook Comments widget", 'heateor-fcm-text'); ?></label>
						</th>
						<td>
						<select id="heateor_fcm_default_status_recent_comments" name="heateor_fcm[comment_status]">
							<option value="approved" <?php echo $heateor_fcm_options['comment_status'] == 'approved' ? 'selected="selected"' : '' ?>><?php _e('Approved', 'heateor-fcm-text') ?></option>
							<option value="unapproved" <?php echo $heateor_fcm_options['comment_status'] == 'unapproved' ? 'selected="selected"' : '' ?>><?php _e('Unapproved', 'heateor-fcm-text') ?></option>
						</select>
						</td>
					</tr>

					<tr class="heateor_help_content" id="heateor_fcm_default_status_recent_comments_help_cont">
						<td colspan="2">
						<?php _e( 'Set to "Approved" to show new comments in Recent Facebook Comments widget without moderation. Set to "Unapproved" to approve new comments manually.', 'heateor-fcm-text' ) ?>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_num_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_num_comments"><?php _e( "Number of comments to display in 'Recent Facebook Comments' section", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_num_comments" name="heateor_fcm[num_comments]" type="text" value="<?php echo $heateor_fcm_options['num_comments'] ?>" />
						</td>
					</tr>

					<tr class="heateor_help_content" id="heateor_fcm_num_comments_help_cont">
						<td colspan="2">
						<?php _e( "Number of comments to display in 'Recent Facebook Comments' section", 'heateor-fcm-text' ) ?>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_num_recovered_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_num_recovered_comments"><?php _e( "Number of comments to display in 'Recovered Facebook Comments' section", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_num_recovered_comments" name="heateor_fcm[num_recovered_comments]" type="text" value="<?php echo $heateor_fcm_options['num_recovered_comments'] ?>" />
						</td>
					</tr>

					<tr class="heateor_help_content" id="heateor_fcm_num_recovered_comments_help_cont">
						<td colspan="2">
						<?php _e( "Number of comments to display in 'Recovered Facebook Comments' section", 'heateor-fcm-text' ) ?>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_recover_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_recover_comments"><?php _e( "Recover Facebook Comments (if moved to SSL)", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_recover_comments" name="heateor_fcm[recover_comments]" type="checkbox" <?php echo isset( $heateor_fcm_options['recover_comments'] ) ? 'checked = "checked"' : '';?> value="1" />
						</td>
					</tr>
					
					<tr class="heateor_help_content" id="heateor_fcm_recover_comments_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'Enable this option to recover Facebook Comments made at your webpages before installing SSL', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_show_recovered_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_show_recovered_comments"><?php _e( "Display recovered Facebook Comments at the webpages", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_show_recovered_comments" onclick="if(this.checked){document.getElementById('heateor_fcm_show_recovered_comments_options').style.display = 'table-row-group';}else{document.getElementById('heateor_fcm_show_recovered_comments_options').style.display = 'none'}" name="heateor_fcm[show_recovered_comments]" type="checkbox" <?php echo isset( $heateor_fcm_options['show_recovered_comments'] ) ? 'checked = "checked"' : '';?> value="1" />
						</td>
					</tr>
					
					<tr class="heateor_help_content" id="heateor_fcm_show_recovered_comments_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'Enable this option to display recovered Facebook Comments at your webpages', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<tbody id="heateor_fcm_show_recovered_comments_options" <?php echo isset( $heateor_fcm_options['show_recovered_comments'] ) ? '' : 'style="display: none"'; ?>>
						<tr>
							<th>
							<img id="heateor_fcm_recovered_comments_title_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							<label for="heateor_fcm_recovered_comments_title"><?php _e( "Title to show above comments", 'heateor-fcm-text' ); ?></label>
							</th>
							<td>
							<input id="heateor_fcm_recovered_comments_title" name="heateor_fcm[recovered_comments_title]" type="text" value="<?php echo $heateor_fcm_options['recovered_comments_title'] ?>" />
							</td>
						</tr>

						<tr class="heateor_help_content" id="heateor_fcm_recovered_comments_title_help_cont">
							<td colspan="2">
							<?php _e( "Use placeholder %number_of_comments% to show comment count.", 'heateor-fcm-text' ) ?>
							</td>
						</tr>

						<tr>
							<th>
							<img id="heateor_fcm_recovered_comments_priority_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							<label for="heateor_fcm_recovered_comments_priority"><?php _e( "Priority for recovered comments to appear at front-end", 'heateor-fcm-text' ); ?></label>
							</th>
							<td>
							<input id="heateor_fcm_recovered_comments_priority" name="heateor_fcm[recovered_comments_priority]" type="text" value="<?php echo $heateor_fcm_options['recovered_comments_priority'] ?>" />
							</td>
						</tr>

						<tr class="heateor_help_content" id="heateor_fcm_recovered_comments_priority_help_cont">
							<td colspan="2">
							<?php _e( "Higher number causes recovered comments to appear below the other elements at the webpage.", 'heateor-fcm-text' ) ?>
							</td>
						</tr>

						<tr>
							<th>
							<img id="heateor_fcm_num_recovered_front_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							<label for="heateor_fcm_num_recovered_front_comments"><?php _e( "Number of Recovered Facebook Comments to display at frontend", 'heateor-fcm-text' ); ?></label>
							</th>
							<td>
							<input id="heateor_fcm_num_recovered_front_comments" name="heateor_fcm[num_recovered_front_comments]" type="text" value="<?php echo $heateor_fcm_options['num_recovered_front_comments'] ?>" />
							</td>
						</tr>

						<tr class="heateor_help_content" id="heateor_fcm_num_recovered_front_comments_help_cont">
							<td colspan="2">
							<?php _e( "Number of Recovered Facebook Comments to display at frontend per webpage", 'heateor-fcm-text' ) ?>
							</td>
						</tr>

						<tr>
							<th>
								<img id="heateor_fcm_show_recovered_comments_placement_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
								<label><?php _e( "Display comments at:", 'heateor-fcm-text' ); ?></label>
							</th>
							<td>
								<input id="heateor_fcm_show_recovered_comments_placement_post" name="heateor_fcm[show_recovered_comments_post]" type="checkbox" <?php echo isset( $heateor_fcm_options['show_recovered_comments_post'] ) ? 'checked = "checked"' : '';?> value="1" />
								<label for="heateor_fcm_show_recovered_comments_placement_post"><?php _e( 'Posts', 'heateor-fcm-text' ) ?></label><br/>
								<input id="heateor_fcm_show_recovered_comments_placement_page" name="heateor_fcm[show_recovered_comments_page]" type="checkbox" <?php echo isset( $heateor_fcm_options['show_recovered_comments_page'] ) ? 'checked = "checked"' : '';?> value="1" />
								<label for="heateor_fcm_show_recovered_comments_placement_page"><?php _e( 'Pages', 'heateor-fcm-text' ) ?></label><br/>
								<?php
								$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
								$post_types = array_diff( $post_types, array( 'post', 'page' ) );
								if( count( $post_types ) ) {	
									foreach ( $post_types as $post_type ) {
										?>
										<input id="heateor_fcm_show_recovered_comments_placement_<?php echo $post_type ?>" name="heateor_fcm[show_recovered_comments_<?php echo $post_type ?>]" type="checkbox" <?php echo isset( $heateor_fcm_options['show_recovered_comments_' . $post_type] ) ? 'checked = "checked"' : '';?> value="1" />
										<label for="heateor_fcm_show_recovered_comments_placement_<?php echo $post_type ?>"><?php echo ucfirst( $post_type ) . 's'; ?></label><br/>
										<?php
									}
								}
								?>
							</td>
						</tr>

						<tr class="heateor_help_content" id="heateor_fcm_show_recovered_comments_placement_help_cont">
							<td colspan="2">
							<div>
							<?php _e( 'Page groups to display Recovered Facebook Comments at', 'heateor-fcm-text' ) ?>
							</div>
							</td>
						</tr>
					</tbody>

					<tr>
						<th>
						<img id="heateor_fcm_custom_css_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_custom_css"><?php _e( "Custom CSS", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<textarea rows="7" cols="40" id="heateor_fcm_custom_css" name="heateor_fcm[custom_css]"><?php echo isset( $heateor_fcm_options['custom_css'] ) ? $heateor_fcm_options['custom_css'] : '' ?></textarea>
						</td>
					</tr>
					
					<tr class="heateor_help_content" id="heateor_fcm_custom_css_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'You can specify any additional CSS rules (without &lt;style&gt; tag)', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_footer_javascript_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_footer_javascript"><?php _e( "Include Javascript in website footer", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_footer_javascript" name="heateor_fcm[footer_script]" type="checkbox" <?php echo isset( $heateor_fcm_options['footer_script'] ) ? 'checked = "checked"' : '';?> value="1" />
						</td>
					</tr>
					
					<tr class="heateor_help_content" id="heateor_fcm_footer_javascript_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'If enabled (recommended), Javascript files of the plugin will be included in the footer of your website.', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_delete_options_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_delete_options"><?php _e( "Delete all the options on plugin deletion", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_delete_options" name="heateor_fcm[delete_options]" type="checkbox" <?php echo isset( $heateor_fcm_options['delete_options'] ) ? 'checked = "checked"' : '';?> value="1" />
						</td>
					</tr>
					
					<tr class="heateor_help_content" id="heateor_fcm_delete_options_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'If enabled, plugin options will get deleted when plugin is deleted/uninstalled and you will need to reconfigure the options when you install the plugin next time.', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

					<tr>
						<th>
						<img id="heateor_fcm_delete_comments_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
						<label for="heateor_fcm_delete_comments"><?php _e( "Delete saved Facebook Comments on plugin deletion", 'heateor-fcm-text' ); ?></label>
						</th>
						<td>
						<input id="heateor_fcm_delete_comments" name="heateor_fcm[delete_comments]" type="checkbox" <?php echo isset( $heateor_fcm_options['delete_comments'] ) ? 'checked = "checked"' : '';?> value="1" />
						</td>
					</tr>
					
					<tr class="heateor_help_content" id="heateor_fcm_delete_comments_help_cont">
						<td colspan="2">
						<div>
						<?php _e( 'If enabled, all the saved Facebook Comments will get deleted when plugin is deleted/uninstalled.', 'heateor-fcm-text' ) ?>
						</div>
						</td>
					</tr>

				</table>
			</div>
		</div>

		<?php if( is_multisite() && is_main_site() ) { ?>
			<div class="stuffbox">
				<h3><label><?php _e('Multisite', 'heateor-fcm-text');?></label></h3>
				<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr>
							<th>
							<img id="heateor_fcm_subsite_config_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							<label for="heateor_fcm_subsite_config_1"><?php _e("Subsite Configuration", 'heateor-fcm-text'); ?></label>
							</th>
							<td>
							<input id="heateor_fcm_subsite_config_0" name="heateor_fcm[subsite_config]" type="radio" value="0" <?php echo !isset($heateor_fcm_options['subsite_config']) || $heateor_fcm_options['subsite_config'] == 0 ? 'checked' : '' ?> /><label for="heateor_fcm_subsite_config_0"><?php _e("Use their own configuration settings for subsites (Default)", 'heateor-fcm-text'); ?></label><br/>
							<input id="heateor_fcm_subsite_config_1" name="heateor_fcm[subsite_config]" type="radio" value="1" <?php echo isset($heateor_fcm_options['subsite_config']) && $heateor_fcm_options['subsite_config'] == 1 ? 'checked' : '' ?> /><label for="heateor_fcm_subsite_config_1"><?php _e("Use above configuration if any subsite has options not configured", 'heateor-fcm-text'); ?></label><br/>
							<input id="heateor_fcm_subsite_config_2" name="heateor_fcm[subsite_config]" type="radio" value="2" <?php echo isset($heateor_fcm_options['subsite_config']) && $heateor_fcm_options['subsite_config'] == 2 ? 'checked' : '' ?> /><label for="heateor_fcm_subsite_config_2"><?php _e("Force above configuration for all the subsites in network", 'heateor-fcm-text'); ?></label><br/>
							</td>
						</tr>

						<tr class="heateor_help_content" id="heateor_fcm_subsite_config_help_cont">
							<td colspan="2">
							<div>
							<?php _e( 'Configuration settings to use for the subsites in the network', 'heateor-fcm-text' ) ?>
							</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php include 'help.php' ?>
</div>

<div class="menu_containt_div" id="tabs-2">
	<div class="clear"></div>
	<div class="heateor_left_column">
		<?php
		global $wpdb;
		$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}heateor_facebook_comments WHERE is_deleted = '0' AND is_recovered = '0' ORDER BY created_time DESC LIMIT %d", $heateor_fcm_options['num_comments'] != '' ? intval( $heateor_fcm_options['num_comments'] ) : 50 ) );
		?>
		<ul class="heateor_fcm_comments_panel">
			<?php
			if ( is_array( $comments ) && count( $comments ) > 0 ) {
				foreach ( $comments as $comment ) {
					?>
					<li class="heateor_fcm_fb_comment" commentId="<?php echo $comment->id;?>">
						<a target="_blank" href="https://www.facebook.com/<?php echo $comment->from_fb_id;?>" target="_blank" class="fb-avatar"><img src="https://graph.facebook.com/<?php echo $comment->from_fb_id;?>/picture" border="0" height="50" width="50"></a> From <a target="_blank" href="https://www.facebook.com/<?php echo $comment->from_fb_id;?>" target="_blank"><?php echo $comment->from_fb_name;?></a> on <a target="_blank" href="<?php echo get_permalink( $comment->wp_post_id );?>"><?php echo get_the_title( $comment->wp_post_id );?></a>
						<time datetime="<?php echo date( "c", $comment->created_time ); ?>"><?php echo date( "d M Y H:i a", $comment->created_time ); ?></time>
						<div class="heateor_fcm_fb_message"><?php echo strip_tags( $comment->comment );?></div>
						<ul class="heateor_fcm_comment_mod_options">
							<li><a href="javascript:void(0)" onclick="heateorFcmApproveComment(this)" class="toggleApprove" commentId="<?php echo $comment->id;?>"><?php echo $comment->is_approved == '1' ? 'Unapprove' : 'Approve' ?></a></li>
							<li><a href="javascript:void(0)" onclick="heateorFcmDeleteComment(this)" class="deleteComment" commentId="<?php echo $comment->id;?>">Delete</a></li>
						</ul>
					</li>
					<?php
				}
			} else {
				?>
				<li>No Comments</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php include 'help.php'; ?>
</div>

<div class="menu_containt_div" id="tabs-3">
	<div class="clear"></div>
	<div class="heateor_left_column">
		<?php
		global $wpdb;
		$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}heateor_facebook_comments WHERE is_deleted = '0' AND is_recovered = '1' ORDER BY created_time DESC LIMIT %d", $heateor_fcm_options['num_recovered_comments'] != '' ? intval( $heateor_fcm_options['num_recovered_comments'] ) : 50 ) );
		?>
		<ul class="heateor_fcm_comments_panel">
			<?php
			if ( is_array( $comments ) && count( $comments ) > 0 ) {
				foreach ( $comments as $comment ) {
					?>
					<li class="heateor_fcm_fb_comment" commentId="<?php echo $comment->id;?>">
						<a target="_blank" href="https://www.facebook.com/<?php echo $comment->from_fb_id;?>" target="_blank" class="fb-avatar"><img src="https://graph.facebook.com/<?php echo $comment->from_fb_id;?>/picture" border="0" height="50" width="50"></a> From <a target="_blank" href="https://www.facebook.com/<?php echo $comment->from_fb_id;?>" target="_blank"><?php echo $comment->from_fb_name;?></a> on <a target="_blank" href="<?php echo get_permalink( $comment->wp_post_id );?>"><?php echo get_the_title( $comment->wp_post_id );?></a>
						<time datetime="<?php echo date( "c", $comment->created_time ); ?>"><?php echo date( "d M Y H:i a", $comment->created_time ); ?></time>
						<div class="heateor_fcm_fb_message"><?php echo strip_tags( $comment->comment );?></div>
						<ul class="heateor_fcm_comment_mod_options">
							<li><a href="javascript:void(0)" onclick="heateorFcmApproveComment(this)" class="toggleApprove" commentId="<?php echo $comment->id;?>"><?php echo $comment->is_approved == '1' ? 'Unapprove' : 'Approve' ?></a></li>
							<li><a href="javascript:void(0)" onclick="heateorFcmDeleteComment(this)" class="deleteComment" commentId="<?php echo $comment->id;?>">Delete</a></li>
						</ul>
					</li>
					<?php
				}
			} else {
				?>
				<li>No Comments</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php include 'help.php'; ?>
</div>

</div>
<div class="heateor_clear"></div>
<p class="submit">
	<input style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e("Save Changes", 'heateor-fcm-text'); ?>" />
</p>
</form>
</div>