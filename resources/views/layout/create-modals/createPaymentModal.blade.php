{{--    Create Payment Modal    --}}
<div class="modal fade" id="createPaymentModal" tabindex="-1" aria-labelledby="createPaymentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPaymentModal">Новый менеджер</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{--                    <form method="POST" action="/createClient">--}}
                <form id="createPayment">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Введите название платежа:</label>
                        <input type="text" name="name" class="form-control" id="payment_name"
                               autocomplete="off">
                        <div id="payment-name-error" class="invalid-feedback"></div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Добавить платёж</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
