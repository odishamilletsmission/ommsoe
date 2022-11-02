
<div class="col-12">
    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">Upload Status</h3>
        </div>
        <div class="block-content block-content-full">
            <form>
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" id="year" name="year">
                            <option value="">Choose Year</option>
                            <?php foreach (getAllYears() as $_year) { ?>
                                <option value="<?=$_year['id']?>" <?php if ($_year['id']==$year_id){ echo 'selected'; } ?>><?=$_year['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" id="month" name="month">
                            <option value="">Choose Month</option>
                            <?php foreach (getAllMonths() as $_month) { ?>
                                <option value="<?=$_month['id']?>" <?php if ($_month['id']==$month_id){ echo 'selected'; } ?>><?=$_month['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if(isset($districts)): ?>
                    <div class="col-md-2">
                        <select class="form-control" id="district_id" name="district_id">
                            <option value="">Choose District</option>
                            <?php foreach ($districts as $_district) { ?>
                                <option value="<?=$_district['id']?>" <?php if ($_district['id']==$district_id){ echo 'selected'; } ?>><?=$_district['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-2">
                        <button class="btn btn-primary"><i class="si si-magnifier"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="block">
        <div class="block-content block-content-full">
            <table class="table table-striped table-vcenter">
                <thead>
                <tr>
                    <th class="text-center">District</th>
                    <th class="text-center">Block</th>
                    <th>MIS</th>
                    <th>Fund Receipt</th>
                    <th>Expense</th>
                    <th>Other Receipt</th>
                    <th>Closing</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($blocks as $block): ?>
                    <tr>
                        <td><?=$block['district']?></td>
                        <td><?=$block['block']?></td>
                        <td><label class="badge badge-<?=$block['mis_color']?>"><?=$block['mis_status']?></label></td>
                        <td><label class="badge badge-<?=$block['fr_color']?>"><?=$block['fr_status']?></label></td>
                        <td><label class="badge badge-<?=$block['ex_color']?>"><?=$block['ex_status']?></label></td>
                        <td><label class="badge badge-<?=$block['or_color']?>"><?=$block['or_status']?></label></td>
                        <td><label class="badge badge-<?=$block['cb_color']?>"><?=$block['cb_status']?></label></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>