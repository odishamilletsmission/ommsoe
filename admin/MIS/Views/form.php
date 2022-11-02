<style>
    .w-50p{width:50px;}
</style>
<!-- Main content -->
<section class="content">

    <div class="block block-themed">
        <div class="block-header bg-info">
            <h3 class="block-title">Summary</h3>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                <tr>
                    <th>District</th>
                    <th>Block</th>
                    <th>Agency Type</th>
                    <th>Month/Year</th>
                    <th>Date Added</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?=$district?></td>
                    <td><?=$block?></td>
                    <td><?=$agency_type?></td>
                    <td><?=$month?> / <?=$year?></td>
                    <td><?=$date_added?></td>
                    <td><?=$status?></td>
                    
                </tr>
                <?php if(!empty($remarks)): ?>
                <tr>
                    <td>Remarks:</td>
                    <td colspan="7"><?=$remarks?></td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">MIS Details</h3>
        </div>
        <div class="block-content block-content-full">
            <?php echo form_open(); ?>
            <div class="tableFixHead">
                <table class="table custom-table " id="txn-table">
                    <thead>
                    <tr>
                        <th width="10%">Sl no</th>
                        <th width="30%">Component</th>
                        <th width="15%">Unit Type</th>
                        <th width="15%">Output Indicator</th>
                        <th width="15%">Achievement</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$components?>
                    </tbody>
                </table>
            </div>
            <?php if($show_form): ?>
            <div class="row">
                <div class="col mt-4">
                    <button type="submit" class="btn btn-alt-primary float-right">Submit</button>
                </div>
            </div>
            <?php endif; ?>
                <?php echo form_close(); ?>
        </div>
    </div>
</section>

<?php js_start(); ?>
<script>
    $(function () {

        var $th = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
            $th.css('transform', 'translateY('+ this.scrollTop +'px)');
        });

        $('.btn-upload').click(function () {

        });

    });

    $('.dm-uploader').dmUploader({
        dnd:false,
        url: '<?=$upload_url?>',
        dataType:'json',
        maxFileSize: 30000000, // 20MB
        multiple: false,
//        allowedTypes: 'image/*',
//        extFilter: ['jpg','png','jpeg','JPG','PNG','JPEG'],
        onInit: function(){
            // Plugin is ready to use
            console.log('initialized')
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
//            $('.tableFixHead').loading('stop');
            $('.tableFixHead').LoadingOverlay("hide",true);
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
//            $('.tableFixHead').loading();
            $('.tableFixHead').LoadingOverlay("show");
        },
        onUploadSuccess: function(id, data){
			//console.log(data.message);
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
//            $('.tableFixHead').loading('stop');
            $('.tableFixHead').LoadingOverlay("hide",true);
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
    function setProgress(percent) {
        $('#progress-bar').width(percent+'%')
        $('#progress-percent').text(percent+'%')
    }
</script>

<?php if(isset($approval)) {
    echo $approve_form;
} ?>

<?php js_end(); ?>
