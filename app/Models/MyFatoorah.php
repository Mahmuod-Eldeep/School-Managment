<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyFatoorah extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'Payment_Status',
        'Country',
        'Currency',
        'Payment_Id',

    ];
    protected $hidden = [

        'updated_at',
        'created_at'
    ];

    public function Payment(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
