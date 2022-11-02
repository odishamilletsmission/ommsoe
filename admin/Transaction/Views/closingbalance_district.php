<!-- Main content -->
    <section class="content">
        <div class="block">
            <div class="block-content block-content-full">
                <form>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="year" name="year">
                                <option value="">Choose Year</option>
                                <?php foreach ($years as $_year) { ?>
                                    <option value="<?=$_year['id']?>" <?php if ($_year['id']==$year_id){ echo 'selected'; } ?>><?=$_year['name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="month" name="month">
                                <option value="">Choose Month</option>
                                <?php foreach ($months as $_month) { ?>
                                    <option value="<?=$_month['id']?>" <?php if ($_month['id']==$month_id){ echo 'selected'; } ?>><?=$_month['name']?></option>
                                <?php } ?>
                            </select>
                        </div>

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
            <div class="block-header block-header-default">
                <h3 class="block-title">Summary</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Agency Type</th>
                        <th>Opening (+)</th>
                        <th>Fund Receipt (+)</th>
                        <th>Other Receipt (+)</th>
                        <th>Expense (-)</th>
                        <th>Closing</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$summary['year']?></td>
                        <td><?=$summary['month']?></td>
                        <td><?=$summary['agency_type']?></td>
                        <td><?=$summary['ob']?></td>
                        <td><?=$summary['fr']?></td>
                        <td><?=$summary['mt']?></td>
                        <td><?=$summary['exp']?></td>
                        <td><?=$summary['bal']?></td>
                        <td><?=$summary['status']?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Closing Balance Breakup</h3>
            </div>
            <div class="block-content">
                <?php echo form_open(); ?>
                <table id="closing-balance-breakup" class="table table-bordered table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th style="width:300px;">Particulars</th>
                        <th style="width:300px;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Advance</td>
                        <td><input class="form-control amount" value="<?=$advance?>" name="advance"></td>
                    </tr>
                    <tr>
                        <td>Bank</td>
                        <td><input class="form-control amount" name="bank" value="<?=$bank?>"></td>
                    </tr>
                    <tr>
                        <td>Cash</td>
                        <td><input class="form-control amount" name="cash" value="<?=$cash?>"></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><input class="form-control" id="total" disabled></td>
                        <td>
                            <?php if($status!==1) { ?>
                            <button class="btn btn-sm btn-primary"><i class="si si-paper-plane"></i> Submit</button>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php echo form_close(); ?>
            </div>
        </div>

    </section>
    <!-- content -->

<?php js_start(); ?>
<script>
    $(function () {
        calcTotal();
        $('.amount').on('keyup',function (e) {
            if (/^-?\d*\.?\d{0,6}$/.test(this.value)==false) {
                $(this).val(this.value.replace(e.key,''));
            } else {
                calcTotal();
            }
        });
    });
    function calcTotal(){
        total = 0;
        $('.amount').each(function (k,v) {
            val = parseFloat($(this).val()) || 0;
            total += val
        })
        $('#total').val(total.toFixed(2));
    }
</script>
<?php js_end(); ?>
