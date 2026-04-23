<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Data\Nilai;

class DataSiswa extends Model
{
    use HasFactory,SoftDeletes;

    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'id_siswa';
    protected $table = 'data_siswa';

    protected $fillable = [
        'nisn',
        'nama',
        'alamat',
        'jenis_kelamin',
        'no_hp',
        'tmp_lahir',
        'tgl_lahir',
        'asal_sekolah',
        'foto'
    ];

    public function penilaians()
    {
        return $this->hasMany(Nilai::class, 'id_siswa', 'id_siswa');
    }

}
