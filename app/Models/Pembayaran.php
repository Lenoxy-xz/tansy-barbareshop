<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pembayaran extends Model {
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;
    protected $fillable = ['id_booking','total_bayar','bukti_pembayaran','status_pembayaran','tanggal_pembayaran'];
    protected $casts = ['total_bayar' => 'decimal:2', 'tanggal_pembayaran' => 'date'];
    public function booking() { return $this->belongsTo(Booking::class, 'id_booking', 'id_booking'); }
    public function getTotalFormatAttribute(): string { return 'Rp '.number_format($this->total_bayar, 0, ',', '.'); }
    public function getStatusBadgeAttribute(): string {
        return match($this->status_pembayaran) {
            'belum_dibayar' => 'secondary', 'menunggu_verifikasi' => 'warning', 'lunas' => 'success', default => 'secondary',
        };
    }
    public function getStatusLabelAttribute(): string {
        return match($this->status_pembayaran) {
            'belum_dibayar' => 'Belum Dibayar', 'menunggu_verifikasi' => 'Menunggu Verifikasi', 'lunas' => 'Lunas', default => '-',
        };
    }
}
