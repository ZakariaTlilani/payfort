<?php

namespace zakariatlilani\payfort\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $guarded = [];

    protected $table = "payment_transactions";

    public $incrementing = false;

    protected $keyType = "string";

    protected $casts = [
        'query' => 'array',
    ];
}