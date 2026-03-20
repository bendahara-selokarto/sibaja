<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithTenantScope;
use App\Http\Requests\KegiatanRequest;
use App\Models\Kegiatan;
use App\Models\Penyedia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    use InteractsWithTenantScope;

    /**
     * Menampilkan menu Penyedia
     */
    public function index(): View
    {
        $kegiatan = Kegiatan::where('kode_desa', Auth::user()->kode_desa)
            ->where('tahun_anggaran', Auth::user()->tahun_anggaran)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($kegiatan->isEmpty()) {
            return view('menu.kegiatan')->with('kegiatans', collect());
        }

        return view('menu.kegiatan')->with('kegiatans', $kegiatan);
    }

    public function create()
    {
        if (!Penyedia::cukupUntukKegiatan(Auth::user()->kode_desa)) {
            return redirect()
                ->route('menu.penyedia')
                ->with('error', Penyedia::pesanError(Auth::user()->kode_desa));
        }

        return view('form.kegiatan', [
            'kegiatan' => new Kegiatan(),
        ]);
    }

    public function store(KegiatanRequest $request)
    {
        try {
            Kegiatan::create($request->validated());
            noty()->success('berhasil ditambahkan');
        } catch (\Throwable $th) {
            noty()->error($th->getMessage());
        }

        return redirect()->route('menu.kegiatan');
    }

    public function show(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id, [
            'pemberitahuan.penawaran',
            'pemberitahuan.belanjas',
            'pemberitahuan.penyedias',
            'negosiasiHarga',
            'pembayaran',
        ]);
        abort_if($kegiatan === null, 404);

        $btn = [
            'penawaran-create' => false,
            'penawaran-delete' => false,
            'penawaran-render' => false,
            'negosiasi-create' => false,
            'negosiasi-render' => false,
            'pembayaran-create' => false,
            'pembayaran-render' => false,
        ];

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penawaran = collect();
        $penyedia = collect();
        $penyediaAda = collect();

        if ($pemberitahuan) {
            $penawaran = $pemberitahuan->penawaran ?? collect();
            $penyediaIds = collect($pemberitahuan->selectedPenyediaIds());
            $penyediaDenganPenawaranIds = $penawaran
                ->pluck('penyedia_id')
                ->unique()
                ->values();

            if ($penyediaIds->isNotEmpty()) {
                $penyedia = Penyedia::whereIn(
                    'id',
                    $penyediaIds->diff($penyediaDenganPenawaranIds)
                )->get();
            }

            if ($penyediaDenganPenawaranIds->isNotEmpty()) {
                $penyediaAda = Penyedia::whereIn('id', $penyediaDenganPenawaranIds)->get();
            }

            $btn['penawaran-create'] = $penyediaIds->isNotEmpty();
            $btn['penawaran-delete'] = $penawaran->isNotEmpty();
            $btn['penawaran-render'] = $penawaran->count() > 1;
            $btn['negosiasi-create'] = $btn['penawaran-render'] && $kegiatan->negosiasiHarga === null;
            $btn['negosiasi-render'] = $kegiatan->negosiasiHarga !== null;
            $btn['pembayaran-create'] = $btn['negosiasi-render'] && $kegiatan->pembayaran === null;
            $btn['pembayaran-render'] = $kegiatan->pembayaran !== null;
        }

        return view('menu.kegiatan-detail')
            ->with('kegiatan', $kegiatan)
            ->with('btn', $btn)
            ->with('pemberitahuan', $pemberitahuan)
            ->with('penawaran', $penawaran)
            ->with('penyediaAda', $penyediaAda)
            ->with('penyedia', $penyedia);
    }

    public function edit(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id);
        abort_if($kegiatan === null, 404);

        return view('form.kegiatan', compact('kegiatan'));
    }

    public function update(KegiatanRequest $request, string $id)
    {
        try {
            $kegiatan = $this->findTenantKegiatan($id);
            abort_if($kegiatan === null, 404);

            $kegiatan->update($request->validated());

            noty()->success('Data kegiatan berhasil diperbarui');
        } catch (\Throwable $th) {
            noty()->error($th->getMessage());
        }

        return redirect()->route('menu.kegiatan');
    }

    public function destroy(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id);
        abort_if($kegiatan === null, 404);

        if ($kegiatan->pemberitahuan !== null) {
            flash()->error('kegiatan sudah memiliki pemberitahuan');
            return back();
        }

        Kegiatan::where('id', $id)->delete();
        flash()->success('kegiatan berhasil diahpus');

        return redirect()->route('menu.kegiatan');
    }

    public function rekap(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id, [
            'pemberitahuan.penawaran.penyedia',
            'negosiasiHarga',
            'pembayaran',
        ]);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penawaranList = $pemberitahuan?->penawaran ?? collect();
        $penawaran = $penawaranList->firstWhere('is_winner', true)
            ?? $penawaranList->first();

        $namaPenyedia1 = optional(
            optional($penawaranList->firstWhere('is_winner', true))->penyedia
        )->nama_penyedia;

        $namaPenyedia2 = optional(
            optional($penawaranList->firstWhere('is_winner', false))->penyedia
        )->nama_penyedia;

        $negosiasiHarga = $kegiatan->negosiasiHarga;
        $pembayaran = $kegiatan->pembayaran;

        $pdf = Pdf::loadView('pdf.rekap', compact(
            'kegiatan',
            'pemberitahuan',
            'penawaran',
            'namaPenyedia1',
            'namaPenyedia2',
            'negosiasiHarga',
            'pembayaran'
        ));

        return $pdf->stream();
    }
}
