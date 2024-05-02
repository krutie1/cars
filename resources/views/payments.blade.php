@extends('layout.layout')

@section('title', 'Платежи')

@section('content')
    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <button
                type="button"
                class="btn btn-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#createPaymentModal">Добавить платёж
            </button>

            <h2 class="text-center mb-3">Список Платежей</h2>
            <div class="table-responsive">
                @if($payments->isEmpty())
                    <p>Список платежей пуст.</p>
                @else
                    <table
                        class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="small-table-column">№</th>
                            <th>Название платежа</th>
                            <th class="small-table-column">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr class="{{ $payment -> deleted_at ? 'bg-danger text-white' : ''}}">
                                <td>{{ $payment -> id}}</td>
                                <td>{{ $payment -> name }}</td>
                                <td class="space-evenly">
                                    @if($payment->deleted_at)
                                        <i class="bi bi-arrow-clockwise text-success"
                                           onclick="restorePayment({{ $payment->id }})"
                                           style="cursor: pointer;"></i>
                                    @else
                                        <i class="bi bi-pencil-fill text-primary" data-bs-toggle="modal"
                                           data-bs-target="#editPaymentModal" data-payment="{{ json_encode($payment) }}"
                                           style="cursor: pointer;"></i>
                                        <i class="bi bi-trash-fill text-danger"
                                           onclick="confirmDeletePayment({{ $payment->id }})"
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
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @include('layout.create-modals.createPaymentModal')
    @include('layout.edit-modals.editPaymentModal')
@endsection

