<?php

namespace App\Models\Master;
use App\Models\Data\Order;
use App\Models\Data\RepeatOrder;

use Illuminate\Database\Eloquent\Model;

class MasterAdv extends Model
{
    protected $table = 'master_adv';
    protected $fillable = ['kode', 'deskripsi'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'adv_id');
    }

    public function repeadorder()
    {
        return $this->hasMany(RepeatOrder::class, 'adv_id');
    }
}
