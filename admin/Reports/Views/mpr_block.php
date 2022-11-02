<section class="content">

    <?=$filter_panel?>

    <?php if($components): ?>
    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">Report</h3>
            <div class="block-options">
                <a href="<?=$download_url?>" class="btn btn-secondary" data-toggle="tooltip" data-original-title="Download">
                    <i class="si si-cloud-download"></i>
                </a>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="tableFixHead">
                <table class="table custom-table " id="txn-table">
                    <thead>
                    <tr>
                        <th rowspan="3">Sl no</th>
                        <th rowspan="3">Component</th>
                        <th rowspan="2" colspan="2">Opening Balance</th>
                        <th rowspan="2" colspan="2">Target</th>
                        <th rowspan="1" colspan="6">Allotment received (in lakhs) from DA & FP(O)</th>
                        <th rowspan="1" colspan="4">Expenditure (in lakhs)</th>
                        <th rowspan="2" colspan="2">Unspent Balance upto the month (in lakhs)</th>
                    </tr>
                    <tr>
                        <th colspan="2">As per statement upto prev month</th>
                        <th colspan="2">During the month</th>
                        <th colspan="2">Upto the month</th>
                        <th colspan="2">During the month</th>
                        <th colspan="2">Cumulative Expenditure upto the month</th>
                    </tr>
                    <tr>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                        <th>Phy</th>
                        <th>Fin</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$components?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</section>