<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'category_id',
        'cover_image',
    ];

    /**
     * Get the category of the content.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
        return $this->belongsToMany(FavoriteList::class, 'favorite_list_content');
    }

    /**
     * Get the seasons for the content.
     */
    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    /**
     * Get the ratings for the content.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the reviews for the content.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
