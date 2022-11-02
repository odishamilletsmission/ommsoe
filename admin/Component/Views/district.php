
<div class="row">
    <div class="col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">List</h3>
            </div>

            <div class="block-content">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comp_districts as $district): ?>
                        <tr>
                            <td><?=$district['name']?></td>
                            <td><a href="<?=$district['assign_url']?>" class="btn btn-primary" title="Assign Components"><i class="fa fa-list"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
