<div id="<?= 'list_' . $type ?>" class="panel panel-primary">
    <div class="panel-heading">
        List of <?= $type . 's' ?>
    </div>
    <?php if ($n == 0): ?>
        <div class="panel-body">No results</div>
    <?php else: ?>
        <table id="<?= 'table_' . $type ?>" class="table table-bordered">
            <thead>
                <tr>
                    <?php foreach ($fields as $field): ?>
                        <th><?= $field ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach($row as $col): ?>
                            <td><?= $col ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>
