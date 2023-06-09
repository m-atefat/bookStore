<?php

namespace Tests;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected function bookToResourceArray(Book $book): array
    {
        return [
            'id' => $book->id,
            'isbn' => $book->isbn,
            'title' => $book->title,
            'description' => $book->description,
            'authors' => $book->authors->map(function (Author $author) {
                return ['id' => $author->id, 'name' => $author->name, 'surname' => $author->surname];
            })->toArray(),
            'review' => [
                'avg' => (int) round($book->reviews->avg('review')),
                'count' => (int) $book->reviews->count(),
            ],
        ];
    }
}
