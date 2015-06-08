$('.file_view a').on('click', function() {
    var type = $('#list_type').val();
    var filename = $(this).html();
    var url = '/getcontent/' + type + '/' + filename;
    $.ajax({
        url: url,
        dataType: 'text',
        type: 'GET',
        success: function(content) {
            $('#file_content_modal').modal();
            $('#file_content_modal .modal-body pre').html(content);
        }
    });
});

// END /public/js/list.js
