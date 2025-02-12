{{-- <div class="prs_newsletter_wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="prs_newsletter_text">
                    <h3>Get update sign up now !</h3>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                <div class="prs_newsletter_field">
                    <input type="text" placeholder="Enter Your Email">
                    <button type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- prs Newsletter Wrapper End -->
<!-- prs footer Wrapper Start -->
@php
    $listBranch = App\Models\Branch::query()->where('is_active', 1)->get();
    $settings = App\Models\SiteSetting::first();
@endphp
<div class="prs_footer_main_section_wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="prs_footer_cont1_wrapper prs_footer_cont1_wrapper_1">
                    <h2>{{ $settings->site_name }}</h2>
                    <ul>
                        <li style="margin-top: 0">
                            <a href="#">
                                {{-- <img style=" height: 80px" src="{{ asset('theme/client/images/header/logo7.svg') }}" alt="logo" /> --}}                    
                                @if($settings->website_logo)
                                {{-- Kiểm tra xem có phải là ảnh mặc định hay không --}}
                                @if(Str::startsWith($settings->website_logo, 'theme/client/images/'))
                                    <img src="{{ asset($settings->website_logo) }}" alt="Website Logo" style="max-width: 150px;">
                                @else
                                    <img src="{{ Storage::url($settings->website_logo) }}" alt="Website Logo" style="max-width: 150px;">
                                @endif
                                @else
                                    {{-- Hiển thị ảnh mặc định nếu không có ảnh nào --}}
                                    <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="Logo Mặc định" style="max-width: 150px;">
                                @endif
                            </a>
                        </li>
                        <li>
                            <span style="color: rgba(255, 255, 255, 0.57);">Trang mạng xã hội</span> <br>
                            <a href="{{ $settings->facebook_link }}" target="_blank"><i style="color: #ffffff; font-size: 20px;margin: 6% 3%;"
                                    class="fa fa-facebook"></i></a>
                            <a href="{{ $settings->youtube_link }}" target="_blank"><i style="color: #ffffff; font-size: 20px;margin: 6% 3%;"
                                    class="fa fa-youtube-play"></i></a>
                            <a href="{{ $settings->instagram_link }}" target="_blank"><i style="color: #ffffff; font-size: 20px;margin: 6% 3%;"
                                class="fa fa-instagram"></i></a>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="prs_footer_cont1_wrapper prs_footer_cont1_wrapper_1">
                    <h2>Quy định & điều khoản</h2>
                    <ul>
                        <li><i class="fa fa-circle"></i> &nbsp;&nbsp;<a href="policy">Chính sách và Điều khoản</a>
                        </li>
                        <li><i class="fa fa-circle"></i> &nbsp;&nbsp;<a href="ticket-price">Giá vé</a>
                        </li>
                        <li><i class="fa fa-circle"></i> &nbsp;&nbsp;<a href="posts">Tin tức</a>
                        </li>
                        <li><i class="fa fa-circle"></i> &nbsp;&nbsp;<a href="contact">Liên hệ</a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="prs_footer_cont1_wrapper prs_footer_cont1_wrapper_2">
                    <h2>chi nhánh</h2>
                    <ul>
                        {{-- @php
                            $listBranch = App\Models\Branch::query()->where('is_active', 1)->get();
                            $settings = App\Models\SiteSetting::first();
                        @endphp --}}

                        @foreach ($listBranch as $branch)
                            <li><i class="fa fa-circle"></i> &nbsp;&nbsp;<a href="#">{{ $branch->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="prs_footer_cont1_wrapper prs_footer_cont1_wrapper_3">
                    <h2>Thông tin website</h2>
                    <ul>
                        <li>
                            <span>Trụ sở chính:</span> &nbsp;&nbsp;
                            <span>{{ $settings->headquarters}}</span>
                        </li>
                        <li>
                            <span>Email:</span> &nbsp;&nbsp;
                            <span>{{ $settings->email}}</span>
                        </li>
                        <li>
                            <span>Hotline:</span> &nbsp;&nbsp;
                            <span>{{ $settings->phone}}</span>
                        </li>
                        <li>
                            <span>Giờ làm việc:</span> &nbsp;&nbsp;
                            <span>{{ $settings->working_hours}}</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="prs_bottom_footer_wrapper"><a href="javascript:" id="return-to-top"><i class="flaticon-play-button"></i></a>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="prs_bottom_footer_cont_wrapper">
                    <p style=" text-align: center;">
                        {{-- Bản quyền ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> của Poly Cinemas --}}
                        {{ $settings->copyright }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
