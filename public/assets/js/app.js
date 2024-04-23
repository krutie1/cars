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

    initEditModal();

    createClient();
    createManager();

    (function () {
        const message = alertMessage.getMessage()
        if (message) {
            alertMessage.showAlert(message);
        }
    })();
});

// create client
function createClient() {
    $('#createClient').on('submit', function (e) {
        e.preventDefault();

        $('#client_firstname').removeClass('is-invalid');
        $('#first-name-error').text('');
        $('#client_lastname').removeClass('is-invalid');
        $('#last-name-error').text('');
        $('#client_patronymic').removeClass('is-invalid');
        $('#patronymic-error').text('');
        $('#client_number').removeClass('is-invalid');
        $('#client-number-error').text('');


        var $form = $(this),
            first_name = $form.find("input[name='first_name']").val(),
            last_name = $form.find("input[name='last_name']").val(),
            patronymic = $form.find("input[name='patronymic']").val(),
            number = $form.find("input[name='phone_number']").val(),
            url = "/clients/create"

        $.ajax({
            type: "POST",
            url: url,
            data: {first_name: first_name, last_name: last_name, patronymic: patronymic, phone_number: number},
            dataType: "json",
            success: function (data) {
                alertMessage.saveMessage(data)

                window.location.reload();
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON.errors.hasOwnProperty('first_name')) {
                    $('#client_firstname').addClass('is-invalid');
                    $('#first-name-error').text(xhr.responseJSON.errors.first_name[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('last_name')) {
                    $('#client_lastname').addClass('is-invalid');
                    $('#last-name-error').text(xhr.responseJSON.errors.last_name[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('patronymic')) {
                    $('#client_patronymic').addClass('is-invalid');
                    $('#patronymic-error').text(xhr.responseJSON.errors.patronymic[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {
                    $('#client_number').addClass('is-invalid');
                    $('#client-number-error').text(xhr.responseJSON.errors.phone_number[0]);
                }
            }
        });
    });
}

// create manager
function createManager() {
    $('#createManager').on('submit', function (e) {
        e.preventDefault();

        $('#manager_name').removeClass('is-invalid');
        $('#manager-name-error').text('');
        $('#manager_password').removeClass('is-invalid');
        $('#manager-password-error').text('');
        $('#manager_number').removeClass('is-invalid');
        $('#manager-number-error').text('');

        var $form = $(this),
            name = $form.find("input[name='name']").val(),
            number = $form.find("input[name='phone_number']").val(),
            password = $form.find("input[name='password']").val(),
            url = "/managers/create"

        $.ajax({
            type: "POST",
            url: url,
            data: {name: name, phone_number: number, password: password},
            dataType: "json",
            success: function (data) {
                alertMessage.saveMessage(data)

                window.location.reload();
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON.errors.hasOwnProperty('name')) {
                    $('#manager_name').addClass('is-invalid');
                    $('#manager-name-error').text(xhr.responseJSON.errors.name[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('password')) {
                    $('#manager_password').addClass('is-invalid');
                    $('#manager-password-error').text(xhr.responseJSON.errors.password[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {
                    $('#manager_number').addClass('is-invalid');
                    $('#manager-number-error').text(xhr.responseJSON.errors.phone_number[0]);
                }
            }
        });
    });
}


function deleteClient(id) {
    $.ajax({
        url: `/clients/${id}`,
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

function confirmDelete(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        deleteClient(rowId);
    }
}

function initEditModal() {
    $('#editClientModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var client = button.data('client');

        var modalTitle = $('#editClientModal').find('.modal-title');
        var clientNumber = $('#editClientModal').find('#edit-client-number');
        var clientFirstName = $('#editClientModal').find('#edit-client-firstname');
        var clientLastName = $('#editClientModal').find('#edit-client-lastname');
        var clientPatronymic = $('#editClientModal').find('#edit-client-patronymic');

        modalTitle.text(`Редактирования клиента: ${client.first_name}`);
        clientNumber.val(client.phone_number);
        clientFirstName.val(client.first_name);
        clientLastName.val(client.last_name);
        clientPatronymic.val(client.patronymic);

        $('#edit-btn').on('click', function () {
            editClient(client.id, {
                first_name: clientFirstName.val(),
                last_name: clientLastName.val(),
                patronymic: clientPatronymic.val(),
                number: clientNumber.val()
            });
        });
    });
}

function editClient(id, client) {
    $.ajax({
        type: "PUT",
        url: `/clients/${id}`,
        data: {
            first_name: client.first_name,
            last_name: client.last_name,
            patronymic: client.patronymic,
            phone_number: client.number
        },
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON.errors.hasOwnProperty('first_name')) {
                $('#edit-client-firstname').addClass('is-invalid');
                $('#edit-first-name-error').text(xhr.responseJSON.errors.first_name[0]);
            }

            if (xhr.responseJSON.errors.hasOwnProperty('last_name')) {
                $('#edit-client-lastname').addClass('is-invalid');
                $('#edit-last-name-error').text(xhr.responseJSON.errors.last_name[0]);
            }

            if (xhr.responseJSON.errors.hasOwnProperty('patronymic')) {
                $('#edit-client-patronymic').addClass('is-invalid');
                $('#edit-patronymic-error').text(xhr.responseJSON.errors.patronymic[0]);
            }

            if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {
                $('#edit-client-number').addClass('is-invalid');
                $('#edit-client-number-error').text(xhr.responseJSON.errors.phone_number[0]);
            }
        }
    });
}
