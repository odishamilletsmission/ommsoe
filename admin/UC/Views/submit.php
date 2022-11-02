
<div class="content">
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-xl-4">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-bag fa-2x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-primary"> <span data-toggle="countTo" data-speed="1000" data-to="<?=$total_allotment?>">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Allotment From SPMU</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-4">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-wallet fa-2x text-earth-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-earth"><span data-toggle="countTo" data-speed="1000" data-to="<?=$total_uc_submitted?>">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">UC Submitted</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-4">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-envelope-open fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="1000" data-to="<?=$total_uc_balance?>">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Balance UC to be Submitted</div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>

    <div class="row invisible" data-toggle="appear">
        <!-- Row #2 -->
        <div class="col-md-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default border-b">
                    <h3 class="block-title">
                        UC Details
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-striped" id="block-components">
                        <thead>
                        <tr>
                            <th>Year</th>
                            <th>UC Date</th>
                            <th>Letter No</th>
                            <th>Allotment</th>
                            <th>UC Submitted</th>
                            <th>UC Balance</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($allotments): ?>
                        <?php foreach ($allotments as $allotment): ?>
                            <tr>
                                <td><?=$allotment['year']?></td>
                                <td><?=$allotment['uc_date']?></td>
                                <td><?=$allotment['letter_no']?></td>
                                <td><?=$allotment['allotment']?></td>
                                <td><?=$allotment['uc_submitted']?></td>
                                <td><?=$allotment['uc_balance']?></td>
                                <td><?=$allotment['action']?></td>
                            </tr>
                        <?php endforeach; ?>
                            <tr>
                                <td colspan="3">Total</td>
                                <td><?=$total_allotment?></td>
                                <td><?=$total_uc_submitted?></td>
                                <td><?=$total_uc_balance?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No Data Found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>