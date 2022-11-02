
<div class="content">

    <div class="row invisible" data-toggle="appear">
        <div class="col-md-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default border-b">
                    <h3 class="block-title">
                        Allotment Details
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-striped" id="block-components">
                        <thead>
                        <tr>
                            <th>Year</th>
                            <th>Allotment Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($allotments): ?>
                            <?php foreach ($allotments as $allotment): ?>
                                <tr>
                                    <td><?=$allotment['year']?></td>
                                    <td><?=$allotment['allotment_date']?></td>
                                    <td><?=$allotment['amount']?></td>
                                    <td><?=$allotment['action']?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="2">Total</td>
                                <td ><?=number_format($total_allotment,2)?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No Data Found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #2 -->
        <div class="col-md-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default border-b">
                    <h3 class="block-title">
                        UC Details
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-striped" id="block-components">
                        <thead>
                        <tr>
                            <th>UC Date</th>
                            <th>Letter No</th>
                            <th>Page No</th>
                            <th>UC Amount</th>
                            <th>Document</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($ucs): ?>
                        <?php foreach ($ucs as $uc): ?>
                            <tr>
                                <td><?=$uc['uc_date']?></td>
                                <td><?=$uc['letter_no']?></td>
                                <td><?=$uc['page_no']?></td>
                                <td><?=$uc['uc_amount']?></td>
                                <td><?php if($uc['uc_document']) { ?><a href="<?=$uc['uc_document']?>" class="btn btn-outline-primary"><i class="fa fa-download"></i></a><?php } ?></td>
                                <td><a href="<?=$uc['action']?>" class="btn btn-primary btn-edit"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                            <tr>
                                <td colspan="3">Total</td>
                                <td><?=number_format($total_uc_submitted,2)?></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No Data Found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add new Modal -->
<div class="modal fade" id="modal-add-new" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="modal-title"></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content" id="modal-content">
                    Hello
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-add" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="modal-title-edit"></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content" id="modal-content-edit">
                    Hello
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-edit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>


<?php js_start(); ?>
<script>
    var url;
    $(function () {
        //add new
        $('.add-new').click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            $.ajax({
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url : url, // json datasource
                type: "get",  // method  , by default get
                dataType:'json',
                beforeSend:function () {
                    $("#main-container").LoadingOverlay('show');
                },
                success:function (json) {
                    $('#modal-title').html(json.title);
                    $('#modal-content').html(json.html);
                    $("#modal-add-new").modal({
                        backdrop: 'static',
                    });
                    bindUploader()
                },
                error: function(){  // error handling
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
                    $("#main-container").LoadingOverlay("hide");
                }
            });
        });

        $(document).on('click','#btn-add',function () {
            formdata = $(this).closest('.modal-content').find('form').serialize();
            year = $('#year').val()||'';
            $.ajax({
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url:url,
                data:formdata,
                type:'POST',
                dataType:'JSON',
                before:function () {
                    $("#main-container").LoadingOverlay('show');
                },
                success:function (json) {
                    location.reload();
                },
                error:function () {
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
                    $("#main-container").LoadingOverlay("hide");
                }
            })
        });

        $(document).on('focus',".js-datepicker", function() {
            $(this).datepicker({
                autoclose:true,
                orientation: 'bottom',
                todayHighlight:true
            });
        });

        $(document).on('click','#btn-edit',function ()
        {
            formdata = $(this).closest('.modal-content').find('form').serialize();

            $.ajax({
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url:url,
                data:formdata,
                type:'POST',
                dataType:'JSON',
                before:function () {
//                $('#main-container').loading();
                    $("#main-container").LoadingOverlay('show');
                },
                success:function (json) {
                    location.reload();
                },
                error:function () {
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
                    $("#main-container").LoadingOverlay("hide");
                }
            })
        });

        $(document).on('click','.btn-edit',function (e){
            e.preventDefault();
            url = $(this).attr('href')
            $.ajax({
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url:url,
                data:{},
                type:'GET',
                dataType:'JSON',
                before:function () {
                    $("#main-container").LoadingOverlay('show');
                },
                success:function (json) {

                    $('#modal-title-edit').html(json.title)
                    $('#modal-content-edit').html(json.html)
                    $("#modal-edit").modal({
                        backdrop: 'static',
                    });
                    bindUploader();
                },
                error:function () {
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
                    $("#main-container").LoadingOverlay("hide");
                }
            });
        });
    });

    function bindUploader() {
        $('.dm-uploader').dmUploader({
            dnd:false,
            url: '<?=$upload_url?>',
            dataType:'json',
            maxFileSize: 1000000, // 1MB
            multiple: false,
            fieldName: 'document',
            allowedTypes: 'application/pdf',
            extFilter: ['pdf'],
            onInit: function(){
                // Plugin is ready to use
//            console.log('initialized')
            },
            onComplete: function(){
                $('.modal-dialog').LoadingOverlay("hide");
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
                $('.modal-dialog').LoadingOverlay("show");
            },
            onUploadSuccess: function(id, data){
                // A file was successfully uploaded server response
                if(data.status) {
                    $(this).find('.status').html(data.message);
                    $(this).find('.filepath').val(data.filepath)
                    $(this).find('.document-name').val(data.filename)
                } else {
                    show_error(this,data.message);
                }

            },
            onUploadError: function(id, xhr, status, message){
                show_error(this,message)
//            $('#closing-balance-breakup').loading('stop');
                $('.modal-dialog').LoadingOverlay("hide");
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
    }

    function show_error(obj,msg){
        $(obj).find('.status').addClass('text-danger').text(msg)
    }
</script>
<?php js_end(); ?>