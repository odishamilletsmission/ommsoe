<form>
    <div class="block">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-2">
                    <label>Year</label>
                    <select class="form-control" id="year" name="year" required>
                        <?php foreach ($years as $year) { ?>
                            <option value="<?=$year['id']?>" <?php if($year['id']==$year_id){echo 'selected';} ?>><?=$year['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Month</label>
                    <select class="form-control" id="month" name="month">
                        <?php foreach ($months as $month) { ?>
                            <option value="<?=$month['id']?>" <?php if($month['id']==$month_id){echo 'selected';} ?>><?=$month['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Fund Agency</label>
                    <?php echo form_dropdown('fund_agency_id', option_array_value($fund_agencies, 'fund_agency_id', 'fund_agency'), set_value('fund_agency_id', $fund_agency_id),"id='fund_agency_id' class='form-control js-select2'"); ?>
                </div>
                <?php  if($agency_types): ?>
                    <div class="col-md-2">
                        <label>Agency Type</label>
                        <select class="form-control" id="agency_type_id" name="agency_type_id">
                            <option value="">All</option>
                            <?php foreach ($agency_types as $agency_type) : ?>
                                <option value="<?=$agency_type['id']?>" <?php if($agency_type['id']==$agency_type_id){echo 'selected';} ?>><?=$agency_type['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif;  ?>

                <?php if($districts): ?>
                    <div class="col-md-2">
                        <label>District</label>
                        <select class="form-control" id="district_id" name="district_id">
                            <option value="">Select District</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?=$district['id']?>" <?php if($district['id']==$district_id){echo 'selected';} ?>><?=$district['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                    <div class="col-md-2">
                        <label>Block</label>
                        <select class="form-control" id="block_id" name="block_id">
                            <option value="">Select Block</option>
                            <?php foreach ($blocks as $block): ?>
                                <option value="<?=$block['id']?>" <?php if($block['id']==$block_id){echo 'selected';} ?>><?=$block['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-2">
                    <button id="btn-filter" class="btn btn-outline btn-primary"><i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var fund_agency_id;
    $(function () {
       $('#district_id').on('change',function () {
           district_id = $(this).val();
           fund_agency_id = $('#fund_agency_id').val();
           $.ajax({
               url:'<?=$get_block_url?>',
               data:{district_id:district_id,fund_agency_id:fund_agency_id},
               success:function (json) {
                   html = '<option value="">Select Block</option>';
                   $.each(json,function (i,v) {
                       html += '<option value="'+v.id+'">'+v.name+'</option>';
                   });
                   $('#block_id').html(html);
               },
               error:function () {
                   alert('Unable to fetch blocks');
               }
           });
       });
       $('#fund_agency_id').on('change',function () {
           fund_agency_id = $(this).val();
           $.ajax({
               url:'<?=$get_district_url?>',
               data:{fund_agency_id:fund_agency_id},
               success:function (json) {
                   html = '<option value="">Select District</option>';
                   $.each(json,function (i,v) {
                       html += '<option value="'+v.id+'">'+v.name+'</option>';
                   });
                   $('#district_id').html(html);
               },
               error:function () {
                   alert('Unable to fetch districts');
               }
           });
       });
    });
</script>