<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Anderson',
            'email' => 'andersonqipo2a@gmail.com',
            'password' => bcrypt('12345678'),
            'telefone' => '(51)991377276',
            'perfil' => 'Administrador',
            'cargo' => 'Técnico TI',
            'nascimento' => '2023-04-19 22:10:22',
            'logradouro' => 'Rua Santa Teresa, 214',
            'bairro' => 'Olaria',
            'cidade' => 'Canoas',
            'estado' => 'RS',
            'cep' => '92035-580',
            'senha' => '123456789',
            'confirmar_senha' => '123456789',
        ]);
    }
}
