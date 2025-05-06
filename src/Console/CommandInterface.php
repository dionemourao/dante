<?php

namespace Lady\Console;

interface CommandInterface
{
    /**
     * Obtém o nome do comando
     *
     * @return string
     */
    public function getName(): string;
    
    /**
     * Obtém a descrição do comando
     *
     * @return string
     */
    public function getDescription(): string;
    
    /**
     * Obtém a sintaxe do comando
     *
     * @return string
     */
    public function getSyntax(): string;
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int;
}