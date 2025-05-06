<?php

namespace Lady\View;

interface ViewInterface
{
    /**
     * Renderiza uma view
     *
     * @param string $view Nome da view a ser renderizada
     * @param array $data Dados a serem passados para a view
     * @return mixed
     */
    public function render(string $view, array $data = []);
    
    /**
     * Verifica se uma view existe
     *
     * @param string $view Nome da view
     * @return bool
     */
    public function exists(string $view): bool;
}