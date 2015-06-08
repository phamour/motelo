<div id="list_model" class="panel panel-primary">
    <input type="hidden" id="list_type" value="model">
    <div class="panel-heading">
        List of models
    </div>
    <?php if (count($data) == 0): ?>
        <div class="panel-body">No results</div>
    <?php else: ?>
        <table id="table_model" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Label</th>
                    <th>Created at</th>
                    <th>File</th>
                    <th>Active?</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['label'] ?></td>
                        <td><?= date('M jS Y gA', strtotime($row['created_at'])) ?></td>
                        <td class="file_view"><a href="#"><?= $row['filename'] ?></a></td>
                        <td>
                            <span class="glyphicon glyphicon-<?= $row['status'] ? 'ok status_ok' : 'remove status_ko' ?>" 
                                aria-hidden="true">
                            </span>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>

<?php include 'list_modal.php'; ?>
