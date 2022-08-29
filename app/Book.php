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

    protected $appends = [
        'reviews_avg'
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

    public function scopeFilter(Builder $builder, array $data): Builder
    {
        $sortDirection = $data['sortDirection'] ?? 'ASC';

        if (isset($data['sortColumn']) && $data['sortColumn'] == 'title') {
            $builder->orderBy('title', $data['sortDirection'] ?? 'ASC');
        }

        if (isset($data['title'])) {
            $builder->where('title', 'LIKE', "%" . $data['title'] . "%");
        }

        if (isset($data['authors'])) {
            $builder->whereHas('authors', function ($query) use ($data) {
                $authorIds = explode(',', $data['authors']);
                $query->whereIn('id', $authorIds);
            });
        }

        if (isset($data['sortColumn']) && $data['sortColumn'] == 'avg_review') {
            $builder->orderBy('reviews_avg_review', $sortDirection);
        }

        return $builder;
    }

    public function getReviewsAvgAttribute(): int
    {
        return (int)round($this->reviews()->avg('review'));
    }
}
