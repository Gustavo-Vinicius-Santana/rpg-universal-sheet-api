# RPG Universal Sheet API

Bem-vindo ao **RPG Universal Sheet API**, uma API desenvolvida em **PHP** com o framework **Laravel 12** para gerenciar e fornecer dados dinâmicos de fichas de RPG. Este projeto tem como objetivo criar uma API flexível para a criação, edição e gerenciamento de modelos de fichas de RPG, onde os usuários podem definir atributos, perícias e habilidades de maneira dinâmica.

## Tecnologias Usadas

- **PHP 8.x**
- **Laravel 12.x**
- **PostgreSQL (PSQL)**
  
## Funcionalidades

- Criação e gerenciamento de modelos de fichas de RPG.
- Criação e edição de campos dinâmicos como atributos, perícias e habilidades.
- Armazenamento de dados em banco de dados PostgreSQL.

## Instalação

### Requisitos

Antes de iniciar, você precisa ter os seguintes requisitos instalados:

- **PHP 8.x ou superior**
- **Composer** (gerenciador de dependências PHP)
- **PostgreSQL** (com banco de dados configurado)

### Passo 1: Clone o repositório

```bash
git clone https://github.com/seu-usuario/rpg-universal-sheet-api.git
cd rpg-universal-sheet-api
```

### Passo 2: Instale as dependências do PHP

```bash
composer install
```

### Passo 3: Configuração do banco de dados

1. Crie um banco de dados no PostgreSQL para a aplicação.
2. No arquivo .env, configure as credenciais do banco de dados:

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rpg_universal
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### Passo 4: Gerar as chaves de autenticação do Laravel
Execute o comando abaixo para gerar a chave de autenticação do Laravel:
```bash
php artisan key:generate
```

### Passo 5: Rodar as migrações
```bash
php artisan migrate
```

### Passo 6: Rodar o servidor
Com tudo configurado, você pode rodar o servidor localmente utilizando o comando abaixo:
```bash
php artisan serve
```

