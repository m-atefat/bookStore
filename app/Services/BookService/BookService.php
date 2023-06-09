<?php

namespace App\Services\BookService;

use App\DTOs\BookDto;
use App\DTOs\BookReviewDto;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookReview;

class BookService
{
    public function store(BookDto $bookDto): Book
    {
        /** @var Book $book */
        $book = Book::query()->create([
            'title'       => $bookDto->getTitle(),
            'isbn'        => $bookDto->getIsbn(),
            'description' => $bookDto->getDescription(),
        ]);

        $authors = Author::query()->findMany($bookDto->getAuthorIds());
        $book->authors()->sync($authors);

        return $book;
    }

    public function storeBookReview(int $bookId, BookReviewDto $bookReviewDto): BookReview
    {
        /** @var Book $book */
        $book = Book::query()->firstOrFail($bookId);

        /** @var BookReview $review */
        $review = $book->reviews()->create([
            'user_id' => $bookReviewDto->getUserId(),
            'review'  => $bookReviewDto->getReview(),
            'comment' => $bookReviewDto->getComment()
        ]);

        return $review;
    }
}
