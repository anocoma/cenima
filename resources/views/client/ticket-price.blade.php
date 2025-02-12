@extends('client.layouts.master')

@section('title')
    Giá vé Poly {{ $cinemas->name }}
@endsection

@section('content')
    <div class="container container-ticket-price">
        <div class="title-ticket-price">
            <h3>Giá vé Rạp Poly {{ $cinemas->name }}</h3>
        </div>
        <div class="content-ticket-price">
            <table class="table table-bordered rounded align-middle table-ticket-price">

                <thead>
                    <tr class="table-header">
                        <th colspan='2' class="text-center">GIÁ THEO GHẾ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($typeSeats as $typeSeat)
                        <tr align="center">
                            <td>{{ $typeSeat->name }}</td>
                            <td>{{ number_format($typeSeat->price) }}đ</td>
                        </tr>
                    @endforeach
                </tbody>


                <thead>
                    <tr  class="table-header">
                        <th colspan='2' class="text-center">PHỤ THU</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($cinemas->surcharge > 0)
                        <tr align="center">
                            <td>Poly {{ $cinemas->name }}</td>
                            <td>{{ number_format($cinemas->surcharge) }}đ</td>
                        </tr>
                    @endif
                    @foreach ($typeRooms as $typeRoom)
                        <tr align="center">
                            <td>{{ $typeRoom->name }}</td>
                            <td>{{ number_format($typeRoom->surcharge) }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
