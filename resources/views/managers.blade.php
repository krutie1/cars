@extends('layout.layout')

@section('title', 'Менеджеры')

@section('content')
    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-success mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createManagerModal">Добавить менеджера
            </button>

            <form method="GET" action="{{ route('client.findByPhone') }}" class="input-group mb-3">
                <input name="phone_number" id="search-input" type="text" class="form-control"
                       placeholder="Введите номер"
                       aria-label="Введите номер"
                       pattern="8[0-9]{10}"
                       aria-describedby="search-button">
                <button class="btn btn-outline-secondary" type="submit" id="search-button">Поиск</button>
            </form>

            <h2 class="text-center mb-3">Список Клиентов</h2>
            <div class="table-responsive">
                @if($managers->isEmpty())
                    <p>Список клиентов пуст.</p>
                @else
                    <table
                        class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="small-table-column">№</th>
                            <th>Номер телефона</th>
                            <th>ФИО</th>
                            <th>Роли</th>
                            <th>Дата создания</th>
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($managers as $manager)
                            <tr>
                                <td>{{ $manager -> id}}</td>
                                <td>{{ $manager -> phone_number}}</td>
                                <td>{{ $manager-> name }}</td>
                                <td>{{ implode(', ', $manager->roles) }}</td>
                                <td>{{ $manager -> created_at}}</td>
                                <td class="space-evenly">
                                    <i class="bi bi-pencil-fill text-primary" data-bs-toggle="modal"
                                       data-bs-target="#editManagerModal" data-client="{{ json_encode($manager) }}"
                                       style="cursor: pointer;"></i>
                                    @if(!in_array('admin', $manager -> roles))
                                        <i class="bi bi-trash-fill text-danger"
                                           onclick="confirmDelete({{ $manager->id }})"
                                           style="cursor: pointer;"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    {{ $managers->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>

    @include('layout.createManagerModal')
@endsection

