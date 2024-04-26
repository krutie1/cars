@extends('layout.layout')

@section('title', 'Посещения')

@section('content')
    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-success mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createVisitModal">Новое посещение
            </button>

            <form method="GET" action="{{ route('client.findByPhone') }}" class="input-group mb-3">
                <input name="phone_number" id="search-input" type="text" class="form-control"
                       placeholder="Введите номер"
                       aria-label="Введите номер"
                       pattern="8[0-9]{10}"
                       aria-describedby="search-button">
                <button class="btn btn-outline-secondary" type="submit" id="search-button">Поиск</button>
            </form>

            <h2 class="text-center mb-3">Список Посещений</h2>
            <div class="table-responsive">
                @if($visits->isEmpty())
                    <p>Список посещений пуст.</p>
                @else
                    <table
                        class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="small-table-column">№</th>
                            <th>Номер телефона</th>
                            <th>ФИО</th>
                            <th>Залог</th>
                            <th>Время начала</th>
                            <th>Время завершения</th>
                            <th>Общая стоимость</th>
                            {{--                            <th>Тип платежа</th>--}}
                            {{--                            @foreach($payments as $payment)--}}
                            {{--                                <th>{{ $payment->name }}</th>--}}
                            {{--                            @endforeach--}}
                            <th>Оплачено</th>
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($visits as $visit)
                            <tr>
                                <td>{{ $visit -> id }}</td>
                                <td>{{ $visit -> clientTrashed -> phone_number }}</td>
                                <td class="{{ $visit -> clientTrashed -> deleted_at ? 'bg-danger text-white' : ''}}">
                                    {{ $visit -> clientTrashed -> last_name }}
                                    {{ $visit -> clientTrashed -> first_name }}
                                    {{ $visit -> clientTrashed -> patronymic }}
                                </td>
                                <td>{{ $visit -> comment }}</td>
                                <td>{{ $visit -> start_time -> format('H:i') }}</td>
                                <td>{{ $visit -> end_time -> format('H:i')  }}</td>
                                <td>{{ $visit -> cost }}</td>
                                {{--                                @foreach($payments as $payment)--}}
                                {{--                                    <th>-</th>--}}
                                {{--                                @endforeach--}}
                                <td>{{ $visit -> paymentsTrashed -> name }}</td>
                                <td class="space-evenly">
                                    <i class="bi bi-pencil-fill text-primary" data-bs-toggle="modal"
                                       data-bs-target="#editManagerModal" data-client=""
                                       style="cursor: pointer;"></i>

                                    <i class="bi bi-trash-fill text-danger"
                                       onclick="confirmDeleteVisit({{ $visit -> id }})"
                                       style="cursor: pointer;"></i>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <br>
            {{ $visits->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @include('layout.create-modals.createVisitModal')
@endsection

