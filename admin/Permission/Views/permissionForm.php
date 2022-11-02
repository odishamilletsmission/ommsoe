<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-permission"'); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><?php echo $text_form; ?></h3>
                <div class="block-options">
                    <button type="submit" form="form-permission" class="btn btn-primary">Save</button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
                </div>
            </div>
            <div class="block-content">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Name</label>
                    <div class="col-lg-10 <?=$validation->hasError('name')?'is-invalid':''?>">
                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                        <?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Name','value' => set_value('name', $name))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('name'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Description</label>
                    <div class="col-lg-10 <?=$validation->hasError('description')?'is-invalid':''?>">
                        <?php echo form_input(array('class'=>'form-control','name' => 'description', 'id' => 'description', 'placeholder'=>'Description','value' => set_value('description', $description))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('description'); ?></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label" for="input-status">Status</label>
                    <div class="col-md-10">
                        <?php  echo form_dropdown('status', array('1'=>'Enable','0'=>'Disable'), set_value('status',$status),array('class'=>'form-control','id' => 'input-status')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<?php js_start(); ?>
    <script type="text/javascript"><!--
    //--></script>
<?php js_end(); ?>