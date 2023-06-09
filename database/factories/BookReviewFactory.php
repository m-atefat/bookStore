<?php

namespace Database\Factories;

use App\Models\BookReview;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends Factory<BookReview>
 */
class BookReviewFactory extends Factory
{

    public function definition(): array
    {
        return [
            'review'  => $this->faker->randomElement(range(1, 10)),
            'comment' => $this->faker->text(),
        ];
    }
}
