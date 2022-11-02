<form>
    <div class="block">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <?php if($blocks): ?>
                            <th>Block</th>
                            <?php endif; ?>
                            <th>Filter</th>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control" id="year" name="year" required>
                                    <?php foreach ($years as $year) { ?>
                                        <option value="<?=$year['id']?>" <?php if($year['id']==$year_id){echo 'selected';} ?>><?=$year['name']?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" id="month" name="month">
                                    <?php foreach ($months as $month) { ?>
                                        <option value="<?=$month['id']?>" <?php if($month['id']==$month_id){echo 'selected';} ?>><?=$month['name']?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php if($blocks): ?>
                            <td>
                                <select class="form-control" id="block_id" name="block_id">
                                    <option value="">All Blocks</option>
                                    <?php foreach ($blocks as $block): ?>
                                        <option value="<?=$block['id']?>" <?php if($block['id']==$block_id){echo 'selected';} ?>><?=$block['name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <?php endif; ?>
                            <td>
                                <button id="btn-filter" class="btn btn-outline btn-primary"><i class="fa fa-filter"></i> Filter</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>