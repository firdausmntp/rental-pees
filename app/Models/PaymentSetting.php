<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'method',
        'label',
        'enabled',
        'auto_cancel_pending',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'auto_cancel_pending' => 'boolean',
    ];
}
