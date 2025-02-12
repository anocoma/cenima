<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="#" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('theme/client/images/header/P3.svg') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="" width="187px" height="40px">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/admin" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('theme/client/images/header/P3.svg') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="" width="187px" height="40px">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>



                {{-- @if (Auth::user()->hasRole('System Admin') || Auth::user()->hasRole('Quản lý cơ sở')) --}}
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="/admin" class="nav-link menu-link" data-key="t-horizontal">
                        <i class="ri-dashboard-2-line"></i>
                        <span data-key="t-dashboards">Tổng quan</span>
                    </a>
                </li>
                {{-- @endif --}}

                @canany(['Danh sách thống kê'])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#chart" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="chart">
                            <i class="ri-bar-chart-grouped-fill"></i>
                            <span data-key="t-landing">Thống Kê</span>
                        </a>

                        <div class="menu-dropdown collapse" id="chart" style="">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.statistical-revenue') }}" class="nav-link"
                                        data-key="t-horizontal">
                                        <span data-key="t-dashboards">Thống kê doanh thu</span>
                                    </a>
                                </li>

                                {{-- <li class="nav-item">
                                        <a href="{{ route('admin.statistical.cinemaRevenue') }}" class="nav-link menu-link"
                                            data-key="t-horizontal">

                                            <span data-key="t-dashboards">Doanh Thu Theo Rạp</span>
                                        </a>
                                    </li> --}}

                                {{-- <div class="menu-dropdown collapse" id="chart" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.statistical-cinemas') }}" class="nav-link menu-link"
                                                    data-key="t-horizontal">
                                                    <span data-key="t-dashboards">Thống kê rạp</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div> --}}


                                <li class="nav-item">
                                    <a href="{{ route('admin.statistical-movies') }}" class="nav-link"
                                        data-key="t-horizontal">
                                        <span data-key="t-dashboards">Thống kê phim</span>
                                    </a>
                                </li>



                                <li class="nav-item">
                                    <a href="{{ route('admin.statistical-tickets') }}" class="nav-link"
                                        data-key="t-horizontal">
                                        <span data-key="t-dashboards">Thống kê hóa đơn</span>
                                    </a>
                                </li>



                                <li class="nav-item">
                                    <a href="{{ route('admin.statistical-combos') }}" class="nav-link"
                                        data-key="t-horizontal">
                                        <span data-key="t-dashboards">Thống kê combo</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- <div class="menu-dropdown collapse" id="chart" style="">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.statistical.revenue') }}" class="nav-link menu-link"
                                        data-key="t-horizontal">
                                        <span data-key="t-dashboards">Doanh Thu</span>
                                    </a>
                                </li>
                            </ul>
                        </div> --}}
                    </li>
                @endcanany

                {{-- Menu mới --}}
                @canany([
                    'Danh sách chi nhánh',
                    'Thêm chi nhánh',
                    'Sửa chi nhánh',
                    'Xóa chi nhánh',
                    'Danh sách rạp',
                    'Thêm rạp',
                    'Sửa rạp',

                    'Danh sách phòng chiếu',
                    'Thêm phòng chiếu',
                    'Sửa phòng chiếu',
                    'Xóa phòng chiếu',
                    'Xem chi tiết phòng chiếu',
                    'Danh sách mẫu sơ đồ ghế',
                    'Thêm mẫu sơ đồ ghế',
                    'Sửa mẫu sơ đồ ghế',
                    'Xóa mẫu sơ đồ
                    ghế',
                    ])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#systemCinemas" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="systemCinemas">
                            <i class=" ri-store-3-fill"></i><span data-key="t-landing">Hệ thống Rạp</span>
                        </a>
                        <div class="menu-dropdown collapse" id="systemCinemas" style="">
                            <ul class="nav nav-sm flex-column">
                                @canany(['Danh sách chi nhánh', 'Thêm chi nhánh', 'Sửa chi nhánh', 'Xóa chi nhánh'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.branches.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class="ri-ancient-gate-fill"></i><span
                                                data-key="t-layouts">Quản lý Chi nhánh</span></a>
                                    </li>
                                @endcan
                                @canany(['Danh sách rạp', 'Thêm rạp', 'Sửa rạp'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.cinemas.index') }}" class="nav-link"
                                            data-key="t-horizontal"> <i class=" ri-store-3-fill"></i> <span
                                                data-key="t-layouts">Quản lý Rạp</span></a>
                                    </li>
                                @endcan
                                @canany(['Danh sách phòng chiếu', 'Thêm phòng chiếu', 'Sửa phòng chiếu', 'Xóa phòng chiếu',
                                    'Xem chi tiết phòng chiếu'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.rooms.index') }}" class="nav-link"
                                            data-key="t-horizontal"> <i class=" ri-tv-line"></i> <span
                                                data-key="t-layouts">Quản
                                                lý Phòng chiếu</span></a>
                                    </li>
                                @endcan
                                @canany([
                                    'Danh sách mẫu sơ đồ ghế',
                                    'Thêm mẫu sơ đồ ghế',
                                    'Sửa mẫu sơ đồ ghế',
                                    'Xóa mẫu sơ đồ
                                    ghế',
                                    ])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.seat-templates.index') }}" class="nav-link"
                                            data-key="t-horizontal"> <i class="ri-rocket-line"></i>
                                            <span data-key="t-layouts">Mẫu sơ đồ ghế</span></a>
                                    </li>
                                @endcan
                                @can('Thẻ thành viên')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.ranks.index') }}" class="nav-link"
                                            data-key="t-horizontal"> <i class="mdi mdi-wallet-giftcard"></i>
                                            <span data-key="t-layouts">Thẻ thành viên</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                @canany([
                    'Danh sách phim',
                    'Thêm phim',
                    'Sửa phim',
                    'Xem chi tiết phim',
                    'Danh sách suất chiếu',
                    'Thêm
                    suất chiếu',
                    'Sửa suất chiếu',
                    'Xóa suất chiếu',
                    'Xem chi tiết suất chiếu',
                    'Danh sách hóa đơn',
                    'Quét
                    hóa đơn',
                    'Danh sách đặt vé',
                    'Thêm đặt vé',
                    'Sửa đặt vé',
                    'Xóa đặt vé',
                    ])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#manageMovies" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="manageMovies">
                            <i class=" ri-slideshow-3-fill"></i> <span data-key="t-landing">Phim & Suất chiếu</span>
                        </a>
                        <div class="menu-dropdown collapse" id="manageMovies" style="">
                            <ul class="nav nav-sm flex-column">

                                @canany(['Danh sách phim', 'Thêm phim', 'Sửa phim', 'Xem chi tiết phim'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.movies.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class="mdi mdi-movie-open-outline"></i> <span
                                                data-key="t-layouts">Quản lý
                                                Phim</span></a>
                                    </li>
                                @endcan

                                @canany([
                                    'Danh sách suất chiếu',
                                    'Thêm suất chiếu',
                                    'Sửa suất chiếu',
                                    'Xóa suất chiếu',
                                    'Xem
                                    chi tiết suất chiếu',
                                    ])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.showtimes.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class=" ri-slideshow-3-fill"></i> <span
                                                data-key="t-layouts">Quản
                                                lý Suất
                                                chiếu</span></a>
                                    </li>
                                @endcan

                                @canany(['Danh sách hóa đơn', 'Quét hóa đơn'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.tickets.index') }}" class="nav-link"
                                            data-key="t-horizontal">
                                            <i class="ri-wallet-3-fill"></i>
                                            <span data-key="t-layouts">Quản lý Hóa đơn</span></a>
                                    </li>
                                @endcan

                                {{-- @canany(['Danh sách đặt vé', 'Thêm đặt vé', 'Sửa đặt vé', 'Xóa đặt vé'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.book-tickets.index') }}" class="nav-link menu-link"
                                            data-key="t-horizontal">
                                            <i class=" mdi mdi-store"></i>
                                            <span data-key="t-dashboards">Đặt vé</span>
                                        </a>
                                    </li>
                                @endcan --}}
                            </ul>
                        </div>
                    </li>
                @endcanany



                @canany([
                    'Danh sách đồ ăn',
                    'Thêm đồ ăn',
                    'Sửa đồ ăn',
                    'Xóa đồ ăn',
                    'Danh sách combo',
                    'Thêm combo',
                    'Sửa combo',
                    'Xóa combo',
                    'Danh sách vouchers',
                    'Thêm vouchers',
                    'Sửa vouchers',
                    'Xóa
                    vouchers',
                    'Danh sách thanh toán',
                    'Thêm thanh toán',
                    'Sửa thanh toán',
                    'Xóa thanh toán',
                    ])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#service" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="service">
                            <i class="ri-shopping-basket-2-line"></i> <span data-key="t-landing">Dịch vụ và Ưu đãi</span>
                        </a>
                        <div class="menu-dropdown collapse" id="service" style="">
                            <ul class="nav nav-sm flex-column">

                                @canany(['Danh sách đồ ăn', 'Thêm đồ ăn', 'Sửa đồ ăn', 'Xóa đồ ăn'])
                                    {{-- Quản lí đồ ăn --}}
                                    <li class="nav-item">
                                        <a href="{{ route('admin.food.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class="ri-cake-3-fill"></i> <span
                                                data-key="t-layouts">Quản lý Đồ ăn</span></a>
                                    </li>
                                @endcan

                                @canany(['Danh sách combo', 'Thêm combo', 'Sửa combo', 'Xóa combo'])
                                    {{-- Quản lí Combo --}}
                                    <li class="nav-item">
                                        <a href="{{ route('admin.combos.index') }}" class="nav-link"
                                            data-key="t-horizontal">
                                            <i class="ri-shopping-basket-2-line"></i> <span data-key="t-layouts">Quản lý
                                                Combo</span></a>
                                    </li>
                                @endcan

                                @canany(['Danh sách vouchers', 'Thêm vouchers', 'Sửa vouchers', 'Xóa vouchers'])
                                    {{-- Vouchers --}}
                                    <li class="nav-item">
                                        <a href="{{ route('admin.vouchers.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class=" ri-coupon-3-line "></i> <span
                                                data-key="t-layouts">Mã giảm giá</span></a>
                                    </li>
                                @endcan

                                {{--
                                @canany(['Danh sách thanh toán', 'Thêm thanh toán', 'Sửa thanh toán', 'Xóa thanh toán'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.payments.index') }}" class="nav-link menu-link"
                                            data-key="t-horizontal"><i class="ri-layout-3-line"></i> <span
                                                data-key="t-layouts">Quản lý
                                                Thanh Toán</span></a>
                                    </li>
                                @endcan --}}

                                @canany(['Danh sách giá', 'Sửa giá'])

                                    {{-- Giá vé --}}
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.ticket-price') }}">
                                            <i class=" ri-ticket-2-line"></i> <span data-key="t-dashboards">Quản lý Giá vé
                                            </span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany



                @canany(['Danh sách bài viết', 'Thêm bài viết', 'Sửa bài viết', 'Xóa bài viết', 'Danh sách slideshows',
                    'Thêm slideshows', 'Sửa slideshows', 'Xóa slideshows'])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#contentMarketing" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="contentMarketing">
                            <i class="ri-file-list-3-line"></i><span data-key="t-landing">Nội dung và Marketing</span>
                        </a>
                        <div class="menu-dropdown collapse" id="contentMarketing" style="">
                            <ul class="nav nav-sm flex-column">

                                @canany(['Danh sách bài viết', 'Thêm bài viết', 'Sửa bài viết', 'Xóa bài viết'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.posts.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class="ri-file-list-3-line"></i> <span
                                                data-key="t-layouts">Quản lý Bài
                                                viết</span></a>
                                    </li>
                                @endcan

                                @canany(['Danh sách slideshows', 'Thêm slideshows', 'Sửa slideshows', 'Xóa slideshows'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.slideshows.index') }}" class="nav-link"
                                            data-key="t-horizontal"> <i class="ri-slideshow-3-line"></i> <span
                                                data-key="t-layouts">Slideshows</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                @canany(['Danh sách liên hệ', 'Sửa liên hệ', 'Danh sách tài khoản', 'Thêm tài khoản', 'Sửa tài khoản',
                    'Xóa tài khoản'])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#user-report" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="user-report">
                            <i class="ri-account-circle-line"></i>
                            <span data-key="t-landing">Tài khoản và Liên hệ</span>
                        </a>
                        <div class="menu-dropdown collapse" id="user-report" style="">
                            <ul class="nav nav-sm flex-column">

                                @canany(['Danh sách tài khoản', 'Thêm tài khoản', 'Sửa tài khoản', 'Xóa tài khoản'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.index') }}" class="nav-link"
                                            data-key="t-horizontal">
                                            <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Tài
                                                khoản</span></a>
                                    </li>
                                @endcan
                                @canany(['Danh sách liên hệ', 'Sửa liên hệ'])
                                    {{-- Quản lí Liên hệ --}}
                                    <li class="nav-item">
                                        <a href="{{ route('admin.contacts.index') }}" class="nav-link"
                                            data-key="t-horizontal"><i class="ri-contacts-book-2-line"></i> <span
                                                data-key="t-layouts">Liên hệ</span></a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                @endcanany



                @if (auth()->user()->hasRole('System Admin'))
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link menu-link"
                            data-key="t-horizontal">
                            <i class="las la-user-plus"></i>
                            <span data-key="t-dashboards">Phân quyền</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('admin.roles.index') }}" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="permissions">
                            <i class=" las la-asterisk"></i>
                            <span data-key="t-landing">Phân quyền</span>
                        </a>
                        <div class="menu-dropdown collapse" id="permissions" style="">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}" class="nav-link menu-link"
                                        data-key="t-horizontal">
                                        <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Danh sách
                                            vai
                                            trò</span></a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.assign-roles.index') }}" class="nav-link menu-link"
                                        data-key="t-horizontal">
                                        <i class="las la-user-plus  "></i> <span data-key="t-layouts">Gán vai
                                            trò</span></a>
                                </li>

                            </ul>
                        </div>
                    </li> --}}
                @endif

                @canany(['Cấu hình website'])
                    <li class="nav-item">
                        <a href="{{ route('admin.site-settings.index') }}" class="nav-link menu-link"
                            data-key="t-horizontal">
                            <i class="ri-settings-5-line"></i> <span data-key="t-landing">Cấu hình Website</span>
                        </a>
                    </li>
                @endcanany


            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
