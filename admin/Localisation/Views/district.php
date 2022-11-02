<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title"><?php echo $heading_title; ?></h3>
		<div class="block-options">
			<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
			<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-district').submit() : false;"><i class="fa fa-trash-o"></i></button>
		</div>
	</div>
	<div class="block-content block-content-full">
		<!-- DataTables functionality is initialized with .js-dataTable-full class in js/district/be_tables_datatables.min.js which was auto compiled from _es6/district/be_tables_datatables.js -->
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-district">        		
		<table id="district_list" class="table table-bordered table-striped table-vcenter js-dataTable-full">
			<thead>
				<tr>
					<th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
					<th>District Code</th>
					<th>District Name</th>
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
	$('#district_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".district_list_error").html("");
				$("#district_list").append('<tbody class="district_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#district_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
});
function delete_district(title,id){

	gbox.show({
		content: '<h2>Delete Manager</h2>Are you sure you want to delete this Manager?<br><b>'+title,
		buttons: {
			'Yes': function() {
				$.post('<?php echo admin_url('members.delete');?>',{user_id:id}, function(data) {
					if (data.success) {
						gbox.hide();
						$('#member_list').DataTable().ajax.reload();
					} else {
						gbox.show({
							content: 'Failed to delete this Manager.'
						});
					}
				});
			},
			'No': gbox.hide
		}
	});
	return false;
}
//--></script>
<?php js_end(); ?>