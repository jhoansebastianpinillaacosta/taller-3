<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'precio' => $this->faker->randomNumber(2),
            'fechadevencimiento' => $this->faker->date,
            // Agrega más atributos según sea necesario
        ];
    }
}