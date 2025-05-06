<?php

namespace Lady\View\Trish;

class TrishCompiler
{
    /**
     * Diretório onde os templates compilados serão armazenados
     *
     * @var string
     */
    protected string $cachePath;

    /**
     * Indica se a compilação deve ser forçada mesmo se o cache existir
     *
     * @var bool
     */
    protected bool $forceCompile = false;

    /**
     * Construtor
     *
     * @param string $cachePath Diretório para armazenar os templates compilados
     */
    public function __construct(string $cachePath)
    {
        $this->cachePath = rtrim($cachePath, '/');
        
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
        $this->forceCompile = $force;
        return $this;
    }

    /**
     * Compila um arquivo de template se necessário
     *
     * @param string $templatePath Caminho para o arquivo de template
     * @return string Caminho para o arquivo compilado
     */
    public function compile(string $templatePath): string
    {
        $cachePath = $this->getCachePath($templatePath);
        
        // Verificar se é necessário compilar
        if ($this->shouldCompile($templatePath, $cachePath)) {
            $content = file_get_contents($templatePath);
            $compiled = $this->compileString($content);
            
            file_put_contents($cachePath, $compiled);
        }
        
        return $cachePath;
    }

    /**
     * Verifica se um template precisa ser compilado
     *
     * @param string $templatePath
     * @param string $cachePath
     * @return bool
     */
    protected function shouldCompile(string $templatePath, string $cachePath): bool
    {
        // Se a compilação for forçada, sempre compilar
        if ($this->forceCompile) {
            return true;
        }
        
        // Se o arquivo compilado não existir, compilar
        if (!file_exists($cachePath)) {
            return true;
        }
        
        // Se o template foi modificado após a última compilação, compilar
        return filemtime($templatePath) > filemtime($cachePath);
    }

    /**
     * Obtém o caminho para o arquivo compilado
     *
     * @param string $templatePath
     * @return string
     */
    protected function getCachePath(string $templatePath): string
    {
        $filename = md5($templatePath) . '.php';
        return $this->cachePath . '/' . $filename;
    }

    /**
     * Compila uma string de template
     *
     * @param string $content
     * @return string
     */
    protected function compileString(string $content): string
    {
        // Ordem de compilação é importante
        $content = $this->compileComments($content);
        $content = $this->compileEchos($content);
        $content = $this->compileStatements($content);
        $content = $this->compileIncludes($content);
        $content = $this->compileExtends($content);
        $content = $this->compileSections($content);
        $content = $this->compileYields($content);
        
        return $content;
    }

    /**
     * Compila comentários do Trish
     *
     * @param string $content
     * @return string
     */
    protected function compileComments(string $content): string
    {
        // Remover comentários {{-- Comentário --}}
        return preg_replace('/\{\{--(.+?)--\}\}/s', '', $content);
    }

    /**
     * Compila expressões de echo
     *
     * @param string $content
     * @return string
     */
    protected function compileEchos(string $content): string
    {
        // Compilar {{ $var }} - com escape
        $content = preg_replace('/\{\{\s*(.+?)\s*\}\}/s', '<?php echo htmlspecialchars($1, ENT_QUOTES, \'UTF-8\'); ?>', $content);
        
        // Compilar {!! $var !!} - sem escape
        $content = preg_replace('/\{!!\s*(.+?)\s*!!\}/s', '<?php echo $1; ?>', $content);
        
        return $content;
    }

    /**
     * Compila estruturas de controle
     *
     * @param string $content
     * @return string
     */
    protected function compileStatements(string $content): string
    {
        // Compilar @if, @elseif, @else
        $content = preg_replace('/@if\s*\((.*?)\)/s', '<?php if ($1): ?>', $content);
        $content = preg_replace('/@elseif\s*\((.*?)\)/s', '<?php elseif ($1): ?>', $content);
        $content = preg_replace('/@else/s', '<?php else: ?>', $content);
        $content = preg_replace('/@endif/s', '<?php endif; ?>', $content);
        
        // Compilar @foreach, @for, @while
        $content = preg_replace('/@foreach\s*\((.*?)\)/s', '<?php foreach ($1): ?>', $content);
        $content = preg_replace('/@endforeach/s', '<?php endforeach; ?>', $content);
        
        $content = preg_replace('/@for\s*\((.*?)\)/s', '<?php for ($1): ?>', $content);
        $content = preg_replace('/@endfor/s', '<?php endfor; ?>', $content);
        
        $content = preg_replace('/@while\s*\((.*?)\)/s', '<?php while ($1): ?>', $content);
        $content = preg_replace('/@endwhile/s', '<?php endwhile; ?>', $content);
        
        // Compilar @php
        $content = preg_replace('/@php/s', '<?php', $content);
        $content = preg_replace('/@endphp/s', '?>', $content);
        
        return $content;
    }

    /**
     * Compila diretivas @include
     *
     * @param string $content
     * @return string
     */
    protected function compileIncludes(string $content): string
    {
        return preg_replace('/@include\s*\([\'"](.*?)[\'"](,\s*(.*?))?\)/s', '<?php include_once $this->engine->render("$1", array_merge(get_defined_vars(), $2 ?? [])); ?>', $content);
    }

    /**
     * Compila diretivas @extends
     *
     * @param string $content
     * @return string
     */
    protected function compileExtends(string $content): string
    {
        // Extrair o layout a ser estendido
        preg_match('/@extends\s*\([\'"](.+?)[\'"]\)/s', $content, $matches);
        
        if (empty($matches)) {
            return $content;
        }
        
        $layoutName = $matches[1];
        
        // Remover a diretiva @extends
        $content = preg_replace('/@extends\s*\([\'"](.+?)[\'"]\)/s', '', $content);
        
        // Adicionar código para estender o layout
        $content = "<?php \$this->extend('{$layoutName}'); ?>\n" . $content;
        
        return $content;
    }

    /**
     * Compila diretivas @section e @endsection
     *
     * @param string $content
     * @return string
     */
    protected function compileSections(string $content): string
    {
        // Compilar @section
        $content = preg_replace('/@section\s*\([\'"](.+?)[\'"]\)/s', '<?php $this->startSection("$1"); ?>', $content);
        
        // Compilar @endsection
        $content = preg_replace('/@endsection/s', '<?php $this->endSection(); ?>', $content);
        
        return $content;
    }

    /**
     * Compila diretivas @yield
     *
     * @param string $content
     * @return string
     */
    protected function compileYields(string $content): string
    {
        return preg_replace('/@yield\s*\([\'"](.+?)[\'"]\)/s', '<?php echo $this->yieldContent("$1"); ?>', $content);
    }
}