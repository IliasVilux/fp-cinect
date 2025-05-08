<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['title', 'episode_number', 'duration', 'season_id'];

    /**
     * Get the season that owns the episode.
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
