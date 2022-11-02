
<div class="row">
    <div class="col-xl-12">
        <form>
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Filter</h3>
                </div>

                <div class="block-content">
                    <div class="form-group row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="code">Year</label>
                                <?php echo form_dropdown('year', option_array_value($years, 'id', 'name'), set_value('year_id', $year_id),"id='year_id' class='form-control js-select2'"); ?>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="code">Fund Agency</label>
                                <?php echo form_dropdown('fund_agency_id', option_array_value($fund_agencies, 'fund_agency_id', 'fund_agency'), set_value('fund_agency_id', $fund_agency_id),"id='fund_agency_id' class='form-control js-select2'"); ?>
                            </div>
                        </div>
                        <div class="col-3">
                            <br>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button id="add-new" class="ml-3 btn btn-info"> <i class="fa fa-plus-circle"></i> Add New Allotment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">UC Summary</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped" id="block-components">
                    <thead>
                    <tr>
                        <th>District/Agency</th>
                        <th>Date</th>
                        <th>Letter No</th>
                        <th>Allotment From SPMU (in lakh)</th>
                        <th>UC Submitted (in lakh)</th>
                        <th>Balance UC to be submitted FY <?=$year?>(in lakhs)</th>
                        <th>Total balance upto FY <?=$year?>(in lakhs)</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recipients as $recipient): ?>
                        <tr>
                            <td><?=$recipient['recipient']?></td>
                            <td><?=$recipient['date_submit']?></td>
                            <td><?=$recipient['letter_no']?></td>
                            <td><?=$recipient['total_allotment']?></td>
                            <td><?=$recipient['uc_amount']?></td>
                            <td><?=$recipient['balance']?></td>
                            <td><?=$recipient['total_balance']?></td>
                            <td><?=$recipient['action']?></td>
                        </tr>
                    <?php endforeach; ?>
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
    });
</script>
<?php js_end(); ?>