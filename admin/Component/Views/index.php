<style>
    .dd-handle {
        height: auto !important;
        font-weight: normal;
    }
    .dd{max-width: none;}
</style>
<?php echo form_open_multipart('', 'id="form-household"'); ?>
<div class="row">

    <div class="col-xl-4">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add components</h3>
            </div>
            <div class="block-content">
                <div class="form-group">
                    <label for="comp_num" >Number</label>
                    <?php echo form_input(array('class'=>'form-control','name' => 'number', 'id' => 'number', 'placeholder'=>'Component Number','value' => set_value('number', ''))); ?>
                </div>
                <div class="form-group">
                    <label for="desc" >Description</label>
                    <?php echo form_textarea(array('class'=>'form-control','name' => 'description', 'id' => 'description', 'placeholder'=>'Component Description','value' => set_value('description', ''))); ?>
                </div>
                <div class="form-group">
                    <label for="row_type" >Row Type</label>
                    <select class="form-control" name="row_type">
                        <option value="heading">Header</option>
                        <option value="component">Component</option>
                    </select>
                </div>
                <p>
                    <button type="button" class="btn btn-light waves-effect addtomenu" data-menu="custom" >Add</button>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Components</h3>
            </div>

            <div class="block-content">
                <?php echo form_open('',array('class' => 'form-horizontal', 'id' => 'form-menu','role'=>'form')); ?>
                <input type="hidden" name="menu_data" id="menu_data" value="">
                <p>Drag each item into the order you prefer.</p>
                <div id="menu_area" class="dd">
                    <?=$components?>
                </div>

                <div class="text-right my-3">
                    <button type="submit" class="btn btn-primary" id="btn-save-menu">Save</button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<?php js_start(); ?>
    <script type="text/javascript"><!--
        $(document).ready(function() {

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
            });

            // add new component
            $(".addtomenu").click(function(){
                $.ajax({
                    type: 'POST',
                    url: '<?=$add_url?>',
                    data: {
                        number:$('#number').val(),
                        description:$('#description').val(),
                        row_type:$('[name="row_type"]').val()
                    },
                    dataType:'json',
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: "Uh aww. Request failed",
                            icon: 'error',
                        })
                    },
                    success: function(json) {
                        if(json.error){
                            Swal.fire({
                                title: 'Error',
                                text: json.error,
                                icon: 'error',
                            })
                        } else {
                            $('#menu_area > ul').append(json.li);
                        }
                    },
                    complete:function () {
                        $('#number').val('');
                        $('#description').val('');
                    }
                });
            });

            //delete
            $('#menu_area').on('mousedown',"a" ,function(event) {
                //alert("ok");
                event.preventDefault();
                return false;
            });

            $(document).on('click','.btn-remove',function (e) {
                e.preventDefault();
                li = $(this).closest('li');
                cid = $(li).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete this component",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                    $.post('<?=$delete_url?>', {id:cid}, function(data) {
                        if (data.status) {
                            li.remove();
                            Swal.fire(
                                'Deleted!',
                                'Delete successul',
                                'success'
                            )

                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete this component.',
                                'warning'
                            )
                        }
                    });
                    }
                });

            });

        });

        //--></script>
<?php js_end(); ?>

