<?php

namespace Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Testing\TestCase;

class TokenControllerTeste extends TestCase
{
    use HasFactory;

    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRetornaErroDeValidacoes()
    {
        $this->post(route('login'));

        $this->assertResponseStatus(422);
        $this->seeJson([
            'password' => ['Por favor. Informe uma senha!'],
            'email' => ['Por favor. Informe um e-mail!']
        ]);

        $this->post(route('login'), [
            'password' => 123456,
            'email' => 'emailteste'
        ]);
        $this->seeJson([
            'email' => ['Por favor. Informe um e-mail válido!']
        ]);
    }

    public function testRotornaErroDeCredenciais()
    {
        $this->post(route('login'), [
            'email' => 'email@incorreto.com',
            'password' => 12345678
        ]);

        $this->assertResponseStatus(401);
        $this->seeJson(["Usuário ou senha inválidos!"]);
    }

    public function testRetornaTokenDeAcesso()
    {
        $this->post(route('register'), [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ]);


        $payloadUser = [
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];

        $this->post(route('login'), $payloadUser);

        $this->assertResponseOk();
        $this->assertArrayHasKey('access_token', ((array)json_decode($this->response->content())));

    }

}
