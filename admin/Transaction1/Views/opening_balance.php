<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-6 mx-auto">
            <div class="block block-themed">
                <div class="block-header bg-success">
                    <h3 class="block-title">Opening Balance</h3>
                </div>
                <div class="block-content">
                    <?=form_open()?>
                        <div class="form-group row">
                            <label class="col-12" for="advance">Advance</label>
                            <div class="col-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-rupee"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control amount" id="advance" name="advance" placeholder="Enter advance amount">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="bank">Bank (Including bank interest)</label>
                            <div class="col-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-rupee"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control amount" id="bank" name="bank" placeholder="Enter bank amount">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="cash">Cash</label>
                            <div class="col-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-rupee"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control amount" id="cash" name="cash" placeholder="Enter cash amount">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="total">Total</label>
                            <div class="col-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-rupee"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" id="total" name="total" disabled placeholder="Total amount">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-alt-success">
                                    <i class="fa fa-plus mr-5"></i> Submit
                                </button>
                            </div>
                        </div>
                    <?=form_close()?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content -->

<?=js_start();?>
<script>
    $(function () {
        calcTotal();
        $('.amount').on('keyup',function (e) {
            if (/^-?\d*\.?\d{0,6}$/.test(this.value)==false) {
                $(this).val(this.value.replace(e.key,''));
            } else {
                calcTotal();
            }
        })
    })
    function calcTotal(){
        total = 0;
        $('.amount').each(function (k,v) {
            val = parseFloat($(this).val()) || 0;
            total += val
        })
        $('#total').val(total.toFixed(2));
    }
</script>
<?=js_end();?>
