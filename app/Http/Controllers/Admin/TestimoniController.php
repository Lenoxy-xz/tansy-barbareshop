<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimoni::with('user')->orderByDesc('id_testimoni');
        if ($request->status) $query->where('status', $request->status);
        $testimoni = $query->get();
        return view('admin.testimoni', compact('testimoni'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id_testimoni' => 'required|exists:testimoni,id_testimoni',
            'status'       => 'required|in:menunggu,disetujui,ditolak',
        ]);
        Testimoni::findOrFail($request->id_testimoni)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Testimoni::findOrFail($id)->delete();
        return back()->with('success', 'Testimoni berhasil dihapus!');
    }
}
