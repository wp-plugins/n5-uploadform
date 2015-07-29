<?php if( empty($post_meta['finish'])): ?>
<?php echo __('The uploading was completed.','N5_UPLOADFORM'); ?>
<?php else: ?>
<?php echo $post_meta['finish'];?>
<?php endif;?>