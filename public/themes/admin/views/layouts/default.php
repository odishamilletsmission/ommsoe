<?php
//$templates = service('template');
$user = service('user');
$session = service('session');
echo view_cell('\Admin\Common\Controllers\Header::index') ;?>
	<main id="main-container">
			<?php if ($user->isLogged() && $header){?>
				<div class="content">
					<!--<nav class="breadcrumb bg-white push">
						<a class="breadcrumb-item" href="javascript:void(0)">Generic</a>
						<span class="breadcrumb-item active">Blank Page</span>
					</nav>-->
				<?php if(isset($error)){?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
				</div>
				<?}else if($session->getFlashdata('message')){?>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="fa fa-exclamation-circle"></i> <?php echo $session->getFlashdata('message'); ?>
				</div>
				<?}else if($session->getFlashdata('error')){?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-exclamation-circle"></i> <?php echo $session->getFlashdata('error'); ?>
                    </div>
                <?}?>
				
				<?php echo $template['body']; ?>
				</div>
			<?}else {; ?>
			<?php echo $template['body']; ?>
			<?}?>			
		</div> <!-- container -->
	</main>
<?= view_cell('\Admin\Common\Controllers\Footer::index') ?>

