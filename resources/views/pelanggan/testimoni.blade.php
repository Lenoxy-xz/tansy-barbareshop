@extends('layouts.pelanggan')
@section('title','Testimoni')
@section('content')
<div class="mb-4">
    <h4 class="text-white fw-bold mb-1">Testimoni & Rating</h4>
    <p class="text-secondary small mb-0">Bagikan pengalaman Anda bersama Tansy Barbershop.</p>
</div>

@if($pernahSelesai)
<div class="stat-card mb-4">
    <h6 class="text-warning mb-3"><i class="fas fa-pen me-2"></i>Tulis Testimoni</h6>
    <form method="POST" action="{{ route('pelanggan.testimoni.store') }}">
        @csrf
        <div class="mb-3">
            <label class="text-secondary small mb-2 d-block">Rating</label>
            <div class="d-flex gap-2" id="starContainer">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star fs-3 star-btn" data-value="{{ $i }}" style="color:#555;cursor:pointer;transition:color 0.15s;" onclick="setRating({{ $i }})"></i>
                @endfor
            </div>
            <input type="hidden" name="rating" id="inputRating" value="0">
        </div>
        <div class="mb-3">
            <label class="text-secondary small mb-2 d-block">Komentar</label>
            <textarea name="komentar" class="form-control" rows="4"
                      placeholder="Ceritakan pengalaman Anda..."
                      style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;"
                      required></textarea>
        </div>
        <button type="submit" class="btn btn-gold"><i class="fas fa-paper-plane me-1"></i>Kirim Testimoni</button>
    </form>
</div>
@else
<div class="stat-card mb-4 text-center py-4">
    <i class="fas fa-lock text-secondary fs-2 mb-3 d-block"></i>
    <p class="text-secondary mb-2">Selesaikan minimal 1 booking untuk memberikan testimoni.</p>
    <a href="{{ route('pelanggan.booking.index') }}" class="btn btn-gold btn-sm">Buat Booking</a>
</div>
@endif

<div class="stat-card">
    <h6 class="text-warning mb-3"><i class="fas fa-history me-2"></i>Testimoni Saya</h6>
    @if($testimoniList->isEmpty())
        <p class="text-secondary text-center py-3">Belum ada testimoni.</p>
    @else
        @foreach($testimoniList as $t)
        <div class="p-3 rounded mb-3" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-warning mb-1">
                        @for($s=1;$s<=5;$s++)<i class="fas fa-star {{ $s<=$t->rating?'':'text-secondary' }}"></i>@endfor
                    </div>
                    <p class="text-secondary small mb-0">{{ $t->komentar }}</p>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    @php $cls = ['menunggu'=>'warning','disetujui'=>'success','ditolak'=>'danger'][$t->status] ?? 'secondary'; @endphp
                    <span class="badge bg-{{ $cls }} text-{{ $cls==='warning'?'dark':'white' }}">{{ ucfirst($t->status) }}</span>
                    <form method="POST" action="{{ route('pelanggan.testimoni.destroy', $t->id_testimoni) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus testimoni ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
@push('scripts')
<script>
function setRating(val) {
    document.getElementById('inputRating').value = val;
    document.querySelectorAll('.star-btn').forEach((star,i) => { star.style.color = i < val ? '#c9a84c' : '#555'; });
}
</script>
@endpush
