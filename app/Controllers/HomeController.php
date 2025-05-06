<?php

namespace App\Controllers;

use Lady\Controller\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        return $this->trish('home', [
            'title' => 'Página Inicial',
            'message' => 'Bem-vindo ao Lady-PHP com Trish!'
        ]);
    }
    
    public function about()
    {
        return $this->trish('about', [
            'title' => 'Sobre Nós',
            'content' => 'Esta é a página sobre nós do projeto Lady-PHP.'
        ]);
    }
    
    public function contact()
    {
        return $this->view('home.contact');
    }
    
    public function api()
    {
        return $this->json([
            'status' => 'success',
            'message' => 'API funcionando corretamente'
        ]);
    }
    
    public function redirectExample()
    {
        return $this->redirect('/');
    }
}