{{--    Edit Manager Modal--}}
<div class="modal fade" id="editManagerModal" tabindex="-1" aria-labelledby="editManagerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editManagerModalLabel">Редактировать менеджера</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="edit-manager-number" class="col-form-label">Номер телефона:</label>
                        <input type="number" pattern="[0-9]+" name="phone_number" class="form-control"
                               id="edit-manager-number"/>
                        <div id="edit-manager-phone_number-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-manager-name" class="col-form-label">ФИО:</label>
                        <input type="text" name="manager-name" class="form-control"
                               id="edit-manager-name"/>
                        <div id="edit-manager-name-error" class="invalid-feedback"></div>
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
