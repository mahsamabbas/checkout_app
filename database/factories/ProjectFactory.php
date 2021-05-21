<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'url' => $this->faker->domainName,
            'reservation_cost' => $this->faker->randomNumber(2),
            'mailchimp_server' => "us" . $this->faker->numberBetween(1, 20),
            'mailchimp_api_key' => $this->faker->asciify("********************"),
            'mailchimp_list_id' => $this->faker->randomNumber(),
            'lead_redirect_url' => $this->faker->url,
            'reservation_redirect_url' => $this->faker->url,
            'data_app_project_id' => $this->faker->randomNumber(),
        ];
    }
}
