<?php

namespace Lady\Console\Command;

class MakeMiddlewareCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'make:middleware';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Cria um novo middleware';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady make:middleware NomeDoMiddleware';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        if (empty($args)) {
            $this->error('Nome do middleware não especificado.');
            $this->info('Uso: ' . $this->getSyntax());
            return 1;
        }
        
        $middlewareName = $args[0];
        
        // Verificar se o diretório app/Middleware existe
        $middlewareDir = getcwd() . '/app/Middleware';
        if (!is_dir($middlewareDir)) {
            if (!mkdir($middlewareDir, 0755, true)) {
                $this->error("Não foi possível criar o diretório 'app/Middleware'.");
                return 1;
            }
        }
        
        // Caminho completo para o arquivo do middleware
        $middlewarePath = $middlewareDir . '/' . $middlewareName . '.php';
        
        // Verificar se o middleware já existe
        if (file_exists($middlewarePath)) {
            $this->error("O middleware '{$middlewareName}' já existe.");
            return 1;
        }
        
        // Criar o conteúdo do middleware
        $content = $this->generateMiddlewareContent($middlewareName);
        
        // Escrever o arquivo
        if (file_put_contents($middlewarePath, $content) === false) {
            $this->error("Não foi possível criar o arquivo do middleware.");
            return 1;
        }
        
        $this->success("Middleware '{$middlewareName}' criado com sucesso.");
        return 0;
    }
    
    /**
     * Gera o conteúdo do middleware
     *
     * @param string $middlewareName
     * @return string
     */
    protected function generateMiddlewareContent(string $middlewareName): string
    {
        $namespace = 'App\\Middleware';
        
        return <<<PHP
<?php

namespace {$namespace};

use Lady\Http\Request;
use Lady\Http\Response;
use Lady\Middleware\MiddlewareInterface;

class {$middlewareName} implements MiddlewareInterface
{
    /**
     * Processa a requisição através do middleware.
     *
     * @param Request \$request
     * @param callable \$next
     * @return Response
     */
    public function handle(Request \$request, callable \$next): Response
    {
        // Lógica do middleware antes da requisição
        
        // Passar a requisição para o próximo middleware
        \$response = \$next(\$request);
        
        // Lógica do middleware após a resposta
        
        return \$response;
    }
}
PHP;
    }
}