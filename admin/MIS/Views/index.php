
<!-- Main content -->
<section class="content">
    <h2 class="content-heading">MIS</h2>
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
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

</section>
<!-- content -->
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
                { targets: [], orderable: false },
                { targets: [], visible: false },
            ],
            "ajax":{
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                url :"<?=$datatable_url?>", // json datasource
                type: "post",  // method  , by default get
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

    var add_url = '<?=$add_url?>';

    $('#btn-add').click(function (e) {
        e.preventDefault();
        setLocation(add_url);
    });
    function setLocation(url) {
        var _year = $('#year').val();
        var month = $('#month').val();
        var block_id = $('#block_id').val() || '';
        var district_id = $('#district_id').val() || '';
        var agency_type_id = $('#agency_type_id').val() || '';
        var fund_agency_id = $('#fund_agency_id').val() || '';
        location.href = url+'?month='+month+'&year='+_year+'&block_id='+block_id+'&district_id='+district_id
            +'&agency_type_id='+agency_type_id+'&fund_agency_id='+fund_agency_id;
    }


</script>
<?php js_end(); ?>