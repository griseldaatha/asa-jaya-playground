<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriProduk::withCount('produk')->get();
        return view('owner.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:25',
            'deskripsi' => 'nullable|string|max:100'
        ]);

        KategoriProduk::create($request->all());
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:25',
            'deskripsi' => 'nullable|string|max:100'
        ]);

        $kategori = KategoriProduk::findOrFail($id);
        $kategori->update($request->all());
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriProduk::withCount('produk')->findOrFail($id);
        
        // Hapus hanya jika belum digunakan produk
        if ($kategori->produk_count > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
