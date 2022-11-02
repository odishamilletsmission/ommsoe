<!-- Main content -->
    <section class="content">

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Summary</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Agency Type</th>
                        <th>Opening (+)</th>
                        <th>Fund Receipt (+)</th>
                        <th>Other Receipt (+)</th>
                        <th>Expense (-)</th>
                        <th>Closing</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$summary['year']?></td>
                        <td><?=$summary['month']?></td>
                        <td><?=$summary['agency_type']?></td>
                        <td><?=$summary['ob']?></td>
                        <td><?=$summary['fr']?></td>
                        <td><?=$summary['mt']?></td>
                        <td><?=$summary['exp']?></td>
                        <td><?=$summary['bal']?></td>
                        <td><?=$summary['status']?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Closing Balance Breakup</h3>
            </div>
            <div class="block-content">
                <?php echo form_open(); ?>
                <table id="closing-balance-breakup" class="table table-bordered table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th style="width:300px;">Particulars</th>
                        <th style="width:300px;">Amount</th>
                        <th>Attachment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Advance</td>
                        <td><input class="form-control amount" value="<?=$advance?>" name="advance"></td>
                        <td class="dm-uploader">
                            <div role="button" class="btn btn-outline-primary mr-2">
                                <i class="si si-paper-clip"></i>
                                <input type="file" title="">
                            </div>
                            <input type="hidden" class="filepath" value="<?=$advance_file?>" name="advance_file">
                            <?php if($advance_file_url) { ?>
                                <?=$advance_file_url?>
                            <?php } else { ?>
                            <small class="status text-muted">Select a file</small>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Bank</td>
                        <td><input class="form-control amount" name="bank" value="<?=$bank?>"></td>
                        <td class="dm-uploader">
                            <div role="button" class="btn btn-outline-primary mr-2">
                                <i class="si si-paper-clip"></i>
                                <input type="file" title="">
                            </div>
                            <input type="hidden" class="filepath" value="<?=$bank_file?>" name="bank_file">
                            <?php if($bank_file_url) { ?>
                                <?=$bank_file_url?>
                            <?php } else { ?>
                                <small class="status text-muted">Select a file</small>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Cash</td>
                        <td><input class="form-control amount" name="cash" value="<?=$cash?>"></td>
                        <td class="dm-uploader">
                            <div role="button" class="btn btn-outline-primary mr-2">
                                <i class="si si-paper-clip"></i>
                                <input type="file" title="">
                            </div>
                            <input type="hidden" class="filepath" name="cash_file" value="<?=$cash_file?>">
                            <?php if($cash_file_url) { ?>
                                <?=$cash_file_url?>
                            <?php } else { ?>
                                <small class="status text-muted">Select a file</small>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><input class="form-control" id="total" disabled></td>
                        <td>
                            <?php if((int)$status!==1) { ?>
                            <button id="btn-submit" class="btn btn-sm btn-primary"><i class="si si-paper-plane"></i> Submit</button>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php echo form_close(); ?>
            </div>
        </div>

    </section>
    <!-- content -->

<?php js_start(); ?>
<script>

    $('.dm-uploader').dmUploader({
        dnd:false,
        url: '<?=$upload_url?>',
        dataType:'json',
        maxFileSize: 1000000, // 1MB
        multiple: false,
//        allowedTypes: ['image/*','application/*'],
        extFilter: ['jpg','png','jpeg','JPG','PNG','JPEG','pdf','PDF'],
        onInit: function(){
            // Plugin is ready to use
//            console.log('initialized')
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
//            $('#closing-balance-breakup').loading('stop');
            $('#closing-balance-breakup').LoadingOverlay("hide",true);
        },
        onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            show_error(this,'')
        },
        onBeforeUpload: function(id){
            // about tho start uploading a file

        },
        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
        },
        onUploadProgress: function(id, percent){
            // Updating file progress
//            $('#closing-balance-breakup').loading();
            $('#closing-balance-breakup').LoadingOverlay("show");
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded server response
            if(data.status) {
                $(this).find('.status').html(data.message);
                $(this).find('.filepath').val(data.filepath)
            } else {
                show_error(this,data.message);
            }

        },
        onUploadError: function(id, xhr, status, message){
            show_error(this,message)
//            $('#closing-balance-breakup').loading('stop');
            $('#closing-balance-breakup').LoadingOverlay("hide",true);
        },
        onFileSizeError: function(file){
            // file.name
            show_error(this,'Invalid file size')
        },
        onFileExtError: function(file){
            // file.name
            show_error(this,'Invalid file type')
        },
        onFileTypeError: function(file){
            // file.name
            show_error(this,'Invalid file type')

        }
    });

    function show_error(obj,msg){
        $(obj).find('.status').addClass('text-danger').text(msg)
    }

    var cb = parseFloat(<?=$summary['bal']?>);

    function toggleSubmit() {
        $('#btn-submit').attr('disabled',cb!=total);
    }

    $(function () {
        calcTotal();
        toggleSubmit();
        $('.amount').on('keyup',function (e) {
            if (/^-?\d*\.?\d{0,6}$/.test(this.value)==false) {
                $(this).val(this.value.replace(e.key,''));
            } else {
                calcTotal();
                toggleSubmit();
            }
        });
    });

    var total;
    function calcTotal(){
        total = 0;
        $('.amount').each(function (k,v) {
            val = parseFloat($(this).val()) || 0;
            total += val
        })
        $('#total').val(total.toFixed(2));
    }

    //check files
    //disabled till september
    <?php if(!$skip_file_validation): ?>
    $('#btn-submit').click(function(e){
        $('.fileerror').remove();
        $('.filepath').each(function(){
            if($(this).val()==''){
                $(this).closest('td').append('<small class="text-danger fileerror">(This file is required.)</small>');
                e.preventDefault();
            }
        })
    });
    <?php endif; ?>
</script>
<?php js_end(); ?>
