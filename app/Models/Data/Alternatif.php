<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Data\Nilai;

class Alternatif extends Model
{
    use HasFactory,SoftDeletes;

    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'id_alternatif';
    protected $table = 'alternatif';

    protected $fillable = [
        'kode',
        'nama',
        'golongan',
    ];

    public function penilaians()
    {
        return $this->hasMany(Nilai::class, 'id_alternatif', 'id_alternatif');
    }

}
