<section class="content">
    <form>
    <div class="block block-themed">
        <div class="block-header bg-primary">
            <h3 class="block-title">Filter</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Year</th>
                            <th>Filter</th>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control" id="year" name="year" required>
                                    <?php foreach ($years as $year) { ?>
                                        <option value="<?=$year['id']?>" <?php if($year['id']==$year_id){echo 'selected';} ?>><?=$year['name']?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <button id="btn-filter" class="btn btn-outline btn-primary"><i class="fa fa-filter"></i> Filter</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </form>

    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">Upload MPR</h3>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-vcenter" id="table-mpr">
                <thead>
                <tr>
                    <th>Month</th>
                    <th class="">Date uploaded</th>
                    <th class="">Status</th>
                    <th class="text-right">Upload</th>
                    <th class="text-right">Download</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($months as $month): ?>
                <tr>
                    <td><?=$month['month']?></td>
                    <td class="date-uploaded"><?=$month['date_uploaded']?></td>
                    <td class="uploaded"><?=$month['status']?></td>
                    <td class="dm-uploader" data-month="<?=$month['month_id']?>">
                        <div role="button" class="btn btn-outline-primary mr-2">
                            <i class="si si-paper-clip"></i>
                            <input type="file" title="">
                        </div>
                        <small class="status text-muted">Upload</small>
                    </td>
                    <td class="download"><?=$month['file']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>

<div id="loading-overlay">
    <div class="progress" style="width: 80%">
        <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" style="width:0%">
            <span id="progress-percent">0</span>
        </div>
    </div>
</div>
<style>
    #loading-overlay {
        background: rgb(255 255 255 / 80%);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        z-index: 9999;
    }
</style>

<?php js_start(); ?>
<script>

    $('.dm-uploader').dmUploader({
        dnd:false,
        url: '<?=$upload_url?>',
        dataType:'json',
        maxFileSize: 10000000, // 10MB
        multiple: false,
        allowedTypes: 'application/*',
        extFilter: ['pdf'],
        extraData: function () {
            return {
                month_id:$(this).data('month')
            }
        },
        onInit: function(){
            // Plugin is ready to use
            //console.log('initialized')
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
            $('#upload-controls').loading('stop');
        },
        onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            show_error('')
        },
        onBeforeUpload: function(id){
            // about tho start uploading a file
            setProgress(0)
            if(typeof(loading)=='undefined') {
                loading = $('#table-mpr').loading({
                    overlay: $('#loading-overlay')
                });
            } else {
                $('#table-mpr').loading();
            }
        },
        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
        },
        onUploadProgress: function(id, percent){
            // Updating file progress
            setProgress(percent)
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded server response
            if(data.status) {
                $(this).closest('tr').find('.uploaded').html(data.uploaded);
                $(this).closest('tr').find('.date-uploaded').text(data.date_uploaded);
                $(this).closest('tr').find('.download').html(data.download);
                $('#table-mpr').loading('stop',false);
            } else {
                show_error(data.message)
            }
        },
        onUploadError: function(id, xhr, status, message){
            show_error(this,message);
            $('#table-mpr').loading('stop',false);
        },
        onFileSizeError: function(file){
            // file.name
            show_error(this,'Invalid file size')
        },
        onFileExtError: function(file){
            // file.name
            show_error(this,'Invalid file extension')
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
<?php js_end(); ?>