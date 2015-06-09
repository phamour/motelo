<form id="creation_instance" method="post" enctype="multipart/form-data">
    <?php if (isset($data['id'])): ?>
        <input type="hidden" name="instance_id" value="<?= $data['id'] ?>">
        <input type="hidden" name="_METHOD" value="PUT">
    <?php endif ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= isset($data['id']) ? 'Edit' : 'Create  an' ?> instance
        </div>
        <div class="panel-body">
            <div class="col-md-2">
                <label for="instance_nb_nodes">Nb nodes</label>
                <input type="number" class="form-control" id="instance_nb_nodes" name="instance_nb_nodes" 
                    placeholder="Enter 'n'" required min="2" 
                    value="<?php if (isset($data['nb_nodes'])) echo $data['nb_nodes']; ?>">
            </div>
            <div class="col-md-2">
                <label for="instance_nb_edges">Nb edges</label>
                <input type="number" class="form-control" id="instance_nb_edges" name="instance_nb_edges" 
                    placeholder="Enter 'm'" required min="1" 
                    value="<?php if (isset($data['nb_edges'])) echo $data['nb_edges']; ?>">
            </div>
            <div class="col-md-2">
                <label for="instance_blockage_o">Blockage origin</label>
                <input type="number" class="form-control" id="instance_blockage_o" name="instance_blockage_o" 
                    placeholder="Enter 'O'" 
                    value="<?php if (isset($data['blockage_o'])) echo $data['blockage_o']; ?>">
            </div>
            <div class="col-md-2">
                <label for="instance_blockage_d">Blockage destination</label>
                <input type="number" class="form-control" id="instance_blockage_d" name="instance_blockage_d" 
                    placeholder="Enter 'D'" 
                    value="<?php if (isset($data['blockage_d'])) echo $data['blockage_d']; ?>">
            </div>
            <div class="col-md-4">
                <label for="instance_file">File (.dat|.txt)</label>
                <?php if (isset($data['filename'])): ?>
                    <input type="text" class="form-control" id="instance_file" name="instance_filename" 
                        value="<?= $data['filename'] ?>" readonly>
                <?php else: ?>
                    <input type="file" id="instance_file" name="instance_file" accept=".dat,.txt" required>
                <?php endif ?>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
