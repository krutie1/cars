{{--    Create Manager Modal--}}
<div class="modal fade" id="createManagerModal" tabindex="-1" aria-labelledby="createManagerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createManagerModal">Новый менеджер</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{--                    <form method="POST" action="/createClient">--}}
                <form id="createManager">
                    <div class="mb-3">
                        <label for="phone_number" class="col-form-label">Введите номер телефона:</label>
                        <input type="number" pattern="[0-9]+" name="phone_number" class="form-control"
                               id="manager_number"
                               autocomplete="off">
                        <div id="manager-number-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Введите ФИО:</label>
                        <input type="text" name="name" class="form-control" id="manager_name"
                               autocomplete="off">
                        <div id="manager-name-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="col-form-label">Пароль:</label>
                        <input type="password" name="password" class="form-control" id="manager_password"
                               autocomplete="new-password">
                        <div id="manager-password-error" class="invalid-feedback"></div>
                    </div>
            </div>
            <div id="create-manager-general-error" class="invalid-feedback"></div>
            <br>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Добавить менеджера</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
