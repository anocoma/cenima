@extends('admin.layouts.master')

@section('title')
    Tổng quan
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">

                    </div>
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                <div class="flex-grow-1">

                                    <h4 class="fs-16 mb-1">Thống kê trên toàn hệ thống
                                    </h4>
                                    <p class="text-muted mb-0">Thống kê tổng quan trên toàn hệ thống Poly Cinemas.</p>
                                </div>
                            </div>
                    <!--end col-->
                </div>
                <!--end row-->
                @php
                    function getClass($value)
                    {
                        if ($value > 0) {
                            return 'text-success';
                        } elseif ($value < 0) {
                            return 'text-danger';
                        } else {
                            return 'text-muted';
                        }
                    }
                @endphp
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Doanh thu</p>
                                        <h3 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="{{ $ticket->total_revenue }}">0</span>VND</h3>
                                        <p class="mb-0 text-muted"><span
                                                class="badge bg-light {{ getClass($revenuePc) }} mb-0"> <i
                                                    class="ri-arrow-up-line align-middle"></i> {{ $revenuePc }} % </span>
                                            so với tháng

                                            trước</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                                <i class="bx bx-dollar-circle text-success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Số vé bán ra</p>
                                        <h3 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="{{ $ticket->total_sales }}">0</span> Vé</h3>
                                        <p class="mb-0 text-muted"><span
                                                class="badge bg-light {{ getClass($salesPc) }} mb-0"> <i
                                                    class="ri-arrow-up-line align-middle"></i> {{ $salesPc }} % </span>
                                            so với tháng
                                            trước</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                                <i class=" ri-ticket-2-line text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->

                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Khách hàng mới</p>
                                        <h3 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="{{ $user }}">0</span></h3>
                                        <p class="mb-0 text-muted"><span
                                                class="badge bg-light {{ getClass($userPc) }} mb-0"> <i
                                                    class="ri-arrow-up-line align-middle"></i> {{ $userPc }} %
                                            </span>
                                            so với tháng
                                            trước</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                <i class="bx bx-user-circle text-warning"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->


                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Suất chiếu</p>
                                        <h3 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="{{ $showtime }}">0</span></h4>
                                            <p class="mb-0 text-muted"><span
                                                    class="badge bg-light {{ getClass($showtimePc) }}  mb-0"> <i
                                                        class="ri-arrow-up-line align-middle"></i> {{ $showtimePc }} %
                                                </span> so với tháng
                                                trước</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                <i class="ri-slideshow-3-line text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div>

                    </div><!-- end col -->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Doanh thu</h4>

                                <div class="flex-shrink-0">

                                <select id="year-select" class="form-select form-select-sm">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" @selected($year == now()->year)>
                                            Năm: {{ $year }}</option>

                                    @endforeach

                                </select>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-header p-0 border-0 bg-light-subtle">
                                <div class="row g-0 text-center">
                                    <div class="col-12 col-sm-12">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <p class="text-muted mb-0">Tổng doanh thu</p>
                                            <h5 class="mb-1"><span class="counter-value" id='total-revenue-year'
                                                    data-target="{{ $data['total_year_revenue'][now()->year] ?? 0 }}">0</span>
                                                VND
                                            </h5>

                                        </div>
                                    </div>

                                </div>
                            </div><!-- end card header -->

                            <div class="card-body p-0 pb-2">
                                <div class="w-100">
                                    {{-- <div id="customer_impression_charts"
                                        data-colors='["--vz-warning", "--vz-success", "--vz-danger"]' class="apex-charts"
                                        dir="ltr"></div> --}}
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-4">
                        <!-- card -->
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Tỷ lệ đặt ghế</h4>
                            </div><!-- end card header -->

                            <!-- card body -->
                            <div class="card-body">
                                <!-- Phần tử canvas cho biểu đồ -->
                                <canvas id="seatChart"></canvas>

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>

                {{-- <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Best Selling Products</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="fw-semibold text-uppercase fs-12">Sort by:
                                            </span><span class="text-muted">Today<i
                                                    class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Yesterday</a>
                                            <a class="dropdown-item" href="#">Last 7 Days</a>
                                            <a class="dropdown-item" href="#">Last 30 Days</a>
                                            <a class="dropdown-item" href="#">This Month</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/products/img-1.png"
                                                                alt="" class="img-fluid d-block" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-reset">Branded
                                                                    T-Shirts</a></h5>
                                                            <span class="text-muted">24 Apr
                                                                2021</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$29.00</h5>
                                                    <span class="text-muted">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">62</h5>
                                                    <span class="text-muted">Orders</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">510</h5>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$1,798</h5>
                                                    <span class="text-muted">Amount</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/products/img-2.png"
                                                                alt="" class="img-fluid d-block" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-reset">Bentwood
                                                                    Chair</a></h5>
                                                            <span class="text-muted">19 Mar
                                                                2021</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$85.20</h5>
                                                    <span class="text-muted">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">35</h5>
                                                    <span class="text-muted">Orders</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal"><span
                                                            class="badge bg-danger-subtle text-danger">Out
                                                            of stock</span> </h5>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$2982</h5>
                                                    <span class="text-muted">Amount</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/products/img-3.png"
                                                                alt="" class="img-fluid d-block" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-reset">Borosil Paper
                                                                    Cup</a></h5>
                                                            <span class="text-muted">01 Mar
                                                                2021</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$14.00</h5>
                                                    <span class="text-muted">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">80</h5>
                                                    <span class="text-muted">Orders</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">749</h5>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$1120</h5>
                                                    <span class="text-muted">Amount</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/products/img-4.png"
                                                                alt="" class="img-fluid d-block" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-reset">One Seater
                                                                    Sofa</a></h5>
                                                            <span class="text-muted">11 Feb
                                                                2021</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$127.50</h5>
                                                    <span class="text-muted">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">56</h5>
                                                    <span class="text-muted">Orders</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal"><span
                                                            class="badge bg-danger-subtle text-danger">Out
                                                            of stock</span></h5>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$7140</h5>
                                                    <span class="text-muted">Amount</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/products/img-5.png"
                                                                alt="" class="img-fluid d-block" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-reset">Stillbird
                                                                    Helmet</a></h5>
                                                            <span class="text-muted">17 Jan
                                                                2021</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$54</h5>
                                                    <span class="text-muted">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">74</h5>
                                                    <span class="text-muted">Orders</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">805</h5>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$3996</h5>
                                                    <span class="text-muted">Amount</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div
                                    class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                                    <div class="col-sm">
                                        <div class="text-muted">
                                            Showing <span class="fw-semibold">5</span> of <span
                                                class="fw-semibold">25</span> Results
                                        </div>
                                    </div>
                                    <div class="col-sm-auto  mt-3 mt-sm-0">
                                        <ul
                                            class="pagination pagination-separated pagination-sm mb-0 justify-content-center">
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link">←</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">1</a>
                                            </li>
                                            <li class="page-item active">
                                                <a href="#" class="page-link">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">→</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Top Sellers</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted">Report<i
                                                    class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Download
                                                Report</a>
                                            <a class="dropdown-item" href="#">Export</a>
                                            <a class="dropdown-item" href="#">Import</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/companies/img-1.png"
                                                                alt="" class="avatar-sm p-2" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1 fw-medium">
                                                                <a href="apps-ecommerce-seller-details.html"
                                                                    class="text-reset">iTest
                                                                    Factory</a>
                                                            </h5>
                                                            <span class="text-muted">Oliver
                                                                Tyler</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Bags and Wallets</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">8547</p>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">$541200</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">32%<i
                                                            class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                    </h5>
                                                </td>
                                            </tr><!-- end -->
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/companies/img-2.png"
                                                                alt="" class="avatar-sm p-2" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="fs-14 my-1 fw-medium"><a
                                                                    href="apps-ecommerce-seller-details.html"
                                                                    class="text-reset">Digitech
                                                                    Galaxy</a></h5>
                                                            <span class="text-muted">John
                                                                Roberts</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Watches</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">895</p>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">$75030</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">79%<i
                                                            class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                    </h5>
                                                </td>
                                            </tr><!-- end -->
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/companies/img-3.png"
                                                                alt="" class="avatar-sm p-2" />
                                                        </div>
                                                        <div class="flex-gow-1">
                                                            <h5 class="fs-14 my-1 fw-medium"><a
                                                                    href="apps-ecommerce-seller-details.html"
                                                                    class="text-reset">Nesta
                                                                    Technologies</a></h5>
                                                            <span class="text-muted">Harley
                                                                Fuller</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Bike Accessories</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">3470</p>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">$45600</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">90%<i
                                                            class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                    </h5>
                                                </td>
                                            </tr><!-- end -->
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/companies/img-8.png"
                                                                alt="" class="avatar-sm p-2" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="fs-14 my-1 fw-medium"><a
                                                                    href="apps-ecommerce-seller-details.html"
                                                                    class="text-reset">Zoetic
                                                                    Fashion</a></h5>
                                                            <span class="text-muted">James
                                                                Bowen</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Clothes</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">5488</p>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">$29456</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">40%<i
                                                            class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                    </h5>
                                                </td>
                                            </tr><!-- end -->
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/companies/img-5.png"
                                                                alt="" class="avatar-sm p-2" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="fs-14 my-1 fw-medium">
                                                                <a href="apps-ecommerce-seller-details.html"
                                                                    class="text-reset">Meta4Systems</a>
                                                            </h5>
                                                            <span class="text-muted">Zoe Dennis</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Furniture</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">4100</p>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">$11260</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">57%<i
                                                            class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                    </h5>
                                                </td>
                                            </tr><!-- end -->
                                        </tbody>
                                    </table><!-- end table -->
                                </div>

                                <div
                                    class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                                    <div class="col-sm">
                                        <div class="text-muted">
                                            Showing <span class="fw-semibold">5</span> of <span
                                                class="fw-semibold">25</span> Results
                                        </div>
                                    </div>
                                    <div class="col-sm-auto  mt-3 mt-sm-0">
                                        <ul
                                            class="pagination pagination-separated pagination-sm mb-0 justify-content-center">
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link">←</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">1</a>
                                            </li>
                                            <li class="page-item active">
                                                <a href="#" class="page-link">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">→</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div> <!-- .card-body-->
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Store Visits by Source</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted">Report<i
                                                    class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Download
                                                Report</a>
                                            <a class="dropdown-item" href="#">Export</a>
                                            <a class="dropdown-item" href="#">Import</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="store-visits-source"
                                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->

                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Recent Orders</h4>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-soft-info btn-sm">
                                        <i class="ri-file-list-3-line align-middle"></i> Generate
                                        Report
                                    </button>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">Order ID</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Vendor</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="apps-ecommerce-order-details.html"
                                                        class="fw-medium link-primary">#VZ2112</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/avatar-1.jpg"
                                                                alt="" class="avatar-xs rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">Alex Smith</div>
                                                    </div>
                                                </td>
                                                <td>Clothes</td>
                                                <td>
                                                    <span class="text-success">$109.00</span>
                                                </td>
                                                <td>Zoetic Fashion</td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">Paid</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 fw-medium mb-0">5.0<span
                                                            class="text-muted fs-11 ms-1">(61
                                                            votes)</span></h5>
                                                </td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="apps-ecommerce-order-details.html"
                                                        class="fw-medium link-primary">#VZ2111</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/avatar-2.jpg"
                                                                alt="" class="avatar-xs rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">Jansh Brown</div>
                                                    </div>
                                                </td>
                                                <td>Kitchen Storage</td>
                                                <td>
                                                    <span class="text-success">$149.00</span>
                                                </td>
                                                <td>Micro Design</td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 fw-medium mb-0">4.5<span
                                                            class="text-muted fs-11 ms-1">(61
                                                            votes)</span></h5>
                                                </td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="apps-ecommerce-order-details.html"
                                                        class="fw-medium link-primary">#VZ2109</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/avatar-3.jpg"
                                                                alt="" class="avatar-xs rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">Ayaan Bowen</div>
                                                    </div>
                                                </td>
                                                <td>Bike Accessories</td>
                                                <td>
                                                    <span class="text-success">$215.00</span>
                                                </td>
                                                <td>Nesta Technologies</td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">Paid</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 fw-medium mb-0">4.9<span
                                                            class="text-muted fs-11 ms-1">(89
                                                            votes)</span></h5>
                                                </td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="apps-ecommerce-order-details.html"
                                                        class="fw-medium link-primary">#VZ2108</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/avatar-4.jpg"
                                                                alt="" class="avatar-xs rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">Prezy Mark</div>
                                                    </div>
                                                </td>
                                                <td>Furniture</td>
                                                <td>
                                                    <span class="text-success">$199.00</span>
                                                </td>
                                                <td>Syntyce Solutions</td>
                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 fw-medium mb-0">4.3<span
                                                            class="text-muted fs-11 ms-1">(47
                                                            votes)</span></h5>
                                                </td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="apps-ecommerce-order-details.html"
                                                        class="fw-medium link-primary">#VZ2107</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/avatar-6.jpg"
                                                                alt="" class="avatar-xs rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">Vihan Hudda</div>
                                                    </div>
                                                </td>
                                                <td>Bags and Wallets</td>
                                                <td>
                                                    <span class="text-success">$330.00</span>
                                                </td>
                                                <td>iTest Factory</td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">Paid</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 fw-medium mb-0">4.7<span
                                                            class="text-muted fs-11 ms-1">(161
                                                            votes)</span></h5>
                                                </td>
                                            </tr><!-- end tr -->
                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div> <!-- end row--> --}}

            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        const data = @json($data); // Dữ liệu từ server được truyền vào JavaScript

        let currentData = {
            months: data.months,
            revenue: data.revenue[{{ now()->year }}] || [], // Dữ liệu doanh thu cho năm {{ now()->year }}
        };

        // Tạo biểu đồ doanh thu cho năm {{ now()->year }}
        const ctx = document.getElementById('revenueChart').getContext('2d');
        let chartDoanhThu = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: currentData.months,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: currentData.revenue,
                    backgroundColor: 'rgba(10, 179, 156, 0.9)',
                    yAxisID: 'y-axis-revenue'
                }]
            },
            options: {
                responsive: true,
                layout: {
                    padding: 20
                },
                plugins: {
                    legend: {
                        position: 'bottom', // Đặt legend ở dưới biểu đồ
                        labels: {
                            boxWidth: 10, // Kích thước chiều rộng biểu tượng
                            boxHeight: 10, // Kích thước chiều cao biểu tượng (tương ứng để thành hình tròn nhỏ hơn)
                            usePointStyle: true, // Biểu tượng hình tròn
                            pointStyle: 'circle', // Xác định hình dạng là hình tròn
                            color: '#333', // Màu chữ
                            font: {
                                size: 14 // Kích thước chữ
                            }

                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    'y-axis-revenue': {
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            callback: (value) => `${value} VND`
                        }
                    }
                }
            }
        });

        // Lắng nghe thay đổi từ dropdown chọn năm
        document.getElementById('year-select').addEventListener('change', function() {

            let selectedYear = this.value;

            // Cập nhật dữ liệu dựa trên năm đã chọn
            currentData = {
                months: data.months,
                revenue: data.revenue[selectedYear] || [],
            };
            document.getElementById('total-revenue-year').setAttribute('data-target', data.total_year_revenue[selectedYear] || 0);
            document.getElementById('total-revenue-year').innerText = data.total_year_revenue[selectedYear] || 0;

            // Cập nhật biểu đồ với dữ liệu mới
            chartDoanhThu.data.labels = currentData.months;
            chartDoanhThu.data.datasets[0].data = currentData.revenue;

            chartDoanhThu.update();
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const seatData = @json($seatData);
        const labels = seatData.map(item => item.seat_name); // Tên các loại ghế
        const dataTotalSeat = seatData.map(item => item.total);
        console.log(data);

        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('seatChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels, // Nhãn
                    datasets: [{
                        data: dataTotalSeat, // Dữ liệu
                        backgroundColor: [
                            "rgba(0, 143, 251, 0.8)",
                            "rgba(0, 227, 150, 0.8)",
                            "rgba(254, 176, 25, 0.8)"
                        ],
                        hoverBackgroundColor: [
                            "rgba(0, 143, 251, 1)",
                            "rgba(0, 227, 150, 1)",
                            "rgba(254, 176, 25, 1)"
                        ],
                        borderColor: "#fff",
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    layout: {
                        padding: 30
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10, // Kích thước chiều rộng biểu tượng
                                boxHeight: 10, // Kích thước chiều cao biểu tượng (tương ứng để thành hình tròn nhỏ hơn)
                                usePointStyle: true, // Biểu tượng hình tròn
                                pointStyle: 'circle', // Xác định hình dạng là hình tròn
                                color: '#333', // Màu chữ
                                font: {
                                    size: 14 // Kích thước chữ
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((sum, value) => sum +
                                        value, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(2);
                                    return `${context.label}: ${context.raw} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${percentage}%`;
                            }
                        }
                    },
                    cutout: '60%' // Độ rộng lỗ tròn ở giữa
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lấy giá trị branchId và cinemaId từ phía server
            var selectedBranchId = "{{ session('dashboard.branch_id', '') }}";
            var selectedCinemaId = "{{ session('dashboard.cinema_id', '') }}";
            var isLoading = false; // Cờ để kiểm tra trạng thái đang tải

            // Xử lý sự kiện thay đổi chi nhánh
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');

                // Đặt lại giá trị của dropdown rạp về mặc định khi chọn chi nhánh khác
                cinemaSelect.empty();

                if (branchId) {
                    if (!isLoading) {
                        isLoading = true; // Đánh dấu đang tải dữ liệu
                        cinemaSelect.html(
                            '<option value="">Đang tải...</option>'); // Hiển thị "Đang tải..."

                        $.ajax({
                            url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                            method: 'GET',
                            success: function(data) {
                                cinemaSelect.empty();
                                cinemaSelect.append(
                                    '<option value="">Tất cả rạp</option>'
                                ); // Hiển thị "Tất cả rạp" sau khi tải

                                $.each(data, function(index, cinema) {
                                    cinemaSelect.append('<option value="' + cinema.id +
                                        '">' + cinema.name + '</option>');
                                });

                                // Chọn lại rạp nếu có selectedCinemaId và branchId khớp
                                if (selectedCinemaId && branchId == selectedBranchId) {
                                    cinemaSelect.val(selectedCinemaId);
                                }
                                isLoading = false; // Kết thúc quá trình tải
                            },
                            error: function() {
                                cinemaSelect.html(
                                    '<option value="">Không thể tải danh sách rạp</option>');
                                isLoading = false; // Kết thúc quá trình tải
                            }
                        });
                    }
                } else {
                    cinemaSelect.empty();
                    cinemaSelect.append('<option value="">Tất cả rạp</option>');
                }
            });

            // Kích hoạt thay đổi chi nhánh để load rạp nếu có selectedBranchId
            // if (selectedBranchId) {
            //     $('#branch').val(selectedBranchId).trigger('change');
            // } else {
            //     // Nếu không có selectedBranchId, hiển thị "Tất cả rạp"
            //     var cinemaSelect = $('#cinema');
            //     if (!cinemaSelect.val()) { // Kiểm tra nếu không có giá trị rạp nào được chọn
            //         cinemaSelect.empty();
            //         cinemaSelect.append('<option value="">Tất cả rạp</option>');
            //     }
            // }
        });
    </script>
@endsection
