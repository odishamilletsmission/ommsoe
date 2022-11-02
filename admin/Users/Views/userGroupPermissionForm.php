<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-usergroup"'); ?>
    <div class="row">

        <div class="col-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title"><?php echo $text_form; ?></h3>
                    <div class="block-options">
                        <button type="submit" form="form-usergroup" class="btn btn-primary">Save</button>
                        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
                    </div>
                </div>
                <div class="block-content">
                    <form action="<?=base_url()?>" class="form-horizontal" role="form" method="post" id="roletype">
                        <table id="" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr>
                                <th class="col-lg-1">Sl No</th>
                                <th class="col-lg-3">Module Name</th>
                                <th class="col-lg-1">Add</th>
                                <th class="col-lg-1">Edit</th>
                                <th class="col-lg-1">Delete</th>
                                <th class="col-lg-1">View</th>
                                <th class="col-lg-1">Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $permissionTable    = array();
                            $permissionCheckBox = array();
                            $permissionCheckBoxVal = array();
                            foreach ($gpermissions as $data) {
                                if(strpos($data->name, '_edit') == false && strpos($data->name, '_view') == false && strpos($data->name, '_delete') == false && strpos($data->name, '_add') == false && strpos($data->name, '_download') == false) {
                                    $push['name'] = $data->name;
                                    $push['description'] = $data->description;
                                    $push['status'] = $data->active;

                                    array_push($permissionTable, $push);

                                }
                                $permissionCheckBox[ $data->name ] = $data->active;
                                $permissionCheckBoxVal[ $data->name ] = $data->id;

                            }
                            ?>
                            <?php
                            $i = 1;
                            foreach($permissionTable as $data) { ?>
                                <tr>
                                    <td data-title="#">
                                        <?php
                                        //echo $i;
                                        $status = "";
                                        if(isset($permissionCheckBox[$data['name']])) {
                                            if ($permissionCheckBox[$data['name']]=="yes") {
                                                if ($permissionCheckBoxVal[$data['name']]) {
                                                    echo "<input type='checkbox' name=".$data['name']." value=".$permissionCheckBoxVal[$data['name']]." checked='checked' id=".$data['name']." onClick='$(this).processCheck();'>";
                                                }
                                            } else {
                                                if ($permissionCheckBoxVal[$data['name']]) {
                                                    $status = "disabled";
                                                    echo "<input type='checkbox' name=".$data['name']." value=".$permissionCheckBoxVal[$data['name']]." id=".$data['name']."  onClick='$(this).processCheck();' >";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td data-title="Module Name">
                                        <?php echo $data['description']; ?>
                                    </td>
                                    <td data-title="Add">
                                        <?php
                                        if(isset($permissionCheckBox[$data['name'].'_add'])) {
                                            if ($permissionCheckBox[$data['name'].'_add']=="yes") {
                                                if ($permissionCheckBoxVal[$data['name'].'_add']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_add'."' value=".$permissionCheckBoxVal[$data['name'].'_add']." checked='checked' id='".$data['name'].'_add'."' ".$status.">";
                                                }
                                            } else {
                                                if ($permissionCheckBoxVal[$data['name'].'_add']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_add'."' value=".$permissionCheckBoxVal[$data['name'].'_add']." id='".$data['name'].'_add'."' ".$status.">";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td data-title="Edit">
                                        <?php
                                        if(isset($permissionCheckBox[$data['name'].'_edit'])) {
                                            if ($permissionCheckBox[$data['name'].'_edit']=="yes") {
                                                if ($permissionCheckBoxVal[$data['name'].'_edit']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_edit'."' value=".$permissionCheckBoxVal[$data['name'].'_edit']." checked='checked' id='".$data['name'].'_edit'."' ".$status.">";
                                                }
                                            } else {
                                                if ($permissionCheckBoxVal[$data['name'].'_edit']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_edit'."' value=".$permissionCheckBoxVal[$data['name'].'_edit']." id='".$data['name'].'_edit'."' ".$status.">";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td data-title="Delete">
                                        <?php
                                        if(isset($permissionCheckBox[$data['name'].'_delete'])) {
                                            // echo "delete";
                                            if ($permissionCheckBox[$data['name'].'_delete']=="yes") {
                                                if ($permissionCheckBoxVal[$data['name'].'_delete']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_delete'."' value=".$permissionCheckBoxVal[$data['name'].'_delete']." checked='checked' id='".$data['name'].'_delete'."' ".$status.">";
                                                }
                                            } else {
                                                if ($permissionCheckBoxVal[$data['name'].'_delete']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_delete'."' value=".$permissionCheckBoxVal[$data['name'].'_delete']." id='".$data['name'].'_delete'."' ".$status.">";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td data-title="View">
                                        <?php
                                        if(isset($permissionCheckBox[$data['name'].'_view'])) {
                                            if ($permissionCheckBox[$data['name'].'_view']=="yes") {
                                                if ($permissionCheckBoxVal[$data['name'].'_view']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_view'."' value=".$permissionCheckBoxVal[$data['name'].'_view']." checked='checked' id='".$data['name'].'_view'."' ".$status.">";
                                                }
                                            } else {
                                                if ($permissionCheckBoxVal[$data['name'].'_view']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_view'."' value=".$permissionCheckBoxVal[$data['name'].'_view']." id='".$data['name'].'_view'."' ".$status.">";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td data-title="Download">
                                        <?php
                                        if(isset($permissionCheckBox[$data['name'].'_download'])) {
                                            if ($permissionCheckBox[$data['name'].'_download']=="yes") {
                                                if ($permissionCheckBoxVal[$data['name'].'_download']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_download'."' value=".$permissionCheckBoxVal[$data['name'].'_download']." checked='checked' id='".$data['name'].'_download'."' ".$status.">";
                                                }
                                            } else {
                                                if ($permissionCheckBoxVal[$data['name'].'_download']) {
                                                    echo "<input type='checkbox' name='".$data['name'].'_download'."' value=".$permissionCheckBoxVal[$data['name'].'_download']." id='".$data['name'].'_download'."' ".$status.">";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>
<?php js_start(); ?>
    <script type="text/javascript"><!--
        $.fn.processCheck = function() {
            var id = $(this).attr('id');
            if ($('input#'+id).is(':checked')) {
                if ($('input#'+id+"_add").length) {
                    $('input#'+id+"_add").prop('disabled', false);
                    $('input#'+id+"_add").prop('checked', true);
                }
                if ($('input#'+id+"_edit").length) {
                    $('input#'+id+"_edit").prop('disabled', false);
                    $('input#'+id+"_edit").prop('checked', true);
                }
                if ($('input#'+id+"_delete").length) {
                    $('input#'+id+"_delete").prop('disabled', false);
                    $('input#'+id+"_delete").prop('checked', true);
                }
                if ($('input#'+id+"_view").length) {
                    $('input#'+id+"_view").prop('disabled', false);
                    $('input#'+id+"_view").prop('checked', true);
                }
                if ($('input#'+id+"_download").length) {
                    $('input#'+id+"_download").prop('disabled', false);
                    $('input#'+id+"_download").prop('checked', true);
                }
            } else {
                if ($('input#'+id+"_add").length) {
                    $('input#'+id+"_add").prop('disabled', true);
                    $('input#'+id+"_add").prop('checked', false);
                }
                if ($('input#'+id+"_edit").length) {
                    $('input#'+id+"_edit").prop('disabled', true);
                    $('input#'+id+"_edit").prop('checked', false);
                }
                if ($('input#'+id+"_delete").length) {
                    $('input#'+id+"_delete").prop('disabled', true);
                    $('input#'+id+"_delete").prop('checked', false);
                }
                if ($('input#'+id+"_view").length) {
                    $('input#'+id+"_view").prop('disabled', true);
                    $('input#'+id+"_view").prop('checked', false);
                }
                if ($('input#'+id+"_download").length) {
                    $('input#'+id+"_download").prop('disabled', true);
                    $('input#'+id+"_download").prop('checked', false);
                }
            }
        };
        //--></script>
<?php js_end(); ?>