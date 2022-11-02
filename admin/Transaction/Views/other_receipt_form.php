<form method="post" id="misc-txn" onsubmit="return false;">
    <?php if($agency_types): ?>
    <div class="form-group row">
        <label class="col-12" for="agency-type">Agency Type</label>
        <div class="col-lg-12">
            <select class="form-control" name="agency_type_id" id="agency-type">
                <?php foreach ($agency_types as $agency_type): ?>
                <option value="<?=$agency_type['id']?>" <?php if ($agency_type['id']==$agency_type_id){echo 'selected';} ?>><?=$agency_type['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>
    <?php foreach ($heads as $head): ?>
    <div class="form-group row">
        <label class="col-12" for="head-<?=$head['id']?>"><?=$head['name']?></label>
        <div class="col-lg-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-rupee"></i>
                    </span>
                </div>
                <input type="text" class="form-control"
                       id="head-<?=$head['id']?>"
                       name="misc[<?=$head['id']?>]"
                       placeholder="<?=$head['name']?>"
                       value="<?=$head['value']?>"
                >
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</form>