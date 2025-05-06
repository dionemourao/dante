<?php

namespace Lady\Console\Command;

class ServeCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'serve';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Inicia o servidor de desenvolvimento';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady serve [--host=127.0.0.1] [--port=8000]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        // Definir valores padrão
        $host = '127.0.0.1';
        $port = 8000;
        
        // Processar argumentos
        foreach ($args as $arg) {
            if (strpos($arg, '--host=') === 0) {
                $host = substr($arg, 7);
            } elseif (strpos($arg, '--port=') === 0) {
                $port = (int) substr($arg, 7);
            }
        }
        
        // Verificar se o diretório public existe
        $publicDir = getcwd() . '/public';
        if (!is_dir($publicDir)) {
            $this->error("Diretório 'public' não encontrado.");
            return 1;
        }
        
        $this->info("Iniciando servidor de desenvolvimento em http://{$host}:{$port}");
        $this->info("Pressione Ctrl+C para parar o servidor");
        
        // Iniciar o servidor PHP embutido
        passthru("php -S {$host}:{$port} -t {$publicDir}");
        
        return 0;
    }
}