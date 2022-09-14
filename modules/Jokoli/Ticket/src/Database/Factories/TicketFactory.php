<?php

namespace Jokoli\Ticket\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Ticket\Models\Ticket;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => auth()->id(),
            'title' => $this->faker->sentence(),
        ];
    }

    public function status($status)
    {
        return $this->state(['status' => $status]);
    }
}
