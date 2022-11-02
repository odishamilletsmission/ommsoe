<?php
$validation = \Config\Services::validation();
?>
<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><?php echo $text_form; ?></h3>
                <div class="block-options">
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-banner"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
                </div>
            </div>
            <div class="block-content">
				<?php echo form_open_multipart('',array('class' => 'form-horizontal', 'id' => 'form-banner','role'=>'form')); ?>
				<div class="form-group row <?=$validation->hasError('title')?'is-invalid':''?>">
					<label class="col-sm-2 control-label" for="input-name">Banner Name</label>
					<div class="col-sm-10">
                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
						<?php echo form_input(array('class'=>'form-control','name' => 'title', 'id' => 'title', 'placeholder'=>'Banner Name','value' => set_value('title', $title))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('title'); ?></div>
                    </div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 control-label" for="input-status">Status</label>
					<div class="col-sm-10">
						<?php echo form_dropdown('status', array('1'=>'Enabled', '0' => 'Disabled'), set_value('status', $status), 'id=\'status\' class=\'form-control\'')?>
					</div>
				</div>
				
				<table id="banner_images" class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="">
							<th style="width: 20px;"></th>
							<th class="text-left">Image</th>
							<th style="width: 200px;" class="text-left">Title/link</th>
							<th class="text-left">Description</th>
							<th class="text-right">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $image_row = 0; ?>
						<?php foreach ($banner_images as $banner_image) { ?>
						<tr id="image-row<?php echo $image_row; ?>">
							<td class="drag_handle"></td>
							<td class="text-left">
								
								<div class="fileinput">
									<div class="options-container">
										<img class="img-fluid options-item"src="<?php echo $banner_image['thumb']; ?>" alt="" id="thumb-image<?php echo $image_row; ?>" />
										<input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
										<div class="options-overlay bg-black-op-75">
											<div class="options-overlay-content">
												<a class="btn btn-sm btn-rounded btn-alt-primary min-width-75" onclick="image_upload('input-image<?php echo $image_row; ?>','thumb-image<?php echo $image_row; ?>')">Browse</a>
												<a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" onclick="$('#thumb-image<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#input-image<?php echo $image_row; ?>').attr('value', '');">Clear</a>
											</div>
										</div>
									</div>
								</div>
							</td>
							<td class="text-left">
								<input type="text" name="banner_image[<?php echo $image_row; ?>][title]" value="<?php echo  $banner_image['title']; ?>" placeholder="Title" class="form-control" />
								<input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo  $banner_image['link']; ?>" placeholder="Link" class="form-control" />
							</td>
							<td class="text-left">
								<textarea name="banner_image[<?php echo $image_row; ?>][description]" class="description form-control"><?php echo $banner_image['description']; ?> </textarea>
							</td>
							<td class="text-right"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus"></i></button></td>
						</tr>
						<?php $image_row++; ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4"></td>
							<td class="text-right"><button type="button" onclick="addImage();" data-toggle="tooltip" title="Banner Add" class="btn btn-primary"><i class="fa fa-plus"></i></button></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>	
<?php js_start(); ?>
<script type="text/javascript">
	var thin_config = {
		toolbar : [
			{ name: 'basicstyles', items : [ 'Bold','Italic','-','NumberedList','BulletedList','-','Link','Unlink','Source'] }
		],
		entities : true,
		entities_latin : false,
		allowedContent: true,
		enterMode : CKEDITOR.ENTER_BR,
		resize_maxWidth : '400px',
		width : '550px',
		height : '120px'
  };
 
  $(document).ready(function() {
      initDnD = function() {
			
         // Sort images (table sort)
         $('#banner_images').tableDnD({
            onDrop: function(table, row) {
               order = $('#banner_images').tableDnDSerialize()
               $.post("<?php echo admin_url('banner/images_order') ?>", order, function() {
                  
               });
            },
            //dragHandle: ".drag_handle"
         });
      }
      initDnD();
		
      $('textarea.description').ckeditor(thin_config);
   });
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
		$.colorbox({href:BASE_URL+"/plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});	
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

</script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	
   html = '<tr id="image-row' +image_row + '">';
	html += '	<td class="drag_handle"></td>';
	html += '  	<td class="text-left">';
	html += '		<div class="fileinput">';
	html += '			<div class="options-container">';
	html += '				<img class="img-fluid options-item" src="<?php echo $no_image; ?>" alt="" id="thumb-image' + image_row + '" />';
	html += '				<input type="hidden" name="banner_image[' + image_row + '][image]" value="" id="input-image'+image_row+'" />';
	html += '				<div class="options-overlay bg-black-op-75">';
	html += '					<div class="options-overlay-content">';
	html += '						<a class="btn btn-sm btn-rounded btn-alt-primary min-width-75" onclick="image_upload(\'input-image' + image_row + '\',\'thumb-image' + image_row + '\')">Browse</a>';
	html += '						<a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" onclick="$(\'#thumb-image' + image_row  + '\').attr(\'src\',  \'<?php echo $no_image; ?>\'); $(\'#input-image'+ image_row +'\').attr(\'value\', \'\');">Clear</a>';
	html += '					</div>';
	html += '				</div>';
	html += '			</div>';
	html += '		</div>';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="banner_image[' + image_row + '][title]" value="" placeholder="Title" class="form-control" />';
	html += '		<input type="text" name="banner_image[' + image_row + '][link]" value="" placeholder="Link" class="form-control" />';
	html += '	</td>';
	html += '	<td class="text-left"><textarea name="banner_image[' + image_row + '][description]" class="description form-control"></textarea></td>	';				
	html += '  	<td class="text-right"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#banner_images tbody').append(html);
	$('textarea.description').ckeditor(thin_config);
	initDnD();
	image_row++;
}
function removeimage(j)
{
	$(".image-row"+j).remove();
	var instance="banner_image["+j+"][description]";
	var editor = CKEDITOR.instances[instance];
	if (editor) { editor.destroy(true); }
	//$('textarea.description').ckeditor(thin_config);
	
}
//--></script>
<?php js_end(); ?>

