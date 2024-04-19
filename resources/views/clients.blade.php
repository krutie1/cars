@extends('layout.layout')

@section('title', 'Клиенты')

@section('content')
    @include('layout.navigation')
    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-success mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createClientModal">Добавить клиента
            </button>
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
                        <th class="small-table-column">№</th>
                        <th>Номер телефона</th>
                        <th>ФИО</th>
                        <th>Дата создания</th>
                        <th class="small-table-column">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client -> id}}</td>
                            <td>{{ $client -> phone_number}}</td>
                            <td>{{ $client -> full_name}}</td>
                            <td>{{ $client -> created_at}}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#clientsModal" data-bs-whatever="@mdo">
                                    Р
                                </button>
                                <button onclick="confirmDelete({{ $client -> phone_number}})" id="button-delete"
                                        type="button" class="btn btn-danger">
                                    У
                                </button>
                            </td>
                        </tr>
                    @endforeach
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
    <div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientModal">Новый клиент</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{--                    <form method="POST" action="/createClient">--}}
                    <form id="createClient">
                        <div class="mb-3">
                            <label for="client-number" class="col-form-label">Введите номер телефона:</label>
                            <input type="text" name="phone_number" class="form-control" id="client_number"
                                   autocomplete="off">
                            <div id="client-number-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="client-name" class="col-form-label">Введите имя:</label>
                            <input type="text" name="full_name" class="form-control" id="client_name"
                                   autocomplete="off">
                            <div id="client-name-error" class="invalid-feedback"></div>
                        </div>
                        <div id="create-general-error" class="invalid-feedback"></div>
                        <br>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Добавить клиента</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
