<?php

function add_team_members_meta_box()
{
    $screens = ['team-members']; // post type
    foreach ($screens as $screen) {
        add_meta_box(
            'team_members_meta_box',      // Meta Box ID
            'Team Member Custom Fields',  // Meta Box title
            'tmcf_fields',  // team members custom fields
            $screen  
        );
    }
}
add_action('add_meta_boxes', 'add_team_members_meta_box');


// create custom fields html
function tmcf_fields($post)
{
    $position = esc_attr(get_post_meta($post->ID, 'tmcf_position', true));
    $twitter = esc_url(esc_attr(get_post_meta($post->ID, 'tmcf_twitter', true)));
    $facebook = esc_url(esc_attr(get_post_meta($post->ID, 'tmcf_facebook', true)));

    ?>

	<table>
		<thead>
		<tr>
			<th class="left">Name</th>
			<th>Value</th>
		</tr>
		</thead>
		<tbody>		
			<tr>
				<td class="left"><label for="tmcf_position">Position</label></td>
				<td class="left"><input type="text" value="<?php echo $position; ?>" name="tmcf_position" id="tcmf_position" /></td>
			</tr>
			<tr>
				<td class="left"><label for="tmcf_twitter">Twitter Url</label></td>
				<td class="left"><input type="text" value="<?php echo $twitter; ?>" name="tmcf_twitter" id="tcmf_twitter" /> </td>
			</tr>
			<tr>
				<td class="left"><label for="tmcf_facebook">Facebook Url</label></td>
				<td class="left"><input type="text" value="<?php echo $facebook; ?>" name="tmcf_facebook" id="tmcf_facebook" /></td>
			</tr>
		</tbody>
	</table>

    <?php
}


// save custom fields
function tmcf_save_postdata($post_id)
{
	$fields = [
        'tmcf_position',
        'tmcf_twitter',
        'tmcf_facebook'
    ];
    foreach ($fields as $field) {    
	    if (array_key_exists($field, $_POST)) {
	        update_post_meta(
	            $post_id,
	            $field,
	            ($field == 'tmcf_position') ? sanitize_text_field(esc_attr($_POST[$field])) : esc_url(esc_attr($_POST[$field]))
	        );
	    }
    }
}
add_action('save_post', 'tmcf_save_postdata');