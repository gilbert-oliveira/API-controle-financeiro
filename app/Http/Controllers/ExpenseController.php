<?php

namespace App\Http\Controllers;

use App\Models\Expense;

class ExpenseController extends BaseController
{
    public function __construct()
    {
        // Define a clase a ser utilizada pelo BasController.
        $this->class = Expense::class;

        // Define o nome do recurso a ser utilizada=o pelo BasController.
        $this->resorceName = 'despesa';
    }
}
