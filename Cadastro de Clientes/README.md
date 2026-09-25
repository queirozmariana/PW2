# ☕ Grão & Co. — CRM de Clientes

Sistema web de **Cadastro de Clientes (CRM Simples)** desenvolvido para uma cafeteria fictícia.

O sistema permite cadastrar clientes, consultar seus dados, registrar compras e acompanhar o histórico de compras de cada cliente.

## 🎯 Objetivo

Desenvolver um sistema CRUD utilizando **PHP e MySQL**, com interface desenvolvida com **Bootstrap e JavaScript**, tendo como foco o gerenciamento de clientes e o histórico de compras.

## 🛠️ Tecnologias utilizadas

- PHP
- MySQL
- Bootstrap 5.3
- Bootstrap Icons
- JavaScript
- HTML5
- CSS3
- XAMPP
- Git/GitHub

## ✨ Funcionalidades

### Dashboard

- Total de clientes cadastrados
- Total de compras
- Total em vendas
- Quantidade de novos clientes no mês
- Lista de clientes recentes

### Clientes

- Cadastrar cliente
- Listar clientes
- Pesquisar clientes
- Visualizar detalhes
- Editar cliente
- Excluir cliente

### Compras

- Cadastrar compra
- Listar compras
- Editar compra
- Excluir compra
- Associar compra a um cliente
- Visualizar histórico de compras por cliente
- Calcular automaticamente o total de cada compra
- Calcular o total gasto por cliente

## 🗃️ Banco de dados

O projeto utiliza duas tabelas principais: `clientes` e `compras`.

### `clientes`

| Campo | Tipo | Descrição |
|---|---|---|
| id | INT | Identificador do cliente |
| nome | VARCHAR(120) | Nome completo |
| email | VARCHAR(150) | E-mail |
| telefone | VARCHAR(20) | Telefone |
| cpf | VARCHAR(14) | CPF |
| data_cadastro | DATETIME | Data e hora do cadastro |

### `compras`

| Campo | Tipo | Descrição |
|---|---|---|
| id | INT | Identificador da compra |
| cliente_id | INT | Cliente relacionado |
| produto | VARCHAR(120) | Produto comprado |
| quantidade | INT | Quantidade |
| valor | DECIMAL(10,2) | Valor unitário |
| data_compra | DATE | Data da compra |

### Relacionamento

Um cliente pode possuir várias compras.

`clientes (1) → (N) compras`

A chave estrangeira `compras.cliente_id` referencia `clientes.id`.

## 🎥 Vídeo de funcionamento

O vídeo demonstrando o funcionamento do sistema está disponível na pasta **`video/`** do projeto.

**Arquivo:** `Gravações de Tela - Cafeteria.mp4`

O vídeo apresenta as principais funcionalidades do sistema em funcionamento.

## 🚀 Como executar o projeto

### 1. Instale o XAMPP

Instale o XAMPP e abra o painel de controle.

Ative:

- Apache
- MySQL

### 2. Coloque o projeto no XAMPP

Copie a pasta `grao-co` para:

`C:\xampp\htdocs\`

O caminho final deve ser:

`C:\xampp\htdocs\grao-co\`

### 3. Crie o banco de dados

Abra:

`http://localhost/phpmyadmin`

Clique em **Importar** e selecione o arquivo:

`sql/banco_phpmyadmin.sql`

Execute a importação.

O banco `grao_co_crm` deve ser criado ou selecionado no phpMyAdmin antes da importação.

O arquivo SQL cria as tabelas e alguns dados de demonstração.

### 4. Verifique a conexão

O arquivo `config/conexao.php` utiliza a configuração padrão do XAMPP:

- **Host:** localhost
- **Usuário:** root
- **Senha:** vazia
- **Banco:** grao_co_crm

Se o XAMPP estiver configurado dessa forma, nenhuma alteração será necessária.

### 5. Abra o sistema

Acesse:

`http://localhost/grao-co/`

## 🔄 Operações CRUD

O projeto possui CRUD de clientes:

- **Create:** cadastrar cliente
- **Read:** listar e visualizar cliente
- **Update:** editar cliente
- **Delete:** excluir cliente

Também possui CRUD de compras:

- **Create:** cadastrar compra
- **Read:** listar e visualizar histórico
- **Update:** editar compra
- **Delete:** excluir compra

## 🎨 Interface

O projeto utiliza uma identidade visual inspirada em cafeterias contemporâneas, com tons de café, creme e caramelo.

A interface foi desenvolvida utilizando **Bootstrap, Bootstrap Icons e CSS próprio**, incluindo:

- Sidebar de navegação
- Dashboard com indicadores
- Ilustração temática de cafeteria
- Cards
- Tabelas responsivas
- Formulários
- Página de detalhes do cliente
- Histórico de compras
- Responsividade para telas menores

## 📁 Estrutura do projeto

```text
grao-co/
├── config/
│   └── conexao.php
├── css/
│   └── style.css
├── img/
│   └── coffee-hero.svg
├── js/
│   └── script.js
├── sql/
│   └── banco_phpmyadmin.sql
├── video/
│   └── Gravações de Tela - Cafeteria.mp4
├── index.php
├── clientes.php
├── cliente.php
├── cadastrar_cliente.php
├── editar_cliente.php
├── excluir_cliente.php
├── compras.php
├── cadastrar_compra.php
├── editar_compra.php
├── excluir_compra.php
├── partials_sidebar.php
└── README.md
```

## 👩‍💻 Autoria

**Mariana Queiroz**

Projeto acadêmico — Desenvolvimento de Sistemas.

---

⚠️ **Projeto desenvolvido para fins acadêmicos.**

**Grão & Co. — CRM de Clientes**
