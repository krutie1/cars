{{--    Edit Client Modal--}}
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">New message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClient">
                    <div class="mb-3">
                        <label for="edit-client-number" class="col-form-label">Номер телефона:</label>
                        <input type="number" pattern="[0-9]+" name="edit-client-number" class="form-control"
                               id="edit-client-number"/>
                        <div id="edit-client-number-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-client-firstname" class="col-form-label">Имя:</label>
                        <input type="text" name="edit-client-firstname" class="form-control"
                               id="edit-client-firstname"/>
                        <div id="edit-first-name-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-client-lastname" class="col-form-label">Фамилия:</label>
                        <input type="text" name="edit-client-lastname" class="form-control"
                               id="edit-client-lastname"/>
                        <div id="edit-last-name-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-client-patronymic" class="col-form-label">Отчество:</label>
                        <input type="text" name="edit-client-patronymic" class="form-control"
                               id="edit-client-patronymic"/>
                        <div id="edit-patronymic-error" class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="edit-btn">Сохранить</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
