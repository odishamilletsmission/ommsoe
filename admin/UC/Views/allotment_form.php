<form method="post" id="allotment-form" onsubmit="return false;">
    <div class="form-group row">
        <label class="col-12" for="year_id">Year</label>
        <div class="col-lg-12">
            <select class="form-control" name="year" id="year">
                <?php foreach ($years as $year): ?>
                    <option value="<?=$year['id']?>" <?php if ($year['id']==$year_id){echo 'selected';} ?>><?=$year['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="recipient_id">District/Agency</label>
        <div class="col-lg-12">
            <select class="form-control" name="recipient_id" id="recipient_id">
                <?php foreach ($recipients as $recipient): ?>
                    <option value="<?=$recipient['id']?>" <?php if ($recipient['id']==$recipient_id){echo 'selected';} ?>><?=$recipient['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="all-date">Allotment Date</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type="text" class="form-control js-datepicker"
                       id="all-date"
                       name="allotment_date"
                       placeholder="Date" autocomplete="false"
                       data-today-highlight="true" data-date-format="dd/mm/yyyy"
                       value="<?=$allotment_date?>"
                >
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="">Allotment Amount</label>
        <div class="col-lg-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-rupee"></i>
                    </span>
                </div>
                <input type="text" class="form-control"
                       id="all-amount"
                       name="amount"
                       placeholder="Amount"
                       value="<?=$amount?>"
                >
            </div>
        </div>
    </div>
</form>