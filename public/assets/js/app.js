$(document).ready(function() {
    $('#clientsModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var recipient = button.data('bs-whatever');
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        var modalTitle = $('#clientsModal').find('.modal-title');
        var modalBodyInput = $('#clientsModal').find('.modal-body input');

        modalTitle.text('New message to ' + recipient);
        modalBodyInput.val(recipient);
    });
});

$(document).ready(function() {
    createClient();
});

function createClient() {
    $('#createClient').on('submit', function(e) {
        e.preventDefault();

        var $form = $( this ),
            name = $form.find( "input[name='full_name']" ).val(),
            number = $form.find( "input[name='phone_number']" ).val(),
            url = "/createClient"

        $.ajax({
            type: "POST",
            url: url,
            data: { full_name: name, phone_number: number },
            dataType: "json",
            success: function(data) {
                console.log(data);
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseJSON.errors);
            }
        });


    });
}
