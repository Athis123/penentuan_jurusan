<?php

namespace App\Models\Master;
use App\Models\Data\Order;
use App\Models\Data\RepeatOrder;

use Illuminate\Database\Eloquent\Model;

class MasterStatusApproval extends Model
{
    protected $table = 'master_status_approval';
    protected $fillable = ['status', 'keterangan', 'color'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'status_approval_id');
    }

    public function repeadorder()
    {
        return $this->hasMany(RepeatOrder::class, 'status_approval_id');
    }
}
