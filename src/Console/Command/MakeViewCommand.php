<?php

namespace Lady\Console\Command;

class MakeViewCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'make:view';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Cria uma nova view';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady make:view nome_da_view [--trish]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        if (empty($args)) {
            $this->error('Nome da view não especificado.');
            $this->info('Uso: ' . $this->getSyntax());
            return 1;
        }
        
        $viewName = $args[0];
        $isTrish = in_array('--trish', $args);
        
        // Converter pontos em separadores de diretório
        $viewPath = str_replace('.', '/', $viewName);
        
        // Definir a extensão do arquivo
        $extension = $isTrish ? '.trish.php' : '.php';
        
        // Verificar se o diretório resources/views existe
        $viewsDir = getcwd() . '/resources/views';
        if (!is_dir($viewsDir)) {
            if (!mkdir($viewsDir, 0755, true)) {
                $this->error("Não foi possível criar o diretório 'resources/views'.");
                return 1;
            }
        }
        
        // Caminho completo para o arquivo da view
        $viewFilePath = $viewsDir . '/' . $viewPath . $extension;
        
        // Verificar se o diretório pai existe
        $parentDir = dirname($viewFilePath);
        if (!is_dir($parentDir)) {
            if (!mkdir($parentDir, 0755, true)) {
                $this->error("Não foi possível criar o diretório para a view.");
                return 1;
            }
        }
        
        // Verificar se a view já existe
        if (file_exists($viewFilePath)) {
            $this->error("A view '{$viewName}' já existe.");
            return 1;
        }
        
        // Criar o conteúdo da view
        $content = $this->generateViewContent($viewName, $isTrish);
        
        // Escrever o arquivo
        if (file_put_contents($viewFilePath, $content) === false) {
            $this->error("Não foi possível criar o arquivo da view.");
            return 1;
        }
        
        $this->success("View '{$viewName}{$extension}' criada com sucesso.");
        return 0;
    }
    
    /**
     * Gera o conteúdo da view
     *
     * @param string $viewName
     * @param bool $isTrish
     * @return string
     */
    protected function generateViewContent(string $viewName, bool $isTrish): string
    {
        $title = ucwords(str_replace(['_', '-', '.'], ' ', $viewName));
        
        if ($isTrish) {
            return <<<HTML
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{$title}</h1>
        
        <p>Esta é a view {$viewName}.</p>
    </div>
@endsection
HTML;
        } else {
            return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>{$title}</h1>
        
        <p>Esta é a view {$viewName}.</p>
    </div>
</body>
</html>
HTML;
        }
    }
}