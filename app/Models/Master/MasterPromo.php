<?php

namespace App\Models\Master;
use App\Models\Data\Order;
use App\Models\Data\RepeatOrder;

use Illuminate\Database\Eloquent\Model;

class MasterPromo extends Model
{
    protected $table = 'master_promo';
    protected $fillable = ['kode', 'deskripsi'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'kode_promo_id');
    }

    public function repeadorder()
    {
        return $this->hasMany(RepeatOrder::class, 'kode_promo_id');
    }
}
