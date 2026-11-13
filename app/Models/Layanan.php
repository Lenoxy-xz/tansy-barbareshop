<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Layanan extends Model {
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    public $timestamps = false;
    protected $fillable = ['nama_layanan','deskripsi','harga','estimasi_waktu','foto'];
    protected $casts = ['harga' => 'decimal:2'];
    public function bookings() { return $this->hasMany(Booking::class, 'id_layanan', 'id_layanan'); }
    public function getHargaFormatAttribute(): string { return 'Rp '.number_format($this->harga, 0, ',', '.'); }
    public function getFotoUrlAttribute(): ?string { return $this->foto ? asset('uploads/layanan/'.$this->foto) : null; }
}
