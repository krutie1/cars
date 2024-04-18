@extends('layout.layout')

@section('title', 'Клиенты')

@section('content')
    @include('layout.navigation')
    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button type="button" class="btn btn-primary mb-3">Добавить клиента</button>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Введите номер" aria-label="Введите номер"
                       aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="button-addon2">Поиск</button>
            </div>

            <h2 class="text-center mb-3">Table Border</h2>
            <div class="table-responsive">
                <table
                    class="table table-bordered">
                    <thead>
                    <tr>
                        <th data-sortable="true">№</th>
                        <th>ФИО</th>
                        <th>Номер телефона</th>
                        <th data-sortable="true">Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Елдар Елдаров</td>
                        <td>87471337514</td>
                        <td>17.04.2024</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-bs-whatever="@mdo">Редактировать
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Тест Тестов</td>
                        <td>8747777777</td>
                        <td>18.04.2024</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-bs-whatever="@fat">Редактировать
                            </button>
                        </td>
                    </tr>
                    <!-- Добавьте другие записи с кнопками редактирования здесь -->

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Recipient:</label>
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message-text"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>
@endsection
