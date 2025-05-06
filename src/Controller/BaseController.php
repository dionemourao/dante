<?php

namespace Lady\Controller;

use Lady\View\View;

class BaseController implements ControllerInterface
{
    /**
     * Diretório base das views
     *
     * @var string
     */
    protected string $viewsPath;
    
    /**
     * Extensão dos arquivos de view
     *
     * @var string
     */
    protected string $viewExtension = '.php';
    
    /**
     * Diretório para armazenar os templates compilados
     *
     * @var string|null
     */
    protected ?string $cachePath = null;
    
    /**
     * Construtor
     */
    public function __construct()
    {
        // Definir o diretório padrão de views
        $this->viewsPath = defined('VIEWS_PATH') ? VIEWS_PATH : dirname(__DIR__, 3) . '/resources/views';
        
        // Definir o diretório padrão de cache
        $this->cachePath = defined('CACHE_PATH') ? CACHE_PATH : dirname(__DIR__, 3) . '/storage/cache/views';
    }
    
    /**
     * Define o diretório base das views
     *
     * @param string $path
     * @return self
     */
    public function setViewsPath(string $path): self
    {
        $this->viewsPath = $path;
        return $this;
    }
    
    /**
     * Define a extensão dos arquivos de view
     *
     * @param string $extension
     * @return self
     */
    public function setViewExtension(string $extension): self
    {
        $this->viewExtension = $extension;
        return $this;
    }
    
    /**
     * Define o diretório de cache
     *
     * @param string $path
     * @return self
     */
    public function setCachePath(string $path): self
    {
        $this->cachePath = $path;
        return $this;
    }
    
    /**
     * Renderiza uma view
     *
     * @param string $view Nome da view a ser renderizada
     * @param array $data Dados a serem passados para a view
     * @return mixed
     */
    public function view(string $view, array $data = [])
    {
        $viewInstance = new View($this->viewsPath, $this->viewExtension, $this->cachePath);
        return $viewInstance->render($view, $data);
    }
    
    /**
     * Renderiza uma view Trish
     *
     * @param string $view Nome da view a ser renderizada
     * @param array $data Dados a serem passados para a view
     * @return mixed
     */
    public function trish(string $view, array $data = [])
    {
        // Garantir que a extensão seja .trish.php
        $this->setViewExtension('.trish.php');
        return $this->view($view, $data);
    }
    
    /**
     * Retorna dados em formato JSON
     *
     * @param mixed $data Dados a serem convertidos para JSON
     * @param int $statusCode Código de status HTTP
     * @return mixed
     */
    public function json($data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        return null;
    }
    
    /**
     * Redireciona para outra URL
     *
     * @param string $url URL para redirecionamento
     * @param int $statusCode Código de status HTTP
     * @return mixed
     */
    public function redirect(string $url, int $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
}