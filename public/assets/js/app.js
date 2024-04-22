class AlertMessage {
    key = 'alertMessage';

    saveMessage({success, message}) {
        sessionStorage.setItem(this.key, JSON.stringify({
            type: success ? "success" : "error",
            message
        }));
    }

    getMessage() {
        const data = sessionStorage.getItem(this.key);
        sessionStorage.removeItem(this.key);

        return JSON.parse(data);
    }

    showAlert({type, message}) {
        toastr[type](message);
    }

    showSuccess(message) {
        this.showAlert({type: 'success', message});
    }

    showError(message) {
        this.showAlert({type: 'error', message});
    }
}

const alertMessage = new AlertMessage();

$(document).ready(function () {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "200",
        "hideDuration": "1000",
        "timeOut": "2000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    createClientModal();
    createClient();

    (function () {
        const message = alertMessage.getMessage()
        if (message) {
            alertMessage.showAlert(message);
        }
    })();
});

function createClient() {
    $('#createClient').on('submit', function (e) {
        e.preventDefault();

        $('#client_name').removeClass('is-invalid');
        $('#client-name-error').text('');
        $('#client_number').removeClass('is-invalid');
        $('#client-number-error').text('');


        var $form = $(this),
            name = $form.find("input[name='full_name']").val(),
            number = $form.find("input[name='phone_number']").val(),
            url = "/client"

        $.ajax({
            type: "POST",
            url: url,
            data: {full_name: name, phone_number: number},
            dataType: "json",
            success: function (data) {
                alertMessage.saveMessage(data)

                window.location.reload();
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON.errors.hasOwnProperty('full_name')) {
                    $('#client_name').addClass('is-invalid');
                    $('#client-name-error').text(xhr.responseJSON.errors.full_name[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {
                    $('#client_number').addClass('is-invalid');
                    $('#client-number-error').text(xhr.responseJSON.errors.phone_number[0]);
                }
            }
        });
    });
}

function createClientModal() {
    $('#clientsModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var client = button.data('client');

        var modalTitle = $('#clientsModal').find('.modal-title');
        var clientNumber = $('#clientsModal').find('#client-number');
        var clientName = $('#clientsModal').find('#client-name');

        modalTitle.text(`Редактирования клиента: ${client.full_name}`);
        clientNumber.val(client.phone_number);
        clientName.val(client.full_name);

        $('#edit-btn').on('click', function () {
            editClient(client.id, {name: clientName.val(), number: clientNumber.val()});
        });
    });
}

function getClient() {

}

function confirmDelete(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        deleteClient(rowId);
    }
}

function deleteClient(id) {
    $.ajax({
        url: `/client/${id}`,
        type: 'DELETE',
        responseType: 'json',
        dataType: 'json',
        success: function (data) {
            alertMessage.saveMessage(data)
            window.location.reload();
        },
        error: function (xhr, status, error) {
            alertMessage.saveMessage({
                success: false,
                message: xhr.responseJSON.message,
            });
            window.location.reload();
        }
    });
}

function editClient(id, client) {
    $.ajax({
        type: "PUT",
        url: `/client/${id}`,
        data: {full_name: client.name, phone_number: client.number},
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {

        }
    });
}
