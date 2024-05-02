{{-- Create Client Modal --}}
<div class="modal fade" id="createVisitModal" tabindex="-1" aria-labelledby="createVisitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVisitModal">Новое посещение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createVisit">
                    <div class="mb-3">
                        <label for="client_id" class="col-form-label">Выберите клиента(client_id):</label>
                        <input type="text" name="client_id" class="form-control" id="client_id" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="start_time_hour" class="col-form-label">Введите начало(start_time):</label>
                        <input type="time" name="start_time_hour" class="form-control" id="start_time_hour"
                               autocomplete="off" step="">
                    </div>
                    <div class="mb-3">
                        <label for="end_time_hour" class="col-form-label">Введите конец(end_time):</label>
                        <input type="time" name="end_time_hour" class="form-control" id="end_time_hour"
                               autocomplete="off" step="">
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="col-form-label">Залог:</label>
                        <input type="text" name="comment" class="form-control" id="comment" autocomplete="off">
                    </div>
                    <!--PAYMENT-->
                    <div id="payment_fields">
                        <div class="mb-3 payment-field row">
                            <div class="col-md-6">
                                <label for="payment" class="col-form-label">Выберите тип оплаты:</label>
                                <select name="payment[]" class="form-select" id="payment">
                                    @foreach($payments as $paymentId => $paymentName)
                                        <option value="{{ $paymentId }}">{{ $paymentName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-3 align-self-end">
                                <input type="number" name="amounts[]" class="form-control amount"
                                       placeholder="Сумма">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="addPaymentField">Добавить тип оплаты
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" id="removePayment">Удалить</button>
                    
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Добавить менеджера</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
