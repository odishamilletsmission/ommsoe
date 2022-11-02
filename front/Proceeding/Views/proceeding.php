<section class="sec-welcome py-5">
    <div class="container">
        <h3 class="mb-3">Government Proceeding</h3>
        <table id="datatable_list" class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
            <tr class="table-primary">
                <th>Date</th>
                <th>Letter No </th>
                <th>Subject</th>
                <th>Download</th>
            </tr>
            </thead>
        </table>

    </div>
</section>


<?php js_start(); ?>
<script type="text/javascript"><!--
    $(function(){
        $('#datatable_list').DataTable({
            "processing": true,
            "serverSide": true,
            "columnDefs": [
                { targets: 'no-sort', orderable: false }
            ],
            "ajax":{
                url :"<?=$datatable_url?>", // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".datatable_list_error").html("");
                    $("#datatable_list").append('<tbody class="datatable_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
                    $("#datatable_list_processing").css("display","none");

                },
                dataType:'json'
            },
        });
    });
    //--></script>
<?php js_end(); ?>