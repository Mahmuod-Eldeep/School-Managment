<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyFatoorah extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'Payment_Status',
        'Country',
        'Currency',
        'PaymentId',

    ];



    public function Payment(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
