<!-- Approval Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="modal-title-edit"><?=$title?></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content" id="modal-content-edit">
                    <form method="post" id="approve-form" >
                        <div class="form-group row">
                            <label class="col-12" for="agency-type">Action</label>
                            <div class="col-lg-12">
                                <select class="form-control" name="status" id="status">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?=$status['id']?>" <?php if ($status['id']==$status_id){echo 'selected';} ?>><?=$status['name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="agency-type">Remarks</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="remarks" rows="5"><?=$remarks?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#btn-action').click(function () {
            $("#modal-edit").modal({
                backdrop: 'static',
            });
        })
    })
</script>