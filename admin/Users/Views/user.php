<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title"><?php echo $heading_title; ?></h3>
		<div class="block-options">
			<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
			<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-users').submit() : false;"><i class="fa fa-trash-o"></i></button>
		</div>
	</div>
	<div class="block-content block-content-full">
		<!-- DataTables functionality is initialized with .js-dataTable-full class in js/users/be_tables_datatables.min.js which was auto compiled from _es6/users/be_tables_datatables.js -->
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-users">        		
		<table id="user_list" class="table table-bordered table-striped table-vcenter js-dataTable-full">
			<thead>
				<tr>
					<th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
					<th>Username</th>
                    <th>Role</th>
					<th>District</th>
					<th>Block</th>
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
	$('#user_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".user_list_error").html("");
				$("#user_list").append('<tbody class="user_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#user_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
});
function delete_user(title,id){

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