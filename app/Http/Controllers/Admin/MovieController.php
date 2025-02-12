<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMovieRequest;
use App\Http\Requests\Admin\UpdateMovieRequest;

use App\Models\Movie;
use App\Models\MovieVersion;
use App\Models\TypeRoom;
use App\Models\TypeSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieController extends Controller
{

    const PATH_VIEW = 'admin.movies.';
    const PATH_UPLOAD = 'movies';
    public function __construct()
    {
        $this->middleware('can:Danh sách phim')->only('index');
        $this->middleware('can:Thêm phim')->only(['create', 'store']);
        $this->middleware('can:Sửa phim')->only(['edit', 'update']);
        $this->middleware('can:Xóa phim')->only('destroy');
        $this->middleware('can:Xem chi tiết phim')->only('show');
    }

    public function index()
    {
        if (!session()->has('movies.selected_tab')) {
            session(['movies.selected_tab' => 'publish']); // Tab mặc định
        }
        $movies = Movie::query()->with('movieVersions')->latest('id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('movies'));
    }


    public function create()
    {
        $ratings = Movie::RATINGS;
        $versions = Movie::VERSIONS;
        // $typeSeats = TypeSeat::all();
        // $typeRooms = TypeRoom::all();
        return view(self::PATH_VIEW . __FUNCTION__, compact(['ratings', 'versions',]));
    }


    public function store(StoreMovieRequest $request)
    {
        try {
            $dataMovie = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category' => $request->category,
                'description' => $request->description,
                'director' => $request->director,
                'cast' => $request->cast,
                'rating' => $request->rating,
                'duration' => $request->duration,
                'release_date' => $request->release_date,
                'end_date' => $request->end_date,
                'trailer_url' => $request->trailer_url,
                'surcharge' => $request->surcharge,
                'surcharge_desc' => $request->surcharge_desc,
                'is_active' => isset($request->is_active) ? 1 : 0,
                'is_hot' => isset($request->is_hot) ? 1 : 0,
            ];
            if ($request->action === 'publish') {
                $dataMovie['is_publish'] = 1;
            }
            DB::transaction(function () use ($request, $dataMovie) {

                if ($request->img_thumbnail) {
                    $dataMovie['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $request->img_thumbnail);
                }

                $movie = Movie::create($dataMovie);

                foreach ($request->versions ?? [] as $version) {
                    MovieVersion::create([
                        'movie_id' => $movie->id,
                        'name' => $version
                    ]);
                }
            });

            return redirect()
                ->route('admin.movies.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function show(Movie $movie)
    {
        $movieVersions = $movie->movieVersions()->pluck('name')->all();
        $ratings = Movie::RATINGS;
        $versions = Movie::VERSIONS;
        $movieReviews = $movie->movieReview()->get();
        $totalReviews = $movieReviews->count();
        $averageRating = $totalReviews > 0 ? $movieReviews->avg('rating') : 0;
        $starCounts = [];
        for ($i = 1; $i <= 10; $i++) {
            $starCounts[$i] = $movieReviews->where('rating', $i)->count();
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact(['ratings', 'versions', 'movie', 'movieVersions', 'movieReviews', 'totalReviews', 'averageRating', 'starCounts']));
    }

    public function edit(Movie $movie)
    {

        $movieVersions = $movie->movieVersions()->pluck('name')->all();
        $ratings = Movie::RATINGS;
        $versions = Movie::VERSIONS;
        // $typeSeats = TypeSeat::all();
        // $typeRooms = TypeRoom::all();


        return view(self::PATH_VIEW . __FUNCTION__, compact('ratings', 'versions', 'movie', 'movieVersions'));
    }
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        try {
            $dataMovie = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category' => $request->category,
                'description' => $request->description,
                'director' => $request->director,
                'cast' => $request->cast,
                'rating' => $request->rating,
                'trailer_url' => $request->trailer_url,
                'surcharge' => $request->surcharge,
                'surcharge_desc' => $request->surcharge_desc,
                'is_active' => isset($request->is_active) ? 1 : 0,
                'is_hot' => isset($request->is_hot) ? 1 : 0,
            ];

            // Kiểm tra trạng thái xuất bản
            if (!$movie->is_publish) {
                // Trường hợp chưa xuất bản: Được phép sửa tất cả thông tin
                $dataMovie['duration'] = $request->duration;
                $dataMovie['start_date'] = $request->start_date;
                $dataMovie['end_date'] = $request->end_date;

                // Nếu nhấn nút xuất bản, cập nhật trạng thái is_publish
                if ($request->action === 'publish') {
                    $dataMovie['is_publish'] = 1;
                }
            }


            DB::transaction(function () use ($dataMovie, $request, $movie) {

                if ($request->img_thumbnail) {
                    $dataMovie['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $request->img_thumbnail);
                    // Lưu lại đường dẫn của ảnh hiện tại để so sánh sau
                    $ImgThumbnailCurrent = $movie->img_thumbnail;
                } else {
                    // Nếu không có ảnh mới, giữ nguyên ảnh cũ
                    unset($dataMovie['img_thumbnail']);
                }
                $isPublishOld = $movie->is_publish;
                $movie->update($dataMovie);

                // Nếu có ảnh mới và ảnh mới khác với ảnh cũ, xóa ảnh cũ khỏi hệ thống
                if (!empty($ImgThumbnailCurrent) && ($dataMovie['img_thumbnail'] ?? null) != $ImgThumbnailCurrent && Storage::exists($ImgThumbnailCurrent)) {
                    Storage::delete($ImgThumbnailCurrent);
                }
                if (!$isPublishOld) {
                    $movie->movieVersions()->delete(); // Xóa cũ & thêm mới
                    foreach ($request->versions ?? [] as $version) {
                        MovieVersion::create([
                            'movie_id' => $movie->id,
                            'name' => $version
                        ]);
                    }
                }
            });

            return redirect()
                ->back()
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public  function selectedTab(Request $request){
        $tabKey = $request->tab_key;
        session(['movies.selected_tab' => $tabKey]);
        return response()->json(['message' => 'Tab saved', 'tab' => $tabKey]);
    }

    public function destroy(Movie $movie)
    {
        try {
            if (!$movie->is_publish || $movie->showtimes()->doesntExist() ) {
                $movie->delete();
                return redirect()
                    ->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
            }

            return redirect()
                ->route('admin.movies.index')->with('error', 'Phim đã được xuất bản & có suất chiếu, không thể xóa!');
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.movies.index')->with('error', $th->getMessage());
        }
    }
}
