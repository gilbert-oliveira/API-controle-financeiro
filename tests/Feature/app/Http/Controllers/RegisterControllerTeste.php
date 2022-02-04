<?php

namespace Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Testing\TestCase;

class RegisterControllerTeste extends TestCase
{
    use HasFactory;

    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testTetornaMensagensDeValidacoes()
    {
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ]);
        $this->post(route('register'));
        $this->assertResponseStatus(422);
        $this->seeJson([
            "name" => ["Por favor. Informe um nome!"],
            "email" => ["Por favor. Informe um e-mail!"],
            "password" => ["Por favor. Informe uma senha!"]
        ]);

        $this->post(route('register'), [
            'name' => 'Usuário Teste',
            'email' => 'emailteste',
            'password' => 12345678
        ]);
        $this->assertResponseStatus(422);
        $this->seeJson([
            "email" => ["Por favor. Informe um e-mail válido!"],
        ]);

        $this->post(route('register'), [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ]);
        $this->assertResponseStatus(422);
        $this->seeJson([
            "email" => ["E-mail já cadastrado. Infome outro!"],
        ]);
    }

    public function testRetornaTokenAoSeCadastrar()
    {
        $payload = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];

        $this->post(route('register', $payload));

        $this->assertResponseStatus(201);
        $this->assertArrayHasKey('access_token', ((array)json_decode($this->response->content())));
    }
}
