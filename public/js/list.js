$('.file_view a').on('click', function() {
    $('#file_content_modal').modal();

    var type = $('#list_type').val();
    var filename = $(this).html();
    var url = '/getcontent/' + type + '/' + filename;
    $.ajax({
        url: url,
        dataType: 'text',
        type: 'GET',
        success: function(content) {
            $('#file_content_modal .loading_img').hide();
            $('#file_content_modal .modal-body pre').html(content).show();
        }
    });
});

// END /public/js/list.js
