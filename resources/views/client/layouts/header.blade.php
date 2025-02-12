<div class="header ">
    <div class="header-top">
        <div class="container-header-top">
            <div class="login">
                {{-- <a href="">Đăng nhập</a> <a href="">|</a>  <a href="">Đăng ký</a> --}}
                @guest
                    @if (Route::has('login'))
                        <a class="a-dndk" href="{{ route('login') }}">{{ __('Đăng Nhập') }}</a>
                    @endif
                    |
                    @if (Route::has('register'))
                        <a class="a-dndk" href="{{ route('register') }}">{{ __('Đăng Ký') }}</a>
                    @endif
                @else
                    <ul class="menu-account">
                        <li class="hello-account">
                            <a href="#"> Xin chào: {{ Auth::user()->name }} <i
                                    class="fa-solid fa-chevron-down"></i></a>
                            <ul class="sub-menu-account">
                                @if (Auth::user()->type == 'admin')
                                    <li><a href="/admin"><i class="fa-solid fa-user-tie"></i> Truy cập trang quản trị</a>
                                    </li>
                                @endif
                                <li><a href="{{ route('my-account.edit') }}"><i class="fa-regular fa-user"></i> Thông tin
                                        tài khoản</a></li>
                                <li><a href="{{ route('my-account.edit', 'membership') }}"><i
                                            class="fa-regular fa-credit-card"></i> Thẻ thành viên</a></li>
                                <li><a href="{{ route('my-account.edit', 'cinema-journey') }}"><i
                                            class="fa-regular fa-paper-plane"></i> Lịch sử giao dịch</a></li>
                                {{-- <li><a href=""><i class="fa-regular fa-hand-point-right"></i> Điểm Poly</a></li> --}}
                                <li><a href="{{ route('my-account.edit', 'my-voucher') }}"><i class="fa-solid fa-ticket"></i> Voucher của tôi</a></li>
                                <li>

                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('Đăng Xuất') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                </li>
                            </ul>
                        </li>
                    </ul>
                @endguest
            </div>
            {{-- <div>
                <a href="#" onclick="return alert('Kích hoạt ! Đã chuyển đổi ngôn ngữ sang tiếng anh')">
                    <img width="20px" src="{{ asset('theme/client/images/languages_english.png') }}" alt="">
                </a>

            </div> --}}
        </div>
    </div>
    <div class="header-buttom ">
        <div class="container-header-buttom">
            {{-- <div class="logo"> --}}
            <div class="img-logo">
                @php
                    $settings = App\Models\SiteSetting::first();
                @endphp
                <div>
                    <a href="/">
                        {{-- <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="logo" /> --}}
                        @if ($settings->website_logo)
                            {{-- Kiểm tra xem có phải là ảnh mặc định hay không --}}
                            @if (Str::startsWith($settings->website_logo, 'theme/client/images/'))
                                <img src="{{ asset($settings->website_logo) }}" alt="Website Logo"
                                    style="height: 80px;">
                            @else
                                <img src="{{ Storage::url($settings->website_logo) }}" alt="Website Logo"
                                    style="height: 80px;">
                            @endif
                        @else
                            {{-- Hiển thị ảnh mặc định nếu không có ảnh nào --}}
                            <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="Logo Mặc định"
                                style="height: 80px;">
                        @endif
                    </a>
                </div>
                <div class="choose-cinemas">
                    <div>
                        @php
                            $branches = App\Models\Branch::where('is_active', '1')->get();
                        @endphp

                        <ul class="dropdown">
                            @if (!Auth::user() || Auth::user()->type == 'member' || Auth::user()->hasRole('System Admin'))
                                <li class="default-base">
                                    @php
                                        $selectedCinema = App\Models\Cinema::find(session('cinema_id'));
                                    @endphp
                                    <a href="#">Poly {{ $selectedCinema->name }} <i
                                            class="fa-solid fa-chevron-down"></i></a>
                                    <ul class="sub-menu">
                                        @foreach ($branches as $branch)
                                            <li class="li-branch">
                                                <a href="#">{{ $branch->name }}</a> {{--   Hà Nội, HCM --}}
                                                <span><i class="fa-solid fa-chevron-right"></i></span>
                                                <ul class="menu-cinema">
                                                    @if ($branch->cinemas->isEmpty())
                                                        <li><a href="#">Không có rạp nào</a></li>
                                                    @else
                                                        @foreach ($branch->cinemas as $cinema)
                                                            <li>
                                                                <a>
                                                                    <form action="{{ route('change-cinema') }}"
                                                                        method="POST" style="display:inline;">
                                                                        @csrf
                                                                        <input type="hidden" name="cinema_id"
                                                                            value="{{ $cinema->id }}">
                                                                        <button type="submit"
                                                                            style="background:none;border:none;text-align:left;cursor:pointer;">
                                                                            Poly {{ $cinema->name }}
                                                                        </button>
                                                                    </form>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                        
                            @elseif(Auth::user()->type == 'admin' && Auth::user()->cinema_id != '')
                                <li class="default-base">
                                    @php
                                        // use Illuminate\Support\Facades\Session;
                                        // Session::put('cinema_id', Auth::user()->cinema_id);
                                        $selectedCinema = App\Models\Cinema::find(Auth::user()->cinema_id);
                                    @endphp
                                    <a href="#">Poly {{ $selectedCinema->name }} </a>
                                </li>
                            @endif
                        </ul>

                    </div>

                </div>
            </div>

            {{-- </div> --}}

            <div class="main-menu">
                <ul>
                    <li>
                        <a href="{{ route('showtimes') }}">Lịch chiếu theo rạp</a>
                    </li>
                    <li>
                        <a href="{{ route('movies.index') }}">Phim</a>
                    </li>
                    <li>
                        <a href="{{ route('policy') }}">Chính sách</a>
                    </li>
                    <li>
                        <a href="{{ route('ticket-price') }}">Giá vé</a>
                    </li>
                    <li>
                        <a href="{{ route('posts') }}">Tin tức</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">Liên hệ</a>
                    </li>
                    <li>
                        <a href="{{ route('introduce') }}">Giới thiệu</a>
                    </li>
                    <li>
                        <a href="{{ route('my-account.edit', 'membership') }}">Thành viên</a>
                    </li>
                </ul>
            </div>
            <div class="menu-responsive">
                <ul class="menu-respon">
                    <li>
                        <a><i class="fa-solid fa-bars"></i></a>
                        <ul class="sub-menu-respon">
                            <li>
                                <a href="{{ route('showtimes') }}"><i class="fa-solid fa-calendar-days"></i> Lịch chiếu
                                    theo rạp</a>
                            </li>
                            <li>
                                <a href="{{ route('movies.index') }}"><i class="fa-solid fa-film"></i> Phim</a>
                            </li>
                            <li>
                                <a href="{{ route('policy') }}"><i class="fa-solid fa-building-shield"></i> Chính
                                    sách</a>
                            </li>
                            <li>
                                <a href="{{ route('ticket-price') }}"><i class="fa-solid fa-money-bill"></i> Giá vé</a>
                            </li>
                            <li>
                                <a href="{{ route('posts') }}"><i class="fa-regular fa-newspaper"></i> Tin tức</a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}"><i class="fa-regular fa-address-card"></i> Liên
                                    hệ</a>
                            </li>
                            <li>
                                <a href=""><i class="fa-regular fa-user"></i> Thành viên</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    @if (Auth::check() && !Auth::user()->hasVerifiedEmail())
        <div class="verify-email-header">
            <div class="container-verify-email">
                <div>
                    <p>
                        <i class="fa-solid fa-circle-exclamation" style="color: #f47b2a;"></i>
                        Vui lòng kiểm tra email và nhấp vào liên kết xác thực.
                    </p>
                </div>
                <div>
                    <button type="button" name="buttonSendMail">
                        <span class="spinner" style="display: none; margin-right: 5px;">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                        </span>
                        Gửi lại thư xác nhận
                    </button>

                    <form id="resend-verification" action="{{ route('verification.send') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                    <a href="#">
                        {{-- icon dấu X --}}
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif


</div>
<script>
    document.querySelector('.fa-xmark').addEventListener('click', function(e) {
        e.preventDefault();
        const verifyEmailHeader = document.querySelector('.verify-email-header');
        verifyEmailHeader.classList.add('hidden');
    });

    document.querySelector('button[name="buttonSendMail"]').addEventListener('click', function() {
        const button = this;
        const spinner = button.querySelector('.spinner');

        // Hiển thị spinner và vô hiệu hóa nút
        spinner.style.display = 'inline-block';
        button.disabled = true;

        fetch("{{ route('verification.send') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
            })
            .then(response => {
                if (response.ok) {
                    button.innerHTML =
                        '<i class="fa-solid fa-check" style="margin-right: 5px; color: green;"></i> Đã gửi!';
                } else {
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                    // Kích hoạt lại nút nếu lỗi
                    button.disabled = false;
                }
            })
            .catch(error => {
                alert("Có lỗi xảy ra. Vui lòng thử lại.");
                console.error(error);
                button.disabled = false;
            })
            .finally(() => {
                spinner.style.display = 'none';
            });
    });
</script>
