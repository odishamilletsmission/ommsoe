<!-- Main content -->
    <section class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Summary</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <thead>
                    <tr>
                        <th>District</th>
                        <th>Block</th>
                        <th>Agency Type</th>
                        <th>Month/Year</th>
                        <th>Funding</th>
                        <th>Date Added</th>
                        <th>Txn Type</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$district?></td>
                        <td><?=$block?></td>
                        <td><?=$agency_type?></td>
                        <td><?=$month?> / <?=$year?></td>
                        <td><?=$fund_agency?></td>
                        <td><?=$date_added?></td>
                        <td><?=$txn_type_text?></td>
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
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Details</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <thead>
                    <tr>
                        <?php foreach ($heads as $head) : ?>
                        <th><?=$head['name']?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php foreach ($heads as $head) : ?>
                        <td><?=$head['value']?></td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!-- content -->

<?php js_start(); ?>
<script></script>
<?php if(isset($approval)) {
    echo $approve_form;
} ?>

<?php js_end(); ?>
