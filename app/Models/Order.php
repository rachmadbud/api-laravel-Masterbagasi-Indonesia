<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $fillable = ['id_user', 'id_product', 'id_voucher', 'total', 'qty', 'total_setelah_discount'];

    public function createOrder($dataArray)
    {
        $create = Order::create($dataArray);
        return $create;
    }
}
