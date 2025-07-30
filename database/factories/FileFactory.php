<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Empresa;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = fake()->word() . '.' . fake()->randomElement(['pdf', 'docx', 'xlsx', 'png', 'jpg']);
        
        return [
            'titulo' => fake()->sentence(3),
            'descricao' => fake()->paragraph(),
            'file_path' => 'files/' . $fileName,
            'original_name' => $fileName,
            'mime_type' => fake()->randomElement([
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'image/png',
                'image/jpeg'
            ]),
            'size' => fake()->numberBetween(1024, 10485760), // 1KB a 10MB
            'user_id' => User::factory(),
            'empresa_id' => Empresa::factory(),
            'category_id' => Category::factory(),
            'subcategory_id' => null,
        ];
    }

    /**
     * Com subcategoria
     */
    public function withSubcategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'subcategory_id' => Subcategory::factory(),
        ]);
    }

    /**
     * Arquivo PDF
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_path' => 'files/documento.pdf',
            'original_name' => 'documento.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    /**
     * Arquivo de imagem
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_path' => 'files/imagem.jpg',
            'original_name' => 'imagem.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }
}
