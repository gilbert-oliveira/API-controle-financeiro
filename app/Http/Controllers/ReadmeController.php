<?php

namespace App\Http\Controllers;

use Parsedown;

class ReadmeController extends Controller
{
    public function index()
    {
        $Parsedown = new Parsedown();

        $content = $Parsedown->text(file_get_contents('README.md'));

        return view('index', compact('content'));
    }
}
