# Lady PHP Framework

Lady PHP é um framework elegante e moderno para desenvolvimento web em PHP, projetado para facilitar a criação de aplicações robustas e escaláveis. Com uma estrutura inspirada nos melhores frameworks do mercado, Lady PHP oferece uma experiência de desenvolvimento fluida e produtiva.

## Estrutura do Projeto

```
lady-framework/
├── app/                    # Código da aplicação
│   ├── Controllers/        # Controladores da aplicação
│   ├── Middleware/         # Middlewares da aplicação
│   └── Models/             # Modelos da aplicação
├── bootstrap/              # Arquivos de inicialização
│   └── app.php             # Inicialização da aplicação
├── config/                 # Arquivos de configuração
│   ├── app.php             # Configurações gerais
│   ├── database.php        # Configurações de banco de dados
│   └── routes.php          # Configurações de rotas
├── database/               # Arquivos relacionados ao banco de dados
│   ├── migrations/         # Migrações de banco de dados
│   └── seeds/              # Seeders para popular o banco de dados
├── public/                 # Diretório público acessível pelo servidor web
│   ├── css/                # Arquivos CSS
│   ├── js/                 # Arquivos JavaScript
│   ├── images/             # Imagens
│   └── index.php           # Ponto de entrada da aplicação
├── resources/              # Recursos da aplicação
│   ├── assets/             # Assets (SASS, JS não compilados)
│   └── views/              # Arquivos de visualização
├── routes/                 # Definições de rotas
│   ├── api.php             # Rotas para API
│   └── web.php             # Rotas para web
├── src/                    # Código fonte do framework
│   ├── Console/            # Componentes de linha de comando
│   │   ├── Command/        # Comandos disponíveis
│   │   └── Lady.php        # Classe principal do CLI
│   ├── Controller/         # Classes base de controladores
│   ├── Database/           # Componentes de banco de dados
│   ├── Http/               # Componentes HTTP
│   ├── Middleware/         # Classes base de middleware
│   ├── Routing/            # Sistema de roteamento
│   └── View/               # Sistema de visualização
├── storage/                # Armazenamento de arquivos gerados
│   ├── app/                # Arquivos da aplicação
│   ├── framework/          # Arquivos do framework
│   └── logs/               # Logs da aplicação
├── tests/                  # Testes automatizados
├── vendor/                 # Dependências do Composer
├── .env                    # Variáveis de ambiente
├── .env.example            # Exemplo de variáveis de ambiente
├── composer.json           # Configuração do Composer
├── lady                    # Executável do Lady CLI
└── README.md               # Documentação do projeto
```

## Requisitos

- PHP 7.4 ou superior
- Composer
- Extensões PHP: PDO, JSON, Mbstring

## Instalação

```bash
composer create-project lady/framework meu-projeto
cd meu-projeto
php lady key:generate
```

## Lady CLI

O Lady Framework inclui uma poderosa ferramenta de linha de comando para facilitar o desenvolvimento. Abaixo estão os principais comandos disponíveis:

### Comandos Básicos

| Comando | Descrição | Exemplo |
|---------|-----------|---------|
| `list` | Lista todos os comandos disponíveis | `php lady list` |
| `help` | Exibe ajuda para um comando específico | `php lady help make:controller` |
| `serve` | Inicia o servidor de desenvolvimento | `php lady serve --port=8080` |
| `key:generate` | Gera uma chave de aplicação | `php lady key:generate` |

### Comandos de Criação

| Comando | Descrição | Exemplo |
|---------|-----------|---------|
| `make:controller` | Cria um novo controlador | `php lady make:controller UserController --resource` |
| `make:model` | Cria um novo modelo | `php lady make:model User --migration` |
| `make:middleware` | Cria um novo middleware | `php lady make:middleware Auth` |
| `make:view` | Cria uma nova view | `php lady make:view users.index` |

### Comandos de Banco de Dados

| Comando | Descrição | Exemplo |
|---------|-----------|---------|
| `migrate` | Executa as migrações pendentes | `php lady migrate` |
| `migrate create` | Cria uma nova migração | `php lady migrate create users --create` |
| `migrate --fresh` | Recria todas as tabelas | `php lady migrate --fresh` |
| `migrate --seed` | Executa seeders após migrações | `php lady migrate --fresh --seed` |

## Exemplos de Uso

### Criando um CRUD Completo

```bash
# Criar modelo com migração
php lady make:model Post --migration

# Criar controlador de recursos
php lady make:controller PostController --resource

# Criar views
php lady make:view posts.index
php lady make:view posts.create
php lady make:view posts.edit
php lady make:view posts.show

# Executar migrações
php lady migrate

# Iniciar servidor
php lady serve
```

### Usando o Servidor de Desenvolvimento

```bash
# Iniciar na porta padrão (8000)
php lady serve

# Iniciar em uma porta específica
php lady serve --port=8080

# Iniciar em um host específico
php lady serve --host=0.0.0.0 --port=8080
```

### Trabalhando com Migrações

```bash
# Criar uma migração para uma nova tabela
php lady migrate create posts --create

# Criar uma migração para modificar uma tabela existente
php lady migrate create add_status_to_posts

# Executar migrações pendentes
php lady migrate

# Recriar todas as tabelas e executar seeders
php lady migrate --fresh --seed
```

## Solução de Problemas

### Comando não encontrado

Se você receber o erro "Command not found", verifique se o arquivo `lady` é executável:

```bash
chmod +x lady
```

### Classe não encontrada

Se você receber o erro "Class not found", regenere o autoloader:

```bash
composer dump-autoload
```

### Erro ao iniciar o servidor

Se você receber o erro "sh: php: command not found" ao executar `php lady serve`, use o caminho completo para o PHP:

```bash
# Para XAMPP no macOS
/Applications/XAMPP/bin/php lady serve

# Para XAMPP no Windows
C:\xampp\php\php.exe lady serve
```

Ou modifique o arquivo `src/Console/Command/ServeCommand.php` para usar `PHP_BINARY` em vez de `php`.

## Usando com XAMPP/MAMP

Se você estiver usando XAMPP ou MAMP, pode criar um alias para facilitar o uso do Lady CLI:

### macOS (Bash/Zsh)

```bash
# Para XAMPP
echo 'alias lady="/Applications/XAMPP/bin/php /caminho/para/lady"' >> ~/.zshrc
source ~/.zshrc

# Para MAMP
echo 'alias lady="/Applications/MAMP/bin/php/php7.4.21/bin/php /caminho/para/lady"' >> ~/.zshrc
source ~/.zshrc
```

### Windows (PowerShell)

```powershell
# Adicionar ao perfil do PowerShell
echo 'function lady { & "C:\xampp\php\php.exe" "C:\caminho\para\lady" $args }' >> $PROFILE
. $PROFILE
```

## Contribuindo

Contribuições são bem-vindas! Por favor, sinta-se à vontade para enviar um Pull Request.

1. Fork o projeto
2. Crie sua branch de feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo LICENSE para detalhes.

## Créditos

Lady PHP Framework é desenvolvido e mantido por [Seu Nome/Equipe].

---

Para mais informações e documentação detalhada, visite [https://lady-framework.com](https://lady-framework.com).
