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
                data-bs-target="#createClientModal">Добавить клиента
            </button>

            <form method="GET" action="{{ route('client.findByPhone') }}" class="input-group mb-3">
                <input name="phone_number" id="search-input" type="text" class="form-control"
                       placeholder="Введите номер телефона"
                       aria-label="Введите номер телефона"
                       pattern="8[0-9]{10}"
                       aria-describedby="search-button">
                <button class="btn btn-outline-secondary" type="submit" id="search-button">Поиск</button>
            </form>

            <h2 class="text-center mb-3">Список Клиентов</h2>
            <div class="table-responsive">
                @if($clients->isEmpty())
                    <p>Список клиентов пуст.</p>
                @else
                    <table
                        class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="small-table-column">№</th>
                            <th>Номер телефона</th>
                            <th>ФИО</th>
                            <th>Дата создания</th>
                            <th>Кол-во посещений</th>
                            <th>Последнее посещение</th>
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{ $client -> id}}</td>
                                <td>{{ $client -> phone_number}}</td>
                                <td>{{ $client -> last_name }} {{ $client->first_name }} {{ $client->patronymic }}</td>
                                <td>{{ $client -> created_at}}</td>
                                <td>
                                    {{ $client -> visits_count ?? 0 }}
                                </td>
                                <td>
                                    @if($client->lastVisit)
                                        {{ $client->lastVisit->created_at }}
                                    @else
                                        Нет посещений.
                                    @endif
                                </td>
                                <td class="space-evenly">
                                    <i class="bi bi-pencil-fill text-primary" data-bs-toggle="modal"
                                       data-bs-target="#editClientModal" data-client="{{ json_encode($client) }}"
                                       style="cursor: pointer;"></i>
                                    <i class="bi bi-trash-fill text-danger" onclick="confirmDelete({{ $client->id }})"
                                       style="cursor: pointer;"></i>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
            </div>
            <br>
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @include('layout.edit-modals.editClientModal')
    @include('layout.create-modals.createClientModal')
@endsection
