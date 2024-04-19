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
            url = "/client/create"

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

function getClient() {

}

function confirmDelete(rowId) {
    if (confirm("Are you sure you want to delete this row?")) {
        // User confirmed, delete the row
        deleteClient(rowId);
    }
}

function deleteClient(id) {
    $.ajax({
        url: '/client/' + id, // Replace '/clients/' with your actual delete endpoint
        type: 'DELETE',
        responseType: 'json',
        dataType: 'json',
        success: function(response) {
            console.log('Client deleted successfully:', response.client);
            // Handle success response here (e.g., update UI, show message)
        },
        error: function(xhr, status, error) {
            console.error('Failed to delete client:', error);
            // Handle error response here (e.g., show error message)
        }
    });
}
