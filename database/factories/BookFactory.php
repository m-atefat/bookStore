<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->text(),
            'isbn'        => $this->faker->isbn13,
        ];
    }
}
