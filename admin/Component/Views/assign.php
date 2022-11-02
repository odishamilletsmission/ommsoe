<?php echo form_open_multipart('', 'id="form-household"'); ?>
<div class="row">

    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><?=$district_name?> >> <?=$block_name?> Components</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped" id="block-components">
                    <thead>
                    <tr>
                        <th width="20"><input type="checkbox" id="select-all"></th>
                        <th width="20">Number</th>
                        <th width="500">Component</th>
                        <th width="100">Agency</th>
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
    </div>
</div>
<?php echo form_close(); ?>

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

        var components = <?=$components_json?>;
        $(function () {
            $('#select-all').on('change',function () {
                $('#block-components tbody').find('input[type="checkbox"]').prop('checked',$(this).is(':checked'))
            });
            
            $.each(components,function (comp,agency) {
                $('#cb'+comp).prop('checked',true);
                $('[name="components['+comp+'][agency_type]"]').val(agency);
            });
        });

        //--></script>
<?php js_end(); ?>

