<?php

namespace App\Http\Controllers;

use App\Models\Revenue;

class RevenueController extends BaseController
{

    public function __construct()
    {
        // Define a clase a ser utilizada pelo BasController.
        $this->class = Revenue::class;

        // Define o nome do recurso a ser utilizada=o pelo BasController.
        $this->resorceName = 'receita';
    }
}
