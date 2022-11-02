
<!-- Main content -->
<section class="content1">
    <div class="block" id="upload-controls">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" id="year" name="year" required>
                            <option value="">Choose Year</option>
                            <?php foreach ($years as $year) { ?>
                                <option value="<?=$year['id']?>" <?php if($year['id']==$year_id){echo 'selected';} ?>><?=$year['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" id="month" name="month" required>
                            <option value="">Choose Month</option>
                            <?php foreach ($months as $month) { ?>
                                <option value="<?=$month['id']?>" <?php if($month['id']==$month_id){echo 'selected';} ?>><?=$month['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if($agency_types): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="agency_type_id" name="agency_type_id" required>
                            <?php foreach ($agency_types as $agency_type) : ?>
                            <option value="<?=$agency_type['id']?>" <?php if($agency_type['id']==$agency_type_id){echo 'selected';} ?>><?=$agency_type['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-2">
                        <select class="form-control" id="txn_type" name="txn_type" required>
                            <option value="expense">Expense</option>
                            <option value="fund_receipt">Fund Receipt</option>
                        </select>
                    </div>
                    <?php if($districts): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="district_id" name="district_id">
                            <option value="">Choose District (if district level)</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?=$district['id']?>"><?=$district['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <?php if($blocks): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="block_id" name="block_id">
                            <option value="">Choose Block (if block level)</option>
                            <?php foreach ($blocks as $block): ?>
                                <option value="<?=$block['id']?>"><?=$block['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="row mt-3" id="btn-row">
                    <?php if(!$upload_enabled): ?>
                        <div class="col-12" id="alert-msg">
                        <div class="alert alert-danger" role="alert">
                            <p class="mb-0">The SoE/Fund Receipt upload is closed for the month. </p>
                        </div>
                        </div>
                    <?php else: ?>
                    <?php if(!$mis_uploaded): ?>
                        <div class="col-12" id="alert-msg">
                        <div class="alert alert-danger" role="alert">
                            <p class="mb-0">Please upload MIS first to enable SoE/Fund Receipt upload. </p>
                        </div>
                        </div>
                    <?php else: ?>
                    <?php if($download_button): ?>
                        <div class="col-md-3 upload-btn">
                            <button id="btn-download" class="btn btn-outline btn-primary"><i class="fa fa-download"></i> Download Template</button>
                        </div>
                        <div class="col-md-4 upload-btn">
                            <form class="dm-uploader" id="uploader">
                                <div role="button" class="btn btn-outline btn-warning">
                                    <i class="fa fa-folder-o fa-fw"></i> Upload Excel
                                    <input type="file" title="Click to add Files">
                                </div>
                                <small class="ml-3 status text-muted">Select a file...</small>
                            </form>
                        </div>
                    <?php endif; ?>
                        <div class="col-md-2 upload-btn">
                            <button id="btn-add" class="btn btn-outline btn-primary"><i class="fa fa-table"></i> Add New</button>
                        </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <div class="block">

        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="datatable">
                <thead>
                <tr>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Date Added</th>
                    <th>Txn Type</th>
                    <th>Agency Type</th>
                    <th>Block</th>
                    <th>Phy</th>
                    <th>Fin</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="loading-overlay">
        <div class="progress" style="width: 80%">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" style="width:0%">
                <span id="progress-percent">0</span>
            </div>
        </div>
    </div>
</section>
<!-- content -->
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

    var loading;

    $(function () {
        $('#datatable').dataTable({
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "filter":false,
            "columnDefs": [
                { targets: [5,6,7,8], orderable: false },
                { targets: [], visible: false },
            ],
            "ajax":{
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url :"<?=$datatable_url?>", // json datasource
                type: "get",  // method  , by default get
                dataType:'json',
                beforeSend:function () {
//                    $('#main-container').loading();
                    $("#main-container").LoadingOverlay("show");
                },
                error: function(){  // error handling
                    $(".datatable-error").html("");
                    $("#datatable").append('<tbody class="datatable-error"><tr><th colspan="3">No data found.</th></tr></tbody>');
                    $("#datatable_processing").css("display","none");

                },
                complete:function () {
                    $("#main-container").LoadingOverlay("hide");
                }
            },
        });

        $(document).on('click','.btn-delete',function (e) {
            if(confirm('Are you sure, you want to delete this record?')===false){
                e.preventDefault();
            }
        });
    });

    //headers: {'X-Requested-With': 'XMLHttpRequest'}
    var download_url = '<?=$download_url?>';
    var add_url = '<?=$add_url?>';

    $(document).on('click','#btn-download',function (e) {
        e.preventDefault();
        setLocation(download_url);
    });
    $(document).on('click','#btn-add',function (e) {
        e.preventDefault();
        setLocation(add_url);
    });
    function setLocation(url) {
        var _year = $('#year').val();
        var month = $('#month').val();
        var txn_type = $('#txn_type').val();
        var block_id = $('#block_id').val() || '';
        var district_id = $('#district_id').val() || '';
        var agency_type_id = $('#agency_type_id').val() || '';
        location.href = url+'?month='+month+'&year='+_year+'&txn_type='
            +txn_type+'&block_id='+block_id+'&district_id='+district_id
            +'&agency_type_id='+agency_type_id;
    }

    $('#uploader').dmUploader({
        dnd:false,
        url: '<?=$upload_url?>',
        dataType:'json',
        maxFileSize: 1000000, // 1MB
        multiple: false,
        allowedTypes: 'application/*',
        extFilter: ['xls'],
        onInit: function(){
            // Plugin is ready to use
//            console.log('initialized')
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
                loading = $('#upload-controls').loading({
                    overlay: $('#loading-overlay')
                });
            } else {
                $('#upload-controls').loading();
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
                show_error('File uploaded successfully');
                $('.dm-uploader .status').addClass('text-success')
            } else {
                show_error(data.message)
            }
            $('#datatable').DataTable().ajax.reload();
        },
        onUploadError: function(id, xhr, status, message){
            console.log(message);
            show_error(message);
            $('#upload-controls').loading('stop');
        },
        onFileSizeError: function(file){
            // file.name
            show_error('Invalid file size')
        },
        onFileExtError: function(file){
            // file.name
            show_error('Invalid file type')
        },
        onFileTypeError: function(file){
            // file.name
            show_error('Invalid file type')
        }
    });

    function show_error(msg){
        $('.dm-uploader .status').addClass('text-danger').text(msg)
    }
    function setProgress(percent) {
        $('#progress-bar').width(percent+'%')
        $('#progress-percent').text(percent+'%')
    }

    //check mis
    $(function () {
        $('#month').on('change',function () {
            year = $('#year').val();
            month = $(this).val();
            $.ajax({
                url:'<?=$check_mis_url?>',
                data:{year:year,month:month},
                success:function (json) {
                    $('#btn-row').html(json.html);
                },
                error:function () {
                    alert('Unable to check MIS upload');
                }
            });
        });
    });

</script>
<?php js_end(); ?>