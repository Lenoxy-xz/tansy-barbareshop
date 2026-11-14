<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\Berkas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('pelanggan.profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id_user.',id_user',
            'no_hp' => 'required|string|max:20',
            'foto'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $foto = $user->foto;
        if ($request->hasFile('foto')) {
            Berkas::hapus($foto);
            $foto = Berkas::simpan($request->file('foto'), 'profil');
        }
        $user->update(['nama' => $request->nama, 'email' => $request->email, 'no_hp' => $request->no_hp, 'foto' => $foto]);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => ['required', 'confirmed', 'max:64', Password::defaults()],
        ], [
            'password_baru.confirmed' => 'Konfirmasi password tidak sesuai!',
            'password_baru.min'       => 'Password baru minimal 8 karakter!',
            'password_baru.max'       => 'Password baru maksimal 64 karakter!',
            'password_baru.password.mixed'     => 'Password baru harus mengandung huruf besar dan huruf kecil!',
            'password_baru.password.letters'   => 'Password baru harus mengandung huruf!',
            'password_baru.password.numbers'   => 'Password baru harus mengandung angka!',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->password_lama, $user->password)) return back()->withErrors(['password_lama' => 'Password lama tidak benar!']);
        $user->update(['password' => Hash::make($request->password_baru)]);
        return back()->with('success', 'Password berhasil diubah!');
    }
}
