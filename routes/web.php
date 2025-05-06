<?php

// Definir rotas para o projeto Dante
$router->get('/', 'HomeController@index');

/*
$router->get('/about', 'HomeController@about');

// Rotas de autenticação
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Rotas protegidas
$router->get('/dashboard', 'DashboardController@index')
    ->middleware('AuthMiddleware');
