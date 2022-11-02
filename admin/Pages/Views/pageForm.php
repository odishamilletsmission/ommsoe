<?php
$validation = \Config\Services::validation();
?>

<?php echo form_open_multipart('', 'id="form-page"'); ?>
<div class="row">
	<div class="col-xl-9">
		
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title"><?php echo $text_form; ?></h3>
				<div class="block-options">
					<a href="<?=admin_url("pages/vedit/$id")?>" class="btn btn-primary">Visual Edit</a>
				</div>
				<input type="hidden" name="id" id="id" value="<?=$id?>"/>
			</div>
			<div class="block-content">
				<div class="form-group <?=$validation->hasError('title')?'is-invalid':''?>">
					<label for="title">Title</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'title', 'id' => 'title', 'placeholder'=>'Title','value' => set_value('title', $title))); ?>
					<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('title'); ?></div>
							
				</div>
				
				<div class="form-group">
					<label for="title">Content</label>
					<textarea name="content" rows="10" cols="40" class="ckeditor_textarea form-control" id = "js-ckeditor"><?=$content?></textarea>
				</div>	
			</div> 
		</div>
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Meta Details</h3>
			</div>
			<div class="block-content"> 
				<div class="form-group <?=$validation->hasError('slug')?'is-invalid':''?>">
					<label for="slug" >Seo Url</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'slug', 'id' => 'slug', 'placeholder'=>'Seo url','value' => set_value('slug', $slug))); ?>
					<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('slug'); ?></div>		
				</div>
				<div class="form-group">
					<label for="meta_title" >Meta Title</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'meta_title', 'id' => 'meta_title', 'placeholder'=>'Meta Title','value' => set_value('meta_title', $meta_title))); ?>
				</div>
				<div class="form-group">
					<label for="meta_keywords" >Meta Keywords</label>
					<?php echo form_textarea(array('name'  => 'meta_keywords','class' => 'form-control','id' => 'meta_keywords','rows'=>'3','value'=>set_value('meta_keywords',$meta_keywords))); ?>
				</div>
				<div class="form-group">
					<label for="meta_description" >Meta Description</label>
					<?php echo form_textarea(array('name'  => 'meta_description','class' => 'form-control','id' => 'meta_description','rows'=>'3','value'=>set_value('meta_description',$meta_description))); ?>
				</div>
			</div>
		</div>
			
	</div> 
	<div class="col-xl-3">
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Publish</h3>
			</div>
			<div class="block-content"> 
				<div class="form-group">
					<label for="status" >Status</label>
					<?php echo form_dropdown('status', array('published'=>'Published', 'draft'=>'Draft', 'disabled' => 'Disabled'), set_value('status', $status), 'id=\'status\' class=\'form-control\'')?>
				</div>
				<div class="form-group">
					<label for="visibilty" >Visibilty</label>
					<?php echo form_dropdown('visibilty', array('public'=>'Public', 'private'=>'Private', 'password protected' => 'Password Protected'), set_value('visibilty', $visibilty), 'id=\'visibilty\' class=\'form-control\'')?>
				</div>
			</div>
			<div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
				<button type="button" class="btn btn-secondary">Preview</button>
				
				<button type="submit" form="form-page" class="btn btn-primary float-right">Save</button>
					
			</div>
		</div>
		
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Page Attribute</h3>
			</div>
			<div class="block-content">
				
				<div class="form-group">
					<label for="template">Template</label>
					<?php echo form_dropdown('layout', $layouts, set_value('layout', $layout), 'id="layout" class="form-control"'); ?>
				</div>
				
				<div class="form-group">
					<label for="parent">Parent</label>
					<?php echo form_dropdown('parent_id', option_array_value($parents, 'id', 'title','No Parent'), set_value('parent_id', $parent_id),array('class'=>'form-control','id'=>'parent_id')); ?>
				</div>
				
				<div class="form-group">
					<label for="sort-order">Sort Order</label>
					<?php echo form_input(array('name' => 'sort_order', 'class'=>'form-control', 'id' => 'sort_order','value' => set_value('sort_order', $sort_order))); ?>
					
				</div>
			</div>
		</div>
		
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Feature Image</h3>
			</div>
			<div class="block-content"> 
				<div class="form-group">
					<div class="mx-auto">
						<div class="text-center mb-2">
							<img src="<?php echo $thumb_feature_image; ?>" class="img-fluid" alt="" id="thumb_feature_image" />
							<input type="hidden" name="feature_image" value="<?php echo $feature_image?>" id="feature_image" />
						</div>
						<div>
							<a class="btn btn-primary btn-xs" onclick="image_upload('feature_image','thumb_feature_image')"><?php echo $text_image; ?></a>
							<a class="btn btn-danger btn-xs" onclick="$('#thumb_feature_image').attr('src', '<?php echo $no_image; ?>'); $('#feature_image').attr('value', '');"><?php echo $text_clear; ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo form_close(); ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
	jQuery(function(){
		Codebase.helpers(['ckeditor']); 
	});
	$(document).ready(function() {
		$('textarea.ckeditor_textarea').each(function(index) {
			
			//ckeditor_config.height = $(this).height();
			
			//CKEDITOR.replace($(this).attr('name'), ckeditor_config); 
			CKEDITOR.replace($(this).attr('name')); 
		});
		
		$('#title').keyup( function(e) {
			$('#slug').val($(this).val().toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, ''))
		});
	});
	
//--></script>
<script type="text/javascript"><!--
	function image_upload1(field, thumb) {
		window.KCFinder = {
			callBack: function(url) {
				
				window.KCFinder = null;
				var lastSlash = url.lastIndexOf("uploads/");
				
				var fileName=url.substring(lastSlash+8);
				url=url.replace("images", ".thumbs/images"); 
				$('#'+thumb).attr('src', url);
				$('#'+field).attr('value', fileName);
				$.colorbox.close();
			}
		};
		$.colorbox({href:BASE_URL+"storage/plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});	
	};
	function image_upload(field, thumb) {
		CKFinder.modal( {
			chooseFiles: true,
			width: 800,
			height: 600,
			onInit: function( finder ) {
				console.log(finder);
				finder.on( 'files:choose', function( evt ) {
					var file = evt.data.files.first();
					url=file.getUrl();
					
					var lastSlash = url.lastIndexOf("uploads/");
					var fileName=url.substring(lastSlash+8);
					//url=url.replace("images", ".thumbs/images"); 
					$('#'+thumb).attr('src', decodeURI(url));
					$('#'+field).attr('value', decodeURI(fileName));

				} );




				finder.on( 'file:choose:resizedImage', function( evt ) {
					var output = document.getElementById( field );
					output.value = evt.data.resizedUrl;
					console.log(evt.data.resizedUrl);
				} );
			}
		});

	};

//--></script>

<?php js_end(); ?>