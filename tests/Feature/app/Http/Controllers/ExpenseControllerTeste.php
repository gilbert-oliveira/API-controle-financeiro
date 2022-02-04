<?php

namespace Feature\app\Http\Controllers;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Testing\TestCase;

class ExpenseControllerTeste extends TestCase
{
    use HasFactory;

    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRetornaTodasAsDespesas()
    {
        Expense::factory()->count(10)->create();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get(route('expense.index'), $header);

        $expenses = Expense::all();

        $index = $this->response->content();

        $this->assertResponseOk();

        $this->assertEquals($index, $expenses);
    }

    public function testRetornarDespesasPorDescricao()
    {
        Expense::factory()->count(10)->create();

        $sub = substr(Expense::find(2)->description, 0, 3);
        $resources = Expense::where('description', 'like', "%{$sub}%")->get();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get("/despesas?descricao={$sub}", $header);
        $this->assertResponseOk();
        $this->assertEquals(json_decode($resources), json_decode($this->response->content()));
    }

    public function testRetornaSemConteudoAoBuscarDespesaPorDescricaoQueNaoExiste()
    {
        Expense::factory()->count(10)->create();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get("/despesas?descricao=abc123", $header);

        $this->assertResponseStatus(204);
    }

    public function testRetornaDespesasAoBuscarPorAnoEMes()
    {

        Expense::factory()->count(2)->create([
            'date' => "2022-11-01"
        ]);
        Expense::factory()->count(2)->create([
            'date' => "2022-10-01"
        ]);
        Expense::factory()->count(2)->create([
            'date' => "2022-09-01"
        ]);

        $expected = Expense::where('date', '2022-11-01')->get();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get(route('expense.show-by-month', ['year' => '2022', 'month' => '11']), $header);

        $this->assertResponseOk();
        $this->assertEquals($this->response->content(), $expected);
    }

    public function testRetornaSemConteudoAoBuscarDespesaPorAnoEMesQueNaoExiste()
    {
        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get(route('expense.show-by-month', ['year' => '2022', 'month' => '12']), $header);
        $this->assertResponseStatus(204);
    }

    public function testRetornaErroAoBuscarDespesaPorIdQueNaoExiste()
    {
        Expense::factory()->count(10)->create();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get(route('expense.show', ['id' => 11]), $header);

        $this->assertResponseStatus(204);
    }

    public function testRetornaJsonDeDespesaCadatradaPorId()
    {
        Expense::factory()->count(2)->create();
        $expense = Expense::find(2);

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->get(route('expense.show', ['id' => 2]), $header);

        $show = $this->response->content();

        $this->assertResponseOk();
        $this->assertEquals($expense, $show);
    }

    public function testRetornaErroAoDeletarDespesaPorIdQueNaoExiste()
    {
        Expense::factory()->create();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->delete(route('expense.destroy', ['id' => 2]), [], $header);

        $this->assertResponseStatus(404);
        $this->seeJson(["error" => "Despesa não encontrada!"]);
    }

    public function testRetornaSucessoComMensagemAoDeletarDespesaPorId()
    {
        Expense::factory()->create();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->delete(route('expense.destroy', ['id' => 1]), [], $header);

        $this->assertResponseStatus(200);
        $this->seeJson(["success" => "Despesa removida com sucesso!"]);
    }

    public function testRetornaMensagensDeErroDasValidacoesAoCadastrar()
    {

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->post(route('expense.store'), [
            'value' => 120,
            'date' => '2022-12-12'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ['Por favor. Informe a descrição da despesa!']]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'date' => '2022-12-12'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["value" => ['Por favor. Informe o valor da despesa!']]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'value' => '120'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe a data da despesa!']]);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'date' => '2022-01-01',
            'value' => 120
        ], $header);

        $this->post(route('expense.store'), [
            'description' => 'Despesa Teste',
            'date' => '2022-01-31',
            'value' => 120
        ], $header);

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

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->post(route('expense.store'), $payload, $header);

        $this->assertResponseStatus(201);
        $this->seeJson(Expense::find(1)->toArray());
    }

    public function testRetornaMensagensDeErroDasValidacoesAoEditar()
    {
        Expense::factory()->count(2)->create();

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->put(route('expense.update', ['id' => 1]), [
            'value' => 120,
            'date' => '2022-12-12'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ['Por favor. Informe a descrição da despesa!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'date' => '2022-12-12'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["value" => ['Por favor. Informe o valor da despesa!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'value' => '120'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe a data da despesa!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'date' => '2022-13-01',
            'value' => '120'
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["date" => ['Por favor. Informe uma data no formato Y-m-d!']]);

        $this->put(route('expense.update', ['id' => 1]), [
            'description' => 'Despesa Teste',
            'date' => '2022-01-01',
            'value' => 120
        ], $header);

        $this->put(route('expense.update', ['id' => 2]), [
            'description' => 'Despesa Teste',
            'date' => '2022-1-31',
            'value' => 120
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["description" => ["Descrição já cadatrada para o mês informado!"]]);

        $this->put(route('expense.update', ['id' => 2]), [
            'description' => 'Despesa Teste',
            'date' => '2022-5-20',
            'value' => 120,
            'category_id' => 9
        ], $header);

        $this->assertResponseStatus(422);
        $this->seeJson(["category_id" => ["Por favor. Informe uma categoria válida!"]]);
    }

    public function testRetornaMensagemDeErroAoEditarDespesaComIdQueNaoExiste()
    {
        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->put(route('expense.update', ['id' => 1]), [], $header);

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

        $payloadUser = [
            'name' => 'Usuário Teste',
            'email' => 'contato@gilbert.dev.br',
            'password' => 12345678
        ];
        $this->post(route('register', $payloadUser));
        $token = json_decode($this->response->content())->access_token;
        $header = ['HTTP_Authorization' => "Bearer $token"];

        $this->put(route('expense.update', ['id' => $expense->id]), $payload, $header);

        $this->assertResponseOk();
        $this->seeJson($payload);
    }
}
