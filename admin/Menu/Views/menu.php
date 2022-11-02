<div class="block">
    <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
        <?php foreach((array)$menu_groups as $key => $value) : ?>
            <li id="group-<?php echo $value['id']; ?>" class="nav-item">
                <a class="nav-link <?=($value['id']==$menu_group_id)?'active':''?>" href="<?php echo admin_url("menu/{$value['id']}"); ?>"> <?php echo $value['title']; ?> </a>
            </li>
        <?php endforeach; ?>
        <li class="nav-item ml-auto">
            <a class="nav-link" href="<?php echo admin_url('menu/0'); ?>">
                <i class="fa fa-plus"></i>
            </a>
        </li>
    </ul>
</div>

<div class="row">
	<div class="col-md-4 <?php echo !$menu_group_id?'disableddiv ':'';?>">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add menu items</h3>
            </div>
            <div class="block-content">
                <div id="menu-left" class="mb-4">
                    <div id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="block block-bordered block-rounded mb-2">
                            <div class="block-header bg-default" role="tab" id="accordion_h1">
                                <a class="font-w600 text-white collapsed" data-toggle="collapse" data-parent="#accordion" href="#accordion_q1" aria-expanded="false" aria-controls="accordion_q1">Pages</a>
                            </div>
                            <div id="accordion_q1" class="collapse" role="tabpanel" aria-labelledby="accordion_h1" data-parent="#accordion" style="">
                                <div class="block-content">
                                    <?php foreach($pages as $page){?>
                                        <div class="checkbox">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <?php echo form_checkbox(array('name' => 'pages[]', 'value' => $page->id,'checked' => false,'data-name'=>$page->title,'data-slug'=>$page->slug,'class'=>'css-control-input')); ?>
                                                <span class="css-control-indicator"></span> <?php echo $page->title;?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    <p>
                                        <button type="button" class="btn btn-success waves-effect waves-light selectall">Select All</button>
                                        <button type="button" class="btn btn-light waves-effect float-right addtomenu" data-menu="pages" >Add to Menu</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="block block-bordered block-rounded mb-2">
                            <div class="block-header bg-elegance" role="tab" id="accordion_h2">
                                <a class="font-w600 text-white" data-toggle="collapse" data-parent="#accordion" href="#accordion_q2" aria-expanded="true" aria-controls="accordion_q2">Custom Link</a>
                            </div>
                            <div id="accordion_q2" class="collapse show" role="tabpanel" aria-labelledby="accordion_h2" data-parent="#accordion" style="">
                                <div class="block-content">
                                    <div class="form-group">
                                        <label for="slug" >Url</label>
                                        <?php echo form_input(array('class'=>'form-control','name' => 'url', 'id' => 'url', 'placeholder'=>'Url','value' => set_value('url', ''))); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="slug" >Link Text</label>
                                        <?php echo form_input(array('class'=>'form-control','name' => 'link text', 'id' => 'title', 'placeholder'=>'Link Text','value' => set_value('title', ''))); ?>
                                    </div>
                                    <p>
                                        <button type="button" class="btn btn-light waves-effect addtomenu" data-menu="custom" >Add to Menu</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
			    </div>
		    </div>
	    </div>
    </div>
	<div class="col-md-8">
		<?php echo form_open('',array('class' => 'form-horizontal', 'id' => 'form-menu','role'=>'form')); ?>
		<div class="block">
			<div class="block-header block-header-default">
				<div class="block-title">
                    <div class="row">
                        <label class="col-lg-3 col-form-label" for="example-hf-email">Menu Name</label>
                        <div class="col-lg-7">
                            <input type="name" class="form-control" id="menu_name" name="menu_name" value="<?php echo $menu_name;?>" placeholder="Menu name">
                            <input type="hidden" name="menu_group_id" id="menu_group_id" value="<?php echo $menu_group_id;?>">
                            <input type="hidden" name="menu_data" id="menu_data" value="">
                        </div>
                    </div>
                </div>
                <div class="block-options">
                    <button type="submit" class="btn btn-primary" id="btn-save-menu"><?php echo $text_form_group;?></button>
                </div>
			</div>
			<div class="block-content">
                <div class="form-group row">
                    <label class="col-sm-3 control-label" for="input-status">Display location</label>
                    <div class="col-sm-3">
                        <?php echo form_dropdown('theme_location', $theme_locations, set_value('theme_location', $theme_location), 'id=\'theme_location\' class=\'form-control\'')?>
                    </div>
                </div>
                <p>Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>
				<div id="menu_area" class="dd">
					<?php if($menu_group_id) echo $menu; ?>
				</div>
			</div>
			<div class="block-content block-content-full bg-body-light font-size-sm">
				<?php if($menu_group_id){?>
				<a class="btn-sm btn btn-danger btn-remove" href="<?php echo admin_url('menu/delete/'.$menu_group_id);?>" onclick="return confirm('Are you sure?') ? true : false;"><i class="fa fa-trash-o"></i></a>

				<div class="float-right">
				   <button type="submit" class="btn btn-primary" id="btn-save-menu"><?php echo $text_form_group;?></button>
				</div>
                <?php }?>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<?php js_start(); ?>
<script type="text/javascript">
	$(function() {

	function admin_url(url) {
		return ADMIN_URL + '/'+ url;
	}

	var menu_serialized;
	var updateOutput = function(e) {

		var list = e.length ? e : $(e.target),
		output = list.data('output');
		if(window.JSON) {
			menu_serialized=window.JSON.stringify(list.nestable('serialize'));//, null, 2));
		}
		else {
			menu_serialized='';
		}
		$("#menu_data").val(menu_serialized);
		//console.log(menu_serialized);
	};
	$('#menu_area').nestable({
		listNodeName:'ul',
		group: 1,
		collapsedClass:'',

	}).on('change', updateOutput);


	var $form = $('#form-menu').on('submit', function (e) {

        var $input = $form.find('[name=menu_data]');
        var json = JSON.stringify($('#menu_area').nestable('serialize'));
        $input.val(json);
        //e.preventDefault();
        //console.log(self.menu.serialize());
    });


    $('#menu_area').on('mousedown',"a" ,function(event) {
		//alert("ok");
	 event.preventDefault();
	 return false;
	});

	$(".selectall").click(function(){
		var checkboxes = $(this).parent().parent().find(':checkbox');
		checkboxes.prop("checked", !checkboxes.prop("checked"));
	});

	$(".addtomenu").click(function(){
		menu_type=$(this).data('menu');
		var checked = [];
		var $checkbox = $(this).parent().parent().find(':checkbox:checked').each(function(){
			var title =  this.getAttribute('data-name');
			var slug =  this.getAttribute('data-slug');
			checked.push({
				id : $(this).val(),
				title:title,
				url:slug
			});
		});

		if(menu_type == "custom"){
			var title =  $("#title").val();
			var slug =  $("#url").val();
			checked.push({
				id : 0,
				title:title,
				url:slug
			});
		}



        $.ajax({
			type: 'POST',
			url: admin_url('menu/add'),
			data: {checked:checked,menu_type:menu_type,menu_group_id:"<?php echo $menu_group_id;?>"},
			dataType:'json',
			error: function() {

			},
			success: function(json) {
				$('.text-danger').remove();

				switch (json.menu.status) {
					case 1:
						$('#menu_area > ul').append(json.menu.li);
						break;
					case 2:

						break;
					case 3:
						$('#menu-title').val('').focus();
						break;
				}

			}
		});
        $checkbox.prop('checked', false);
	});

	/* delete menu
	------------------------------------------------------------------------- */
	$('#menu_area').on('click',".delete-menu" ,function(event) {
		event.preventDefault();

		var li = $(this).closest('li');
		var param = { menu_id : $(li).data('id') };

		Swal.fire({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.value) {
		    $.post(admin_url('menu/deleteMenuItem'), param, function(data) {
                var response = jQuery.parseJSON(data);
		        if (response.success) {
					li.remove();
					Swal.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )

				} else {
					Swal.fire(
				      'Error!',
					  'Failed to delete this menu.',
					  'warning'
				    )

				}
			});

		  }
		})

		/*swal({
		  title: 'Are you sure?',
		  text: "This will also delete all submenus under this menu!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then(function() {
			$.post(admin_url('menu/deleteMenuItem'), param, function(data) {
				if (data.success) {
					li.remove();
					swal(
					    'Deleted!',
					    'Your menu has been deleted.',
					    'success'
					);

				} else {
					swal(
					    'Error!',
					    'Failed to delete this menu.',
					    'warning'
					);
				}
			});
		})*/
		return false;
	});
});
</script>
<?php js_end(); ?>