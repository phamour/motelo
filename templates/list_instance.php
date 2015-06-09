<div id="list_instance" class="panel panel-primary">
    <input type="hidden" id="list_type" value="instance">
    <div class="panel-heading">
        List of instances
    </div>
    <?php if (count($data) == 0): ?>
        <div class="panel-body">No results</div>
    <?php else: ?>
        <table id="table_instance" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nb nodes</th>
                    <th>Nb edges</th>
                    <th>O</th>
                    <th>D</th>
                    <th>Created at</th>
                    <th>File</th>
                    <th>Active?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nb_nodes'] ?></td>
                        <td><?= $row['nb_edges'] ?></td>
                        <td><?= $row['blockage_o'] ?></td>
                        <td><?= $row['blockage_d'] ?></td>
                        <td><?= date('M jS Y gA', strtotime($row['created_at'])) ?></td>
                        <td class="file_view"><a href="#"><?= $row['filename'] ?></a></td>
                        <td>
                            <span class="glyphicon glyphicon-<?= $row['status'] ? 'ok status_ok' : 'remove status_ko' ?>" 
                                aria-hidden="true">
                            </span>
                        </td>
                        <td>
                            <a class="list_action action_view btn btn-default" role="button" title="view" href="<?= 
                                $app->urlFor('view', array('type' => 'instance', 'id' => $row['id'])) ?>">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
                            </a>
                            <a class="list_action action_edit btn btn-default" role="button" title="edit" href="<?= 
                                $app->urlFor('edit', array('type' => 'instance', 'id' => $row['id'])) ?>">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </a>
                            <form class="list_action" method="post" action="<?= 
                                $app->urlFor('delete', array('type' => 'instance', 'id' => $row['id'])) ?>">
                                <input type="hidden" name="_METHOD" value="DELETE">
                                <button class="btn btn-default" title="delete">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>

<?php include 'list_modal.php'; ?>
