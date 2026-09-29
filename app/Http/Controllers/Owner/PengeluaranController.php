<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengeluaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = Pengeluaran::with('kategori')->orderBy('tanggal_pengeluaran', 'desc')->get();
        $kategori = KategoriPengeluaran::all();

        return view('owner.pengeluaran.index', compact('pengeluaran', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
            'nominal' => 'required|integer|min:1',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $kategoriObj = KategoriPengeluaran::find($request->kategori_pengeluaran_id);
        $namaPengeluaran = $request->input('keterangan') ?: ($kategoriObj ? $kategoriObj->nama_kategori : 'Pengeluaran');

        Pengeluaran::create([
            'user_id' => Auth::id(),
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'nama_pengeluaran' => $namaPengeluaran,
            'nominal' => $request->nominal,
            'deskripsi' => $request->input('keterangan'),
        ]);

        return back()->with('success', 'Catatan pengeluaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();

        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
