<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    protected $table      = 'booking';
    protected $primaryKey = 'id_booking';
    public $timestamps    = false;

    protected $fillable = [
        'kode_booking','id_user','id_layanan','tanggal_booking',
        'jam_booking','metode_pembayaran','catatan','status_booking','nomor_antrian'
    ];

    protected $casts = ['tanggal_booking' => 'date'];

    public function user()      { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
    public function layanan()   { return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan'); }
    public function pembayaran(){ return $this->hasOne(Pembayaran::class, 'id_booking', 'id_booking'); }

    public function getStatusBadgeAttribute(): string {
        return match($this->status_booking) {
            'pending'          => 'secondary',
            'diproses'         => 'primary',
            'sedang_melayani'  => 'warning',
            'hampir_selesai'   => 'info',
            'selesai'          => 'success',
            'dibatalkan'       => 'danger',
            default            => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string {
        return match($this->status_booking) {
            'pending'          => 'Pending',
            'diproses'         => 'Diproses',
            'sedang_melayani'  => 'Sedang Melayani',
            'hampir_selesai'   => 'Hampir Selesai (10 mnt)',
            'selesai'          => 'Selesai',
            'dibatalkan'       => 'Dibatalkan',
            default            => '-',
        };
    }

    public static function generateKode(): string {
        return 'TBS-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -4));
    }
}
