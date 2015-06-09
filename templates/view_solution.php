<input type="hidden" id="instance_url" value="<?= $data['instance_url'] ?>">
<input type="hidden" id="solution_url" value="<?= $data['url'] ?>">
<div id="test_case" class="col-md-4">
    <h3>Tested graph:</h3>
    <div class="graph_wrapper"></div>
</div>
<div id="result_graph" class="col-md-4">
    <h3 class="col-md-9">Result graph:</h3>
    <div class="col-md-3">
        <select id="odpairs" class="form-control">
            <option>OD pairs</option>
        </select>
    </div>
    <div class="graph_wrapper col-md-12"></div>
</div>
<div id="result_content" class="col-md-4">
    <h3>Result file content:</h3>
    <div id="content_wrapper">
        <textarea class="form-control" rows="22" readonly autofocus="on"></textarea>
    </div>
</div>
