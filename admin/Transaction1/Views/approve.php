
<!-- Main content -->
<section class="content">

    <div class="block" id="upload-controls">
        <div class="block-content block-content-full">
            <form>
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control" id="year" name="year" required>
                        <option value="">Choose Year</option>
                        <?php foreach ($years as $year) { ?>
                            <option value="<?=$year['id']?>" <?php if($year['id']==$year_id){echo 'selected';} ?>><?=$year['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" id="month" name="month" required>
                        <option value="">Choose Month</option>
                        <?php foreach ($months as $month) { ?>
                            <option value="<?=$month['id']?>" <?php if($month['id']==$month_id){echo 'selected';} ?>><?=$month['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php if($agency_types): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="agency_type_id" name="agency_type_id">
                            <option value="">Choose Agency Type</option>
                            <?php foreach ($agency_types as $agency_type) : ?>
                                <option value="<?=$agency_type['id']?>" <?php if($agency_type['id']==$agency_type_id){echo 'selected';} ?>><?=$agency_type['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-md-2">
                    <select class="form-control" id="txn_type" name="txn_type">
                        <option value="">Choose Txn Type</option>
                        <option value="expense" <?php if($txn_type=='expense'){echo 'selected';} ?>>Expense</option>
                        <option value="fund_receipt" <?php if($txn_type=='fund_receipt'){echo 'selected';} ?>>Fund Receipt</option>
                        <option value="other_receipt" <?php if($txn_type=='other_receipt'){echo 'selected';} ?>>Other Receipt</option>
                        <option value="closing_balance" <?php if($txn_type=='closing_balance'){echo 'selected';} ?>>Closing Balance</option>
                    </select>
                </div>
                <?php if($districts): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="district_id" name="district_id">
                            <option value="">Choose District (if district level)</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?=$district['id']?>" <?php if($district['id']==$district_id){echo 'selected';} ?>><?=$district['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if($fund_agencies): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="fund_agency_id" name="fund_agency_id">
                            <?php foreach ($fund_agencies as $agency): ?>
                                <option value="<?=$agency['fund_agency_id']?>" <?php if($agency['fund_agency_id']==$fund_agency_id){echo 'selected';} ?>><?=$agency['fund_agency']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if($blocks): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="block_id" name="block_id">
                            <option value="">Choose Block (if block level)</option>
                            <?php foreach ($blocks as $block): ?>
                                <option value="<?=$block['id']?>" <?php if($block['id']==$block_id){echo 'selected';} ?>><?=$block['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row mt-3">
                <div class="col-md-2">
                    <button id="btn-add" class="btn btn-outline btn-primary"><i class="fa fa-table"></i> Filter</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!--  Table block  -->
    <div class="block">
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="datatable">
                <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date Added</th>
                    <th>Txn Type</th>
                    <th>Block</th>
                    <th>Agency Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($upload_statuses as $status): ?>
                <tr>
                    <td><?=$status->year?></td>
                    <td><?=$status->month?></td>
                    <td><?=$status->created_at?></td>
                    <td><?=$status->module?></td>
                    <td><?=$status->block?></td>
                    <td><?=$status->agency_type?></td>
                    <td><?=$status->status?></td>
                    <td><?php if($status->action) { ?> <a href="<?=$status->action?>" class="btn btn-primary"><i class="fa fa-send"></i></a> <?php } ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>
<!-- content -->

<?php js_start(); ?>
<script>

    var loading;

    $(function () {

        $('.js-dataTable-full').dataTable({
            columnDefs: [ { orderable: false, targets: [ 4 ] } ],
            autoWidth: false
        });
    });

</script>
<?php js_end(); ?>