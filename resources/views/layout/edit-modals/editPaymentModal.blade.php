{{--    Edit Payment Modal--}}
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel">Редактировать платёж</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPayment">
                    <div class="mb-3">
                        <label for="edit-payment-name" class="col-form-label">Название платежа:</label>
                        <input type="text" name="edit-payment-name" class="form-control"
                               id="edit-payment-name"/>
                        <div id="edit-payment-name-error" class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="edit-btn">Сохранить</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close-btn">Закрыть</button>
            </div>
        </div>
    </div>
</div>
