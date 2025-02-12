@extends('client.layouts.master')

@section('title')
    Giá vé Poly {{ $cinemas->name }}
@endsection

@section('content')
    <div class="container-ticket-price">
        <h1 class="ticket-price">BẢNG GIÁ VÉ - {{ $cinemas->branch->name }} - {{ $cinemas->name }}</h1>
        <table class="table table-bordered rounded align-middle">
            <thead>
                <tr class="table-header">
                    <th colspan='2' class="text-center">GIÁ THEO GHẾ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($typeSeats as $typeSeat)
                    <tr>
                        <td>{{ $typeSeat->name }}</td>
                        <td>{{ number_format($typeSeat->price) }}đ</td>
                    </tr>
                @endforeach
            </tbody>
            <thead>
                <tr class="table-header">
                    <th colspan='2' class="text-center">PHỤ THU</th>
                </tr>
            </thead>
            <tbody>
                @if ($cinemas->surcharge > 0)
                    <!-- Kiểm tra phụ phí của rạp chiếu -->
                    <tr>
                        <td>Poly {{ $cinemas->name }}</td>
                        <td>{{ number_format($cinemas->surcharge) }}đ</td>
                    </tr>
                @endif
                @foreach ($typeRooms as $typeRoom)
                    <tr>
                        <td>{{ $typeRoom->name }}</td>
                        <td>{{ number_format($typeRoom->surcharge) }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1.ticket-price {
            font-size: 20px;
            text-align: center;
            margin-bottom: 40px;
            margin-top: 50px;
            background: linear-gradient(145deg, #ff9966, #ff5e62);
            padding: 20px;
            color: white;
            border-radius: 25px;
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.2), -10px -10px 30px rgba(255, 255, 255, 0.1);
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .container-ticket-price {
            width: 60%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(25px);
            padding: 60px;
            margin-bottom: 40px;
            border-radius: 30px;
            box-shadow: 15px 15px 40px rgba(0, 0, 0, 0.15), -15px -15px 40px rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 20px;
        }

        th,
        td {
            padding: 22px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.05), -10px -10px 20px rgba(255, 255, 255, 0.6);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        th {
            background: linear-gradient(145deg, #ff9966, #ff5e62);
            color: white;
            font-weight: bold;
            border: none;
            text-transform: uppercase;
        }

        tr:hover td {
            background-color: #ffcccb;
            transform: translateY(-10px);
            box-shadow: 12px 12px 30px rgba(0, 0, 0, 0.1), -12px -12px 30px rgba(255, 255, 255, 0.3);
        }

        td {
            color: #444;
        }

        tr:nth-child(even) td {
            background-color: rgba(247, 247, 247, 0.7);
        }

        .back-button {
            display: inline-block;
            margin-top: 40px;
            padding: 15px 35px;
            background: linear-gradient(145deg, #ff9966, #ff5e62);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 12px 12px 40px rgba(0, 0, 0, 0.3), -12px -12px 40px rgba(255, 255, 255, 0.2);
        }

        .back-button:hover {
            background: linear-gradient(145deg, #ff5e62, #ff9966);
            box-shadow: 15px 15px 45px rgba(0, 0, 0, 0.35), -15px -15px 45px rgba(255, 255, 255, 0.25);
            transform: translateY(-8px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container-ticket-price {
                width: 85%;
            }

            h1.ticket-price {
                font-size: 20px;
            }

            th,
            td {
                font-size: 16px;
                padding: 18px;
            }
        }
    </style>
@endsection
