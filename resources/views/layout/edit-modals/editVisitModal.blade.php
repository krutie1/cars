{{--    Edit Payment Modal--}}
<div class="modal fade" id="editVisitModal" tabindex="-1" aria-labelledby="editVisitModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVisitModalLabel">Время завершения посещения</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVisit">
                    <div class="mb-3">
                        <label for="end_time_hour" class="col-form-label">Введите конец:</label>
                        <input type="time" name="end_time_hour" class="form-control" id="end_time_hour"
                               autocomplete="off" step="">
                        <div id="visit-end-error" class="invalid-feedback"></div>
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
