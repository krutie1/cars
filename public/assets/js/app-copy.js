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

class ErrorHandler {
    constructor(formId, errorPrefix) {
        this.formId = formId;
        this.errorPrefix = errorPrefix;
    }

    displayFormErrors(errors) {
        this.clearErrors();

        for (const field in errors) {
            const inputField = $(`#${this.formId} input[name='${field}']`);
            if (inputField.length) {
                inputField.addClass('is-invalid');
                $(`#${this.errorPrefix}-${field}-error`).text(errors[field][0]);

                // console.log(`#${this.errorPrefix}-${field}-error`)
            }
        }
        // console.log(this.formId)
    }

    displayGeneralError(message) {
        alertMessage.saveMessage({
            success: false,
            message: message,
        });
        window.location.reload();
    }

    clearErrors() {
        $(`#${this.formId} input`).removeClass('is-invalid');
        $(`[id^='${this.errorPrefix}']`).text('');
    }

    handleError(xhr) {
        if (xhr.responseJSON.errors) {
            this.displayFormErrors(xhr.responseJSON.errors);
        } else if (xhr.responseJSON.message) {
            this.displayGeneralError(xhr.responseJSON.message);
        }
    }
}

class SuccessHandler {
    constructor() {
    }

    handleSuccess(data) {
        alertMessage.saveMessage(data)
        window.location.reload();
    }
}

class Entity {
    constructor(url) {
        this.url = url;
        this.errorHandler = new ErrorHandler(this.getFormId(), this.getPrefix())
        this.successHandler = new SuccessHandler();
    }

    save(data) {
        $.ajax({
            type: "POST",
            url: `/${this.url}/create`,
            data: data,
            dataType: "json",
            success: this.successHandler.handleSuccess.bind(this.successHandler),
            error: (xhr) => {
                this.errorHandler.handleError(xhr);
            }
        });
    }

    update(id, data) {
        $.ajax({
            type: "PUT",
            url: `${this.url}/${id}`,
            data: data,
            dataType: "json",
            success: this.successHandler.handleSuccess.bind(this.successHandler),
            error: (xhr) => {
                this.errorHandler.handleError(xhr);
            }
        });
    }

    delete(id, callback) {
        $.ajax({
            type: "DELETE",
            url: `${this.url}/${id}`,
            dataType: "json",
            success: this.successHandler.handleSuccess.bind(this.successHandler),
            error: this.errorHandler.handleError.bind(this.errorHandler),
        });
    }

    getFormId() {
        return '';
    }

    getPrefix() {
        return ''
    }
}

class Manager extends Entity {
    constructor() {
        super('managers');
    }

    createManager(data) {
        this.save(data);
    }

    editManager(id, data) {
        this.update(id, data);
    }

    deleteManager(id) {
        this.delete(id);
    }

    getFormId() {
        return 'createManager';
    }

    getPrefix() {
        return ''
    }
}

class Client extends Entity {
    constructor() {
        super('clients');
    }

    createClient(data) {
        this.save(data);
    }

    editClient(id, data) {
        this.update(id, data);
    }

    deleteClient(id) {
        this.delete(id);
    }

    getFormId() {
        return 'createClient';
    }

    getErrorPrefix() {
        return 'client';
    }
}

const alertMessage = new AlertMessage();
const manager = new Manager();
// const client = new Client();

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

    $('#createManager').on('submit', function (e) {
        e.preventDefault();

        const data = {
            name: $(this).find("input[name='name']").val(),
            phone_number: $(this).find("input[name='phone_number']").val(),
            password: $(this).find("input[name='password']").val(),
        };

        manager.createManager(data);
    });

    $('#editManagerModal').on('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract info from data-bs-* attributes
        var manager = button.data('manager');

        var modalTitle = $(this).find('.modal-title');
        var managerPhone = $(this).find('#edit-phone_number');
        var managerName = $(this).find('#edit-name');

        modalTitle.text(`Редактирование менеджера: ${manager.name}`);
        managerPhone.val(manager.phone_number);
        managerName.val(manager.name);

        $('#editManager').on('submit', function (e) {
            e.preventDefault()

            const data = {
                name: $(this).find("input[name='name']").val(),
                phone_number: $(this).find("input[name='phone_number']").val(),
                password: $(this).find("input[name='password']").val(),
            };

            manager.editManager(data);
        });
    });

    // $('#createClient').on('submit', function (e) {
    //     e.preventDefault();
    //
    //     const data = {
    //         phone_number: $(this).find("input[name='phone_number']").val(),
    //         first_name: $(this).find("input[name='first_name']").val(),
    //         last_name: $(this).find("input[name='last_name']").val(),
    //         patronymic: $(this).find("input[name='patronymic']").val(),
    //     };
    //
    //     client.createClient(data);
    // });

    (function () {
        const message = alertMessage.getMessage()
        if (message) {
            alertMessage.showAlert(message);
        }
    })();
});

function confirmDeleteManager(rowId) {
    if (confirm("Вы уверены что хотите удалить запись?")) {
        manager.deleteManager(rowId);
    }
}
