<section class="content">
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
                <?php if($periodic): ?>
                <div class="col-md-2">
                    <label>From Month</label>
                    <select class="form-control" id="from_month" name="from_month">
                        <option value="">From Month</option>
                        <?php foreach ($months as $month) { ?>
                            <option value="<?=$month['id']?>" <?php if($month['id']==$from_month){echo 'selected';} ?>><?=$month['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>To Month</label>
                    <select class="form-control" id="to_month" name="to_month">
                        <option value="">To Month</option>
                        <?php foreach ($months as $month) { ?>
                            <option value="<?=$month['id']?>" <?php if($month['id']==$to_month){echo 'selected';} ?>><?=$month['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php else: ?>
                <div class="col-md-2">
                    <label>Month</label>
                    <select class="form-control" id="month" name="month">
                        <?php foreach ($months as $month) { ?>
                            <option value="<?=$month['id']?>" <?php if($month['id']==$month_id){echo 'selected';} ?>><?=$month['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php endif; ?>
                <?php if($agency_types): ?>
                    <div class="col-md-2">
                        <label>Agency Type</label>
                        <select class="form-control" id="agency_type_id" name="agency_type_id">
                            <option value="">All</option>
                            <?php foreach ($agency_types as $agency_type) : ?>
                                <option value="<?=$agency_type['id']?>" <?php if($agency_type['id']==$agency_type_id){echo 'selected';} ?>><?=$agency_type['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if($districts): ?>
                    <div class="col-md-2">
                        <label>District</label>
                        <select class="form-control" id="district_id" name="district_id">
                            <option value="">Choose District</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?=$district['id']?>"><?=$district['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if($blocks): ?>
                    <div class="col-md-2">
                        <label>Block</label>
                        <select class="form-control" id="block_id" name="block_id">
                            <option value="">Choose Block (if block level)</option>
                            <?php foreach ($blocks as $block): ?>
                                <option value="<?=$block['id']?>"><?=$block['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if($fund_agencies): ?>
                    <div class="col-md-2">
                        <label>Fund Agancy</label>
                        <select class="form-control" id="fund_agency_id" name="fund_agency_id">
                            <?php foreach ($fund_agencies as $agency): ?>
                                <option value="<?=$agency['fund_agency_id']?>"><?=$agency['fund_agency']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row mt-3">
                <div class="col-md-2">
                    <button id="btn-filter" class="btn btn-outline btn-primary"><i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>
        </div>
    </div>
    </form>

    <?php if($components): ?>
    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">Report</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="tableFixHead">
                <table class="table custom-table " id="txn-table">
                    <thead>
                    <tr>
                        <th rowspan="3">Sl no</th>
                        <th rowspan="3">Component</th>
                        <th rowspan="2" colspan="2">Fund available up to date</th>
                        <th rowspan="1" colspan="6">Allotment received (in lakhs) from DA & FP(O)</th>
                        <th rowspan="1" colspan="6">Expenditure (in lakhs)</th>
                        <th rowspan="2" colspan="2">Unspent Balance upto the month (in lakhs)</th>
                    </tr>
                    <tr>
                        <th colspan="2">As per statement upto prev month</th>
                        <th colspan="2">During the month</th>
                        <th colspan="2">Upto the month</th>
                        <th colspan="2">Upto prev month</th>
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