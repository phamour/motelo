// model label list
if ($('#solution_model_id') !== undefined) {
    var model_id = $('#solution_model_id').attr('value') === '' 
        ? '-1' 
        : $('#solution_model_id').attr('value');

    $.ajax({
        url: '/getmodels',
        dataType: 'json',
        type: 'GET',
        success: function(models) {
            for (var i = 0; i < models.length; i++) {
                $('#solution_model_id').append(
                    '<option value="' + models[i].id + '">' + models[i].label + '</option>'
                );
            }
            $('#solution_model_id').val(model_id);
        }
    });
}

// model label list
if ($('#solution_instance_id') !== undefined) {
    var instance_id = $('#solution_instance_id').attr('value') === '' 
        ? '-1' 
        : $('#solution_instance_id').attr('value');

    $.ajax({
        url: '/getinstances',
        dataType: 'json',
        type: 'GET',
        success: function(instances) {
            for (var i = 0; i < instances.length; i++) {
                $('#solution_instance_id').append(
                    '<option value="' + instances[i].id + '">' + instances[i].label + '</option>'
                );
            }
            $('#solution_instance_id').val(instance_id);
        }
    });
}

// no solution checkbox
$('#solution_no_solution').on('change', function() {
    if ($(this).prop('checked')) {
        $('#solution_z').prop('disabled', true);
        $('#solution_t').prop('disabled', true);
    } else {
        $('#solution_z').prop('disabled', false);
        $('#solution_t').prop('disabled', false);
    }
});

// END /public/js/form.js
