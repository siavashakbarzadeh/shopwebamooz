<?php

namespace Jokoli\Ticket\Database\Factories;

use App\Models\TicketReply;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketReplyFactory extends Factory
{

    protected $model = TicketReply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>auth()->id(),
            'body'=>$this->faker->text,
        ];
    }
}
