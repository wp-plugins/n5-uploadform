<div class="wrap">
	 <h2><?php echo __('Create a New Uploading Form','N5_UPLOADFORM'); ?></h2>
	 <div>
		<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] );?>">
		 <input type="hidden" name="taxonomy" value="n5uploadform">
		 <input type="hidden" name="action" value="save-n5uploadform">
		 <input type="hidden" name="n5uf-id" value="<?php echo $post->ID;?>"> 

		 <table class="form-table">
			<tbody>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-name"><?php echo __('Form Name','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-name" id="n5uf-name" value="<?php echo esc_attr($post->post_title); ?>">
					 <p class="description"><?php echo __('This is a name of the form. The user controls the form with this name, such as listing. Use a unique name to differentiate with other forms. This name is only used in the Uploading Form Administration page.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>
		 
				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-name"><?php echo __('Form Code','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-code" id="n5uf-code" value="<?php echo esc_attr(sprintf('[n5uploadform id="%s"]', $post->ID)); ?>" readonly="readonly">
					 <p class="description"><?php echo __('Paste this code to the static (web) pages or articles in WordPress.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-directory"><?php echo __('Uploading Directory','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-directory" id="n5uf-directory" value="<?php echo esc_attr($post_meta['directory']);?>">
					 <p class="description"><?php echo __('This entry directs the saving location of the uploaded file.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-ext"><?php echo __('Extension Filter','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-ext" id="n5uf-ext" value="<?php echo esc_attr($post_meta['ext']);?>">
					 <p class="description"><?php echo __('This entry enables the user to limit the file extension of the uploaded file. Divide the list of the extensions with comma (,) when limiting multiple file extensions.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>
		 
				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-mime"><?php echo __('MIME-TYPE Filter','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-mime" id="n5uf-mime" value="<?php echo esc_attr($post_meta['mime']);?>">
					 <p class="description"><?php echo __('This enables the user to limit the MMIE-TYPE of the uploaded file. Divide the list of the MMIE-TYPE with comma (,) when limiting multiple file extensions.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-finish"><?php echo __('Uploading Completion Screen Message','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <textarea name="n5uf-finish" id="n5uf-finish"><?php echo esc_attr($post_meta['finish']);?></textarea>
					 <p class="description"><?php echo __('This enables the user to preset the message when the uploading is completed. If this section was set empty, the default message will be displayed. The user can write the message with HTML.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-adminnotice"><?php echo __('E-Mail Address','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-adminnotice" id="n5uf-adminnotice" value="<?php echo esc_attr($post_meta['adminnotice']);?>">
					 <p class="description"><?php echo __('Type the E-mail address for sending a notification to the administrator.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-adminnotice_subject"><?php echo __('E-mail Subject','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-adminnotice_subject" id="n5uf-adminnotice_subject" value="<?php echo esc_attr($post_meta['adminnotice_subject']);?>">
					 <p class="description"><?php echo __('This is the subject of the notification E-mail.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-adminnotice_body"><?php echo __('E-mail Body','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <textarea name="n5uf-adminnotice_body" id="n5uf-adminnotice_body"><?php echo esc_html($post_meta['adminnotice_body']);?></textarea>
					 <p class="description"><?php echo __('This is the body of the notification E-mail.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-usernotice"><?php echo __('Sending E-mail Notification','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <select name="n5uf-usernotice" id="n5uf-usernotice">
						<option value="0" <?php echo ($post_meta['usernotice']>0)?'':'selected=\'selected\''; ?>><?php echo __('OFF','N5_UPLOADFORM'); ?></option>
						<option value="1" <?php echo ($post_meta['usernotice']>0)?'selected=\'dselected\'':''; ?>><?php echo __('ON','N5_UPLOADFORM'); ?></option>
					 </select>
					 <p class="description"><?php echo __('This determines whether the notification E-mail is sent to the uploading user. Selecting "ON" will create a form to type the E-Mail address.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-usernotice_subject"><?php echo __('E-mail Subject','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <input type="text" name="n5uf-usernotice_subject" id="n5uf-usernotice_subject" value="<?php echo esc_attr($post_meta['usernotice_subject']);?>">
					 <p class="description"><?php echo __('This is the subject of the notification E-mail.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>

				 <tr class="form-field form-required term-name-wrap">
					<th scope="row">
					 <label for="n5uf-usernotice_body"><?php echo __('E-mail Body','N5_UPLOADFORM'); ?></label>
					</th>
					<td>
					 <textarea name="n5uf-usernotice_body" id="n5uf-usernotice_body"><?php echo esc_html($post_meta['usernotice_body']);?></textarea>
					 <p class="description"><?php echo __('This is the body of the notification E-mail.','N5_UPLOADFORM'); ?></p>
					</td>
				 </tr>


			</tbody>
		 </table>

		 <?php submit_button('SAVE');?>
	 
		</form>

	 
	 </div>
</div>