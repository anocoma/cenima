@foreach ($vouchers as $voucher)
<div class="voucher-item">
    <div class="voucher-left">
        <div class="voucher-tag">Poly Voucher</div>
        <div class="voucher-info">
            <h4>{{ number_format($voucher->discount, 0, ',', '.') }} đk</h4>
            {{-- <p>Đơn tối thiểu ₫40k</p> --}}
        </div>
    </div>
    <div class="voucher-right">
        <button class="apply-btn">Áp dụng</button>
        <p class="voucher-status">Còn {{ $voucher->remaining_uses }} mã</p>
    </div>
</div>
@endforeach
