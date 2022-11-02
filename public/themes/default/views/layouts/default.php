<?php
$templates = service('template');
$user = service('user');
echo view_cell('\Front\Common\Controllers\Header::index') ;?>
	<div class="content-page">
		<?php if($header && !$home){?>
		<div class="pages-hero" id="particles-js" style="background: url('<?=$feature_image?>')  #cfcfe6">
			<div class="container">
				<div class="pages-title">
					<?php if(isset($heading_title)){?>
					<h1><?=$heading_title?></h1>
					<p><?=$meta_title?></p>
					<?}?>
				</div>
			</div>
		</div>
		<?}?>
		<?php echo $template['body']; ?>

	</div> <!-- container -->
<?= view_cell('\Front\Common\Controllers\Footer::index') ?>

