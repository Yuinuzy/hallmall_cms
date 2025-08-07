<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantKategori extends Model
{
    protected $table = 'merchantkategori';

    protected $fillable = [
        'kategori',
        'slug',
        'img',
        'waktu_pengiriman',
        'waktu_respon',
        'order_by'
    ];

    public $timestamps = false; // karena tidak ada kolom created_at/updated_at

    public function subKategoris()
    {
        return $this->hasMany(SubKategori::class, 'id_kategori');
    }
}
