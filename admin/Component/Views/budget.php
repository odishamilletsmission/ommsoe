
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
                            <label for="code">Agency Type</label>
                            <?php echo form_dropdown('agency_type_id', option_array_value($agency_types, 'id', 'name',[''=>'Select Agency']), set_value('agency_type_id', $agency_type_id),"id='agency_type_id' class='form-control js-select2'"); ?>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="code">District</label>
                            <?php echo form_dropdown('district_id', option_array_value($districts, 'id', 'name',[''=>'Select District']), set_value('district_id', $district_id),"id='district_id' class='form-control js-select2'"); ?>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="code">Block</label>
                            <?php echo form_dropdown('block_id', option_array_value($blocks, 'id', 'name',[''=>'Select Block']), set_value('block_id', $block_id),"id='block_id' class='form-control js-select2'"); ?>
                        </div>
                    </div>
                    <div class="col-3">
                        <br>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <?php if($components): ?>
    <div class="col-xl-12">
        <?php echo form_open(); ?>
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">List</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped" id="block-components">
                    <thead>
                    <tr>
                        <th width="20">Number</th>
                        <th width="500">Component</th>
                        <th width="100">Physical</th>
                        <th width="100">Financial</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$components?>
                    </tbody>
                </table>
                <div class="text-right my-3">
                    <button type="submit" class="btn btn-primary" id="btn-save-menu">Save</button>
                </div>
            </div>
        </div>
        <?php form_close(); ?>
    </div>
    <?php endif; ?>
</div>

<?php js_start(); ?>
    <script type="text/javascript"><!--
        $(document).ready(function() {

            $('select[name=\'district_id\']').bind('change', function() {
                $.ajax({
                    url: '<?php echo admin_url("district/block"); ?>/' + this.value,
                    dataType: 'json',
                    beforeSend: function() {},
                    complete: function() {},
                    success: function(json) {
                        html = '<option value="0">Select Block</option>';

                        if (json) {
                            $.each(json,function (i,v) {
                                html += '<option value="' + v.id + '">' + v.name + '</option>';
                            })
                        } else {
                            html += '<option value="">Select Block</option>';
                        }

                        $('select[name=\'block_id\']').html(html);
                        $('select[name=\'block_id\']').select2();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });

        });

        //--></script>
<?php js_end(); ?>

