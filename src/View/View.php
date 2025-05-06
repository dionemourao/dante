<?php

namespace Lady\View;

use Lady\View\Trish\TrishEngine;
use Lady\View\Trish\TrishException;

class View implements ViewInterface
{
    /**
     * Diretório base das views
     *
     * @var string
     */
    protected string $viewsPath;
    
    /**
     * Diretório para armazenar os templates compilados
     *
     * @var string
     */
    protected string $cachePath;
    
    /**
     * Extensão dos arquivos de view
     *
     * @var string
     */
    protected string $viewExtension;
    
    /**
     * Motor de renderização Trish
     *
     * @var TrishEngine|null
     */
    protected ?TrishEngine $trishEngine = null;
    
    /**
     * Construtor
     *
     * @param string $viewsPath Diretório base das views
     * @param string $viewExtension Extensão dos arquivos de view
     * @param string|null $cachePath Diretório para armazenar os templates compilados
     */
    public function __construct(string $viewsPath, string $viewExtension = '.php', ?string $cachePath = null)
    {
        $this->viewsPath = rtrim($viewsPath, '/');
        $this->viewExtension = $viewExtension;
        $this->cachePath = $cachePath ?? $this->viewsPath . '/../cache/views';
        
        // Criar diretório de cache se não existir
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Renderiza uma view
     *
     * @param string $view Nome da view a ser renderizada
     * @param array $data Dados a serem passados para a view
     * @return mixed
     * @throws \Exception Se a view não existir
     */
    public function render(string $view, array $data = [])
    {
        // Verificar se é uma view Trish
        if (str_ends_with($view, '.trish') || str_ends_with($this->viewExtension, '.trish.php')) {
            return $this->renderTrish($view, $data);
        }
        
        // Renderização padrão para views PHP simples
        $viewPath = $this->getViewPath($view);
        
        if (!$this->exists($view)) {
            throw new \Exception("View not found: {$view}");
        }
        
        // Extrair os dados para que fiquem disponíveis na view
        extract($data);
        
        // Iniciar o buffer de saída
        ob_start();
        
        // Incluir o arquivo da view
        include $viewPath;
        
        // Obter o conteúdo do buffer e limpá-lo
        $content = ob_get_clean();
        
        // Retornar o conteúdo renderizado
        echo $content;
        return null;
    }
    
    /**
     * Renderiza uma view usando o motor Trish
     *
     * @param string $view Nome da view a ser renderizada
     * @param array $data Dados a serem passados para a view
     * @return mixed
     */
    protected function renderTrish(string $view, array $data = [])
    {
        // Inicializar o motor Trish se ainda não foi feito
        if ($this->trishEngine === null) {
            $this->trishEngine = new TrishEngine(
                $this->viewsPath,
                $this->cachePath,
                '.trish.php'
            );
        }
        
        try {
            // Remover a extensão .trish se estiver presente
            $view = str_replace('.trish', '', $view);
            
            // Renderizar a view usando o motor Trish
            $content = $this->trishEngine->render($view, $data);
            
            echo $content;
            return null;
        } catch (TrishException $e) {
            throw new \Exception("Error rendering Trish view: " . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Verifica se uma view existe
     *
     * @param string $view Nome da view
     * @return bool
     */
    public function exists(string $view): bool
    {
        return file_exists($this->getViewPath($view));
    }
    
    /**
     * Obtém o caminho completo para um arquivo de view
     *
     * @param string $view Nome da view
     * @return string
     */
    protected function getViewPath(string $view): string
    {
        // Converter pontos em separadores de diretório
        $view = str_replace('.', '/', $view);
        
        // Garantir que a extensão esteja presente
        if (!str_ends_with($view, $this->viewExtension)) {
            $view .= $this->viewExtension;
        }
        
        return $this->viewsPath . '/' . $view;
    }
}