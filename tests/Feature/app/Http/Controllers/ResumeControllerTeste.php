<?php

namespace Feature\app\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Revenue;
use Laravel\Lumen\Testing\TestCase;
use function PHPUnit\Framework\assertEquals;

class ResumeControllerTeste extends TestCase
{

    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRetornaResumoPorAnoEMes()
    {

        Expense::factory(3)->create([
            'value' => 135,
            'date' => '2022-01-01',
            'category_id' => 1
        ]);
        Expense::factory(3)->create([
            'value' => 150,
            'date' => '2022-02-01',
            'category_id' => 7
        ]);

        Revenue::factory(3)->create([
            'value' => 185,
            'date' => '2022-01-01',
        ]);
        Revenue::factory(3)->create([
            'value' => 600,
            'date' => '2022-02-01',
        ]);

        $expenses = Expense::wherebetween('date', ['2022-02-01', '2022-02-28'])->get()->toArray();
        $revenues = Revenue::wherebetween('date', ['2022-02-01', '2022-02-28'])->get()->toArray();

        $amountExpenses = array_sum(array_map(function ($expense) {
            return $expense['value'];
        }, $expenses));

        $amountRevenues = array_sum(array_map(function ($revenue) {
            return $revenue['value'];
        }, $revenues));

        $categories = Category::all()->toArray();

        $CategoriesIndex = array_map(function ($category) {
            return $category['name'];
        }, $categories);

        $amounts = array_map(function ($category) {
            return array_map(function ($e) {
                return $e['value'];
            },
                Expense::whereBetween('date', ['2022-02-01', '2022-02-28'])->where('category_id', $category['id'])->get()->toArray());
        }, $categories);

        $amounts = array_map(function ($e) {
            return array_sum($e);
        }, $amounts);

        $this->get(route('resume', ['year' => '2022', 'month' => '02']));

        $teste = json_encode([
            "receitas" => $amountRevenues,
            "despesas" => $amountExpenses,
            "saldo" => $amountRevenues - $amountExpenses,
            "gastos" => array_combine($CategoriesIndex, $amounts)
        ]);
        assertEquals($this->response->content(), $teste);
    }
}
