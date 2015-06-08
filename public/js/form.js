// model label list
if ($('#solution_model_id') !== undefined) {
    $.ajax({
        url: '/getmodels',
        dataType: 'json',
        type: 'GET',
        success: function(models) {
            $.each(models, function(i, model) {
                $('#solution_model_id').append(
                    '<option value="' + model.id + '">' + model.label + '</option>'
                );
            });
        }
    });
}

// model label list
if ($('#solution_instance_id') !== undefined) {
    $.ajax({
        url: '/getinstances',
        dataType: 'json',
        type: 'GET',
        success: function(instances) {
            $.each(instances, function(i, instance) {
                var label = 'v' + instance.nb_nodes + '_e' + instance.nb_edges;
                if (instance.blockage_o !== undefined && instance.blockage_d !== undefined) {
                    label += '_o' + instance.blockage_o + '_d' + instance.blockage_d;
                }
                $('#solution_instance_id').append(
                    '<option value="' + instance.id + '">' + label + '</option>'
                );
            });
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
