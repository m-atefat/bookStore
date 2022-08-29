<?php

declare (strict_types=1);

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Filters\SearchAuthors;
use App\Filters\SearchTitle;
use App\Filters\Sort;
use App\Http\Requests\PostBookRequest;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function getCollection(Request $request): AnonymousResourceCollection
    {
        $books = app(Pipeline::class)
            ->send(Book::query())
            ->through([
                SearchTitle::class,
                SearchAuthors::class,
                Sort::class
            ])
            ->thenReturn()
            ->paginate();

        return BookResource::collection($books);
    }

    public function post(PostBookRequest $request): BookResource
    {
        $bookData = [
            'isbn'        => $request->isbn,
            'title'       => $request->title,
            'description' => $request->description
        ];

        /** @var Book $book */
        $book = Book::query()->create($bookData);
        $authors = Author::query()->findMany($request->authors);
        $book->authors()->sync($authors);

        return BookResource::make($book);
    }

    public function postReview(Book $book, PostBookReviewRequest $request): BookReviewResource
    {
        $reviewData = [
            'user_id' => Auth::id(),
            'review'  => $request->review,
            'comment' => $request->comment
        ];
        $review = $book->reviews()->create($reviewData);

        return BookReviewResource::make($review);
    }
}
