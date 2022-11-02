
<div class="row">
    <div class="col-xl-12">
        <form>
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Filter</h3>
            </div>

            <div class="block-content">
                <div class="form-group row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="code">District</label>
                            <?php echo form_dropdown('district', option_array_value($districts, 'id', 'name',[''=>'Select District']), set_value('district', $district),"id='district' class='form-control js-select2'"); ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <br>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">List</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Block</th>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comp_blocks as $block): ?>
                        <tr>
                            <td><?=$block['name']?></td>
                            <td><?=$block['district']?></td>
                            <td><a href="<?=$block['assign_url']?>" class="btn btn-primary" title="Assign Components"><i class="fa fa-list"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php js_start(); ?>
    <script type="text/javascript"><!--
        $(document).ready(function() {

            $('select[name=\'district\']').bind('change', function() {
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

                        $('select[name=\'block\']').html(html);
                        $('select[name=\'block\']').select2();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });

        });

        $(function () {
            $('#select-all').on('change',function () {
                $('[name="components[]"]').prop('checked',$(this).is(':checked'))
            })
        })

        //--></script>
<?php js_end(); ?>

