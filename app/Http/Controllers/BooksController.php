<?php

declare (strict_types=1);

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function getCollection(Request $request): AnonymousResourceCollection
    {
        $books = Book::filter($request->all())->paginate();
        return BookResource::collection($books);
    }

    public function post(PostBookRequest $request): BookResource
    {
        $book = Book::query()->create($request->except('authors'));
        $authors = Author::query()->findMany($request->authors);
        $book->authors()->sync($authors);

        return BookResource::make($book);
    }

    public function postReview(Book $book, PostBookReviewRequest $request): BookReviewResource
    {
        $request->merge(['user_id' => Auth::id()]);
        $review = $book->reviews()->create($request->all());
        return BookReviewResource::make($review);
    }
}
