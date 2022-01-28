<?php

namespace App\Http\Controllers;

use App\Models\Expense;

class ExpenseController extends BaseController
{
    public function __construct()
    {
        // Define a clase a ser utilizada pelo BasController.
        $this->model = new Expense();

        // Define o nome do recurso a ser utilizada=o pelo BasController.
        $this->resorceName = 'despesa';
    }
}
