<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class NomorSurat
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
       $no_pengadaan = Auth::user()->desa;
    }
}
