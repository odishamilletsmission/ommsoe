<style>
    table.minimalistBlack {
        border: 2px solid #000000;
        width: 100%;
        text-align: left;
        border-collapse: collapse;
		
		display: block;
		// overflow-x: auto;

    }
	table.minimalistBlack td, table.minimalistBlack th {
        border: 1px solid #000000;
        padding: 4px 10px;
    }
    table.minimalistBlack tbody td {
        font-size: 13px;
        color: #333;
    }
    table.minimalistBlack thead {
        background: #CFCFCF;
        background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
        background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
        background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
        border-bottom: 3px solid #000000;
    }
    table.minimalistBlack thead th {
        font-size: 15px;
        font-weight: bold;
        color: #000000;
        text-align: center;
    }
    table.minimalistBlack tfoot td {
        font-size: 14px;
    }
	
	
	
	.table-scroll-wrapper {
		overflow: auto;
		border: var(--border-size-s) solid var(--color-neutral-4);
	}

	.table-scroll {
		margin: 0px;
		border: none;
	}

	.table-scroll thead th {
	  position: -webkit-sticky;
	  position: sticky;
	  top: 0;
	}

	.table-scroll tr td:first-child,
	.table-scroll th:first-child {
	  position: -webkit-sticky;
	  position: sticky;
	  width:120px;
	  left: 0;
	  z-index: 2;
	  border-right: var(--border-size-s) solid var(--color-neutral-4);
	}

	.table-scroll th:first-child {
		z-index: 4;
	}

	.table-scroll tr td:nth-child(2),
	.table-scroll th:nth-child(2) {
	  position: -webkit-sticky;
	  position: sticky;
	  width: 120px;
	  left: 110px;
	  z-index: 2;
	  border-right: var(--border-size-s) solid var(--color-neutral-4);
	}

	.table-scroll th:nth-child(2) {
		z-index: 4;
	}


	.table-scroll tr td:nth-child(3),
	.table-scroll th:nth-child(3) {
	  position: -webkit-sticky;
	  position: sticky;
	  left: 210px;
	  z-index: 2;
	  border-right: var(--border-size-s) solid var(--color-neutral-4);
	}

	.table-scroll th:nth-child(3) {
		z-index: 4;
	}

	.table-scroll tr td:last-child {
	  border-right: none;
	}

	.phone .table-scroll tr td,
	.tablet .table-scroll tr td {
	  border-right: none;
	}
</style>
<section class="content">
    <form>
		<div class="block">
			<div class="block-content block-content-full">
				<div class="row">
					<div class="col-md-2">
						<label>Year</label>
						<select class="form-control" id="year" name="year" required>
							<?php foreach ($years as $key=>$year) { ?>
								<option value="<?=$key?>" <?php if($key==$year_id){echo 'selected';} ?>><?=$year?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-2 mt-4">
						<button id="btn-filter" class="btn btn-outline btn-primary"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</div>
			</div>
		</div>
	</form>

    <?php if($cdata): ?>
    <div class="block block-themed">
        <div class="block-header bg-muted">
            <h3 class="block-title"> Statement of Expenditure(SoE),Odisha Millets Mission for - <?=$fin_year?></h3>
			<div class="block-options d-none">
                <a href="<?=$download_url?>" class="btn btn-secondary" data-toggle="tooltip" data-original-title="Download">
                    Download
                </a>
            </div>
		</div>
        <div class="block-content block-content-full">
            <div class="tableFixHead">
                <table class="table minimalistBlack " id="txn-table">
                    <thead>
                        <tr>
                            <th rowspan="2" width="80">Sl No.</th>
                            <th rowspan="2" width="100">Component</th>
                            <th rowspan="2" >Agency</th>
                            <th colspan="2" class="text-center">Opening balance</th>
                            <th colspan="2" class="text-center">Budget</th>
							<th colspan="2" class="text-center">Total Target </th>
                            <th colspan="2" class="text-center">Allotment</th>
                            <th colspan="2" class="text-center">Expenditure as on date</th>
                            <th colspan="2" class="text-center">Closing Balance</th>
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
						</tr>
                    </thead>
                    <tbody>
                    <?php foreach($cdata['components'] as $component){?>
						<?php if($component['heading']){ ?>
                            <tr style="font-weight: bold;background:#ddd;">
                                <td><?=$component['number']?></td>
                                <td colspan="18"><?=$component['description']?></td>
                            </tr>
                        <?php } else { ?>
                            <tr <?php if($component['sub_total']){ ?> style="font-weight: bold;background:yellow;"<?php }?>>
                                <td><?=$component['number']?></td>
                                <td><?=$component['description']?></td>
                                <td><?=$component['agency']?></td>
                                <td><?=$component['ob_phy']?></td>
                                <td><?=$component['ob_fin']?></td>
								<td><?=$component['bud_phy']?></td>
                                <td><?=$component['bud_fin']?></td>
                                <td><?=$component['tar_phy']?></td>
                                <td><?=$component['tar_fin']?></td>
                                <td><?=$component['fr_phy']?></td>
                                <td><?=$component['fr_fin']?></td>
                                <td><?=$component['ex_phy']?></td>
                                <td><?=$component['ex_fin']?></td>
                                <td><?=$component['cb_phy']?></td>
                                <td><?=$component['cb_fin']?></td>
                            </tr>
                        <?}?>
					<?}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</section>