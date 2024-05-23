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
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $('.generate_password_btn').click(function () {
        const length = 6; // You can change the length of the generated password here
        const generatedPassword = generatePassword(length);
        $(this).siblings('.manager_password').val(generatedPassword);
    });

    initEditModal();
    initEditPaymentModal();
    initEditManagerModal();
    initVisitPaymentModal();
    initVisitModal();

    createClient();
    createManager();
    createVisit();
    createPayment();

    (function () {
        const message = alertMessage.getMessage()
        if (message) {
            alertMessage.showAlert(message);
        }
    })();
});

// CREATE FUNCTIONS
// create client
function createClient() {
    $('#createClient').on('submit', function (e) {
        e.preventDefault();

        $('#create-first_name').removeClass('is-invalid');
        $('#client-first_name-error').text('');
        $('#create-last_name').removeClass('is-invalid');
        $('#client-last_name-error').text('');
        $('#create-patronymic').removeClass('is-invalid');
        $('#client-patronymic-error').text('');
        $('#create-phone_number').removeClass('is-invalid');
        $('#client-phone_number-error').text('');


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
                    $('#create-first_name').addClass('is-invalid');
                    $('#client-first_name-error').text(xhr.responseJSON.errors.first_name[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('last_name')) {
                    $('#create-last_name').addClass('is-invalid');
                    $('#client-last_name-error').text(xhr.responseJSON.errors.last_name[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('patronymic')) {
                    $('#create-patronymic').addClass('is-invalid');
                    $('#client-patronymic-error').text(xhr.responseJSON.errors.patronymic[0]);
                }

                if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {
                    $('#create-phone_number').addClass('is-invalid');
                    $('#client-phone_number-error').text(xhr.responseJSON.errors.phone_number[0]);
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
                if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {

                    $('#manager_number').addClass('is-invalid');
                    $('#manager-number-error').text(xhr.responseJSON.errors.phone_number[0]);
                }
                if (xhr.responseJSON.errors.hasOwnProperty('password')) {
                    $('#manager_password').addClass('is-invalid');
                    $('#manager-password-error').text(xhr.responseJSON.errors.password[0]);
                }
            }
        });
    });
}

function createVisit() {
    $('#searchClient').on('input', function () {
        var query = $(this).val();
        if (query.length > 3) { // Minimum characters to trigger search
            $.ajax({
                url: '/clients/search-client',
                type: 'GET',
                data: {query: query},
                success: function (response) {
                    $('#searchResults').html('');
                    if (response.success && response.clients.length > 0) {
                        response.clients.forEach(function (client) {
                            var clientHtml =
                                `<div class="client-item card w-50 mt-3 mb-3 client-card" data-client=${JSON.stringify(client)} data-client-id="${client.id}">
                                    <div class="card-body">
                                        <span class="card-title">ФИО: ${client.last_name ?? ''} ${client.first_name} ${client.patronymic ?? ''}</span><br>
                                        <span class="body">Номер: ${client.phone_number}</span>
                                    </div>
                                </div>`;
                            $('#searchResults').append(clientHtml);
                        });
                    } else {
                        $('#searchResults').html('<div class="text-muted mt-3">Номер телефона не найден.</div>');
                    }
                }
            });
        } else {
            $('#searchResults').html('');
        }
    });

    $(document).on('click', '.client-item', function () {
        var clientId = $(this).data('client-id');
        var client = $(this).data('client');

        $('#searchClient').val(`${client.last_name ?? ''} ${client.first_name} ${client.patronymic ?? ''} | ${client.phone_number}`);
        $('#searchResults').html('');

        $('#client_id_input').val(clientId);
    });

    // Handle date input and checkbox interaction
    const visitDateInput = document.getElementById('visit_date');
    const specialDateCheck = document.getElementById('specialDateCheck');

    visitDateInput.addEventListener('change', function () {
        if (this.value === '2023-01-01') {
            specialDateCheck.checked = true;
        } else {
            specialDateCheck.checked = false;
        }
    });

    specialDateCheck.addEventListener('change', function () {
        if (this.checked) {
            visitDateInput.value = '2023-01-01';
        }
    });

    $('#createVisit').on('submit', function (e) {
        e.preventDefault();

        $('#searchClient').removeClass('is-invalid');
        $('#visit-id-error').text('');
        $('#start_time_hour').removeClass('is-invalid');
        $('#visit-start-error').text('');
        $('#comment').removeClass('is-invalid');
        $('#visit-comment-error').text('');


        var $form = $(this),
            client_id = $form.find("input[name='client_id']").val(),
            start_time = $form.find("input[name='start_time_hour']").val(),
            comment = $form.find("input[name='comment']").val(),
            cost = $form.find("input[name='cost']").val(),
            visit_date = $form.find("input[name='visit_date']").val(),
            user_id = $form.find("input[name='user_id']").val(),
            url = "/visits/create";

        var car_id = $form.find(".carSelect").val();

        var data = {
            client_id: client_id,
            start_time: start_time,
            comment: comment,
            cost: cost,
            user_id: user_id,
            car_id: car_id
        };

        if (visit_date) {
            data.visit_date = visit_date;
        }

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                alertMessage.saveMessage(data);

                window.location.reload();
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON.errors.hasOwnProperty('client_id')) {
                    $('#searchClient').addClass('is-invalid');
                    $('#visit-id-error').text(xhr.responseJSON.errors.client_id[0]);
                }
                if (xhr.responseJSON.errors.hasOwnProperty('comment')) {
                    $('#comment').addClass('is-invalid');
                    $('#visit-comment-error').text(xhr.responseJSON.errors.comment[0]);
                }
                if (xhr.responseJSON.errors.hasOwnProperty('start_time')) {
                    $('#start_time_hour').addClass('is-invalid');
                    $('#visit-start-error').text(xhr.responseJSON.errors.start_time[0]);
                }
            }
        });
    });

}


// create createPayment
function createPayment() {
    $('#createPayment').on('submit', function (e) {
        e.preventDefault();

        $('#payment_name').removeClass('is-invalid');
        $('#payment-name-error').text('');

        var $form = $(this),
            name = $form.find("input[name='name']").val(),
            url = "/payments/create"

        $.ajax({
            type: "POST",
            url: url,
            data: {
                name: name
            },
            dataType: "json",
            success: function (data) {
                alertMessage.saveMessage(data)

                window.location.reload();
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON.errors.hasOwnProperty('name')) {
                    $('#payment_name').addClass('is-invalid');
                    $('#payment-name-error').text(xhr.responseJSON.errors.name[0]);
                }
            }
        });
    });
}

//confirming remove
function confirmDelete(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        deleteClient(rowId);
    }
}

function confirmDeletePayment(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        deletePayment(rowId);
    }
}

function confirmDeleteManager(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        deleteManager(rowId);
    }
}

function confirmDeleteVisit(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        deleteVisit(rowId);
    }
}

function restorePayment(id) {
    $.ajax({
        url: `/payments/${id}/restore`,
        type: 'POST',
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

// DELETE FUNCTIONS
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

function deletePayment(id) {
    $.ajax({
        url: `/payments/${id}`,
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

function deleteManager(id) {
    $.ajax({
        url: `/managers/${id}`,
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

function deleteVisit(id) {
    $.ajax({
        url: `/visits/${id}`,
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

function generatePassword(length) {
    const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
    const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numericChars = '0123456789';
    // const specialChars = '!@#$%^&*()-=_+[]{}|;:,.<>?';
    // const specialChars = '!@#$%^&*()-=_+[]{}|;:,.<>?';
    const allChars = lowercaseChars + uppercaseChars + numericChars;

    let password = '';
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * allChars.length);
        password += allChars[randomIndex];
    }
    return password;
}

// EDIT FUNCTIONS
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

        modalTitle.text(`Редактирование клиента: ${client.first_name}`);
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

function initEditPaymentModal() {
    $('#editPaymentModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var payment = button.data('payment');

        var modalTitle = $('#editPaymentModal').find('.modal-title');
        var paymentName = $('#editPaymentModal').find('#edit-payment-name');

        modalTitle.text(`Редактирование платежа: ${payment.name}`);
        paymentName.val(payment.name);

        $('#edit-btn').on('click', function () {
            editPayment(payment.id, {
                name: paymentName.val(),
            });
        });
    });
}

function initEditManagerModal() {
    var modalTitle = $('#editManagerModal').find('.modal-title');
    var managerPhone = $('#editManagerModal').find('#edit-manager-number');
    var managerName = $('#editManagerModal').find('#edit-manager-name');
    var managerPassword = $('#editManagerModal').find('#edit-manager-password');

    $('#editManagerModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var manager = button.data('manager');


        modalTitle.text(`Редактирование менеджера: ${manager.name}`);
        managerPhone.val(manager.phone_number);
        managerName.val(manager.name);
        managerPassword.val(manager.password);

        $('#edit-btn').on('click', function () {
            editManager(manager.id, {
                phone_number: managerPhone.val(),
                name: managerName.val(),
                password: managerPassword.val()
            });
        });
    });
}

function initVisitModal() {
    $('#editVisitModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var visit = button.data('visit');

        $('#edit-btn').on('click', function () {
            editVisit(visit.id);
        });
    });

    $('#createVisitModal').on('show.bs.modal', function (event) {
        fetchCarsAndPopulateOptions();
    });

    $('#editCarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var visit = button.data('visit');

        fetchCarsAndPopulateOptions();

        $('#edit-car').on('click', function () {
            event.preventDefault();
            updateCars(visit.id);
        });
    });
}

function fetchCarsAndPopulateOptions() {
    $.ajax({
        url: '/cars',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            var cars = response.cars;

            // Clear existing options
            $('.carSelect').empty();

            // Append each car as an option
            cars.forEach(function (car) {
                $('.carSelect').append('<option value="' + car.id + '">' + car.name + '</option>');
            });
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function updateCars(visitId) {
    var $form = $('#editCar');
    var carId = $form.find(".carSelect").val();

    $.ajax({
        type: "PUT",
        url: `/cars/${visitId}`,
        data: {
            car_id: carId
        },
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON.errors.hasOwnProperty('end_time')) {
                $('#end_time_hour').addClass('is-invalid');
                $('#visit-end-error').text(xhr.responseJSON.errors.end_time[0]);
            }
        }
    });
}

//edit visit
function editVisit(id) {
    $('#end_time_hour').removeClass('is-invalid');
    $('#visit-end-error').text('');

    var $form = $('#editVisit');
    var end_time = $form.find("input[name='end_time_hour']").val();

    $.ajax({
        type: "PUT",
        url: `/visits/${id}`,
        data: {
            end_time: end_time
        },
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON.errors.hasOwnProperty('end_time')) {
                $('#end_time_hour').addClass('is-invalid');
                $('#visit-end-error').text(xhr.responseJSON.errors.end_time[0]);
            }
        }
    });
}


function initVisitPaymentModal() {
    $('#addPaymentField').click(function () {
        var paymentField = $('#payment_fields .payment-field').first().clone();
        paymentField.find('select').val('');
        paymentField.find('.amount').val('');
        paymentField.appendTo('#payment_fields');
    });

    $('#removePayment').click(function () {
        const $paymentFields = $('#payment_fields .payment-field');

        // Check if there is more than one payment field
        if ($paymentFields.length > 1) {
            // Remove the last payment field
            $paymentFields.last().remove();
        }
    });

    $('#editVisitPaymentModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var visit = button.data('visit');


        $('#edit-btn-payment').on('click', function () {
            var payments = [];
            var amounts = [];

            $('.payment-field').each(function () {
                var paymentType = $(this).find('select').val();
                var amount = $(this).find('.amount').val();

                if (paymentType && amount) {
                    payments.push(paymentType);
                    amounts.push(amount);
                }
            });

            editVisitPayment(visit.id, payments, amounts);
        });
    });
}

//edit visit
function editVisitPayment(id, payments, amounts) {
    $('#payment').removeClass('is-invalid');
    $('#visit-type-error').text('');
    $('#amount').removeClass('is-invalid');
    $('#visit-amount-error').text('');

    $.ajax({
        type: "PUT",
        url: `/visits/payment/${id}`,
        data: {
            payment_types: payments,
            payment_amounts: amounts,
        },
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON.errors.hasOwnProperty('payment_types')) {
                $('#payment').addClass('is-invalid');
                $('#visit-type-error').text(xhr.responseJSON.errors.payment_amounts[0]);
            }
            if (xhr.responseJSON.errors.hasOwnProperty('start_time')) {
                $('#start_time_hour').addClass('is-invalid');
                $('#visit-start-error').text(xhr.responseJSON.errors.start_time[0]);
            }
        }
    });
}


// edit client
function editClient(id, client) {
    $('#edit-client-firstname').removeClass('is-invalid');
    $('#edit-first-name-error').text('');

    $('#edit-client-lastname').removeClass('is-invalid');
    $('#edit-last-name-error').text('');

    $('#edit-client-patronymic').removeClass('is-invalid');
    $('#edit-patronymic-error').text('');

    $('#edit-client-number').removeClass('is-invalid');
    $('#edit-client-number-error').text('');

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

// edit payment
function editPayment(id, payment) {
    $.ajax({
        type: "PUT",
        url: `/payments/${id}`,
        data: {
            name: payment.name,
        },
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON.errors.hasOwnProperty('name')) {
                $('#edit-payment-name').addClass('is-invalid');
                $('#edit-payment-name-error').text(xhr.responseJSON.errors.name[0]);
            }
        }
    });
}

// edit payment
function editManager(id, manager) {
    $.ajax({
        type: "PUT",
        url: `/managers/${id}`,
        data: {
            phone_number: manager.phone_number,
            name: manager.name,
            password: manager.password,
        },
        success: function (data) {
            alertMessage.saveMessage(data)

            window.location.reload();
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON.errors.hasOwnProperty('name')) {
                $('#edit-manager-name').addClass('is-invalid');
                $('#edit-manager-name-error').text(xhr.responseJSON.errors.name[0]);
            }

            if (xhr.responseJSON.errors.hasOwnProperty('phone_number')) {
                $('#edit-manager-number').addClass('is-invalid');
                $('#edit-manager-phone_number-error').text(xhr.responseJSON.errors.phone_number[0]);
            }

            if (xhr.responseJSON.errors.hasOwnProperty('password')) {
                $('#edit-manager-password').addClass('is-invalid');
                $('#edit-manager-password-error').text(xhr.responseJSON.errors.password[0]);
            }
        }
    });
}
