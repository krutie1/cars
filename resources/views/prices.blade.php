@extends('layout.layout')

@section('title', 'Тариф')

@section('content')

    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <form action="{{ route('prices.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <button class="mt-3 btn btn-primary">Отправить файл</button>
            </form>

            @if($message)
                <div class="alert {{ $success ? 'alert-success' : 'alert-danger' }} w-25 mt-3">
                    <span>{{ $message }}</span>
                </div>
            @endif

            <div class="table-responsive">
                @if($prices->isEmpty())
                    <p>Список тарифов пуст.Загрузите Excel.</p>
                @else
                    <table
                        class="mt-3 w-50 table table-bordered">
                        <thead>
                        <tr>
                            <th class="small-table-column">Минута</th>
                            <th>Стоимость</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prices as $price)
                            <tr>
                                <td>{{ $price -> minute }}</td>
                                <td>{{ $price -> cost }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
            </div>
            <br>
        </div>
    </div>

@endsection
