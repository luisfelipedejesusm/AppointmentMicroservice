<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->unique()->safeEmail,
            'customer_phone' => $this->faker->phoneNumber,
            'datetime_appointment' => $this->faker->dateTime(),
            'duration_appointment' => $this->faker->numberBetween(1, 3)
        ];
    }
}
