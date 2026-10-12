<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengeluaran;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengeluaranLaporanController extends Controller
{
    /**
     * Memvalidasi dan mengekstrak tanggal & filter kategori dari request
     */
    private function resolveFilters(Request $request): array
    {
        $defaultDari = now()->startOfMonth()->format('Y-m-d');
        $defaultSampai = now()->format('Y-m-d');

        $dari = $request->query('dari', $defaultDari);
        $sampai = $request->query('sampai', $defaultSampai);
        $kategoriId = $request->query('kategori_id');

        try {
            $start = Carbon::createFromFormat('Y-m-d', $dari)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $sampai)->endOfDay();
        } catch (\Throwable $e) {
            return [
                'error' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'kategori_id' => null,
            ];
        }

        if ($dari > $sampai) {
            return [
                'error' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'kategori_id' => null,
            ];
        }

        if ($start->diffInDays($end) > 366) {
            return [
                'error' => 'Rentang filter maksimal adalah 366 hari (1 tahun).',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'kategori_id' => null,
            ];
        }

        // Normalisasi kategori_id (jika string kosong atau 0, anggap null)
        $kategoriFilter = (!empty($kategoriId) && is_numeric($kategoriId)) ? (int) $kategoriId : null;

        return [
            'error' => null,
            'dari' => $dari,
            'sampai' => $sampai,
            'kategori_id' => $kategoriFilter,
        ];
    }

    /**
     * Mengambil ringkasan & rekap per kategori pengeluaran
     */
    private function getReportData(string $dari, string $sampai, ?int $kategoriId): array
    {
        $baseQuery = Pengeluaran::whereBetween('tanggal_pengeluaran', [$dari, $sampai]);

        if ($kategoriId) {
            $baseQuery->where('kategori_pengeluaran_id', $kategoriId);
        }

        $totalPengeluaran = (int) (clone $baseQuery)->sum('nominal');
        $countPengeluaran = (int) (clone $baseQuery)->count();

        // Pengeluaran Terbesar
        $terbesarRow = (clone $baseQuery)->with('kategori')->orderBy('nominal', 'desc')->first();
        $pengeluaranTerbesar = [
            'nominal' => $terbesarRow ? (int) $terbesarRow->nominal : 0,
            'kategori' => $terbesarRow ? ($terbesarRow->kategori->nama_kategori ?? 'Tanpa Kategori') : '-',
            'nama' => $terbesarRow ? $terbesarRow->nama_pengeluaran : '-',
        ];

        // Rekap Per Kategori
        $kategoriMap = KategoriPengeluaran::all()->keyBy('id');
        $rekapRaw = (clone $baseQuery)
            ->selectRaw('kategori_pengeluaran_id, COUNT(*) as jml_catatan, SUM(nominal) as total_nominal')
            ->groupBy('kategori_pengeluaran_id')
            ->orderByDesc('total_nominal')
            ->get();

        $rekapKategori = $rekapRaw->map(function ($row) use ($kategoriMap, $totalPengeluaran) {
            $kat = $row->kategori_pengeluaran_id ? $kategoriMap->get($row->kategori_pengeluaran_id) : null;
            $namaKategori = $kat ? $kat->nama_kategori : 'Tanpa Kategori';
            $persentase = $totalPengeluaran > 0 ? round(($row->total_nominal / $totalPengeluaran) * 100, 1) : 0;

            return [
                'kategori' => $namaKategori,
                'jml_catatan' => (int) $row->jml_catatan,
                'total_nominal' => (int) $row->total_nominal,
                'persentase' => $persentase,
            ];
        });

        // Nama kategori terpilih jika ada filter kategori
        $namaKategoriAktif = null;
        if ($kategoriId) {
            $katAktif = $kategoriMap->get($kategoriId);
            $namaKategoriAktif = $katAktif ? $katAktif->nama_kategori : 'Kategori #' . $kategoriId;
        }

        $semuaKategori = KategoriPengeluaran::orderBy('nama_kategori', 'asc')->get();

        return [
            'baseQuery' => $baseQuery,
            'totalPengeluaran' => $totalPengeluaran,
            'countPengeluaran' => $countPengeluaran,
            'pengeluaranTerbesar' => $pengeluaranTerbesar,
            'rekapKategori' => $rekapKategori,
            'semuaKategori' => $semuaKategori,
            'namaKategoriAktif' => $namaKategoriAktif,
        ];
    }

    /**
     * Halaman layar laporan pengeluaran (dengan pagination 15 baris)
     */
    public function index(Request $request)
    {
        $resolved = $this->resolveFilters($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pengeluaran.index')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $kategoriId = $resolved['kategori_id'];

        $data = $this->getReportData($dari, $sampai, $kategoriId);

        // Pagination rincian pengeluaran (15 baris per halaman)
        $rincianPengeluaran = (clone $data['baseQuery'])
            ->with(['kategori', 'user'])
            ->orderBy('tanggal_pengeluaran', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'page_pengeluaran')
            ->withQueryString();

        return view('owner.laporan-pengeluaran.index', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'kategori_id' => $kategoriId,
            'rincianPengeluaran' => $rincianPengeluaran,
        ]));
    }

    /**
     * Halaman cetak laporan pengeluaran (A4 standalone, tanpa pagination)
     */
    public function cetak(Request $request)
    {
        $resolved = $this->resolveFilters($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pengeluaran.index')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $kategoriId = $resolved['kategori_id'];

        $data = $this->getReportData($dari, $sampai, $kategoriId);

        // Seluruh baris tanpa pagination untuk cetak
        $rincianPengeluaran = (clone $data['baseQuery'])
            ->with(['kategori', 'user'])
            ->orderBy('tanggal_pengeluaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('owner.laporan-pengeluaran.cetak', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'kategori_id' => $kategoriId,
            'rincianPengeluaran' => $rincianPengeluaran,
        ]));
    }
}
