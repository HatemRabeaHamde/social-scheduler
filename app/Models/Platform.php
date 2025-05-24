<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Platform extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type'];

    public function posts(): BelongsToMany
{
    return $this->belongsToMany(Post::class, 'post_platform')
        ->withPivot(['status', 'failure_reason'])
        ->withTimestamps();
}

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
