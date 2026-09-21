<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = Pengeluaran::with('kategoriPengeluaran')->orderBy('tanggal_pengeluaran', 'desc')->get();
        $kategori = KategoriPengeluaran::all();
        return view('owner.pengeluaran.index', compact('pengeluaran', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
            'nominal' => 'required|integer|min:1',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        Pengeluaran::create($request->all());
        return back()->with('success', 'Catatan pengeluaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
