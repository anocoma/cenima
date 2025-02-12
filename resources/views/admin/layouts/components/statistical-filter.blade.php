<div class="row">
    @if (Auth::user()->hasRole('System Admin'))
        <div class="col-md-2">
            <label class="mb-0">Chi nhánh</label>
        </div>
        <div class="col-md-2">
            <label class="mb-0">Rạp</label>
        </div>
    @endif
    <div class="col-md-2">
        <label class="mb-0">Ngày bắt đầu</label>
    </div>
    <div class="col-md-2">
        <label class="mb-0">Ngày kết thúc</label>
    </div>
</div>
<div class="row">

    @if (Auth::user()->hasRole('System Admin'))
        <div class="col-md-2">
            <select name="branch_id" id="branch" class="form-select py-2 px-2">
                <option value="">Tất cả chi nhánh</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ session('statistical.branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="cinema_id" id="cinema" class="form-select">
            </select>
        </div>
    @else
        <input type="hidden" name="cinema_id" value="{{ Auth::user()->cinema_id }}">
    @endif
    <div class="col-md-2">
        <input type="date" name="start_date" class="form-control" value="{{ session('statistical.start_date', $startDate) }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="end_date" class="form-control" value="{{ session('statistical.end_date', $endDate) }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-success" type="submit">
            <i class="ri-equalizer-fill me-1 align-bottom"></i>Lọc
        </button>
    </div>

</div>


{{-- <div class="row">
    <div class="col-md-10">
        <div class="row">
            @if (Auth::user()->hasRole('System Admin'))
                <div class="col-md-2">
                    <label class="mb-0">Chi nhánh</label>
                </div>
                <div class="col-md-2">
                    <label class="mb-0">Rạp</label>
                </div>
            @endif
            <div class="col-md-2">
                <label class="mb-0">Ngày bắt đầu</label>
            </div>
            <div class="col-md-2">
                <label class="mb-0">Ngày kết thúc</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <div class="row">
            @if (Auth::user()->hasRole('System Admin'))
                <div class="col-md-2">
                    <select name="branch_id" id="branch" class="form-select py-2 px-2">
                        <option value="">Tất cả chi nhánh</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="cinema_id" id="cinema" class="form-select">
                    </select>
                </div>
            @endif
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control"
                    value="{{ old('start_date', $startDate) }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control"
                    value="{{ old('end_date', $endDate) }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-success" type="submit">
                    <i class="ri-equalizer-fill me-1 align-bottom"></i>Lọc
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-2" align="right">
            <a href="{{ route('admin.statistical-movies') }}" class="btn btn-primary">Tổng
                quan</a>
    </div>
</div> --}}
