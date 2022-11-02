<?php
$templates = service('template');
$user = service('user');
echo view_cell('\App\Controllers\Frontend\Common\Header::index') ;?>
	<div class="content-page">
		<?php if($header){?>
			<div class="pages-hero">
				<div class="container">
					<div class="pages-title">
						<h1><?=$heading_title?></h1>
						<p><?=$meta_title?></p>
					</div>
				</div>
			</div>
		<?}?>
		<div class="container mb-2 mt-5">
			<div class="row">
				<div class="col-md-8">
					<?php echo $template['body']; ?>
				</div>
				<div class="col-md-4">
					<section>
						<h3>Reporting Matter To Higher Authority</h3>
						<div class="card text-white bg-info mb-3">
						  <div class="card-body">
								<form action="" method="post" id="complain_form">
									  <div class="form-group">
										<label for="exampleInputEmail1">Email address</label>
										<input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" required>
										</div>
									  <div class="form-group">
										<label for="exampleInputPassword1">Name</label>
										<input type="text" class="form-control" id="name" name="name" required>
									  </div>
									  <div class="form-group">
										<label for="exampleFormControlTextarea1">Your Matter</label>
										<textarea class="form-control" id="complain" name="complain" rows="3" required></textarea>
									  </div>
									  <button type="submit" class="btn btn-primary">Submit</button>
								</form>
							</div>
						</div>
						
					</section>
					
				</div>
			</div>
		</div>
		
	</div> <!-- container -->
<?= view_cell('\App\Controllers\Frontend\Common\Footer::index') ?>

