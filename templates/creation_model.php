<form id="creation_model" method="post" enctype="multipart/form-data">
    <?php if (isset($data['id'])): ?>
        <input type="hidden" name="model_id" value="<?= $data['id'] ?>">
        <input type="hidden" name="_METHOD" value="PUT">
    <?php endif ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= isset($data['id']) ? 'Edit' : 'Create  a' ?> model
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <label for="model_label">Label</label>
                <input type="text" class="form-control" id="model_label" name="model_label" 
                    placeholder="Enter the label of model" required
                    value="<?php if (isset($data['label'])) echo $data['label']; ?>">
            </div>
            <div class="col-md-6">
                <label for="model_file">File (.mod|.txt)</label>
                <?php if (isset($data['filename'])): ?>
                    <input type="text" class="form-control" id="model_file" name="model_filename" 
                        value="<?= $data['filename'] ?>" readonly>
                <?php else: ?>
                    <input type="file" id="model_file" name="model_file" accept=".mod,.txt">
                    <p class="help-block">The original model file is optional for confidentiality reason.</p>
                <?php endif ?>
            </div>
        </div>
        <div class="panel-footer">
            <a href="<?= $app->urlFor('list', array('type' => 'model')) ?>" 
                class="btn btn-danger" role="button">
                Cancel
            </a>
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
