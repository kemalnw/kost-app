<?php

namespace Database\Factories\User;

use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'role_id' => rand(1, 3),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'balance' => 0,
        ];
    }

    public function withRole(int $roleId)
    {
        $roleId = in_array($roleId, [1,2,3]) ? $roleId : Role::REGULAR_USER;

        return $this->state(function (array $attributes) use($roleId) {
            return [
                'role_id' => $roleId,
            ];
        });
    }
}
