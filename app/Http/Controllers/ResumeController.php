<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Revenue;

class ResumeController extends Controller
{
    public function index($year, $month)
    {

        $totalRevenues = $this->totalRevenues($year, $month);
        $totalExpenses = $this->totalExpenses($year, $month);

        $resume = [
            'receitas' => $totalRevenues,
            'despesas' => $totalExpenses,
            'saldo' => $totalRevenues - $totalExpenses,
            'gastos' => $this->getSpending($year, $month)
        ];

        return response()->json($resume);
    }

    /**
     * Recupera o total de receitas gasta no mês.
     * @param $year string Ano da receita.
     * @param $month string Mês da receita.
     * @return float Valor total de receitas no mês.
     */
    public function totalRevenues(string $year, string $month): float
    {
        // Recupera todas as receitas do mês
        $expenses = Revenue::whereBetween('date', ["$year-$month-01", date('Y-m-t', strtotime("$year-$month"))])->get()->toArray();
        return array_sum(array_map(function ($e) {
            return $e['value'];
        }, $expenses));
    }

    /**
     * Recupera o total de despesas gasto no mês.
     * @param $year string Ano da despesa.
     * @param $month string Mês da despesa.
     * @return float Valor total de despesas no mês.
     */
    public function totalExpenses(string $year, string $month): float
    {
        // Recupera todas as despesas do mês
        $expenses = Expense::whereBetween('date', ["$year-$month-01", date('Y-m-t', strtotime("$year-$month"))])->get()->toArray();
        return array_sum(array_map(function ($e) {
            return $e['value'];
        }, $expenses));
    }

    /**
     * Recupera todas as categorias e seu total de gasto mensal.
     * @param $year string Ano da despesa.
     * @param $month string Mês da despesa.
     * @return array Array com os nomes da categoria e o total gasto.
     */
    private function getSpending(string $year, string $month): array
    {
        // Recupera todas as categorias de despesas.
        $categories = Category::all()->toArray();

        // Cria um array somente com os nomes das categorias de despesas.
        $categoriesName = array_map(function ($category) {
            return $category['name'];
        }, $categories);

        // cria um array somente com os id's das categorias de despesas.
        $categoriesId = array_map(function ($category) {
            return $category['id'];
        }, $categories);

        // Cria um array com todos as despesas por categoria do mês.
        $totalbycategory = array_map(function ($e) use ($month, $year) {
            return array_sum(array_map(function ($e) {
                return $e['value'];
            }, Expense::whereBetween('date', ["$year-$month-01", date('Y-m-t', strtotime("$year-$month"))])->get()->where('category_id', $e)->toArray())); // Busca todas as despesas de um mês e de uma categoria.
        }, $categoriesId);

        // Cria um com os nomes da categoria e o total gasto!
        return array_combine($categoriesName, $totalbycategory);
    }
}
