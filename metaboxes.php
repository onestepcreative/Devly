<?php 
add_action('add_meta_boxes', 'freshMetaBoxes');
add_action('save_post', 'freshSaveMetaBoxes'); 

function freshMetaBoxes() {

	add_meta_box(
		'box_id',			   // $ID
		'Box Title',		   // $TITLE
		'freshShowMetaBoxes',  // $CALLBACK
		'post',				   // $PAGE
		'normal',			   // $CONTEXT
		'high'				   // $PRIORITY
	);

}

$prefix = 'custom_';
$fresh_meta_fields = array(
	array(
		'label'	  => 'Text Input',
		'desc'	  => 'Text Input Description',
		'id'	  => $prefix . 'text',
		'type'	  => 'text'
	),
	array(
		'label'	  => 'Textarea',
		'desc'	  => 'Textarea Input Description',
		'id'	  => $prefix . 'textarea',
		'type'	  => 'textarea'
	),
	array(
		'label'	  => 'Checkbox Input',
		'desc'	  => 'Checkbox Input Description',
		'id'	  => $prefix . 'checkbox',
		'type'	  => 'checkbox'
	),
	array(
		'name'	=> 'Image',
		'desc'	=> 'Image field Description.',
		'id'	=> $prefix.'image',
		'type'	=> 'image'
	),
	array (
		'label'	=> 'Checkbox Group',
		'desc'	=> 'Checkbox Input Group',
		'id'	=> $prefix.'checkbox_group',
		'type'	=> 'checkbox_group',
		'options' => array (
			'one' => array (
				'label' => 'Option One',
				'value'	=> 'one'
			),
			'two' => array (
				'label' => 'Option Two',
				'value'	=> 'two'
			),
			'three' => array (
				'label' => 'Option Three',
				'value'	=> 'three'
			)
		)
	),
	array(
		'label'	  => 'Radio Group',
		'desc'	  => 'Radio Button Description',
		'id'	  => $prefix . 'radio',
		'type'	  => 'radio',
		'options' => array(
			'one' => array(
				'label'	=> 'Option One',
				'value'	=> 'One',
			),
			'two' => array(
				'label'	=> 'Option Two',
				'value'	=> 'Two',
			),
			'three' => array(
				'label'	=> 'Option Three',
				'value'	=> 'Three',
			)
		)
	),
	array(
		'label'	  => 'Select Input',
		'desc'	  => 'Select Input Description',
		'id'	  => $prefix . 'checkbox',
		'type'	  => 'select',
		'options' => array(
			'one' => array(
				'label'	=> 'Option One',
				'value'	=> 'One',
			),
			'two' => array(
				'label'	=> 'Option Two',
				'value'	=> 'Two',
			),
			'three' => array(
				'label'	=> 'Option Three',
				'value'	=> 'Three',
			)
		)
	),
	array(
		'label'	=> 'Repeatable',
		'desc'	=> 'Repeatable Fields.',
		'id'	=> $prefix.'repeatable',
		'type'	=> 'repeatable'
	)
);


// THE CUSTOM META BOXES CALLBACK
function freshShowMetaBoxes() {

	global $fresh_meta_fields, $post;

	// USE NONCE FOR VERIFICATION
	echo '<input type="hidden" name="fresh_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// BEGIN THE FIELD TABLE & LOOP
	echo '<table class="form-table">';
	
	foreach ($fresh_meta_fields as $field) {

		// GET VALUE OF FIELD IF IT EXISTS
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		// BEGIN TABLE ROW
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
			  <td>';
			  
			  switch($field['type']) {
				
				// TEXT INPUT
				case 'text':
					echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" /><br>
						  <span class="description">'.$field['desc'].'</span>';
				break;
				
				// TEXTAREA INPUT
				case 'textarea':
					echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea><br>
						  <span class="description">'.$field['desc'].'</span>';
				break;
				
				// CHECKBOX INPUT
				case 'checkbox':
					echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
						  <label for="'.$field['id'].'">'.$field['desc'].'</label>';
				break;
				
				// IMAGE UPLOAD BOX
				case 'image':
					$image = get_template_directory_uri().'/images/image.png';
					echo '<span class="custom_default_image" style="display:none">'.$image.'</span>';
					
					if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }
						echo '<input name="'.$field['id'].'" type="hidden" class="custom_upload_image" value="'.$meta.'" />
							 	<img src="'.$image.'" class="custom_preview_image" alt="" /><br />
								<input class="custom_upload_image_button button" type="button" value="Choose Image" />
								<small><a href="#" class="custom_clear_image_button">Remove Image</a></small><br>
								<span class="description">'.$field['desc'].'';
				break;
				
				// CHECKBOX GROUP
				case 'checkbox_group':
					foreach ($field['options'] as $option) {
						echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
								<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
					}
					echo '<span class="description">'.$field['desc'].'</span>';
				break; 
				
				// RADIO INPUT
				case 'radio':
					foreach ( $field['options'] as $option ) {
						echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
								<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
					}
				break;  
				
				// SELECT INPUT
				case 'select':
					echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
					}
					echo '</select><br><span class="description">'.$field['desc'].'</span>';
				break;
				
				// REPEATABLE FIELDS
				case 'repeatable':
					echo '<a class="repeatable-add button" href="#">+</a>
							<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
					$i = 0;
					if ($meta) {
						foreach($meta as $row) {
							echo '<li><span class="sort hndle">|||</span>
										<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="'.$row.'" size="30" />
										<a class="repeatable-remove button" href="#">-</a></li>';
							$i++;
						}
					} else {
						echo '<li><span class="sort hndle">|||</span>
									<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="" size="30" />
									<a class="repeatable-remove button" href="#">-</a></li>';
					}
					echo '</ul>
						<span class="description">'.$field['desc'].'</span>';
				break;

				}
		echo '</td></tr>';
	}
	
	echo '</table>';
}

// SAVE NEW DATA FROM META BOXES
function freshSaveMetaBoxes($post_id) {
	
	global $fresh_meta_fields;

	// VERIFY THE NONCE
	if (!wp_verify_nonce($_POST['fresh_meta_box_nonce'], basename(__FILE__)))
		return $post_id;
		
	// CHECK IF WP IS AUTOSAVING
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
		
	// MAKE SURE USER HAS PROPER PERMISSION 
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}

	// LOOP THRU NEW META BOXES AND SAVE THE DATA
	foreach ($fresh_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
 
  

?>