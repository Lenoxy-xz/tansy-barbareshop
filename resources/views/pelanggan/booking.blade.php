@extends('layouts.pelanggan')
@section('title','Booking Layanan')
@section('content')
<div class="mb-4">
    <h4 class="text-white fw-bold mb-1">Booking Layanan</h4>
    <p class="text-secondary small mb-0">Pilih layanan dan masukkan waktu yang Anda inginkan.</p>
</div>

@if($errors->any())
<div class="alert mb-4" style="background:rgba(220,53,69,0.15);border:1px solid rgba(220,53,69,0.3);color:#ff6b6b;">
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="stat-card">
            <form method="POST" action="{{ route('pelanggan.booking.store') }}" id="formBooking">
                @csrf

                {{-- Pilih Layanan --}}
                <div class="mb-4">
                    <label class="text-secondary small fw-semibold mb-3 d-block">
                        <i class="fas fa-scissors text-warning me-2"></i>PILIH LAYANAN
                    </label>
                    <div class="row g-3">
                        @foreach($layanan as $l)
                        <div class="col-md-6">
                            <div class="layanan-option p-3 rounded {{ $selectedLayanan === $l->id_layanan ? 'selected' : '' }}"
                                 style="border:1px solid {{ $selectedLayanan===$l->id_layanan ? '#c9a84c':'rgba(255,255,255,0.1)' }};background:{{ $selectedLayanan===$l->id_layanan ? 'rgba(201,168,76,0.1)':'' }};cursor:pointer;transition:all 0.2s;"
                                 onclick="pilihLayanan({{ $l->id_layanan }}, {{ $l->harga }}, {{ $l->estimasi_waktu }}, '{{ addslashes($l->nama_layanan) }}', this)">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-white fw-semibold small">{{ $l->nama_layanan }}</div>
                                        <div class="text-secondary mt-1" style="font-size:0.75rem;">
                                            <i class="fas fa-clock me-1"></i>{{ $l->estimasi_waktu }} menit
                                        </div>
                                    </div>
                                    <span class="text-warning fw-bold small">{{ $l->harga_format }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="id_layanan" id="inputIdLayanan" value="{{ $selectedLayanan ?: '' }}">
                </div>

                {{-- Tanggal & Jam Input Manual --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-secondary small fw-semibold mb-2 d-block">
                            <i class="fas fa-calendar text-warning me-2"></i>TANGGAL BOOKING
                        </label>
                        <input type="date" name="tanggal_booking" id="inputTanggal"
                               class="form-control"
                               style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;"
                               min="{{ today()->format('Y-m-d') }}"
                               value="{{ old('tanggal_booking', today()->format('Y-m-d')) }}"
                               onchange="updateInfoWaktu(this.value)"
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small fw-semibold mb-2 d-block">
                            <i class="fas fa-clock text-warning me-2"></i>JAM BOOKING
                            <span class="text-secondary ms-1" style="font-size:0.7rem;">(10:00 - 20:00)</span>
                        </label>
                        <input type="time" name="jam_booking" id="inputJam"
                               class="form-control"
                               style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;"
                               min="10:00" max="20:00"
                               value="{{ old('jam_booking') }}"
                               onchange="updateRingkasanJam(this.value)"
                               required>
                        <small class="text-secondary mt-1 d-block">
                            <i class="fas fa-info-circle me-1 text-warning"></i>
                            Sistem otomatis cek ketersediaan waktu.
                        </small>
                    </div>
                </div>

                {{-- Info Waktu Terblokir --}}
                @if(count($waktuTerblokir) > 0)
                <div class="mb-4 p-3 rounded" style="background:rgba(220,53,69,0.08);border:1px solid rgba(220,53,69,0.25);">
                    <p class="text-danger small fw-semibold mb-2">
                        <i class="fas fa-ban me-2"></i>Waktu terblokir pada {{ \Carbon\Carbon::parse($tanggalCek)->format('d/m/Y') }}:
                    </p>
                    @foreach($waktuTerblokir as $blokir)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-danger">{{ $blokir['mulai_str'] }} - {{ $blokir['selesai_str'] }}</span>
                        <span class="text-secondary small">{{ $blokir['nama_layanan'] }} ({{ $blokir['estimasi'] }} menit)</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="mb-4 p-3 rounded" style="background:rgba(25,135,84,0.08);border:1px solid rgba(25,135,84,0.25);">
                    <p class="text-success small mb-0">
                        <i class="fas fa-check-circle me-2"></i>Semua waktu tersedia pada tanggal ini.
                    </p>
                </div>
                @endif

                {{-- Metode Pembayaran --}}
                <div class="mb-4">
                    <label class="text-secondary small fw-semibold mb-3 d-block">
                        <i class="fas fa-credit-card text-warning me-2"></i>METODE PEMBAYARAN
                    </label>
                    <div class="row g-3">
                        @foreach([['cash','Bayar di Tempat','fa-money-bill-wave'],['transfer','Transfer Bank','fa-university'],['qris','QRIS','fa-qrcode']] as [$val,$label,$icon])
                        <div class="col-4">
                            <div class="metode-option text-center p-3 rounded {{ old('metode_pembayaran')===$val ? 'selected':'' }}"
                                 style="border:1px solid {{ old('metode_pembayaran')===$val ? '#c9a84c':'rgba(255,255,255,0.1)' }};background:{{ old('metode_pembayaran')===$val ? 'rgba(201,168,76,0.1)':'' }};cursor:pointer;transition:all 0.2s;"
                                 onclick="pilihMetode('{{ $val }}', '{{ $label }}', this)">
                                <i class="fas {{ $icon }} text-warning d-block mb-2 fs-4"></i>
                                <span style="font-size:0.8rem;color:#ccc;">{{ $label }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="metode_pembayaran" id="inputMetode" value="{{ old('metode_pembayaran') }}">

                    {{-- Info Cash --}}
                    <div id="infoCash" class="mt-3 p-3 rounded" style="display:none;background:rgba(25,135,84,0.1);border:1px solid rgba(25,135,84,0.3);">
                        <p class="text-success small mb-0"><i class="fas fa-info-circle me-2"></i>Bayar langsung di kasir saat datang ke barbershop.</p>
                    </div>
                    {{-- Info Transfer --}}
                    <div id="infoTransfer" class="mt-3 p-3 rounded" style="display:none;background:rgba(13,110,253,0.1);border:1px solid rgba(13,110,253,0.3);">
                        <p class="text-warning small fw-semibold mb-2"><i class="fas fa-university me-2"></i>Rekening Transfer:</p>
                        <table class="table table-sm mb-2" style="color:#ccc;font-size:0.85rem;">
                            <tr><td style="color:#888;width:40%;">Bank</td><td>: <strong class="text-white">BCA</strong></td></tr>
                            <tr><td style="color:#888;">No. Rekening</td><td>: <strong class="text-white">1234567890</strong></td></tr>
                            <tr><td style="color:#888;">Atas Nama</td><td>: <strong class="text-white">Tansy Barbershop</strong></td></tr>
                        </table>
                        <p class="text-secondary small mb-0"><i class="fas fa-exclamation-triangle text-warning me-1"></i>Setelah transfer, upload bukti di menu <strong class="text-warning">Pembayaran</strong>.</p>
                    </div>
                    {{-- Info QRIS --}}
                    <div id="infoQris" class="mt-3 p-3 rounded" style="display:none;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);">
                        <p class="text-warning small fw-semibold mb-2"><i class="fas fa-qrcode me-2"></i>Scan QRIS:</p>
                        <div class="text-center my-2">
                            <img src="{{ asset('assets/foto/qris.jpeg') }}" alt="QRIS"
                                 style="max-width:200px;border-radius:10px;border:3px solid #c9a84c;"
                                 onerror="this.style.display='none';document.getElementById('qrisPlaceholder').style.display='block';">
                            <div id="qrisPlaceholder" style="display:none;">
                                <div class="p-4 rounded d-inline-block" style="background:#2a2a2a;border:2px dashed #c9a84c;">
                                    <i class="fas fa-qrcode text-warning" style="font-size:4rem;"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-secondary small mb-0 text-center"><i class="fas fa-exclamation-triangle text-warning me-1"></i>Setelah bayar, upload bukti di menu <strong class="text-warning">Pembayaran</strong>.</p>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label class="text-secondary small fw-semibold mb-2 d-block">
                        <i class="fas fa-sticky-note text-warning me-2"></i>CATATAN (Opsional)
                    </label>
                    <textarea name="catatan" class="form-control" rows="2"
                              placeholder="Contoh: Jangan terlalu pendek di bagian samping..."
                              style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;">{{ old('catatan') }}</textarea>
                </div>

                <button type="button" class="btn btn-gold w-100 py-2" onclick="submitBooking()">
                    <i class="fas fa-calendar-check me-2"></i>Konfirmasi Booking
                </button>
            </form>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="col-lg-4">
        <div class="stat-card" style="position:sticky;top:80px;">
            <h6 class="text-warning mb-3"><i class="fas fa-receipt me-2"></i>Ringkasan Booking</h6>
            <div id="ringkasanLayanan" class="mb-1 text-secondary small">Belum ada layanan dipilih</div>
            <div id="ringkasanEstimasi" class="text-secondary mb-2" style="font-size:0.75rem;"></div>
            <div id="ringkasanHarga" class="text-warning fw-bold fs-5 mb-3"></div>
            <hr style="border-color:rgba(255,255,255,0.08);">
            <div class="text-secondary small">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tanggal:</span>
                    <span id="ringkasanTanggal" class="text-white">{{ today()->format('Y-m-d') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Jam Mulai:</span>
                    <span id="ringkasanJam" class="text-white">-</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Jam Selesai:</span>
                    <span id="ringkasanJamSelesai" class="text-warning">-</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Metode:</span>
                    <span id="ringkasanMetode" class="text-white">-</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.layanan-option.selected,.metode-option.selected { border-color:#c9a84c !important; background:rgba(201,168,76,0.1) !important; }
input[type="time"]::-webkit-calendar-picker-indicator { filter:invert(1); }
</style>
@endpush

@push('scripts')
<script>
let selNama='', selHarga=0, selEstimasi=0;
let selId = {{ $selectedLayanan ?: 0 }};

@if($selectedLayanan)
const initEl = document.querySelector('.layanan-option.selected');
if (initEl) {
    selNama     = initEl.querySelector('.text-white.fw-semibold').textContent.trim();
    selHarga    = {{ $layanan->firstWhere('id_layanan', $selectedLayanan)?->harga ?? 0 }};
    selEstimasi = {{ $layanan->firstWhere('id_layanan', $selectedLayanan)?->estimasi_waktu ?? 0 }};
    document.getElementById('ringkasanLayanan').textContent  = selNama;
    document.getElementById('ringkasanEstimasi').textContent = 'Estimasi: '+selEstimasi+' menit';
    document.getElementById('ringkasanHarga').textContent    = 'Rp '+selHarga.toLocaleString('id-ID');
}
@endif

function pilihLayanan(id, harga, estimasi, nama, el) {
    document.querySelectorAll('.layanan-option').forEach(e => {
        e.classList.remove('selected'); e.style.borderColor='rgba(255,255,255,0.1)'; e.style.background='';
    });
    el.classList.add('selected'); el.style.borderColor='#c9a84c'; el.style.background='rgba(201,168,76,0.1)';
    document.getElementById('inputIdLayanan').value = id;
    selId=id; selNama=nama; selHarga=harga; selEstimasi=estimasi;
    document.getElementById('ringkasanLayanan').textContent  = nama;
    document.getElementById('ringkasanEstimasi').textContent = 'Estimasi: '+estimasi+' menit';
    document.getElementById('ringkasanHarga').textContent    = 'Rp '+harga.toLocaleString('id-ID');
    updateJamSelesai();
}

function updateRingkasanJam(jam) {
    document.getElementById('ringkasanJam').textContent = jam || '-';
    updateJamSelesai();
}

function updateJamSelesai() {
    const jam = document.getElementById('inputJam').value;
    if (!jam || !selEstimasi) { document.getElementById('ringkasanJamSelesai').textContent='-'; return; }
    const [h,m]   = jam.split(':');
    const selesai = parseInt(h)*60 + parseInt(m) + selEstimasi;
    document.getElementById('ringkasanJamSelesai').textContent =
        String(Math.floor(selesai/60)).padStart(2,'0') + ':' + String(selesai%60).padStart(2,'0');
}

function updateInfoWaktu(tanggal) {
    document.getElementById('ringkasanTanggal').textContent = tanggal;
    const id = document.getElementById('inputIdLayanan').value;
    window.location.href = '{{ route("pelanggan.booking.index") }}?cek_tanggal='+tanggal+(id?'&layanan='+id:'');
}

function pilihMetode(metode, label, el) {
    document.querySelectorAll('.metode-option').forEach(e => {
        e.classList.remove('selected'); e.style.borderColor='rgba(255,255,255,0.1)'; e.style.background='';
    });
    el.classList.add('selected'); el.style.borderColor='#c9a84c'; el.style.background='rgba(201,168,76,0.1)';
    document.getElementById('inputMetode').value = metode;
    document.getElementById('ringkasanMetode').textContent = label;
    document.getElementById('infoCash').style.display='none';
    document.getElementById('infoTransfer').style.display='none';
    document.getElementById('infoQris').style.display='none';
    if (metode==='cash')     document.getElementById('infoCash').style.display='block';
    if (metode==='transfer') document.getElementById('infoTransfer').style.display='block';
    if (metode==='qris')     document.getElementById('infoQris').style.display='block';
}

function submitBooking() {
    if (!document.getElementById('inputIdLayanan').value) {
        Swal.fire({icon:'warning',title:'Perhatian',text:'Pilih layanan terlebih dahulu!',timer:2000,showConfirmButton:false}); return;
    }
    if (!document.getElementById('inputJam').value) {
        Swal.fire({icon:'warning',title:'Perhatian',text:'Masukkan jam booking!',timer:2000,showConfirmButton:false}); return;
    }
    if (!document.getElementById('inputMetode').value) {
        Swal.fire({icon:'warning',title:'Perhatian',text:'Pilih metode pembayaran!',timer:2000,showConfirmButton:false}); return;
    }
    const jam        = document.getElementById('inputJam').value;
    const jamSelesai = document.getElementById('ringkasanJamSelesai').textContent;
    Swal.fire({
        title: 'Konfirmasi Booking',
        html: `<p>Layanan: <b>${selNama}</b><br>Jam: <b>${jam} - ${jamSelesai}</b><br>Estimasi: <b>${selEstimasi} menit</b><br>Total: <b>Rp ${selHarga.toLocaleString('id-ID')}</b></p>`,
        icon: 'question', showCancelButton:true, confirmButtonText:'Ya, Booking!', cancelButtonText:'Batal', confirmButtonColor:'#c9a84c'
    }).then(r => { if (r.isConfirmed) document.getElementById('formBooking').submit(); });
}
</script>
@endpush
