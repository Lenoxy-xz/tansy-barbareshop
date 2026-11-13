<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use App\Models\Booking; use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimoniList = Testimoni::where('id_user', Auth::id())->orderByDesc('id_testimoni')->get();
        $pernahSelesai = Booking::where('id_user', Auth::id())->where('status_booking','selesai')->exists();
        return view('pelanggan.testimoni', compact('testimoniList','pernahSelesai'));
    }

    public function store(Request $request)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5', 'komentar' => 'required|string']);
        Testimoni::create(['id_user' => Auth::id(), 'rating' => $request->rating, 'komentar' => $request->komentar, 'status' => 'menunggu']);
        return back()->with('success', 'Testimoni berhasil dikirim! Menunggu persetujuan admin.');
    }

    public function destroy($id)
    {
        Testimoni::where('id_testimoni',$id)->where('id_user', Auth::id())->delete();
        return back()->with('success', 'Testimoni berhasil dihapus!');
    }
}
