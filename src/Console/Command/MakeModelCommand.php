<?php

namespace Lady\Console\Command;

class MakeModelCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'make:model';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Cria um novo modelo';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady make:model NomeDoModelo [--migration]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        if (empty($args)) {
            $this->error('Nome do modelo não especificado.');
            $this->info('Uso: ' . $this->getSyntax());
            return 1;
        }
        
        $modelName = $args[0];
        $createMigration = in_array('--migration', $args);
        
        // Verificar se o diretório app/Models existe
        $modelsDir = getcwd() . '/app/Models';
        if (!is_dir($modelsDir)) {
            if (!mkdir($modelsDir, 0755, true)) {
                $this->error("Não foi possível criar o diretório 'app/Models'.");
                return 1;
            }
        }
        
        // Caminho completo para o arquivo do modelo
        $modelPath = $modelsDir . '/' . $modelName . '.php';
        
        // Verificar se o modelo já existe
        if (file_exists($modelPath)) {
            $this->error("O modelo '{$modelName}' já existe.");
            return 1;
        }
        
        // Criar o conteúdo do modelo
        $content = $this->generateModelContent($modelName);
        
        // Escrever o arquivo
        if (file_put_contents($modelPath, $content) === false) {
            $this->error("Não foi possível criar o arquivo do modelo.");
            return 1;
        }
        
        $this->success("Modelo '{$modelName}' criado com sucesso.");
        
        // Criar migração se solicitado
        if ($createMigration) {
            $migrateCommand = new MigrateCommand();
            $tableName = $this->pluralize(strtolower($modelName));
            $migrateCommand->execute(['create', $tableName, '--create']);
        }
        
        return 0;
    }
    
    /**
     * Gera o conteúdo do modelo
     *
     * @param string $modelName
     * @return string
     */
    protected function generateModelContent(string $modelName): string
    {
        $namespace = 'App\\Models';
        $tableName = $this->pluralize(strtolower($modelName));
        
        return <<<PHP
<?php

namespace {$namespace};

use Lady\Database\Model;

class {$modelName} extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected \$table = '{$tableName}';
    
    /**
     * Chave primária da tabela.
     *
     * @var string
     */
    protected \$primaryKey = 'id';
    
    /**
     * Indica se o modelo deve gerenciar timestamps.
     *
     * @var bool
     */
    protected \$timestamps = true;
    
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected \$fillable = [
        // Defina os campos preenchíveis aqui
    ];
}
PHP;
    }
    
    /**
     * Converte uma string para o plural (simplificado)
     *
     * @param string \$singular
     * @return string
     */
    protected function pluralize(string $singular): string
    {
        $lastLetter = substr($singular, -1);
        
        if ($lastLetter === 'y') {
            return substr($singular, 0, -1) . 'ies';
        }
        
        if (in_array($lastLetter, ['s', 'x', 'z'])) {
            return $singular . 'es';
        }
        
        return $singular . 's';
    }
}