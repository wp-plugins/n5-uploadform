<div class="wrap">
	<h2><?php echo __('Manage for Uploadform','N5_UPLOADFORM'); ?></h2>
	<div>
		<div id="col-container">
		 
			<div id="col-right">
				<div class="col-wrap">
		 
					<form id="posts-filter" action="" method="post">

						<input type="hidden" name="taxonomy" value="n5uf-form">
						<input type="hidden" name="post_type" value="post">
						<?php $n5_upload_form_list_table->views(); ?>
						<?php $n5_upload_form_list_table->display(); ?>

						<br class="clear">
					</form>

					<div class="form-wrap">
					</div>

				</div><!-- .col-wrap -->
		 
			</div><!-- #col-right -->
		 
			<div id="col-left">
				<div class="col-warp">

					<div class="form-wrap">
						<h3><?php echo __('Create a New Uploading Form','N5_UPLOADFORM'); ?></h3>

						<form id="addtag" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] );?>" class="validate" _lpchecked="1">
		 
							<input type="hidden" name="taxonomy" value="n5uploadform">
							<input type="hidden" name="action" value="add-n5uploadform">
		 
							<div class="form-field form-required term-name-wrap">
									 <label for="n5uf-name"><?php echo __('Form Name','N5_UPLOADFORM'); ?></label>
									 <input type="text" name="n5uf-name" id="n5uf-name" value="" size="40" aria-required="true">
									 <p class="description"><?php echo __('This is a name of the form. The user controls the form with this name, such as listing. Use a unique name to differentiate with other forms. This name is only used in the Uploading Form Administration page.','N5_UPLOADFORM'); ?></p>
							</div>

							<div class="form-field form-required term-name-wrap">
									 <label for="n5uf-directory"><?php echo __('Uploading Directory','N5_UPLOADFORM'); ?></label>
									 <input type="text" name="n5uf-directory" id="n5uf-directory" value="" size="40" aria-required="true">
									 <p class="description"><?php echo __('This entry directs the saving location of the uploaded file.','N5_UPLOADFORM'); ?></p>
							</div>
<?php if(false):?>
							<div class="form-field form-required term-name-wrap">
									 <label for="n5uf-ext"><?php echo __('Extension Filter','N5_UPLOADFORM'); ?></label>
									 <input type="text" name="n5uf-ext" id="n5uf-ext" value="" size="40" aria-required="true">
									 <p class="description"><?php echo __('This entry enables the user to limit the file extension of the uploaded file. Divide the list of the extensions with comma (,) when limiting multiple file extensions.','N5_UPLOADFORM'); ?></p>
							</div>
		 
							<div class="form-field form-required term-name-wrap">
									 <label for="n5uf-mime"><?php echo __('MIME-TYPE Filter','N5_UPLOADFORM'); ?></label>
									 <input type="text" name="n5uf-mime" id="n5uf-mime" value="" size="40" aria-required="true">
									 <p class="description"><?php echo __('This enables the user to limit the MMIE-TYPE of the uploaded file. Divide the list of the MMIE-TYPE with comma (,) when limiting multiple file extensions.','N5_UPLOADFORM'); ?></p>
							</div>

<?php endif; ?>
		 
							<div class="form-field form-required term-name-wrap">
									 <label for="n5uf-adminnotice"><?php echo __('E-Mail Address','N5_UPLOADFORM'); ?></label>
									 <input type="text" name="n5uf-adminnotice" id="n5uf-adminnotice" value="" size="40" aria-required="true">
									 <p class="description"><?php echo __('Type the E-mail address for sending a notification to the administrator.','N5_UPLOADFORM'); ?></p>
							</div>
<?php if(false):?>
							<div class="form-field form-required term-name-wrap">
									 <label for="n5uf-notice"><?php echo __('Sending E-mail Notification','N5_UPLOADFORM'); ?></label>
									 <select name="n5uf-usernotice" id="n5uf-usernotice">
										 <option value="0" selected="selected"><?php echo __('OFF','N5_UPLOADFORM'); ?></option><option value="1" selected="selected"><?php echo __('ON','N5_UPLOADFORM'); ?></option>
									 </select>
									 <p class="description"><?php echo __('This determines whether the notification E-mail is sent to the uploading user. Selecting "Yes" will create a form to type the E-Mail address.','N5_UPLOADFORM'); ?></p>
							</div>
<?php endif;?>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="CREATE"></p>

						</form>
					</div><!-- .form-wrap -->
				</div><!-- .col-wrap -->
			</div><!-- #col-left -->
		</div><!-- #col-container -->
	</div>
</div><!-- .wrap -->