{{-- Create Client Modal --}}
<div class="modal fade" id="createVisitModal" tabindex="-1" aria-labelledby="createVisitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVisitModalLabel">Новое посещение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createVisit">
                    <div class="mb-3">
                        <label for="searchClient" class="col-form-label">Выбрать клиента:</label>
                        <input type="text" name="searchClient" class="form-control" id="searchClient"
                               autocomplete="off">
                        <div id="searchResults"></div>
                        <div id="visit-id-error" class="invalid-feedback"></div>
                    </div>
                    <input type="hidden" name="client_id" id="client_id_input" value="">
                    <div class="mb-3">
                        <label for="start_time_hour" class="col-form-label">Введите начало:</label>
                        <input type="time" name="start_time_hour" class="form-control" id="start_time_hour"
                               autocomplete="off" step="">
                        <div id="visit-start-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="carSelect" class="col-form-label">Предмет проката:</label>
                        <select name="car_id" class="carSelect form-control">
                            <!-- Options will be populated dynamically -->
                        </select>
                        <div id="visit-car-error" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="col-form-label">Наименование залога:</label>
                        <input type="text" name="comment" class="form-control" id="comment" autocomplete="off">
                        <div id="visit-comment-error" class="invalid-feedback"></div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Сохранить посещение</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                    <div class="mb-3 d-none">
                        <label for="visit_date" class="col-form-label">Дата посещения:</label>
                        <input type="date" name="visit_date" class="form-control" id="visit_date" autocomplete="off">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="specialDateCheck">
                        <label class="form-check-label" for="specialDateCheck">
                            Специальная дата (01/01/2023)
                        </label>
                    </div>
                    <input type="hidden" name="special_date" id="special_date_input" value="0">
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
