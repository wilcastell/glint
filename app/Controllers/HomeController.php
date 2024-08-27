<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $user = checkAuth();
        return $this->view('home.inicio', compact('user'));
    }

    public function admin()
    {
        return $this->view('home.inicio');
    }
}
