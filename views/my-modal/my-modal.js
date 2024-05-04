$(document).ready(function() {
    var modal =
        '<div id="view_modal" class="modal-dialog-scrollable modal fade" tabindex="-1" aria-hidden="true" aria-labelledby="view_modal-label">\n' +
        '<div class="modal-dialog modal-lg modal-dialog-centered">\n' +
        '<div class="modal-content">\n' +
        '<div class="modal-header">\n' +
        '<h5 id="view_modal-label" class="modal-title"></h5><h2 id="view_modal_title"></h2>\n' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>\n' +
        '<div class="modal-body">\n' +
        '<div id="view_modal_content"></div>\n' +
        '</div>\n' +
        '</div>\n' +
        '</div>\n' +
        '</div>'

    $('body').append(modal);
});

function show_modal(id, name, path) {
    $.get(
        path,
        {
            'id': id
        },
        function (data) {
            $('#view_modal_content').html(data);
            $('#view_modal_title').html(name);
            $('#view_modal').modal('show');
        }
    );
}
