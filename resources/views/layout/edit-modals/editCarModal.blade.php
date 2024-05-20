{{-- Create Client Modal --}}
<div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCarModalLabel">Изменить предмет проката</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCar">
                    <div class="mb-3">
                        <label for="carSelect" class="col-form-label">Предмет проката:</label>
                        <select name="car_id" class=" carSelect form-control">
                            <!-- Options will be populated dynamically -->
                        </select>
                        <div id="visit-car-error" class="invalid-feedback"></div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" id="edit-car" class="btn btn-primary">Выбрать предмет</button>
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
