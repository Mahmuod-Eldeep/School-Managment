<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
    ];




    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id");
    }
}
