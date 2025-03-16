<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckDefaultKode;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PenyediaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemberitahuanController;
use App\Http\Controllers\NegosiasiHargaController;
use App\Http\Controllers\PenawaranHargaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('menu/kegiatan', [KegiatanController::class , 'index'])->middleware(CheckDefaultKode::class)->name('menu.kegiatan');
    Route::get('kegiatan/create', [KegiatanController::class , 'create'])->name('kegiatan.create');
    Route::get('kegiatan/edit/{id}', [KegiatanController::class , 'edit'])->name('kegiatan.edit');
    Route::patch('kegiatan/update/{id}', [KegiatanController::class , 'update'])->name('kegiatan.update');
    Route::post('kegiatan/store', [KegiatanController::class , 'store'])->name('kegiatan.store');
    Route::delete('menu/kegiatan/{id}', [KegiatanController::class , 'destroy'])->name('kegiatan.destroy');
    Route::get('kegiatan/detail/{id}', [KegiatanController::class , 'show'])->name('kegiatan.show');
    
    Route::get('menu/penyedia', [PenyediaController::class , 'index'])->middleware(CheckDefaultKode::class)->name('menu.penyedia');
    Route::get('penyedia/create', [PenyediaController::class , 'create'])->name('penyedia.create');
    Route::get('penyedia/edit/{id}', [PenyediaController::class , 'edit'])->name('penyedia.edit');
    Route::patch('penyedia/update/{id}', [PenyediaController::class , 'update'])->name('penyedia.update');
    Route::post('penyedia/store', [PenyediaController::class , 'store'])->name('penyedia.store');
    Route::delete('menu/penyedia/{id}', [PenyediaController::class , 'destroy'])->name('penyedia.destroy');
    
    Route::get('menu/pemberitahuan', [PemberitahuanController::class , 'index'])->name('menu.pemberitahuan');
    Route::post('pemberitahuan/create/{id}', [PemberitahuanController::class , 'create'])->name('pemberitahuan.create');
    Route::post('pemberitahuan/store', [PemberitahuanController::class , 'store'])->name('pemberitahuan.store');
    Route::get('pemberitahuan/render/{id}', [PemberitahuanController::class , 'render'])->name('pemberitahuan.render');
    
    Route::get('penawaran-harga', [PenawaranHargaController::class , 'index'])->name('penawaran');
    Route::post('penawaran-harga/create/{id}', [PenawaranHargaController::class , 'create'])->name('penawaran.create');
    Route::post('penawaran-harga/store', [PenawaranHargaController::class , 'store'])->name('penawaran.store');
    Route::get('penawaran-harga/render/{id}', [PenawaranHargaController::class , 'render'])->name('penawaran.render');
    
    Route::post('negosiasi/create/{id}', [NegosiasiHargaController::class , 'index'])->name('negosiasi.create');
    Route::post('negosiasi/store', [NegosiasiHargaController::class , 'store'])->name('negosiasi.store');
    Route::get('negosiasi/render/{id}', [NegosiasiHargaController::class , 'renderPDF'])->name('negosiasi.render');
    
    Route::post('/pembayaran/create/{id}' , [PembayaranController::class , 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/store' , [PembayaranController::class , 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/render/{id}' , [PembayaranController::class , 'render'])->name('pembayaran.render');
});

require __DIR__.'/auth.php';
