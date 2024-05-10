@extends('layout.layout')

@section('title', 'Посещения')

@section('content')
    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            @if(!auth()->user()->isAdmin())
                <button
                    type="button"
                    class="btn btn-primary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#createVisitModal">Новое посещение
                </button>
            @endif

            <form method="GET" action="{{ route('visit.findByPhone') }}" class="input-group mb-3">
                <input name="phone_number" id="search-input" type="text" class="form-control"
                       placeholder="Введите номер телефона"
                       aria-label="Введите номер телефона"
                       pattern="8[0-9]{10}"
                       aria-describedby="search-button">
                <button class="btn btn-outline-secondary" type="submit">Поиск</button>
            </form>

            @if(auth()->user()->isAdmin())
                <form method="GET" action="{{ route('visit.filter') }}">
                    <div class="row pb-3 justify-content-end">
                        <div class="col-lg-2 col-md-3 col-sm-6 align-self-end">
                            <button
                                type="button"
                                class="btn btn-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#createVisitModal">Новое посещение
                            </button>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-6 pt-2 pt-md-0 ">
                            <label for="start">Начало даты</label>
                            <input type="date" name="start" class="form-control"/>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-6 pt-2 pt-md-0">
                            <label for="end">Конец даты</label>
                            <input type="date" name="end" class="form-control"/>
                        </div>
                        {{--                        <div class="col-lg-2 col-md-3 col-sm-6 pt-2 pt-md-0">--}}
                        {{--                            <label for="status">Статус</label>--}}
                        {{--                            <select name="status" class="form-control">--}}
                        {{--                                <option value="not_deleted" selected>Не удаленные</option>--}}
                        {{--                                <option value="deleted">Удаленные</option>--}}
                        {{--                                <option value="all">Все</option>--}}
                        {{--                            </select>--}}
                        {{--                        </div>--}}
                        <div class="col-lg-2 col-md-3 col-sm-6 align-self-end">
                            <button type="submit" class="btn btn-primary  w-100">Фильтр</button>
                        </div>
                    </div>
                </form>

            @endif

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
                            <th>Скидка</th>
                            <th>Оплачено</th>
                            @if(auth()->user()->isAdmin())
                                <th>Дата создания</th>
                                <th>Телефон менеджера</th>
                            @endif
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($visits as $visit)
                            <tr class="{{ $visit -> payment_date == null ? 'bg-secondary text-white' : ''}}">
                                <td>{{ $visit -> id }}</td>
                                <td class="{{ $visit -> clientTrashed -> deleted_at ? 'bg-danger text-white' : ''}}">
                                    {{ $visit -> clientTrashed -> phone_number }}
                                </td>
                                <td class="{{ $visit -> clientTrashed -> deleted_at ? 'bg-danger text-white' : ''}}">
                                    {{ $visit -> clientTrashed -> last_name }}
                                    {{ $visit -> clientTrashed -> first_name }}
                                    {{ $visit -> clientTrashed -> patronymic }}
                                </td>
                                <td>{{ $visit -> comment }}</td>
                                <td>{{ $visit -> start_time -> format('H:i') }}</td>
                                <td>{{ $visit -> end_time ? $visit -> end_time -> format('H:i') : '--:--'}}</td>
                                <td>{{ $visit -> cost }}</td>
                                <td>{{ $visit -> discount }}%</td>
                                <td>{!! $visit -> totalPayments  !!}</td>
                                @if(auth()->user()->isAdmin())
                                    <td>{{ $visit->created_at->format('d-m-Y H:i') }}</td>
                                    <td>{{ $visit -> userTrashed -> phone_number }}</td>
                                @endif
                                <td class="space-around">
                                    <i title="Время завершения" class="bi bi-stopwatch-fill text-primary"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editVisitModal" data-visit="{{ $visit }}"
                                       style="cursor: pointer;"></i>

                                    @if($visit -> end_time)
                                        <i title="Оплата" class="bi bi-credit-card-fill text-success"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editVisitPaymentModal" data-visit="{{ $visit }}"
                                           style="cursor: pointer;"></i>
                                    @endif

                                    @if(!$visit -> deleted_at && !$visit -> payment_date)
                                        <i title="Удалить" class="bi bi-trash-fill text-danger"
                                           onclick="confirmDeleteVisit({{ $visit -> id }})"
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
            {{ $visits->links('pagination::bootstrap-5') }}

            @if(request()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ request('error') }}
                </div>
            @endif
        </div>
    </div>

    @include('layout.create-modals.createVisitModal')
    @include('layout.edit-modals.editVisitModal')
    @include('layout.edit-modals.editVisitPaymentModal')
@endsection

