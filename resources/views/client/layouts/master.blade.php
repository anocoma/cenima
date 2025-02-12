<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title')
    </title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="description" content="Poly Cenimas" />
    <meta name="keywords" content="Poly Cenimas" />
    <meta name="author" content="" />
    <meta name="MobileOptimized" content="320" />
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('style-libs')

    <!--Template style -->
    @include('client.layouts.partials.css')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    @yield('styles')

</head>

<body>

    @php
        $not_issued = App\Models\Ticket::NOT_ISSUED;
        $expired = App\Models\Ticket::EXPIRED;
        //  dd($not_issued, $expired);

        App\Models\Ticket::query()->
        where([
            ['status', $not_issued],
            ['expiry', '<', now()]
        ])->update([
            'status' => $expired
        ]);
    @endphp

    <!-- preloader Start -->
    <div id="preloader">
        <div id="status">
            <img src="{{ asset('theme/client/images/header/horoscope.gif') }}" id="preloader_image" alt="loader">
        </div>
    </div>
    <!-- prs navigation Start -->

    @include('client.layouts.header')

    <!-- prs navigation End -->

    @yield('content')

    <!-- prs patner slider End -->
    <!-- prs Newsletter Wrapper Start -->

    @include('client.layouts.footer')

    <!-- prs footer Wrapper End -->
    <!-- st login wrapper Start -->

    <!-- st login wrapper End -->
    <!--main js file start-->
    <script>
        const APP_URL = "{{ env('APP_URL') }}";
    </script>
    @include('client.layouts.partials.js')
    <!--main js file end-->
    @vite('resources/js/public.js')
    @yield('scripts')
    @yield('script-libs')
</body>

</html>
