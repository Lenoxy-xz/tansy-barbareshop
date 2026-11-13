<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Testimoni extends Model {
    protected $table = 'testimoni';
    protected $primaryKey = 'id_testimoni';
    public $timestamps = false;
    protected $fillable = ['id_user','rating','komentar','status'];
    public function user() { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
}
