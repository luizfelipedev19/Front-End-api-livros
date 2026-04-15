<div align="center">

# BookManager

Aplicacao web para gerenciamento de livros, desenvolvida em PHP, com interface server-side e integracao com uma API externa de autenticacao e biblioteca.

<p>
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img alt="Apache" src="https://img.shields.io/badge/Apache-Server-D22128?style=for-the-badge&logo=apache&logoColor=white">
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black">
  <img alt="Docker" src="https://img.shields.io/badge/Docker-Containerized-2496ED?style=for-the-badge&logo=docker&logoColor=white">
  <img alt="Cloudinary" src="https://img.shields.io/badge/Cloudinary-Media-3448C5?style=for-the-badge&logo=cloudinary&logoColor=white">
</p>

</div>

## Visao geral

O **BookManager** e a camada web de um sistema de biblioteca pessoal. Ele entrega a interface usada pelo usuario, controla sessao, organiza as rotas da aplicacao e intermedeia a comunicacao com uma **API externa de livros e autenticacao**.

Mesmo sendo um repositorio focado no front-end da aplicacao, este projeto nao e apenas uma interface estatica. Ele tambem executa responsabilidades de camada web, como:

- renderizacao server-side com PHP;
- controle de autenticacao por sessao;
- consumo de endpoints REST via cURL;
- envio e tratamento de formularios;
- retorno de JSON para interacoes AJAX;
- organizacao das paginas, rotas e assets.

## Funcionalidades

- Login de usuario
- Cadastro de usuario
- Logout
- Recuperacao e redefinicao de senha
- Listagem de livros cadastrados
- Cadastro de novos livros
- Edicao de livros
- Exclusao de livros
- Busca textual de livros
- Filtros por autor, ano e status
- Resumo da biblioteca na home
- Tela de perfil do usuario
- Upload de foto de perfil
- Controle de sessao por inatividade
- Feedback visual com popup e flash message

## Stack utilizada

### Back-end

- PHP 8.2
- Apache
- cURL nativo do PHP
- Sessoes nativas do PHP
- Composer
- Cloudinary PHP SDK

### Front-end

- HTML
- CSS
- JavaScript puro
- Fetch API

### Infraestrutura

- Docker
- Docker Compose
- Apache `mod_rewrite`

## Arquitetura

O projeto segue uma organizacao inspirada em **MVC simples**, sem framework PHP.

### Camadas principais

- `index.php`: front controller da aplicacao
- `routers.php`: tabela de rotas
- `app/Controller`: controllers PHP da camada web
- `app/Views`: telas renderizadas no servidor
- `app/Utils`: utilitarios de autenticacao, flash e configuracao
- `public/`: CSS, imagens e scripts do navegador

### Fluxo da aplicacao

```text
Navegador
  -> requisita uma rota web
index.php
  -> resolve a rota em routers.php
Controller PHP
  -> valida entrada e sessao
  -> consome a API externa via cURL
API externa
  -> retorna JSON
Controller PHP
  -> renderiza view ou responde JSON
Front-end
  -> atualiza interface com JavaScript
```

## Estrutura de pastas

```text
Front-End-api-livros/
|- app/
|  |- Controller/
|  |- Utils/
|  `- Views/
|- public/
|  |- Controller/
|  |- Models/
|  |- css/
|  |- img/
|  `- js/
|- vendor/
|- .htaccess
|- composer.json
|- docker-compose.yml
|- Dockerfile
|- index.php
|- routers.php
|- README.md
`- DOCUMENTACAO_PROJETO.md
```

## Controllers principais

- `AuthController`: login e exibicao da tela inicial
- `RegisterController`: cadastro de usuario
- `HomeController`: dashboard e listagem de livros
- `LivroController`: cadastro, edicao e exclusao de livros
- `PerfilController`: pagina de perfil e resumo da biblioteca
- `UploadFotoController`: upload de foto de perfil
- `SenhaController`: recuperacao e redefinicao de senha
- `LogoutController`: encerramento de sessao

## Integracao com API

A aplicacao depende de uma API disponivel no ambiente Docker em:

```bash
http://api_livros_app:80
```

### Endpoints consumidos

- `POST /login`
- `POST /register`
- `GET /livros`
- `POST /livros`
- `PUT /livro/editar`
- `DELETE /livro/deletar`
- `PATCH /usuario/foto`
- `POST /recuperar-senha`
- `POST /redefinir-senha`

## Autenticacao e sessao

A autenticacao e baseada em sessao PHP. Depois do login, o sistema armazena dados como:

- token de acesso
- UUID do usuario
- nome
- email
- foto de perfil
- horario da ultima atividade

Essas informacoes sao usadas para autorizar chamadas para a API, personalizar a interface e controlar expiracao por inatividade.

## Upload de foto

A foto de perfil e enviada para a **Cloudinary**. Depois do upload, a URL final da imagem e encaminhada para a API principal e tambem atualizada na sessao do usuario.

## Como executar

### Requisitos

- Docker
- Docker Compose
- Rede Docker externa `api-livros_api_livros_net`
- API principal rodando na mesma rede

### Subir o projeto

```bash
docker compose up --build
```

### Acesso local

```bash
http://localhost:8082/Front-Biblioteca
```

## Dependencias

A dependencia principal declarada no projeto e:

```json
{
  "require": {
    "cloudinary/cloudinary_php": "*"
  }
}
```

As demais bibliotecas presentes em `vendor/` sao dependencias transitivas instaladas pelo Composer.
