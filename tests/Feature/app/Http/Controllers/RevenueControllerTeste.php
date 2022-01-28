<?php

namespace Feature\app\Http\Controllers;

use App\Models\Revenue;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase;

class RevenueControllerTeste extends TestCase
{
    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRetornaTodasAsReceitas()
    {
        Revenue::factory()->count(10)->create();
        $revenues = Revenue::all();

        $this->get(route('revenue.index'));

        $index = $this->response->content();

        $this->assertResponseOk();

        $this->assertEquals($index, $revenues);
    }

    public function testRetornaErroAoBuscarRecitaPorIdQueNaoExiste()
    {
        Revenue::factory()->count(10)->create();
        $this->get(route('revenue.show', ['id' => 11]));

        $this->assertResponseStatus(204);
    }

    public function testRetornaJsonDeReceitaCadatradaPorId()
    {
        Revenue::factory()->count(2)->create();
        $revenue = Revenue::find(2);

        $this->get(route('revenue.show', ['id' => 2]));

        $show = $this->response->content();

        $this->assertResponseOk();
        $this->assertEquals($revenue, $show);
    }

    public function testRetornaErroAoDeletarReceitaPorIdQueNaoExiste()
    {
        Revenue::factory()->create();

        $this->delete(route('revenue.destroy', ['id' => 2]));

        $this->assertResponseStatus(404);
        $this->seeJson(["error" => "Receita não encontrada!"]);
    }

    public function testRetornaSucessoComMensagemAoDeletarReceitaPorId()
    {
        Revenue::factory()->create();

        $this->delete(route('revenue.destroy', ['id' => 1]));

        $this->assertResponseStatus(200);
        $this->seeJson(["success" => "Receita removida com sucesso!"]);
    }

    public function testRetornaMensagensDeErroDasValidacoesAoCadastrar()
    {
        $this->post(route('revenue.store'), [
            'value' => 120,
            'date' => '2022-12-12'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ['Por favor. Informe a descrição da receita!']]);

        $this->post(route('revenue.store'), [
            'description' => 'Receita Teste',
            'date' => '2022-12-12'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["value" => ['Por favor. Informe o valor da receita!']]);

        $this->post(route('revenue.store'), [
            'description' => 'Receita Teste',
            'value' => '120'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe a data da receita!']]);

        $this->post(route('revenue.store'), [
            'description' => 'Receita Teste',
            'date' => '2022-01-01',
            'value' => 120
        ]);

        $this->post(route('revenue.store'), [
            'description' => 'Receita Teste',
            'date' => '2022-01-31',
            'value' => 120
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ["Descrição já cadatrada para o mês informado!"]]);
    }

    public function testRetornaAReceitaComMensagemDeCriadoAoFazerCadatro()
    {
        $payload = [
            'description' => "Nova Receita",
            'value' => 100,
            'date' => '2022-01-01'
        ];

        $this->post(route('revenue.store'), $payload);
        $this->assertResponseStatus(201);
        $this->seeJson(Revenue::find(1)->toArray());
    }

    public function testRetornaMensagensDeErroDasValidacoesAoEditar()
    {

        Revenue::factory()->count(2)->create();


        $this->put(route('revenue.update', ['id' => 1]), [
            'value' => 120,
            'date' => '2022-12-12'
        ]);
        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ['Por favor. Informe a descrição da receita!']]);

        $this->put(route('revenue.update', ['id' => 1]), [
            'description' => 'Receita Teste',
            'date' => '2022-12-12'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["value" => ['Por favor. Informe o valor da receita!']]);

        $this->put(route('revenue.update', ['id' => 1]), [
            'description' => 'Receita Teste',
            'value' => '120'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe a data da receita!']]);

        $this->put(route('revenue.update', ['id' => 1]), [
            'description' => 'Receita Teste',
            'date' => '2022-01-01',
            'value' => 120
        ]);

        $this->put(route('revenue.update', ['id' => 2]), [
            'description' => 'Receita Teste',
            'date' => '2022-1-31',
            'value' => 120
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ["Descrição já cadatrada para o mês informado!"]]);
    }

    public function testRetornaMensagemDeErroAoEditarReceitaComIdQueNaoExiste()
    {
        $this->put(route('revenue.update', ['id' => 1]));

        $this->assertResponseStatus(404);
        $this->seeJson(["error" => "Receita não encontrada!"]);
    }

    public function testRetornaAReceitaComStatusDeSuccessAoEditar()
    {
        $revenue = Revenue::factory()->create();
        $payload = [
            'description' => 'Descrição da receita',
            'value' => 50,
            'date' => '2021-12-01'
        ];

        $this->put(route('revenue.update', ['id' => $revenue->id]), $payload);

        $this->assertResponseOk();
        $this->seeJson($payload);
    }
}
