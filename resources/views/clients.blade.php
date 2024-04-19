@extends('layout.layout')

@section('title', 'Клиенты')

@section('content')
    @include('layout.navigation')
    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createClientModal">Добавить клиента</button>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Введите номер" aria-label="Введите номер"
                       aria-describedby="search-button">
                <button class="btn btn-outline-secondary" type="button" id="search-button">Поиск</button>
            </div>

            <h2 class="text-center mb-3">Список Клиентов</h2>
            <div class="table-responsive">
                <table
                    class="table table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Номер телефона</th>
                        <th>ФИО</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>87471337514</td>
                        <td>Елдар Елдаров</td>
                        <td>17.04.2024</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#clientsModal" data-bs-whatever="@mdo">Редактировать
                            </button>
                            <button onclick="confirmDelete(2)"  id="button-delete" type="button" class="btn btn-danger">
                                Удалить
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>8747777777</td>
                        <td>Тест Тестов</td>
                        <td>18.04.2024</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#clientsModal" data-bs-whatever="@fat">Редактировать
                            </button>

                            <button id="button-delete" onclick="confirmDelete(3)" type="button" class="btn btn-danger">
                                Удалить
                            </button>
                        </td>
                    </tr>
                    <!-- Добавьте другие записи с кнопками редактирования здесь -->

                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{--    Edit Client Modal--}}
    <div class="modal fade" id="clientsModal" tabindex="-1" aria-labelledby="clientsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientsModalLabel">New message</h5>
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
{{--    Create Client Modal--}}
    <div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientModal">Новый клиент</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
{{--                    <form method="POST" action="/createClient">--}}
                    <form id="/client/create">
                        <div class="mb-3">
                            <label for="client-name" class="col-form-label">Введите имя:</label>
                            <input type="text" name="full_name" class="form-control" id="client_name" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="client-number" class="col-form-label">Введите номер телефона:</label>
                            <input type="text" name="phone_number" class="form-control" id="client_number" autocomplete="off">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Добавить клиента</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
