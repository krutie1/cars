$(document).ready(function() {
    $('#exampleModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var recipient = button.data('bs-whatever');
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        var modalTitle = $('#exampleModal').find('.modal-title');
        var modalBodyInput = $('#exampleModal').find('.modal-body input');

        modalTitle.text('New message to ' + recipient);
        modalBodyInput.val(recipient);
    });
});
