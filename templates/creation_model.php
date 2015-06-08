<form id="creation_model" method="post" enctype="multipart/form-data">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Create a model
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <label for="model_label">Label</label>
                <input type="text" class="form-control" id="model_label" name="model_label" 
                    placeholder="Enter the label of model" required>
            </div>
            <div class="col-md-6">
                <label for="model_file">File (.mod|.txt)</label>
                <input type="file" id="model_file" name="model_file" accept=".mod,.txt">
                <p class="help-block">The original model file is optional for confidentiality reason.</p>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary">Create</button>
        </div>
    </div>
</form>
