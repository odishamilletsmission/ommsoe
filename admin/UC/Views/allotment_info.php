<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Allotments</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped" id="block-components">
                    <thead>
                    <tr>
                        <th>District/Agency</th>
                        <th>Year</th>
                        <th>Allotment Date</th>
                        <th>Allotment From SPMU (in lakh)</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($allotments): ?>
                    <?php foreach ($allotments as $allotment): ?>
                        <tr>
                            <td><?=$allotment['recipient']?></td>
                            <td><?=$allotment['year']?></td>
                            <td><?=$allotment['allotment_date']?></td>
                            <td><?=$allotment['amount']?></td>
                            <td><?=$allotment['action']?></td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><?=number_format($total_allotment,2)?></td>
                            <td></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No Data</td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">UC Submissions</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped" id="block-components">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Letter No</th>
                        <th>Page No</th>
                        <th>UC Submitted (in lakh)</th>
                        <th>Document</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($submissions): ?>
                        <?php foreach ($submissions as $allotment): ?>
                            <tr>
                                <td><?=$allotment['date_submit']?></td>
                                <td><?=$allotment['letter_no']?></td>
                                <td><?=$allotment['page_no']?></td>
                                <td><?=$allotment['amount']?></td>
                                <td><?php if($allotment['document']) { ?><a href="<?=$allotment['document']?>" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf-o"></i></a><?php } ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><?=number_format($total_uc,2)?></td>
                            <td></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No Data</td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
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
        $('#add-new').click(function (e) {
            e.preventDefault();
            fai = $('#fund_agency_id').val() || '';
            $.ajax({
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                data: {year:$('#year_id').val(),fund_agency_id:fai},
                url :"<?=$add_url?>", // json datasource
                type: "get",  // method  , by default get
                dataType:'json',
                beforeSend:function () {
    //                    $('#main-container').loading();
                    $("#main-container").LoadingOverlay('show');
                    $('#res-message').text('');
                },
                success:function (json) {
                    if(json.status==false){
                        $('#res-message').text(json.message);
                    } else {
                        $('#modal-title').html(json.title);
                        $('#modal-content').html(json.html);
                        $("#modal-add-new").modal({
                            backdrop: 'static',
                        });
                    }
                },
                error: function(){  // error handling
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
    //                    $('#main-container').loading('stop');
                    $("#main-container").LoadingOverlay("hide");
                }
            });
        });

        $(document).on('click','#btn-add',function () {
            formdata = $(this).closest('.modal-content').find('form').serialize();
            year = $('#year').val()||'';
            $.ajax({
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url:'<?=$add_url?>',
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
//                $('#main-container').loading('stop');
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
                    $("#main-container").LoadingOverlay("hide");
//                $('#main-container').loading('stop');
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

        $(document).on('click','#btn-edit',function () {
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
//                $('#main-container').loading('stop');
                },
                complete:function () {
//                $('#main-container').loading('stop');
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
//                $('#main-container').loading();
                    $("#main-container").LoadingOverlay('show');
                },
                success:function (json) {
                    if(json.status==false){
                        $('#res-message').text(json.message);
                    } else {
                        $('#modal-title-edit').html(json.title)
                        $('#modal-content-edit').html(json.html)
                        $("#modal-edit").modal({
                            backdrop: 'static',
                        });
                    }
                },
                error:function () {
//                $('#main-container').loading('stop');
                    $("#main-container").LoadingOverlay("hide");
                },
                complete:function () {
//                $('#main-container').loading('stop');
                    $("#main-container").LoadingOverlay("hide");
                }
            });
        });
    });
</script>
<?php js_end(); ?>