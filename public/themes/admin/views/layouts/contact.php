<?php
$templates = service('template');
$user = service('user');
echo view_cell('\App\Controllers\Frontend\Common\Header::index') ;?>
	<div class="content-page">
		
		<?php echo $template['body']; ?>
						
	</div> <!-- container -->
<?= view_cell('\App\Controllers\Frontend\Common\Footer::index') ?>

