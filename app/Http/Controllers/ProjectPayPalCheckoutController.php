<?php

namespace App\Http\Controllers;

class ProjectPayPalCheckoutController extends Controller
{
    public function store()
    {
        dd(request()->all());
    }
}