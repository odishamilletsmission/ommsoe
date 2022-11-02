
<div class="content">
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-login fa-2x text-earth-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-earth"><i class="fa fa-rupee"></i> <span data-toggle="countTo" data-speed="1000" data-to="<?=$fr?>">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Fund Receipt</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-logout fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance"><i class="fa fa-rupee"></i> <span data-toggle="countTo" data-speed="1000" data-to="<?=$ex?>">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Expense</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-briefcase fa-2x text-pulse"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-pulse"><i class="fa fa-rupee"></i> <span  data-toggle="countTo" data-speed="1000" data-to="<?=$cb?>">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Closing Balance <small>as on date</small></div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <?php if($components): ?>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-12">
                <div class="block block-themed">
                    <div class="block-header bg-muted">
                        <h3 class="block-title">MPR <?=$year?></h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="tableFixHead">
                            <table class="table custom-table " id="txn-table">
                                <thead>
                                <tr>
                                    <th rowspan="3">Sl no</th>
                                    <th rowspan="3">Component</th>
                                    <th rowspan="2" colspan="2">Opening Balance(in lakhs)</th>
                                    <th rowspan="2" colspan="2">Target (Including spill over)(in lakhs)</th>
                                    <th rowspan="1" colspan="6">Allotment received (in lakhs)</th>
                                    <th rowspan="1" colspan="4">Expenditure (in lakhs)</th>
                                    <th rowspan="2" colspan="2">Unspent Balance upto the month (in lakhs)</th>
                                </tr>
                                <tr>
                                    <th colspan="2">As per statement upto prev month</th>
                                    <th colspan="2">During the month</th>
                                    <th colspan="2">Upto the month</th>
                                    <th colspan="2">During the month</th>
                                    <th colspan="2">Cumulative upto the month</th>
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
                                    <th>Phy</th>
                                    <th>Fin</th>
                                    <th>Phy</th>
                                    <th>Fin</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?=$components?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
        <!-- END Row #1 -->
    </div>
    <?php endif; ?>

</div>

<!-- Pop Out Modal -->
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Fund Receipt</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div>
                        <p>Do you have any fund receipt this month?
                            <button type="button" data-value="yes" class="btn btn-alt-success btn-fr" >Yes</button>
                            <button type="button" data-value="no" class="btn btn-alt-danger btn-fr" >No</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->

<!-- Pop Out Modal -->
<div class="modal fade" id="modal-feature" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Announcement</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div>
                        <span class="text-danger"><strong>Few new features have been added: </strong></span>
                        <br>
                        <br>
                        <ol>
                            <li>SoE and other uploads are only enabled from 25th to 30th of every month</li>
                            <li>Failing to upload SoE before 30th day will have to write a letter to the State accountant (jnanajit@wassan.org)</li>
                            <li>SoE upload can only be enabled after MIS upload</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->

<?php js_start(); ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script type="text/javascript">
    $(function () {
//        $('#modal-feature').modal('show');
    })
    <?php if($fr_check): ?>
        $(function () {
            $('#modal-popout').modal('show');

            $('.btn-fr').click(function () {
                choice = $(this).data('value');
                $.ajax({
                    data:{choice:choice},
                    type:'GET',
                    dataType:'JSON',
                    success:function (json) {
                        $('#modal-popout').modal('hide');
                        if(choice=='yes') {
                            location.href = "<?=$fr_url?>";
                        }
                    },
                    error:function () {
                        alert('Request not succussful');
                        $('#modal-popout').modal('hide');
                    }
                });
            });
        })
    <?php endif; ?>
</script>
<?php js_end(); ?>


                