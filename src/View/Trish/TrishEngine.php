<?php

namespace Lady\View\Trish;

class TrishEngine
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
     * Extensão dos arquivos de template
     *
     * @var string
     */
    protected string $extension = '.trish.php';
    
    /**
     * Compilador Trish
     *
     * @var TrishCompiler
     */
    protected TrishCompiler $compiler;
    
    /**
     * Seções definidas no template
     *
     * @var array
     */
    protected array $sections = [];
    
    /**
     * Pilha de seções sendo renderizadas
     *
     * @var array
     */
    protected array $sectionStack = [];
    
    /**
     * Layout a ser estendido
     *
     * @var string|null
     */
    protected ?string $layoutName = null;
    
    /**
     * Construtor
     *
     * @param string $viewsPath Diretório base das views
     * @param string $cachePath Diretório para armazenar os templates compilados
     * @param string $extension Extensão dos arquivos de template
     */
    public function __construct(string $viewsPath, string $cachePath, string $extension = '.trish.php')
    {
        $this->viewsPath = rtrim($viewsPath, '/');
        $this->cachePath = rtrim($cachePath, '/');
        $this->extension = $extension;
        
        $this->compiler = new TrishCompiler($this->cachePath);
        
        // Criar diretório de cache se não existir
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Define se a compilação deve ser forçada
     *
     * @param bool $force
     * @return self
     */
    public function forceCompile(bool $force = true): self
    {
        $this->compiler->forceCompile($force);
        return $this;
    }
    
    /**
     * Renderiza um template
     *
     * @param string $view Nome da view
     * @param array $data Dados a serem passados para a view
     * @return string Conteúdo renderizado
     */
    public function render(string $view, array $data = []): string
    {
        // Limpar estado
        $this->sections = [];
        $this->sectionStack = [];
        $this->layoutName = null;
        
        // Obter o caminho completo do template
        $templatePath = $this->getTemplatePath($view);
        
        if (!file_exists($templatePath)) {
            throw new TrishException("View not found: {$view}");
        }
        
        // Compilar o template
        $compiledPath = $this->compiler->compile($templatePath);
        
        // Renderizar o template
        $content = $this->evaluateTemplate($compiledPath, $data);
        
        // Se houver um layout, renderizá-lo
        if ($this->layoutName !== null) {
            $content = $this->render($this->layoutName, $data);
        }
        
        return $content;
    }
    
    /**
     * Avalia um template compilado
     *
     * @param string $compiledPath Caminho para o template compilado
     * @param array $data Dados a serem passados para o template
     * @return string Conteúdo renderizado
     */
    protected function evaluateTemplate(string $compiledPath, array $data = []): string
    {
        // Extrair os dados para que fiquem disponíveis no template
        extract($data);
        
        // Iniciar o buffer de saída
        ob_start();
        
        // Incluir o template compilado
        include $compiledPath;
        
        // Obter o conteúdo do buffer e limpá-lo
        return ob_get_clean();
    }
    
    /**
     * Obtém o caminho completo para um arquivo de template
     *
     * @param string $view Nome da view
     * @return string
     */
    protected function getTemplatePath(string $view): string
    {
        // Converter pontos em separadores de diretório
        $view = str_replace('.', '/', $view);
        
        // Garantir que a extensão esteja presente
        if (!str_ends_with($view, $this->extension)) {
            $view .= $this->extension;
        }
        
        return $this->viewsPath . '/' . $view;
    }
    
    /**
     * Define o layout a ser estendido
     *
     * @param string $name Nome do layout
     * @return void
     */
    public function extend(string $name): void
    {
        $this->layoutName = $name;
    }
    
    /**
     * Inicia uma seção
     *
     * @param string $name Nome da seção
     * @return void
     */
    public function startSection(string $name): void
    {
        $this->sectionStack[] = $name;
        
        // Iniciar o buffer de saída para capturar o conteúdo da seção
        ob_start();
    }
    
    /**
     * Finaliza a seção atual
     *
     * @return void
     */
    public function endSection(): void
    {
        if (empty($this->sectionStack)) {
            throw new TrishException("Cannot end a section without first starting one.");
        }
        
        // Obter o nome da seção atual
        $name = array_pop($this->sectionStack);
        
        // Capturar o conteúdo da seção
        $content = ob_get_clean();
        
        // Armazenar o conteúdo da seção
        $this->sections[$name] = $content;
    }
    
    /**
     * Retorna o conteúdo de uma seção
     *
     * @param string $name Nome da seção
     * @param string $default Conteúdo padrão se a seção não existir
     * @return string
     */
    public function yieldContent(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }
    
    /**
     * Verifica se uma seção existe
     *
     * @param string $name Nome da seção
     * @return bool
     */
    public function hasSection(string $name): bool
    {
        return isset($this->sections[$name]);
    }
    
    /**
     * Obtém o compilador
     *
     * @return TrishCompiler
     */
    public function getCompiler(): TrishCompiler
    {
        return $this->compiler;
    }
}