<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookReview;
use App\Models\User;
use Tests\TestCase;

class BookReviewTest extends TestCase
{
    public function testDenyGuestAccess()
    {
        $book = Book::factory()->create();

        $response = $this->postJson($this->postUrl($book->id), [
            'review' => 5,
            'comment' => 'Lorem ipsum',
        ]);

        $response->assertStatus(401);
    }

    public function testSuccessfulPost()
    {
        $user = User::factory()->admin()->create();
        $book = Book::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson($this->postUrl($book->id), [
                'review' => 5,
                'comment' => 'Lorem ipsum',
            ]);

        $response->assertStatus(201);
        $id = $response->json('data.id');

        $bookReview = BookReview::find($id);

        $response->assertJson([
            'data' => [
                'id' => $bookReview->id,
                'review' => $bookReview->review,
                'comment' => $bookReview->comment,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ],
        ]);

        $this->assertEquals(5, $bookReview->review);
        $this->assertEquals('Lorem ipsum', $bookReview->comment);
        $this->assertEquals($book->id, $bookReview->book->id);
        $this->assertEquals($user->id, $bookReview->user->id);
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $book = Book::factory()->create(['isbn' => '9788328302341']);
        $user = User::factory()->admin()->create();

        $validData = [
            'review' => 5,
            'comment' => 'Lorem ipsum',
        ];
        $data = array_merge($validData, $invalidData);

        $response = $this
            ->actingAs($user)
            ->postJson($this->postUrl($book->id), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public static function validationDataProvider(): array
    {
        return [
            [['review' => null], 'review'],
            [['review' => ''], 'review'],
            [['review' => 0], 'review'],
            [['review' => 11], 'review'],
            [['review' => 3.5], 'review'],
            [['review' => []], 'review'],
            [['comment' => null], 'comment'],
            [['comment' => ''], 'comment'],
            [['comment' => []], 'comment'],
        ];
    }

    private function postUrl(int $bookId): string
    {
        return sprintf('/api/books/%d/reviews', $bookId);
    }
}
