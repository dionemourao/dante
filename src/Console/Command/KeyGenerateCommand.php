<?php

namespace Lady\Console\Command;

class KeyGenerateCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'key:generate';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Gera uma chave de aplicação';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady key:generate [--show]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        // Gerar uma chave aleatória
        $key = $this->generateRandomKey();
        
        // Verificar se é apenas para mostrar a chave
        $showOnly = in_array('--show', $args);
        
        if ($showOnly) {
            $this->info("Chave de aplicação gerada: {$key}");
            return 0;
        }
        
        // Verificar se o arquivo .env existe
        $envPath = getcwd() . '/.env';
        if (!file_exists($envPath)) {
            // Verificar se existe um arquivo .env.example
            $envExamplePath = getcwd() . '/.env.example';
            if (file_exists($envExamplePath)) {
                // Copiar .env.example para .env
                if (!copy($envExamplePath, $envPath)) {
                    $this->error("Não foi possível criar o arquivo .env a partir de .env.example.");
                    return 1;
                }
            } else {
                // Criar um arquivo .env básico
                $defaultEnv = "APP_NAME=LadyPHP\nAPP_ENV=local\nAPP_DEBUG=true\nAPP_KEY=\n";
                if (file_put_contents($envPath, $defaultEnv) === false) {
                    $this->error("Não foi possível criar o arquivo .env.");
                    return 1;
                }
            }
        }
        
        // Ler o conteúdo do arquivo .env
        $envContent = file_get_contents($envPath);
        
        // Verificar se a chave APP_KEY já existe
        if (preg_match('/^APP_KEY=(.*)$/m', $envContent)) {
            // Substituir a chave existente
            $envContent = preg_replace('/^APP_KEY=(.*)$/m', "APP_KEY={$key}", $envContent);
        } else {
            // Adicionar a chave ao final do arquivo
            $envContent .= "\nAPP_KEY={$key}\n";
        }
        
        // Escrever o conteúdo atualizado no arquivo .env
        if (file_put_contents($envPath, $envContent) === false) {
            $this->error("Não foi possível atualizar o arquivo .env.");
            return 1;
        }
        
        $this->success("Chave de aplicação definida com sucesso.");
        return 0;
    }
    
    /**
     * Gera uma chave aleatória
     *
     * @return string
     */
    protected function generateRandomKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }
}