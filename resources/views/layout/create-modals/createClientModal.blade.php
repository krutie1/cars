{{--    Create Client Modal--}}
<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModal">Новый клиент</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{--                    <form method="POST" action="/createClient">--}}
                <form id="createClient">
                    <div class="mb-3">
                        <label for="phone_number" class="col-form-label">Введите номер телефона:</label>
                        <input type="number" pattern="[0-9]+" name="phone_number" class="form-control"
                               id="create-phone_number"
                               autocomplete="off">
                        <div id="client-phone_number-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="col-form-label">Введите имя:</label>
                        <input type="text" name="first_name" class="form-control" id="create-first_name"
                               autocomplete="off">
                        <div id="client-first_name-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="col-form-label">Введите фамилию:</label>
                        <input type="text" name="last_name" class="form-control" id="create-last_name"
                               autocomplete="off">
                        <div id="client-last_name-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="patronymic" class="col-form-label">Введите отчество (Необязательно):</label>
                        <input type="text" name="patronymic" class="form-control" id="create-patronymic"
                               autocomplete="off">
                        <div id="client-patronymic-error" class="invalid-feedback"></div>
                    </div>
                    <div id="client-general-error" class="invalid-feedback"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Добавить клиента</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
