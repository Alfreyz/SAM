<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @re      turn void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


}
