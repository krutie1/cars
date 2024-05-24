@extends('layout.layout')

@section('title', 'Посещения')

@section('content')
    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createVisitModal">Новое посещение
            </button>
            <button
                type="button"
                class="btn btn-success mb-3"
                data-bs-toggle="modal"
                data-bs-target="#cashModal">Касса
            </button>

            @if(!auth()->user()->isAdmin())
                <form method="GET" action="{{ route('visit.find') }}" class="input-group mb-3">
                    <input name="search_query" id="search-input" type="text" class="form-control"
                           placeholder="Введите номер телефона, ФИО или предмет проката"
                           aria-label="Введите номер телефона, ФИО или предмет проката"
                           aria-describedby="search-button">
                    <button class="btn btn-outline-secondary" type="submit">Поиск</button>
                </form>
            @endif

            {{--            @if(auth()->user()->isAdmin())--}}
            <form method="GET" action="{{ route('visit.filter') }}">
                <input name="custom_search" id="search-input" type="text" class="form-control mb-3"
                       placeholder="Введите номер телефона, ФИО или предмет проката"
                       aria-label="Введите номер телефона, ФИО или предмет проката"
                       aria-describedby="search-button"
                       value="{{ request('custom_search') }}">
                <div class="row pb-3 justify-content-end">
                    <div class="col-lg-4 col-md-3 col-sm-6 pt-2 pt-md-0">
                        <label for="start">Начало даты</label>
                        <input type="date" name="start" class="form-control"
                               value="{{ request('start') }}"/>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-6 pt-2 pt-md-0">
                        <label for="end">Конец даты</label>
                        <input type="date" name="end" class="form-control"
                               value="{{ request('end') }}"/>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 align-self-end mt-3">
                        <input type="hidden" name="search_query"
                               value="{{ request('search_query') }}">

                        <input type="submit" name="action" value="Фильтр"
                               class="btn btn-primary w-100"/>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 align-self-end mt-3">
                        <input type="submit" name="action" value="Выгрузить"
                               class="btn btn-success w-100"/>
                    </div>
                </div>
            </form>
            {{--            @endif--}}


            @if(!empty($startDate) && !empty($endDate))
                <p class="visit-header text-center">
                    Посещения с
                    <span>{{ $startDate->format('d-m-Y')  }}</span>
                    по
                    <span>{{ $endDate->format('d-m-Y')  }}</span>
                </p>
            @else
                <h4 class="text-center">
                    <span>{{ !empty($endDate) && $endDate ? $endDate->format('d-m-Y') : now()->format('d-m-Y') }}</span>
                </h4>
            @endif

            <p class="visit-header text-center mb-3">Остаток в кассе:
                <span>{{ !empty($dayAmount) && $dayAmount ? $dayAmount : '----'}}</span>
            </p>

            <div class="table-responsive">
                @if($visits->isEmpty())
                    <p>Список посещений пуст.</p>
                @else
                    <table
                        class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Предмет проката</th>
                            <th>ФИО</th>
                            <th>Номер телефона</th>
                            <th>Наименование залога</th>
                            <th>Время начала</th>
                            <th>Время завершения</th>
                            <th>Скидка</th>
                            <th>Общая стоимость</th>
                            @if(auth()->user()->isAdmin())
                                <th>Дата создания</th>
                            @endif
                            <th>Оплачено</th>
                            <th>Имя менеджера</th>
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($visits as $visit)
                            <tr class="{{ !$visit -> end_time ? 'bg-secondary text-white' : ''}}">
                                <td>{{ $visit -> carTrashed -> name }}</td>
                                <td class="{{ $visit -> clientTrashed -> deleted_at ? 'bg-danger text-white' : ''}}">
                                    {{ $visit -> clientTrashed -> last_name }}
                                    {{ $visit -> clientTrashed -> first_name }}
                                    {{ $visit -> clientTrashed -> patronymic }}
                                </td>
                                <td class="{{ $visit -> clientTrashed -> deleted_at ? 'bg-danger text-white' : ''}}">
                                    {{ $visit -> clientTrashed -> phone_number }}
                                </td>
                                <td>{{ $visit -> comment }}</td>
                                <td>{{ $visit -> start_time -> format('H:i') }}</td>
                                <td>{{ $visit -> end_time ? $visit -> end_time -> format('H:i') : '--:--'}}</td>
                                <td>{{ $visit -> discount }}%</td>
                                <td>{{ $visit -> cost }}</td>
                                @if(auth()->user()->isAdmin())
                                    <td>{{ $visit->created_at->format('d-m-Y H:i') }}</td>
                                @endif
                                <td class="{{ $visit->cost != $visit->totalPayments ? 'bg-danger text-white' : '' }}">{!! $visit -> displayPayments  !!}</td>
                                <td>{{ $visit -> userTrashed -> name }}</td>
                                <td class="space-around">
                                    @if(!$visit -> deleted_at && !$visit -> payment_date)
                                        <i title="Редактировать машинку" class="bi bi-cart-fill text-primary"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editCarModal"
                                           data-visit="{{ $visit }}"
                                           style="cursor: pointer;"></i>
                                    @endif

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

                                    @if(!$visit -> deleted_at && !$visit -> payment_date || auth()->user()->isAdmin())
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


            <p class="visit-bottom text-center">Всего: {{ $total }}</p>
            @foreach($totalByType as $type => $amount)
                <p class="visit-bottom text-center">{{ $type }}: <span>{{ $amount }}</span></p>
            @endforeach
            {{--            <p class="visit-bottom text-center">Диляра: <span>!EachAmount</span> <span class="edit-span">edit</span></p>--}}
            {{--            <p class="visit-bottom text-center">В кассе: !Cass<span>!Amount</span></p>--}}

            @if(request()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ request('error') }}
                </div>
            @endif
        </div>
    </div>

    @include('layout.create-modals.createVisitModal')
    @include('layout.edit-modals.editVisitModal')
    @include('layout.edit-modals.editCarModal')
    @include('layout.cashModal')
    @include('layout.edit-modals.editVisitPaymentModal')
@endsection

