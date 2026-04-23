<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Kriteria extends Model
{
    use HasFactory,SoftDeletes;

    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'id_kriteria';
    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'bobot',
        'tipe',
    ];

}
