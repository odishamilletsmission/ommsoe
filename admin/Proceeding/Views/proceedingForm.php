<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-proceeding"'); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><?php echo $text_form; ?></h3>
                <div class="block-options">
                    <button type="submit" form="form-proceeding" class="btn btn-primary">Save</button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
                </div>
            </div>
            <div class="block-content">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Subject</label>
                    <div class="col-lg-10 <?=$validation->hasError('name')?'is-invalid':''?>">
                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                        <?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Name','value' => set_value('name', $name))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('name'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Letter No</label>
                    <div class="col-lg-10 <?=$validation->hasError('letter_no')?'is-invalid':''?>">
                        <?php echo form_input(array('class'=>'form-control','name' => 'letter_no', 'id' => 'letter_no', 'placeholder'=>'Letter No','value' => set_value('letter_no', $letter_no))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('letter_no'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Letter Date</label>
                    <div class="col-lg-10 <?=$validation->hasError('letter_no')?'is-invalid':''?>">
                        <?php echo form_input(array('class'=>'form-control js-flatpickr bg-white','name' => 'letter_date', 'id' => 'letter_date', 'placeholder'=>'Letter Date','value' => set_value('letter_date', $letter_date))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('letter_date'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Attachment</label>
                    <div class="col-lg-10 <?=$validation->hasError('attachment')?'is-invalid':''?>">
                        <div class="input-group">
                            <?php echo form_input(array('class'=>'form-control','name' => 'attachment', 'id' => 'attachment', 'placeholder'=>'Attachment','value' => set_value('attachment', $attachment),'readonly'=>true)); ?>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" onclick="file_upload('attachment')" type="button">File Choose</button>
                            </div>
                        </div>

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
        jQuery(function(){
            Codebase.helpers(['flatpickr']);
        });
        function file_upload(field) {
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