
<table class="table table-bordered table-striped table-vcenter js-dataTable-full">
    <thead>
    <tr>
        <th>District</th>
        <th>Block</th>
        <th>Year</th>
        <?php if($periodic): ?>
            <th>Period</th>
        <?php else: ?>
            <th>Month</th>
        <?php endif; ?>
        <th>Fund Agency</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?=$district?></td>
        <td><?=$block?></td>
        <td><?=$fin_year?></td>
        <?php if($periodic): ?>
            <td><?=$period?></td>
        <?php else: ?>
            <td><?=$month_name?></td>
        <?php endif; ?>
        <td><?=$fund_agency?></td>
    </tr>
    </tbody>
</table>