<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title"><?php echo $heading_title; ?></h3>
        <div class="block-options">
            <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-usergroup').submit() : false;"><i class="fa fa-trash-o"></i></button>
        </div>
    </div>
    <div class="block-content block-content-full">
        <!-- DataTables functionality is initialized with .js-dataTable-full class in js/usergroup/be_tables_datatables.min.js which was auto compiled from _es6/usergroup/be_tables_datatables.js -->
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-usergroup">
            <table id="usergroup_list" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                <tr>
                    <th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                    <th>Name</th>
                    <th>Status</th>
                    <th class="text-right no-sort">Actions</th>
                </tr>
                </thead>
            </table>
        </form>
    </div>
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
    $(function(){
        $('#usergroup_list').DataTable({
            "processing": true,
            "serverSide": true,
            "columnDefs": [
                { targets: 'no-sort', orderable: false }
            ],
            "ajax":{
                url :"<?=$datatable_url?>", // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".usergroup_list_error").html("");
                    $("#usergroup_list").append('<tbody class="usergroup_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
                    $("#usergroup_list_processing").css("display","none");

                },
                dataType:'json'
            },
        });
    });
    //--></script>
<?php js_end(); ?>