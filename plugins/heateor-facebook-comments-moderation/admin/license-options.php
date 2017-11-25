<?php defined('ABSPATH') or die("Cheating........Uh!!"); ?>
<h2>Facebook Comments Moderation</h2>
<form action="options.php" method="post">
	<?php settings_fields('heateor_fcm_license_options'); ?>
	<div class="metabox-holder columns-2" id="post-body">
		<div class="heateor_left_column">
			<div class="stuffbox">
				<h3 class="hndle"><label><?php _e('License Options', 'heateor-fcm-text');?></label></h3>
				<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr>
							<th>
							<img id="heateor_fcm_license_key_help" class="heateor_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							<label for="heateor_fcm_license_key"><?php _e("License Key", 'heateor-fcm-text'); ?></label>
							</th>
							<td>
							<input style="width:330px" type="text" id="heateor_fcm_license_key" name="heateor_fcm_license[license_key]" value="<?php echo isset($heateor_fcm_license_options['license_key']) ? $heateor_fcm_license_options['license_key'] : '' ?>" />
							</td>
						</tr>

						<tr class="heateor_help_content" id="heateor_fcm_license_key_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Save the license key you received with this plugin, to enable updates', 'heateor-fcm-text') ?>
							</div>
							</td>
						</tr>

					</table>
				</div>
			</div>
			<div style="clear:both"></div>
			<p class="submit">
				<input type="submit" name="save" class="button button-primary" value="<?php _e("Save Changes", "heateor-fcm-text"); ?>" />
			</p>
		</div>
		<?php require 'help.php' ?>
	</div>
</form>