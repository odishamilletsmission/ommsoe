<?php
$validation = \Config\Services::validation();
?>

<?php echo form_open_multipart('', 'id="form-district"'); ?>
<div class="row">
	<div class="col-xl-12">
		
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title"><?php echo $text_form; ?></h3>
				<div class="block-options">
					<button type="submit" form="form-district" class="btn btn-primary">Save</button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
				</div>
			</div>
			
			<div class="block-content">
				<div class="form-group <?=$validation->hasError('code')?'is-invalid':''?>">
					<label for="code">Code</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'code', 'placeholder'=>'Code','value' => set_value('code', $code))); ?>
					<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('code'); ?></div>
							
				</div>
				<div class="form-group <?=$validation->hasError('name')?'is-invalid':''?>">
					<label for="name" >Name</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Name','value' => set_value('name', $name))); ?>
					<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('name'); ?></div>		
				</div>	
			</div> 
		</div>
	</div> 
</div>
<?php echo form_close(); ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
	
//--></script>
<script type="text/javascript"><!--
	
//--></script>

<?php js_end(); ?>