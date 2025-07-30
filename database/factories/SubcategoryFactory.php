<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subcategory>
 */
class SubcategoryFactory extends Factory
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
                'Engenharia Civil',
                'Engenharia Elétrica',
                'Engenharia Mecânica',
                'Contratações',
                'Treinamentos',
                'Avaliações',
                'Relatórios Mensais',
                'Orçamentos',
                'Contas a Pagar',
                'Auditoria',
                'Certificações',
                'Cronogramas',
                'Especificações',
                'Documentação Técnica',
                'Manuais'
            ]),
            'category_id' => Category::factory(),
        ];
    }
}
