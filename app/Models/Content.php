<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'release_year',
        'duration',
        'genre_id',
        'cover_image',
    ];

    /**
     * Get the genre of the content.
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * the platforms that belongs to the content.
     */
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class);
    }

    /**
     * the list that belongs to the content.
     */
    public function favoriteLists(): BelongsToMany
    {
        return $this->belongsToMany(FavoriteList::class, 'content_favorite_list');
    }

    /**
     * Get the seasons for the content.
     */
    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class)->orderBy('season_number');
    }

    /**
     * Get the ratings for the content.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the rating for the content made by the user.
     */
    public function userRating(): HasOne
    {
        return $this->hasOne(Rating::class)->where('user_id', Auth::id());
    }

    /**
     * Get the reviews for the content.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the revciew for the content made by the user.
     */
    public function userReview(): HasOne
    {
        return $this->hasOne(Review::class)->where('user_id', Auth::id());
    }

    /**
     * Get all movies.
     */
    public function movies($query)
    {
        return $query->where('type', 'movie');
    }

    /**
     * Get all series.
     */
    public function series($query)
    {
        return $query->where('type', 'series');
    }

    /**
     * Get all animes.
     */
    public function animes($query)
    {
        return $query->where('type', 'anime');
    }
}
