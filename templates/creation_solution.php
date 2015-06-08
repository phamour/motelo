<form id="creation_solution" method="post" enctype="multipart/form-data">
    <input type="hidden" name="solution_id" value="<?php if (isset($solution['id'])) echo $solution['id']; ?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Create a solution
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Model</label>
                    <select class="form-control" id="solution_model_id" name="solution_model_id">
                        <option value="-1">-- Choose model --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Instance</label>
                    <select class="form-control" id="solution_instance_id" name="solution_instance_id">
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
                        value="<?php if (isset($solution['z'])) echo $solution['z']; ?>">
                </div>
                <div class="col-md-4">
                    <label for="solution_t">CPU time</label>
                    <input type="number" class="form-control" id="solution_t" name="solution_t" 
                        placeholder="Enter 't'" step="any" 
                        value="<?php if (isset($solution['t'])) echo $solution['t']; ?>">
                </div>
                <div class="col-md-4">
                    <label for="solution_file">File (.sol|.txt)</label>
                    <?php if (isset($solution['filename'])): ?>
                        <input type="text" class="form-control" id="solution_file" name="solution_filename" 
                            value="<?= $solution['filename'] ?>" readonly>
                    <?php else: ?>
                        <input type="file" id="solution_file" name="solution_file" accept=".sol,.txt" required>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary">Create</button>
        </div>
    </div>
</form>
