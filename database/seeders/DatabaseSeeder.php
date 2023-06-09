<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(\App\Models\User::class, 5)->create();
        $admin = factory(\App\Models\User::class, 1)->admin()->create();

        factory(\App\Models\Author::class, 15)->create()->each(function (\App\Models\Author $author) {
            factory(\App\Models\Book::class, 3)->create()->each(function (\App\Models\Book $book) use ($author) {
                $book->authors()->saveMany([
                    $author,
                ]);
            });
        });

        \App\Models\Book::all()->each(function (\App\Models\Book $book) use ($users) {
            $reviews = factory(\App\Models\BookReview::class, 4)->make();
            $reviews->each(function (\App\Models\BookReview $review) use ($users) {
                $review->user()->associate($users->random());
            });
            $book->reviews()->saveMany($reviews);
        });
    }
}
