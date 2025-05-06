<?php

namespace Lady\Console\Command;

class MakeControllerCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'make:controller';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Cria um novo controlador';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady make:controller NomeDoController [--resource]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        if (empty($args)) {
            $this->error('Nome do controlador não especificado.');
            $this->info('Uso: ' . $this->getSyntax());
            return 1;
        }
        
        $controllerName = $args[0];
        $isResource = in_array('--resource', $args);
        
        // Verificar se o nome do controlador termina com "Controller"
        if (!str_ends_with($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }
        
        // Verificar se o diretório app/Controllers existe
        $controllersDir = getcwd() . '/app/Controllers';
        if (!is_dir($controllersDir)) {
            if (!mkdir($controllersDir, 0755, true)) {
                $this->error("Não foi possível criar o diretório 'app/Controllers'.");
                return 1;
            }
        }
        
        // Caminho completo para o arquivo do controlador
        $controllerPath = $controllersDir . '/' . $controllerName . '.php';
        
        // Verificar se o controlador já existe
        if (file_exists($controllerPath)) {
            $this->error("O controlador '{$controllerName}' já existe.");
            return 1;
        }
        
        // Criar o conteúdo do controlador
        $content = $this->generateControllerContent($controllerName, $isResource);
        
        // Escrever o arquivo
        if (file_put_contents($controllerPath, $content) === false) {
            $this->error("Não foi possível criar o arquivo do controlador.");
            return 1;
        }
        
        $this->success("Controlador '{$controllerName}' criado com sucesso.");
        return 0;
    }
    
    /**
     * Gera o conteúdo do controlador
     *
     * @param string $controllerName
     * @param bool $isResource
     * @return string
     */
    protected function generateControllerContent(string $controllerName, bool $isResource): string
    {
        $namespace = 'App\\Controllers';
        
        if ($isResource) {
            return <<<PHP
<?php

namespace {$namespace};

use Lady\Controller\BaseController;

class {$controllerName} extends BaseController
{
    /**
     * Exibe uma lista dos recursos.
     *
     * @return mixed
     */
    public function index()
    {
        // Implementação do método index
        return \$this->view('index');
    }

    /**
     * Exibe o formulário para criar um novo recurso.
     *
     * @return mixed
     */
    public function create()
    {
        // Implementação do método create
        return \$this->view('create');
    }

    /**
     * Armazena um recurso recém-criado.
     *
     * @return mixed
     */
    public function store()
    {
        // Implementação do método store
        // Processar dados do formulário
        return \$this->redirect('/');
    }

    /**
     * Exibe o recurso especificado.
     *
     * @param int \$id
     * @return mixed
     */
    public function show(int \$id)
    {
        // Implementação do método show
        return \$this->view('show', ['id' => \$id]);
    }

    /**
     * Exibe o formulário para editar o recurso especificado.
     *
     * @param int \$id
     * @return mixed
     */
    public function edit(int \$id)
    {
        // Implementação do método edit
        return \$this->view('edit', ['id' => \$id]);
    }

    /**
     * Atualiza o recurso especificado.
     *
     * @param int \$id
     * @return mixed
     */
    public function update(int \$id)
    {
        // Implementação do método update
        // Processar dados do formulário
        return \$this->redirect('/');
    }

    /**
     * Remove o recurso especificado.
     *
     * @param int \$id
     * @return mixed
     */
    public function destroy(int \$id)
    {
        // Implementação do método destroy
        return \$this->redirect('/');
    }
}
PHP;
        } else {
            return <<<PHP
<?php

namespace {$namespace};

use Lady\Controller\BaseController;

class {$controllerName} extends BaseController
{
    /**
     * Exibe a página inicial.
     *
     * @return mixed
     */
    public function index()
    {
        return \$this->view('index');
    }
}
PHP;
        }
    }
}