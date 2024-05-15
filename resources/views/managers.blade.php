@extends('layout.layout')

@section('title', 'Менеджеры')

@section('content')
    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createManagerModal">Добавить менеджера
            </button>

            <form method="GET" action="{{ route('manager.find') }}" class="input-group mb-3">
                <input name="search_query" id="search-input" type="text" class="form-control"
                       placeholder="Введите номер телефона или ФИО"
                       aria-label="Введите номер телефона или ФИО"
                       aria-describedby="search-button">
                <button class="btn btn-outline-secondary" type="submit" id="search-button">Поиск</button>
            </form>

            <h4 class="text-center mb-3">Список Менеджеров</h4>
            <div class="table-responsive">
                @if($managers->isEmpty())
                    <p>Список менеджеров пуст.</p>
                @else
                    <table
                        class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ФИО</th>
                            <th>Номер телефона</th>
                            <th>Роли</th>
                            <th>Дата создания</th>
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($managers as $manager)
                            <tr>
                                <td>{{ $manager-> name }}</td>
                                <td>{{ $manager -> phone_number}}</td>
                                <td>{{ implode(', ', $manager->roles) }}</td>
                                <td>{{ $manager -> created_at->format('d-m-Y H:i')}}</td>
                                <td class="space-evenly">
                                    <i title="Редактировать" class="bi bi-pencil-fill text-primary"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editManagerModal" data-manager="{{ json_encode($manager) }}"
                                       style="cursor: pointer;"></i>
                                    @if(!in_array('admin', $manager -> roles))
                                        <i title="Удалить" class="bi bi-trash-fill text-danger"
                                           onclick="confirmDeleteManager({{ $manager->id }})"
                                           style="cursor: pointer;"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
            </div>
            <br>
            {{ $managers->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @include('layout.create-modals.createManagerModal')
    @include('layout.edit-modals.editManagerModal')
@endsection

