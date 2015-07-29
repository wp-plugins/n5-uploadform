<?php if( count($upload_errors) > 0 ): ?>
<section class="n5uf-errors">
	<div>
		<ul>
<?php foreach( $upload_errors as $upload_error): ?>	
		<li><?php echo $upload_error;?></li>
<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<form action="#" method="POST" enctype="multipart/form-data" class="n5uf-form">
	<input type="hidden" name="action" value="input-n5uploadform">
	 
	<ul>
<?php if( $post_meta['usernotice'] > 0 ): ?>
		<li>
			<div><label><?php echo __('E-Mail','N5_UPLOADFORM'); ?></label></div>
			<div><input type="text" name="n5uf-usermail"></div>
		</li>
<?php endif; ?>
	 
		<li>
			<div><label><?php echo __('File','N5_UPLOADFORM'); ?></label></div>
			<div><input type="file" name="n5uf-file"></div>
		</li>

	</ul>

	<input type="submit" value="UPLOAD">
</form>