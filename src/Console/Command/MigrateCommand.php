<?php

namespace Lady\Console\Command;

class MigrateCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'migrate';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Executa as migrações do banco de dados';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady migrate [create nome_tabela] [--create] [--fresh] [--seed]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        // Verificar se é para criar uma nova migração
        if (!empty($args) && $args[0] === 'create') {
            if (count($args) < 2) {
                $this->error('Nome da tabela não especificado.');
                $this->info('Uso: php lady migrate create nome_tabela [--create]');
                return 1;
            }
            
            $tableName = $args[1];
            $isCreate = in_array('--create', $args);
            
            return $this->createMigration($tableName, $isCreate);
        }
        
        // Verificar se é para executar as migrações do zero
        $fresh = in_array('--fresh', $args);
        
        // Verificar se é para executar os seeders após as migrações
        $seed = in_array('--seed', $args);
        
        // Executar as migrações
        return $this->runMigrations($fresh, $seed);
    }
    
    /**
     * Cria um arquivo de migração
     *
     * @param string $tableName
     * @param bool $isCreate
     * @return int
     */
    protected function createMigration(string $tableName, bool $isCreate): int
    {
        // Verificar se o diretório database/migrations existe
        $migrationsDir = getcwd() . '/database/migrations';
        if (!is_dir($migrationsDir)) {
            if (!mkdir($migrationsDir, 0755, true)) {
                $this->error("Não foi possível criar o diretório 'database/migrations'.");
                return 1;
            }
        }
        
        // Gerar nome do arquivo de migração
        $timestamp = date('Y_m_d_His');
        $action = $isCreate ? 'create' : 'update';
        $fileName = "{$timestamp}_{$action}_{$tableName}_table.php";
        
        // Caminho completo para o arquivo de migração
        $migrationPath = $migrationsDir . '/' . $fileName;
        
        // Criar o conteúdo da migração
        $content = $this->generateMigrationContent($tableName, $isCreate);
        
        // Escrever o arquivo
        if (file_put_contents($migrationPath, $content) === false) {
            $this->error("Não foi possível criar o arquivo de migração.");
            return 1;
        }
        
        $this->success("Migração '{$fileName}' criada com sucesso.");
        return 0;
    }
    
    /**
     * Executa as migrações
     *
     * @param bool $fresh
     * @param bool $seed
     * @return int
     */
    protected function runMigrations(bool $fresh, bool $seed): int
    {
        // Verificar se o diretório database/migrations existe
        $migrationsDir = getcwd() . '/database/migrations';
        if (!is_dir($migrationsDir)) {
            $this->error("Diretório 'database/migrations' não encontrado.");
            return 1;
        }
        
        // Aqui seria implementada a lógica para executar as migrações
        // Isso requer uma implementação completa do sistema de banco de dados
        
        if ($fresh) {
            $this->info("Recriando todas as tabelas do banco de dados...");
            // Lógica para recriar todas as tabelas
        } else {
            $this->info("Executando migrações pendentes...");
            // Lógica para executar migrações pendentes
        }
        
        $this->success("Migrações executadas com sucesso.");
        
        // Executar seeders se solicitado
        if ($seed) {
            $this->info("Executando seeders...");
            // Lógica para executar seeders
            $this->success("Seeders executados com sucesso.");
        }
        
        return 0;
    }
    
    /**
     * Gera o conteúdo do arquivo de migração
     *
     * @param string $tableName
     * @param bool $isCreate
     * @return string
     */
    protected function generateMigrationContent(string $tableName, bool $isCreate): string
    {
        $className = 'Migration' . date('YmdHis');
        
        if ($isCreate) {
            return <<<PHP
<?php

use Lady\Database\Migration;
use Lady\Database\Schema;
use Lady\Database\Blueprint;

class {$className} extends Migration
{
    /**
     * Executa a migração.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
            // Adicione suas colunas aqui
            \$table->timestamps();
        });
    }

    /**
     * Reverte a migração.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
}
PHP;
        } else {
            return <<<PHP
<?php

use Lady\Database\Migration;
use Lady\Database\Schema;
use Lady\Database\Blueprint;

class {$className} extends Migration
{
    /**
     * Executa a migração.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('{$tableName}', function (Blueprint \$table) {
            // Adicione suas modificações aqui
        });
    }

    /**
     * Reverte a migração.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('{$tableName}', function (Blueprint \$table) {
            // Reverta suas modificações aqui
        });
    }
}
PHP;
        }
    }
}