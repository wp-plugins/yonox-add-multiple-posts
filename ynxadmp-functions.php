<?php
if ( !defined( 'ABSPATH' ) ) exit;

global $wpdb;

?>

<div class="wrap">

<h2 style="font-weight:600;margin-bottom:6px;">Yonox Add Multiple Posts</h2>
<hr style="margin:10px 0 20px;">

<div style="display:none;" id="msg_finished" class="updated"><p><?php _e('Process finished OK.','ynxadmp'); ?></p></div>
<div style="display:none;" id="msg_notitles" class="error"><p><?php _e('No titles found.','ynxadmp'); ?></p></div>
	
<table class="widefat" style="border-radius:4px;">
	<thead>
		<tr>
			<th class="manage-column" style="width:180px;"><?php _e('Option','ynxadmp'); ?></th>
			<th colspan="2" class="manage-column"><?php _e('Setting','ynxadmp'); ?></th>
		</tr>
	</thead>
	<tbody>
		<input type="hidden" name="ynxadmp_createpost_input" value="post" />
		<tr class="alternate iedit">
			<td valign="top"><?php _e('Posts Titles:','ynxadmp'); ?><br />
			<small><?php _e('One post on each line','ynxadmp'); ?></small></td>
			<td style="padding:8px 12px;">
				<textarea style="width:100%;border-radius:4px;" name="ynxadmp_titles_texarea" id="ynxadmp_titles_texarea" rows="8"></textarea>
			</td>
		</tr>
		<tr class="alternate iedit">
			<td valign="middle" style="padding:14px 10px;"><?php _e('Author of Posts:','ynxadmp'); ?></td>
			<td colspan="2">
				<select name="author_id">
				<?php
				$user_query = "SELECT ID, user_login, display_name, user_email FROM $wpdb->users ORDER BY ID ASC";
				$users = $wpdb->get_results($user_query);
				foreach ($users AS $row) {
					echo '<option value="'.$row->ID.'">'.$row->display_name. '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="iedit">
			<td valign="middle" style="padding:13px 10px;"><?php _e('Progress Bar:','ynxadmp'); ?></td>
			<td style="padding:8px 14px;" colspan="2">
				<div id="ynxadmp_progressbar" style="height:26px;width:100%;border:1px solid rgb(197, 197, 197);border-radius:4px;padding:1px;background:#fff;"><span style="position:absolute;margin:4px 0 0 8px;"></span></div>
			</td>
		</tr>
	</tbody>
</table>

<div style="margin:15px 0;">
	<a id='btn_ynxadmp_create_posts' class='button button-primary'><?php _e('Create Multiple Posts','ynxadmp'); ?></a>
	<a id='btn_ynxadmp_reset_form' class='button' style="margin-left:8px;"><?php _e('Reset Form','ynxadmp'); ?></a>
</div>

</div>