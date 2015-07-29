<div class="wrap">
	 <h2><?php echo __('Manage for UploadFile','N5_UPLOADFORM')  ?></h2>
	 
	<form id="posts-filter" action="" method="post">

		<input type="hidden" name="taxonomy" value="n5uf-file">
		<input type="hidden" name="post_type" value="post">

		<?php $n5_upload_file_list_table->display(); ?>
		<br class="clear">
	
	</form>

</div><!-- .wrap -->