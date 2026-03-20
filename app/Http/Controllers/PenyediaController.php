<?php

namespace App\Http\Controllers;

use App\Actions\Penyedia\AttachPenyediaAction;
use App\Actions\Penyedia\CreatePenyediaAction;
use App\Actions\Penyedia\DeletePenyediaAction;
use App\Actions\Penyedia\DetachPenyediaAction;
use App\Actions\Penyedia\UpdatePenyediaAction;
use App\Models\Penyedia;
use App\UseCases\Penyedia\ListBankPenyediaUseCase;
use App\UseCases\Penyedia\ListUserPenyediaUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\PenyediaRequest;

class PenyediaController extends Controller
{
    public function __construct(
        private readonly ListBankPenyediaUseCase $listBankPenyediaUseCase,
        private readonly ListUserPenyediaUseCase $listUserPenyediaUseCase,
        private readonly CreatePenyediaAction $createPenyediaAction,
        private readonly UpdatePenyediaAction $updatePenyediaAction,
        private readonly AttachPenyediaAction $attachPenyediaAction,
        private readonly DetachPenyediaAction $detachPenyediaAction,
        private readonly DeletePenyediaAction $deletePenyediaAction,
    ) {
    }

    public function index(){
       $data = $this->listBankPenyediaUseCase->execute(Auth::user());
        return view('submenu.bank-penyedia', ['penyedia' => $data ]);
    }

    public function attach(Penyedia $penyedia)
    {
        $user = Auth::user();
        $authorization = Gate::inspect('attach', $penyedia);

        if (!$authorization->allowed()) {
            return redirect()
                ->route('menu.penyedia')
                ->with('error', $authorization->message());
        }

        $this->attachPenyediaAction->execute($user, $penyedia);

        return redirect()
            ->route('menu.penyedia')
            ->with('success', 'Penyedia berhasil ditambahkan ke daftar Anda.');
    }

    public function detachPenyedia(Penyedia $penyedia)
    {
        $user = Auth::user();
        $authorization = Gate::inspect('detach', $penyedia);

        if (!$authorization->allowed()) {
            return redirect()
                ->route('menu.penyedia')
                ->with('error', $authorization->message());
        }

        $this->detachPenyediaAction->execute($user, $penyedia);

        return back()->with('success', 'Relasi penyedia berhasil dihapus');
    }

    public function show()
    {
        $penyedia = $this->listUserPenyediaUseCase->execute(Auth::user());

        return view('menu.penyedia', [
          'penyedia' => $penyedia
        ]);
    }


    public function create() {
        $penyedia = new Penyedia;
        
        return view('form.penyedia' , compact('penyedia'));
    }

    public function store(PenyediaRequest $request) {
        $this->authorize('create', Penyedia::class);
        $nama_penyedia = $request->nama_penyedia;
        $penyedia = $this->createPenyediaAction->execute($request);
        $user = Auth::user();
        $this->attachPenyediaAction->execute($user, $penyedia);
        flash()->success('Berhasi Menambah Penyedia ' . $nama_penyedia);
         
        
        return redirect()->route('menu.penyedia');
    }

    public function destroy(Penyedia $penyedia){
        $authorization = Gate::inspect('delete', $penyedia);

        if (!$authorization->allowed()) {
            return redirect()
                ->route('menu.penyedia')
                ->with('error', $authorization->message());
        }

        if (!$this->deletePenyediaAction->execute($penyedia)) {
            flash()->error('penyedia sudah dipakai dalam dokumen pengadaan');
            return back();
        }

        flash()->success('penyedia berhasil diahpus');

        return redirect()->route('menu.penyedia');
    }

    public function edit(Penyedia $penyedia){
        $authorization = Gate::inspect('update', $penyedia);

        if (!$authorization->allowed()) {
            return redirect()
                ->route('menu.penyedia')
                ->with('error', $authorization->message());
        }
        return view('form.penyedia', compact('penyedia'));
    }

    public function update(PenyediaRequest $request, Penyedia $penyedia){
        $authorization = Gate::inspect('update', $penyedia);

        if (!$authorization->allowed()) {
            return redirect()
                ->route('menu.penyedia')
                ->with('error', $authorization->message());
        }
        $this->updatePenyediaAction->execute($request, $penyedia);
        $this->attachPenyediaAction->execute(Auth::user(), $penyedia);
        flash()->success('Berhasi Update Penyedia');
        return redirect()->route('menu.penyedia');
    }
}
