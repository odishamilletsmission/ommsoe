
<!-- Main content -->
<section class="content">
    <?php if($upload_enabled) { ?>
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
                    <?php if($fund_agencies): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="fund_agency_id" name="fund_agency_id">
                            <?php foreach ($fund_agencies as $agency): ?>
                                <option value="<?=$agency['fund_agency_id']?>"><?=$agency['fund_agency']?></option>
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
                <div class="row mt-3">
                    <div class="col-md-2">
                        <button id="btn-add" class="btn btn-outline btn-primary"><i class="fa fa-table"></i> Add New</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

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
                { targets: [3,4,5,6], orderable: false },
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
        })
    })

    //headers: {'X-Requested-With': 'XMLHttpRequest'}
    var add_url = '<?=$add_url?>';
    $('#btn-add').click(function (e) {
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
        var fund_agency_id = $('#fund_agency_id').val() || '';
        location.href = url+'?month='+month+'&year='+_year+'&txn_type='
            +txn_type+'&block_id='+block_id+'&district_id='+district_id
            +'&agency_type_id='+agency_type_id+'&fund_agency_id='+fund_agency_id;
    }

</script>
<?php js_end(); ?>