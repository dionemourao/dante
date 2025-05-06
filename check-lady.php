<?php

// Verificar se o autoloader existe
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "Erro: Autoloader não encontrado. Execute 'composer install'.\n";
    exit(1);
}

// Carregar o autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Verificar se a classe Lady existe
if (!class_exists('Lady\\Console\\Lady')) {
    echo "Erro: A classe Lady\\Console\\Lady não foi encontrada.\n";
    
    // Verificar a estrutura de diretórios
    $srcDir = __DIR__ . '/src';
    $consoleDir = $srcDir . '/Console';
    $ladyFile = $consoleDir . '/Lady.php';
    
    echo "Verificando estrutura de diretórios:\n";
    echo "- Diretório src: " . (is_dir($srcDir) ? "Existe" : "Não existe") . "\n";
    echo "- Diretório Console: " . (is_dir($consoleDir) ? "Existe" : "Não existe") . "\n";
    echo "- Arquivo Lady.php: " . (file_exists($ladyFile) ? "Existe" : "Não existe") . "\n";
    
    if (file_exists($ladyFile)) {
        echo "\nConteúdo do arquivo Lady.php:\n";
        echo "--------------------------------\n";
        echo file_get_contents($ladyFile);
        echo "--------------------------------\n";
    }
    
    exit(1);
} else {
    echo "Sucesso: A classe Lady\\Console\\Lady foi encontrada.\n";
}

// Verificar se todas as classes de comando existem
$commands = [
    'Lady\\Console\\Command\\Command',
    'Lady\\Console\\Command\\ListCommand',
    'Lady\\Console\\Command\\HelpCommand',
    'Lady\\Console\\Command\\MakeControllerCommand',
    'Lady\\Console\\Command\\MakeModelCommand',
    'Lady\\Console\\Command\\MakeViewCommand',
    'Lady\\Console\\Command\\ServeCommand',
    'Lady\\Console\\Command\\MigrateCommand',
    'Lady\\Console\\Command\\KeyGenerateCommand'
];

$allCommandsExist = true;
foreach ($commands as $command) {
    if (!class_exists($command)) {
        echo "Erro: A classe {$command} não foi encontrada.\n";
        $allCommandsExist = false;
    }
}

if ($allCommandsExist) {
    echo "Sucesso: Todas as classes de comando foram encontradas.\n";
    echo "\nA instalação do Lady CLI parece estar correta!\n";
    exit(0);
} else {
    echo "\nAlgumas classes de comando não foram encontradas. Verifique a estrutura de diretórios e o namespace.\n";
    exit(1);
}