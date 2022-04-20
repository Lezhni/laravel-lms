<?php

namespace App\Http\Controllers;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @param string|null $alias
     */
    public function __invoke(string $alias = null)
    {
        return view('template');
    }
}