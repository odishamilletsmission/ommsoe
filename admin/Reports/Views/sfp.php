<section class="content">
    <form>
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Filter</h3>
            </div>
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
                </div>
                <div class="row mt-3">
                    <div class="col-md-2">
                        <button id="btn-filter" class="btn btn-outline btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title">Statement of Fund Position as on <?=$reporting_period?></h3>
        </div>
        <div class="block-content block-content-full">
            <div class="invoice-box">

            <table class="table table-bordered">
                <tr>
                    <th>District</th>
                    <th>Block</th>
                    <th>Agency Name</th>
                    <th>Date</th>
                    <th>Agency Type</th>
                </tr>
                <tr>
                    <td><?=$district_name?></td>
                    <td><?=$block_name?></td>
                    <td><?=$agency_name?></td>
                    <td><?=$report_date?></td>
                    <td><?=$agency_type_name?></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
                <tr class="heading">
                    <td colspan="5">Opening Balance</td>
                </tr>

                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td>Advance</td>
                    <td><?=$opening['advance']?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr class="item-row">
                    <td width="5%">&nbsp;</td>
                    <td width="50%">Bank</td>
                    <td width="20%"><?=$opening['bank']?></td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%">&nbsp;</td>
                </tr>

                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td>Cash</td>
                    <td><?=$opening['cash']?></td>
                    <td class="s-total"><?=$opening['total']?></td>
                    <td>&nbsp;</td>
                </tr>

                <tr class="total">
                    <td>&nbsp;</td>
                    <td>Total: </td>
                    <td>&nbsp;</td>
                    <td><?=$opening['total']?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="heading">
                    <td colspan="5">Fund Receipt</td>
                </tr>
                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td><strong>Fund Receipt</strong></td>
                    <td>&nbsp;</td>
                    <td><strong><?=$fund_receipt?></strong></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="heading">
                    <td colspan="5">Expenditure</td>
                </tr>
                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td><strong>Expenditure</strong></td>
                    <td>&nbsp;</td>
                    <td><strong><?=$expense?></strong></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="heading">
                    <td colspan="5">Other Receipt/Repayment:</td>
                </tr>
                <?php foreach ($or as $item): ?>
                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td><?=$item['head']?></td>
                    <td><?=$item['total']?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
                <tr class="total">
                    <td>&nbsp;</td>
                    <td>Total</td>
                    <td>&nbsp;</td>
                    <td><?=$or_total?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="heading">
                    <td colspan="5">Closing Balance:</td>
                </tr>
                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td>Advance</td>
                    <td><?=$closing['advance']?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td>Bank </td>
                    <td><?=$closing['bank']?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="item-row">
                    <td>&nbsp;</td>
                    <td>Cash</td>
                    <td><?=$closing['cash']?></td>
                    <td><?=$closing['total']?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="total">
                    <td>&nbsp;</td>
                    <td>Total</td>
                    <td>&nbsp;</td>
                    <td><?=$closing['total']?></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</section>
<style>
    .invoice-box {
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        color: #555;
        background: #fff;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(3),.invoice-box table tr td:nth-child(4) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #e5e3e3;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item-row td{
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item-row.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(3),.invoice-box table tr.total td:nth-child(4) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    .invoice-box tr.total {
        background: #f4f4f4;
        font-weight: bold;
    }

</style>