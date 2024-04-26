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
                        <label for="client_id" class="col-form-label">Введите client_id:</label>
                        <input type="text" name="client_id" class="form-control" id="client_id" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="start_time_hour" class="col-form-label">Введите start_time:</label>
                        <input type="time" name="start_time_hour" class="form-control" id="start_time_hour"
                               autocomplete="off" step="">
                    </div>
                    <div class="mb-3">
                        <label for="end_time_hour" class="col-form-label">Введите end_time:</label>
                        <input type="time" name="end_time_hour" class="form-control" id="end_time_hour"
                               autocomplete="off" step="">
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="col-form-label">Введите comment:</label>
                        <input type="text" name="comment" class="form-control" id="comment" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="cost" class="col-form-label">Введите cost:</label>
                        <input type="text" name="cost" class="form-control" id="cost" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="payment_id" class="col-form-label">Введите payment_id:</label>
                        <input type="text" name="payment_id" class="form-control" id="payment_id" autocomplete="off">
                        <div id="manager-name-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="col-form-label">Введите user_id:</label>
                        <input type="text" name="user_id" class="form-control" id="user_id" autocomplete="off">
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Добавить менеджера</button>
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
