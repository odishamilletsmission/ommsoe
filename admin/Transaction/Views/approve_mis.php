<style>
    .w-50p{width:50px;}
</style>
<!-- Main content -->
<section class="content">

    <div class="block block-themed">
        <div class="block-header bg-info">
            <h3 class="block-title">Summary</h3>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                <tr>
                    <th>District</th>
                    <th>Block</th>
                    <th>Agency Type</th>
                    <th>Month/Year</th>
                    <th>Date Added</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?=$district?></td>
                    <td><?=$block?></td>
                    <td><?=$agency_type?></td>
                    <td><?=$month?> / <?=$year?></td>
                    <td><?=$date_added?></td>
                    <?php if(isset($approval) && $approval): ?>
                        <td><button class="btn btn-primary" id="btn-action"><?=$status?></button> </td>
                    <?php else: ?>
                        <td><?=$status?></td>
                    <?php endif; ?>
                    
                </tr>
                <?php if(!empty($remarks)): ?>
                <tr>
                    <td>Remarks:</td>
                    <td colspan="7"><?=$remarks?></td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">MIS Details</h3>
        </div>
        <div class="block-content block-content-full">
            <?php echo form_open(); ?>
            <div class="tableFixHead">
                <table class="table custom-table " id="txn-table">
                    <thead>
                    <tr>
                        <th width="10%">Sl no</th>
                        <th width="30%">Component</th>
                        <th width="15%">Unit Type</th>
                        <th width="15%">Output Indicator</th>
                        <th width="15%">Achievement</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$components?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col mt-4">
                    <button type="submit" class="btn btn-alt-primary float-right">Submit</button>
                </div>
            </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</section>

<?php if(isset($approval)) {
    echo $approve_form;
} ?>

