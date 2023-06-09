<?php

declare (strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\BookDto;
use App\DTOs\BookReviewDto;
use App\Filters\SearchAuthors;
use App\Filters\SearchTitle;
use App\Filters\Sort;
use App\Http\Requests\BookListRequest;
use App\Http\Requests\PostBookRequest;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookReviewResource;
use App\Models\Book;
use App\Services\BookService\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(BookListRequest $request): AnonymousResourceCollection
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

    public function store(PostBookRequest $request): BookResource
    {
        $bookDto = (new BookDto())
            ->setTitle($request->title)
            ->setIsbn($request->isbn)
            ->setDescription($request->description)
            ->setAuthorIds($request->authors);

        $book = $this->bookService->store($bookDto);
        return BookResource::make($book);
    }

    public function storeReview(Book $book, PostBookReviewRequest $request): JsonResponse
    {
        $bookReviewDto = (new BookReviewDto())
            ->setUserId(Auth::id())
            ->setReview($request->get('review'))
            ->setComment($request->get('comment'));

        $review = $this->bookService->storeBookReview($book->id, $bookReviewDto);
        return BookReviewResource::make($review)->response()->setStatusCode(201);
    }
}
