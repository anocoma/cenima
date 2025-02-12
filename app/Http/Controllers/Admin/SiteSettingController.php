<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;

class SiteSettingController extends Controller
{


    public function __construct()
    {
        $this->middleware('can:Cấu hình website')->only('index', 'update', 'resetToDefault');
    }
    // 1. Quản lý Trang chủ
    public function index()
    {
        $settings = SiteSetting::firstOrCreate([], SiteSetting::defaultSettings());
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(UpdateSiteSettingsRequest $request, SiteSetting $settings)
    {
        try {
            // Lấy bản ghi đầu tiên của SiteSetting
            $settings = SiteSetting::first();

            // Kiểm tra nếu không có thì tạo mới
            if (!$settings) {
                $settings = SiteSetting::create(SiteSetting::defaultSettings());
            }

            // Lấy tất cả dữ liệu từ request
            $dataSiteSetting = $request->except(['_token', '_method']);

            // Kiểm tra nếu có file ảnh mới cho website_logo
            if ($request->hasFile('website_logo')) {
                if ($settings->website_logo && Storage::exists($settings->website_logo)) {
                    Storage::delete($settings->website_logo);
                }
                $path = $request->file('website_logo')
                    ->storeAs('public/site-settings', Str::uuid() . '.' . $request->file('website_logo')->getClientOriginalExtension());
                $dataSiteSetting['website_logo'] = $path;
            } else {
                if (!$settings->website_logo) {
                    $dataSiteSetting['website_logo'] = 'theme/client/images/Logo_Poly_Cinemas.png';
                }
            }

            // Kiểm tra và cập nhật từng ảnh đại diện mới nếu có
            $images = [
                'introduction_image',
                'terms_of_service_image',
                'privacy_policy_image'
            ];

            foreach ($images as $imageField) {
                if ($request->hasFile($imageField)) {
                    if ($settings->$imageField && Storage::exists($settings->$imageField)) {
                        Storage::delete($settings->$imageField);
                    }
                    $path = $request->file($imageField)
                        ->storeAs('public/site-settings', Str::uuid() . '.' . $request->file($imageField)->getClientOriginalExtension());
                    $dataSiteSetting[$imageField] = $path;
                }
            }

            // Cập nhật các trường khác
            $settings->update($dataSiteSetting);

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    // 2. Đặt lại cài đặt về giá trị mặc định

    public function resetToDefault()
    {
        $settings = SiteSetting::first();

        // Danh sách các trường ảnh cần kiểm tra và xóa
        $images = [
            'website_logo',
            'introduction_image',
            'terms_of_service_image',
            'privacy_policy_image'
        ];

        // Kiểm tra và xóa từng file nếu có
        foreach ($images as $imageField) {
            if ($settings->$imageField && Storage::exists($settings->$imageField)) {
                Storage::delete($settings->$imageField);
            }
        }

        // Đặt lại cài đặt về giá trị mặc định
        $settings->resetToDefault();

        return redirect()->back()->with('success', 'Đã đặt lại cài đặt về giá trị mặc định!');
    }
}
