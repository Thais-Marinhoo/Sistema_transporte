# Sistema de Gerenciamento de Transporte Escolar

## Sobre o Projeto

O Sistema de Gerenciamento de Transporte Escolar foi desenvolvido com o objetivo de auxiliar na administração do transporte de estudantes, permitindo o gerenciamento de alunos, motoristas, ônibus, rotas e pontos de embarque.

O sistema oferece uma interface simples e funcional para cadastro, consulta, edição e exclusão de informações, além de recursos de autenticação de usuários e recuperação de senha por e-mail.

---

## Funcionalidades

### Gerenciamento de Alunos
- Cadastro de alunos
- Edição de dados dos alunos
- Exclusão de alunos
- Listagem de alunos cadastrados
- Promoção dos alunos para o próximo ano letivo
- Associação de alunos aos seus respectivos pontos 

### Gerenciamento de Motoristas
- Cadastro de motoristas
- Edição de motoristas
- Exclusão de motoristas
- Listagem de motoristas cadastrados

### Gerenciamento de Ônibus
- Cadastro de ônibus
- Edição de ônibus
- Exclusão de ônibus
- Listagem de ônibus cadastrados

### Gerenciamento de Rotas
- Cadastro de rotas
- Edição de rotas
- Exclusão de rotas
- Listagem das rotas cadastradas

### Gerenciamento de Pontos
- Cadastro de pontos de embarque
- Edição de pontos
- Associação de pontos às rotas

### Relatórios
- Geração de relatórios em PDF

### Autenticação
- Login de usuários
- Logout
- Controle de sessão
- Recuperação de senha por e-mail
- Validação por código de recuperação

---

## Tecnologias Utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- PHPMailer
- FPDF
- Apache (XAMPP)

---

## Estrutura do Projeto

projeto_transporte/
│
├── PHPMailer-master/
│   └── Biblioteca utilizada para envio de e-mails
│
├── site/
│   │
│   ├── fpdf19/
│   │   └── Biblioteca utilizada para geração de PDFs
│   │
│   ├── alerta_botao.js
│   ├── alertas.php
│   ├── cadastro.js
│   ├── cadastroback.php
│   ├── editar_aluno_back.php
│   ├── editar_ponto_back.php
│   ├── editar_rota_back.php
│   ├── excluir_aluno.php
│   ├── gerar_pdf.php
│   ├── imagem.png
│   ├── lista.alunos.php
│   ├── lista.js
│   ├── logo.png
│   ├── logout.php
│   ├── main.php
│   ├── menu.php
│   ├── mstyle.css
│   ├── rotas.js
│   ├── rotas_back.php
│   ├── subir_ano.php
│   ├── tela.cadastro.php
│   └── telarotas.php
│
├── alterar.php
├── conexao.php
├── enviar_codigo.php
├── esqueci_senha.php
├── index.php
├── login.php
├── logo.png
├── salvar_nova_senha.php
├── style.css
├── validar_codigo.php
└── verificar_codigo.php

---

## Descrição dos Principais Arquivos

| Arquivo | Função |
|----------|---------|
| index.php | Página inicial do sistema |
| login.php | Autenticação dos usuários |
| conexao.php | Conexão com o banco de dados |
| lista.alunos.php | Listagem de alunos associados ao seus respectivos pontos |
| telarotas.php | Gerenciamento de rotas e pontos|
| rotas_back.php | Processamento das operações relacionadas às rotas e pontos |
| editar_aluno_back.php | Atualização dos dados dos alunos |
| editar_rota_back.php | Atualização dos dados das rotas |
| editar_ponto_back.php | Atualização dos dados dos pontos |
| excluir_aluno.php | Exclusão de alunos |
| gerar_pdf.php | Geração de relatórios em PDF |
| subir_ano.php | Promoção dos alunos para o próximo ano |
| enviar_codigo.php | Envio do código de recuperação de senha |
| verificar_codigo.php | Verificação do código enviado |
| validar_codigo.php | Validação da recuperação de senha |
| salvar_nova_senha.php | Atualização da senha do usuário |
| logout.php | Encerramento da sessão |

---

## Requisitos

Para executar o sistema é necessário possuir:

- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Apache
- XAMPP (recomendado)
- Navegador web atualizado

---

## Instalação

### 1. Baixar ou clonar o projeto


git clone https://github.com/Thais-Marinhoo/Sistema_transporte.git

Ou copie a pasta do projeto para:

C:\xampp\htdocs\[PASTA_DO_SISTEMA] -> Cole o diretório aqui


### 2. Iniciar os serviços

Abra o XAMPP e inicie:

- Apache
- MySQL

### 3. Criar o banco de dados

Acesse:

http://localhost/phpmyadmin


Crie o banco de dados utilizado pelo sistema.

### 4. Configurar a conexão

Abra o arquivo:

conexao.php

Configure os dados do banco:

```php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = [NOME_DO_BANCO];
```

### 5. Executar o sistema

Acesse no navegador:

http://localhost/[PASTA_DO_SISTEMA]/index.php

---

## Bibliotecas Utilizadas

### PHPMailer

Biblioteca responsável pelo envio de e-mails utilizados na recuperação de senha.

### FPDF

Biblioteca utilizada para geração de relatórios em formato PDF.

### Google Charts

Biblioteca JavaScript baseada em nuvem, gratuita e mantida pelo Google, utilizada neste projeto para a criação de gráficos interativos e painéis de dados. 

---

## Desenvolvedores

- Thais Marinho Gomes
- Nayra Amanda de Sousa Soares
- Heitor Almeida de Oliveira Almeida 

---

## Instituição

Projeto desenvolvido para fins acadêmicos.

---

## Licença

Este projeto foi desenvolvido exclusivamente para fins educacionais.
