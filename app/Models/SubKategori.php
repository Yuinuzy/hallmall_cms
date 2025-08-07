<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    protected $table = 'merchantkategori_sub';

    protected $fillable = [
        'id_kategori',
        'name_kategori_sub',
        'slug_kategori_sub',
        'is_disabled',
        'create_date',
        'create_by',
        'modify_date',
        'modify_by',
    ];

    public $timestamps = false;

    public function merchantKategori()
    {
        return $this->belongsTo(MerchantKategori::class, 'id_kategori');
    }
}
