<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Jurusan extends Model
{
    use HasFactory,SoftDeletes;

    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'id_jurusan';
    protected $table = 'jurusan';

    protected $fillable = [
        'kode',
        'nama',
    ];

}
