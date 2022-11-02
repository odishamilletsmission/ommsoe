<?php
$validation = \Config\Services::validation();
?>

<?php echo form_open_multipart('', 'id="form-grampanchayat"'); ?>
<div class="row">
	<div class="col-xl-12">
		
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title"><?php echo $text_form; ?></h3>
				<div class="block-options">
					<button type="submit" form="form-grampanchayat" class="btn btn-primary">Save</button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
				</div>
			</div>
			
			<div class="block-content">
				<div class="form-group <?=$validation->hasError('district')?'is-invalid':''?>">
                    <label for="code">District</label>
                    <?php echo form_dropdown('district_id', option_array_value($districts, 'id', 'name'), set_value('district_id', $district_id),"id='district_id' class='form-control js-select2'"); ?>
                    <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('district_id'); ?></div>

                </div>
                <div class="form-group <?=$validation->hasError('block_id')?'is-invalid':''?>">
                    <label for="code">Block</label>
                    <?php echo form_dropdown('block_id', array(), set_value('block_id', $block_id),"id='block_id' class='form-control js-select2'"); ?>
                    <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('block_id'); ?></div>
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
    $(document).ready(function() {
        $('select[name=\'district_id\']').bind('change', function() {
            $.ajax({
                url: '<?php echo admin_url("district/block"); ?>/' + this.value,
                dataType: 'json',
                beforeSend: function() {
                    //$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    //$('.wait').remove();
                },
                success: function(json) {

                    html = '<option value="">Select Block</option>';

                    if (json['block'] != '') {
                        for (i = 0; i < json['block'].length; i++) {
                            html += '<option value="' + json['block'][i]['id'] + '"';
                            if (json['block'][i]['id'] == '<?php echo $block_id; ?>') {
                                html += ' selected="selected"';
                            }
                            html += '>' + json['block'][i]['name'] + '</option>';
                        }
                    } else {
                        html += '<option value="0" selected="selected">Select Block</option>';
                    }

                    $('select[name=\'block_id\']').html(html);
                    $('select[name=\'block_id\']').select2();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        $('select[name=\'district_id\']').trigger('change');
    });

    //--></script>
<?php js_end(); ?>