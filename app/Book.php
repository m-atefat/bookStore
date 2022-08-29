<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder filter(array $data)
 */
class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'description',
    ];

    protected $with = [
        'authors'
    ];

    protected $withCount = [
        'reviews'
    ];

    protected static function booted()
    {
        static::addGlobalScope('addReviewAvg', function (Builder $builder) {
            $builder->withAvg('reviews', 'review');
        });
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class);
    }
}
