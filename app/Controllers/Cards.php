<?php

namespace App\Controllers;

class Cards extends BaseController
{
    public function index(): string
    {
        return view('cards');
    }

}
