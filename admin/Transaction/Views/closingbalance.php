    <!-- Main content -->
    <section class="content">
        <div class="block">
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
                    <?php if($agency_types): ?>
                        <div class="col-md-2">
                            <select class="form-control" name="agency_type_id" id="agency-type">
                                <?php foreach ($agency_types as $agency_type): ?>
                                    <option value="<?=$agency_type['id']?>" <?php if ($agency_type['id']==$agency_type_id){echo 'selected';} ?>><?=$agency_type['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if($fund_agencies): ?>
                        <div class="col-md-2">
                            <select class="form-control" id="fund_agency_id" name="fund_agency_id">
                                <?php foreach ($fund_agencies as $agency): ?>
                                    <option value="<?=$agency['fund_agency_id']?>" <?php if ($agency['fund_agency_id']==$fund_agency_id){echo 'selected';} ?>><?=$agency['fund_agency']?></option>
                                <?php endforeach; ?>
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
                        <th class="text-center">Month</th>
                        <th>Opening</th>
                        <th>Fund Receipt</th>
                        <th>Other Receipt</th>
                        <th>Expense</th>
                        <th>Closing</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($months as $i => $month) { ?>
                        <tr>
                            <th class="text-center" scope="row"><?php echo $month->month; ?></th>
                            <td><?php echo $month->ob; ?></td>
                            <td><?php echo $month->fr; ?></td>
                            <td><?php echo $month->mt; ?></td>
                            <td><?php echo $month->exp; ?></td>
                            <td><?php echo $month->bal; ?></td>
                            <td><?php echo $month->status; ?></td>
                            <td class="text-center">
                                <?php if($month->edit_url){ ?>
                                <div class="btn-group">
                                    <a href="<?=$month->edit_url?>" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!-- content -->

