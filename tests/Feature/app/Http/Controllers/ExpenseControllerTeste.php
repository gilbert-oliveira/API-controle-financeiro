<?php

namespace Feature\app\Http\Controllers;

use App\Models\Expense;
use Laravel\Lumen\Testing\TestCase;

class ExpenseControllerTeste extends TestCase
{
    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRetornaTodasAsDespesas()
    {
        Expense::factory()->count(10)->create();

        $this->get(route('expense.index'));
        $expenses = Expense::all();

        $index = $this->response->content();

        $this->assertResponseOk();

        $this->assertEquals($index, $expenses);
    }

    public function testRetornaErroAoBuscarDespesaPorIdQueNaoExiste()
    {
        Expense::factory()->count(10)->create();
        $this->get(route('expense.show', ['id' => 11]));

        $this->assertResponseStatus(204);
    }

    public function testRetornaJsonDeDespesaCadatradaPorId()
    {
        Expense::factory()->count(2)->create();
        $expense = Expense::find(2);

        $this->get(route('expense.show', ['id' => 2]));

        $show = $this->response->content();

        $this->assertResponseOk();
        $this->assertEquals($expense, $show);
    }

    public function testRetornaErroAoDeletarDespesaPorIdQueNaoExiste()
    {
        Expense::factory()->create();

        $this->delete(route('expense.destroy', ['id' => 2]));

        $this->assertResponseStatus(404);
        $this->seeJson(["error" => "Despesa não encontrada!"]);
    }

    public function testRetornaSucessoComMensagemAoDeletarDespesaPorId()
    {
        Expense::factory()->create();

        $this->delete(route('expense.destroy', ['id' => 1]));

        $this->assertResponseStatus(200);
        $this->seeJson(["success" => "Despesa removida com sucesso!"]);
    }

    public function testRetornaMensagensDeErroDasValidacoesAoCadastrar()
    {
        $this->post(route('expense.store'), [
            'value' => 120,
            'date' => '2022-12-12'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ['Por favor. Informe a descrição da despesa!']]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'date' => '2022-12-12'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["value" => ['Por favor. Informe o valor da despesa!']]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'value' => '120'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe a data da despesa!']]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'date' => '2022-01-01',
            'value' => 120
        ]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'date' => '2022-01-31',
            'value' => 120
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ["Descrição já cadatrada para o mês informado!"]]);
    }

    public function testRetornaADespesaComMensagemDeCriadoAoFazerCadatro()
    {
        $payload = [
            'description' => "Nova Despesa",
            'value' => 100,
            'date' => '2022-01-01',
            'category_id' => 1
        ];

        $this->post(route('expense.store'), $payload);

        $this->assertResponseStatus(201);
        $this->seeJson(Expense::find(1)->toArray());
    }

    public function testRetornaMensagensDeErroDasValidacoesAoEditar()
    {

        Expense::factory()->count(2)->create();


        $this->put(route('expense.update', ['id' => 1]), [
            'value' => 120,
            'date' => '2022-12-12'
        ]);
        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ['Por favor. Informe a descrição da despesa!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'date' => '2022-12-12'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["value" => ['Por favor. Informe o valor da despesa!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'value' => '120'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe a data da despesa!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'date' => '2022-13-01',
            'value' => '120'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe uma data no formato Y-m-d!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'date' => '2022-01-01',
            'value' => 120
        ]);

        $this->put(route('expense.update', ['id' => 2]), [
            'description' => 'Despesa Teste',
            'date' => '2022-1-31',
            'value' => 120
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ["Descrição já cadatrada para o mês informado!"]]);

        $this->put(route('expense.update', ['id' => 2]), [
            'description' => 'Despesa Teste',
            'date' => '2022-5-20',
            'value' => 120,
            'category_id' => 9
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson(["category_id" => ["Por favor. Informe uma categoria válida!"]]);
    }

    public function testRetornaMensagemDeErroAoEditarDespesaComIdQueNaoExiste()
    {
        $this->put(route('expense.update', ['id' => 1]));

        $this->assertResponseStatus(404);
        $this->seeJson(["error" => "Despesa não encontrada!"]);
    }

    public function testRetornaADespesaComStatusDeSuccessAoEditar()
    {
        $expense = Expense::factory()->create();
        $payload = [
            'description' => 'Descrição da despesa',
            'value' => 50,
            'date' => '2021-12-01'
        ];

        $this->put(route('expense.update', ['id' => $expense->id]), $payload);

        $this->assertResponseOk();
        $this->seeJson($payload);
    }
}
