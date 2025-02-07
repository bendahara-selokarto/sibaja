<?php

namespace App;

enum Kategori :string
{
    case Kecil = 'dibawah 10 juta';
    case Sedang = '10 juta sampaidengan 200 juta';
    case Besar = 'diatas 200 juta';
}
