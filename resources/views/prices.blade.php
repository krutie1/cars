@extends('layout.layout')

@section('title', 'Тариф')

@section('content')

    @include('layout.navigation')

    <div class="main-right d-flex flex-column p-3">
        <div class="main-right__content">
            <form action="{{ route('prices.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">

                <br>
                <button class="btn btn-primary">Отправить файл</button>
            </form>

            @if($message)
                <div class="alert {{ $success ? 'alert-success' : 'alert-danger' }} w-25 mt-3">
                    <span>{{ $message }}</span>
                </div>
            @endif
        </div>
    </div>

@endsection
