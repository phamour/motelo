<form id="creation_solution" method="post" enctype="multipart/form-data">
    <?php if (isset($data['id'])): ?>
        <input type="hidden" name="solution_id" value="<?= $data['id'] ?>">
        <input type="hidden" name="_METHOD" value="PUT">
    <?php endif ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= isset($data['id']) ? 'Edit' : 'Create  a' ?> solution
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Model</label>
                    <select class="form-control" id="solution_model_id" name="solution_model_id"
                        value="<?php if (isset($data['model_id'])) echo $data['model_id']; ?>">
                        <option value="-1">-- Choose model --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Instance</label>
                    <select class="form-control" id="solution_instance_id" name="solution_instance_id"
                        value="<?php if (isset($data['instance_id'])) echo $data['instance_id']; ?>">
                        <option value="-1">-- Choose instance --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="solution_no_solution">No solution</label><br>
                    <input type="checkbox" id="solution_no_solution" name="solution_no_solution" value="1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="solution_z">Objective value</label>
                    <input type="number" class="form-control" id="solution_z" name="solution_z" 
                        placeholder="Enter 'z'" step="any" 
                        value="<?php if (isset($data['z'])) echo $data['z']; ?>">
                </div>
                <div class="col-md-4">
                    <label for="solution_t">CPU time</label>
                    <input type="number" class="form-control" id="solution_t" name="solution_t" 
                        placeholder="Enter 't'" step="any" 
                        value="<?php if (isset($data['t'])) echo $data['t']; ?>">
                </div>
                <div class="col-md-4">
                    <label for="solution_file">File (.sol|.txt)</label>
                    <?php if (isset($data['filename'])): ?>
                        <input type="text" class="form-control" id="solution_file" name="solution_filename" 
                            value="<?= $data['filename'] ?>" readonly>
                    <?php else: ?>
                        <input type="file" id="solution_file" name="solution_file" accept=".sol,.txt" required>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="<?= $app->urlFor('list', array('type' => 'solution')) ?>" 
                class="btn btn-danger" role="button">
                Cancel
            </a>
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
