<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement([
                'Engenharia',
                'Recursos Humanos',
                'Financeiro',
                'Qualidade',
                'Projetos',
                'Comercial',
                'Tecnologia da Informação',
                'Logística',
                'Marketing',
                'Jurídico'
            ]),
            'empresa_id' => Empresa::factory(),
        ];
    }
}
