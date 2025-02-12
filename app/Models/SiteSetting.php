<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    // Các trường có thể được gán giá trị hàng loạt (mass-assignable)
    protected $fillable = [
        'website_logo',
        'site_name',
        'brand_name',
        'slogan',
        'phone',
        'email',
        'headquarters',
        'business_license',
        'working_hours',
        'facebook_link',
        'youtube_link',
        'instagram_link',
        'privacy_policy_image',
        'privacy_policy',
        'terms_of_service_image',
        'terms_of_service',
        'introduction_image',
        'introduction',
        'copyright',
    ];

    // Định nghĩa các giá trị mặc định
    public static function defaultSettings()
    {
        return [
            'website_logo' => 'theme/client/images/header/P.svg',
            'site_name' => 'Poly Cinemas',
            'brand_name' => 'Công Ty Phim Việt Nam Poly Cinemas',
            'slogan' => 'Hãy đặt vé Xem phim ngay!',
            'phone' => '0123456789',
            'email' => 'polycinemas@poly.cenimas',
            'headquarters' => 'Tòa nhà FPT Polytechnic, Phố Trịnh Văn Bô, Nam Từ Liêm, Hà Nội',
            'business_license' => 'Đây là giấy phép kinh doanh',
            'working_hours' => '7:00 - 22:00',
            'facebook_link' => 'https://facebook.com/',
            'youtube_link' => 'https://youtube.com/',
            'instagram_link' => 'https://instagram.com/',
            'privacy_policy_image' => 'theme/client/images/header/P.svg',
            'privacy_policy' => '
            <b>Chào mừng Quý khách hàng đến với Hệ thống Bán Vé Online của chuỗi Rạp Chiếu Phim POLY CINEMAS!</b>
            <p>Xin cảm ơn và chúc Quý khách hàng có những giây phút xem phim tuyệt vời tại POLY CINEMAS!</p>
            <b>Sau đây là một số lưu ý trước khi thanh toán trực tuyến:</b> <br>
            <ul>
                <li>1. Thẻ phải được kích hoạt chức năng thanh toán trực tuyến, và có đủ
                    hạn
                    mức/ số dư để thanh toán. Quý khách cần nhập chính xác thông tin thẻ
                    (tên
                    chủ thẻ, số thẻ, ngày hết hạn, số CVC, OTP,...).</li>
                <li>2. Vé và hàng hóa đã thanh toán thành công không thể hủy/đổi
                    trả/hoàn tiền
                    vì bất kỳ lý do gì. POLY CINEMAS chỉ thực hiện hoàn tiền trong
                    trường hợp
                    thẻ của Quý khách đã bị trừ tiền nhưng hệ thống của Beta không ghi
                    nhận việc
                    đặt vé/đơn hàng của Quý khách, và Quý khách không nhận được xác nhận
                    đặt
                    vé/đơn hàng thành công.</li>
                <li>3. Trong vòng 30 phút kể từ khi thanh toán thành công, POLY CINEMAS
                    sẽ gửi
                    Quý khách mã xác nhận thông tin vé/đơn hàng qua email của Quý khách.
                    Nếu Quý
                    khách cần hỗ trợ hay thắc mắc, khiếu nại về xác nhận mã vé/đơn hàng
                    thì vui
                    lòng phản hồi về Fanpage Facebook POLY CINEMAS trong vòng 60 phút kể
                    từ khi
                    thanh toán vé thành công. Sau khoảng thời gian trên, POLY CINEMAS sẽ
                    không
                    chấp nhận giải quyết bất kỳ khiếu nại nào.</li>
                <li>4. POLY CINEMAS không chịu trách nhiệm trong trường hợp thông tin
                    địa chỉ
                    email, số điện thoại Quý khách nhập không chính xác dẫn đến không
                    nhận được
                    thư xác nhận. Vui lòng kiểm tra kỹ các thông tin này trước khi thực
                    hiện
                    thanh toán. POLY CINEMAS không hỗ trợ xử lý và không chịu trách
                    nhiệm trong
                    trường hợp đã gửi thư xác nhận mã vé/đơn hàng đến địa chỉ email của
                    Quý
                    khách nhưng vì một lý do nào đó mà Quý khách không thể đến xem phim.
                </li>
                <li>5. Vui lòng kiểm tra thông tin xác nhận vé cẩn thận và ghi nhớ mã
                    đặt vé/đơn
                    hàng. Khi đến nhận vé/hàng hóa tại Quầy vé của POLY CINEMAS, Quý
                    khách cũng
                    cần mang theo giấy tờ tùy thân như Căn cước công dân/Chứng minh nhân
                    dân,
                    Thẻ học sinh, Thẻ sinh viên hoặc hộ chiếu.</li>
                <li>7. Vì một số sự cố kỹ thuật bất khả kháng, suất chiếu có thể bị huỷ
                    để đảm
                    bảo an toàn tối đa cho khách hàng, POLY CINEMAS sẽ thực hiện hoàn
                    trả số
                    tiền giao dịch về tài khoản mà Quý khách đã thực hiện mua vé. Beta
                    Cinemas
                    sẽ liên hệ với Quý khách qua các thông tin liên hệ trong mục Thông
                    tin thành
                    viên để thông báo và xác nhận.</li>
                <li></li>
            </ul>',
            'terms_of_service_image'=> 'theme/client/images/header/P.svg',
            'terms_of_service' => 'Đây là  điều khoản Dịch vụ',
            'introduction_image' => 'theme/client/images/header/P.svg',
            'introduction' => '
                <p>F5 Poly Media được thành lập bởi doanh nhân F5 Poly Cinemas (F5 Poly Beta) vào cuối năm 2014 với sứ mệnh "Mang trải nghiệm điện ảnh với mức giá hợp lý cho mọi người dân Việt Nam".</p>
                <p>Với thiết kế độc đáo, trẻ trung, F5 Poly Cinemas mang đến trải nghiệm điện ảnh chất lượng với chi phí đầu tư và vận hành tối ưu - nhờ việc chọn địa điểm phù hợp, tận dụng tối đa diện tích, bố trí khoa học, nhằm duy trì giá vé xem phim trung bình chỉ từ 40,000/1 vé - phù hợp với đại đa số người dân Việt Nam.</p>
                <p>Năm 2023 đánh dấu cột mốc vàng son cho Poly Cinemas khi ghi nhận mức tăng trưởng doanh thu ấn tượng 150% so với năm 2019 - là năm đỉnh cao của ngành rạp chiếu phim trước khi đại dịch Covid-19 diễn ra. Thành tích này cho thấy sức sống mãnh liệt và khả năng phục hồi ấn tượng của chuỗi rạp.</p>
                <p>Tính đến thời điểm hiện tại, Poly Cinemas đang có 20 cụm rạp trải dài khắp cả nước, phục vụ tới 6 triệu khách hàng mỗi năm, là doanh nghiệp dẫn đầu phân khúc đại chúng của thị trường điện ảnh Việt. Poly Media cũng hoạt động tích cực trong lĩnh vực sản xuất và phát hành phim.</p>
                <p>Ngoài đa số các cụm rạp do Poly Media tự đầu tư, ¼ số cụm rạp của Poly Media còn được phát triển bằng hình thức nhượng quyền linh hoạt. Chi phí đầu tư rạp chiếu phim Poly Cinemas được tối ưu giúp nhà đầu tư dễ dàng tiếp cận và nhanh chóng hoàn vốn, mang lại hiệu quả kinh doanh cao và đảm bảo.</p>',
            'copyright' => 'Bản quyền © 2024 Poly Cinemas',
        ];
    }

    // Phương thức đặt lại cài đặt về mặc định
    public function resetToDefault()
    {
        $this->update(self::defaultSettings());
    }
}
