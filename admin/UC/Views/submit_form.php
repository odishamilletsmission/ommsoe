<form method="post" id="allotment-form" onsubmit="return false;">
    <div class="form-group row">
        <label class="col-12" for="all-date">Submit Date</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type="text" class="form-control js-datepicker"
                       id="sub-date"
                       name="date_submit"
                       placeholder="Date" autocomplete="false"
                       data-today-highlight="true" data-date-format="dd/mm/yyyy"
                       value="<?=$date_submit?>"
                >
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="">UC Amount</label>
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
    <div class="form-group row">
        <label class="col-12" for="">Letter No.</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type="text" class="form-control"
                       id="letter"
                       name="letter_no"
                       placeholder="Letter no."
                       value="<?=$letter_no?>"
                >
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="">Page No</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type="text" class="form-control"
                       id="page"
                       name="page_no"
                       placeholder="Page no"
                       value="<?=$page_no?>"
                >
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="">Document</label>
        <div class="col-lg-12 dm-uploader">
            <div class="input-group">
                <input type="text" class="form-control document-name" disabled
                       value="<?=$document_name?>"
                >
            <div role="button" class="btn btn-outline-primary mr-2">
                <i class="si si-paper-clip"></i>
                <input type="file">
            </div>
            <input type="hidden" class="filepath" value="<?=$document?>" name="document">
            </div>
            <?php if($document_url) { ?>
            <small class="status text-muted"><?=$document_url?></small>
            <?php } else { ?>
            <small class="status text-muted">Upload PDF</small>
            <?php } ?>
        </div>
    </div>
</form>