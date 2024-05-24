{{--    Edit Payment Modal--}}
<div class="modal fade" id="editVisitPaymentModal" tabindex="-1" aria-labelledby="editVisitPaymentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVisitPaymentModalLabel">Оплата посещения</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVisit">
                    <!--PAYMENT-->
                    <div id="payment_fields">
                        <div class="payment-field-template mb-3 row d-none">
                            <div class="col-md-6">
                                <label for="payment" class="col-form-label">Выберите тип оплаты:</label>
                                <select class="form-control">
                                    <!-- Options here -->
                                    @foreach($payments as $paymentId => $paymentName)
                                        <option value="{{ $paymentId }}">{{ $paymentName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-3 align-self-end">
                                <input type="number" class="form-control amount" placeholder="Сумма"/>
                            </div>
                        </div>
                        <div class="mb-3 payment-field row">
                            <div class="col-md-6">
                                <label for="payment" class="col-form-label">Выберите тип оплаты:</label>
                                <select name="payment[]" class="form-select" id="payment">
                                    @foreach($payments as $paymentId => $paymentName)
                                        <option value="{{ $paymentId }}">{{ $paymentName }}</option>
                                    @endforeach
                                </select>

                                <div id="visit-type-error" class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mt-3 align-self-end">
                                <input type="number" name="amounts[]" class="form-control amount"
                                       placeholder="Сумма" id="amount">

                                <div id="visit-amount-error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="payment_field_buttons">
                        <button type="button" class="btn btn-sm btn-primary" id="addPaymentField">Добавить тип оплаты
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="removePayment">Удалить</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="edit-btn-payment">Сохранить</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close-btn">Закрыть</button>
            </div>
        </div>
    </div>
</div>
