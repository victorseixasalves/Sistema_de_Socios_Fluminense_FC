<p align="center">
  <img src="public/images/EscudoFLU.png" alt="Fluminense F.C." width="90">
</p>

<h1 align="center">Fluminense F.C. — Sistema de Sócio-Torcedor</h1>

<p align="center">
  Sistema web para cadastro de sócio-torcedor do Fluminense Football Club, desenvolvido como teste técnico para vaga de estágio na Mupi Systems.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Node.js-24-339933?style=flat&logo=node.js&logoColor=white" alt="Node.js 24">
  <img src="https://img.shields.io/badge/Composer-2.10-885630?style=flat&logo=composer&logoColor=white" alt="Composer 2.10">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=flat&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/SQLite-3-003B57?style=flat&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=flat&logo=alpine.js&logoColor=white" alt="Alpine.js">
</p>

---

## Sumário

- [Sobre o projeto](#sobre-o-projeto)
- [Funcionalidades](#funcionalidades)
- [Stack utilizada](#stack-utilizada)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Pré-requisitos](#pré-requisitos)
- [Como rodar o projeto localmente](#como-rodar-o-projeto-localmente)
- [Enviando e-mails de verdade (SMTP)](#enviando-e-mails-de-verdade-smtp)
- [Estrutura do cadastro de sócio](#estrutura-do-cadastro-de-sócio)
- [Idiomas](#idiomas)

---

## Sobre o projeto

O sistema tem duas partes:

- 🌐 **Página pública**: qualquer visitante pode conhecer os planos de sócio-torcedor disponíveis e se cadastrar preenchendo um formulário. O cadastro é salvo com status `pendente`.
- 🔐 **Painel administrativo**: área protegida por login, onde o administrador visualiza todos os cadastros recebidos, aceita ou rejeita cada um, acompanha estatísticas em gráficos, e gerencia os planos e setores disponíveis no formulário público.

---

## Funcionalidades

### ✅ Requisitos principais
- Página pública com informações do programa de sócio-torcedor e formulário de cadastro
- Cadastro salvo automaticamente com status `pendente`
- Confirmação visual ao enviar o formulário
- Usuário não autenticado é redirecionado ao tentar acessar o painel
- Login do administrador com credenciais válidas
- Painel lista todos os cadastros, ordenados por data, com indicação visual de status
- Logout funcional

### Além do mínimo
- **Tema claro/escuro**, com preferência salva no navegador
- **Tradução completa** da interface em Português e Inglês (incluindo mensagens de validação)
- **Ordenação clicável** em qualquer coluna da tabela do painel
- **Aceitar/rejeitar cadastros** diretamente no painel, com notificação automática por email ao sócio
- **Notificação por email real**: suporte a envio via SMTP (Gmail) documentado abaixo, além do driver `log` padrão para ambiente de desenvolvimento sem depender de credenciais externas
- **Gráficos interativos** (sócios confirmados por plano e por setor), com efeito de destaque ao passar o mouse
- **Gerenciamento de Planos e Setores pelo admin**: criar, editar, ativar/desativar planos (com benefícios e opção de destaque "Mais popular") e setores do estádio — mudanças refletem automaticamente na página pública
- **Prevenção de cadastro duplicado** por email
- **Identidade visual própria**, com paleta e tipografia inspiradas no clube, fundo animado com padrão de escudos, e layout responsivo (desktop e mobile)
- **Segurança**: proteção anti-spam (honeypot), limite de tentativas de envio do formulário público (rate limiting), remoção da rota pública de registro (evitando criação não autorizada de contas administrativas), e cabeçalhos HTTP de segurança (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`)

> Mais detalhes e o raciocínio por trás de cada decisão estão em [`DECISOES.md`](./DECISOES.md).

---

## Stack utilizada

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Banco de dados | SQLite (padrão do Laravel 11+, sem configuração externa) |
| Autenticação | Laravel Breeze (Blade + Alpine.js) |
| Frontend | Blade + Tailwind CSS + Chart.js |
| Email | Laravel Mail (Markdown Mailables), driver `log` por padrão |

---

## Estrutura do projeto

```
projeto_estagio_2026_2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SocioController.php       # Página pública, cadastro, dashboard e gráficos
│   │   │   ├── PlanoController.php       # CRUD de planos de sócio
│   │   │   ├── SetorController.php       # CRUD de setores do estádio
│   │   │   └── Auth/                     # Controllers de autenticação (Breeze)
│   │   └── Middleware/
│   │       ├── SetLocale.php             # Aplica o idioma (pt/en) salvo na sessão
│   │       └── SecurityHeaders.php       # Cabeçalhos HTTP de segurança
│   ├── Mail/
│   │   └── SocioStatusMail.php           # Email de confirmação/rejeição de cadastro
│   └── Models/
│       ├── Socio.php
│       ├── Plano.php                     # Com scope ativos() e accessor beneficios_lista
│       ├── Setor.php
│       └── User.php
├── database/
│   ├── migrations/                       # Estrutura das tabelas do banco
│   └── seeders/                          # AdminSeeder, PlanoSeeder, SetorSeeder
├── lang/
│   ├── pt/                               # Mensagens de validação em português
│   └── en/                               # Mensagens de validação em inglês
├── resources/
│   ├── css/app.css                       # Tailwind + animações customizadas
│   ├── js/app.js                         # Tema, idioma, gráficos (Chart.js), interações
│   ├── lang/en.json                      # Traduções dos textos da interface
│   └── views/
│       ├── socios/create.blade.php       # Página pública (cadastro de sócio)
│       ├── dashboard.blade.php           # Painel admin (lista, gráficos, ações)
│       ├── planos/index.blade.php        # Gerenciamento de planos
│       ├── setores/index.blade.php       # Gerenciamento de setores
│       ├── emails/socio-status.blade.php # Template do email enviado ao sócio
│       └── layouts/                      # Layouts base (app, guest, navigation)
├── routes/
│   ├── web.php                           # Rotas da aplicação
│   └── auth.php                          # Rotas de autenticação (Breeze)
└── public/
    └── images/EscudoFLU.png              # Escudo oficial do clube
```

**Alguns pontos de arquitetura:**

- Segue o padrão **MVC** padrão do Laravel: rotas → controllers → models/views.
- Usa **route model binding** (ex: `Route::patch('/socios/{socio}/confirmar', ...)`), então os controllers recebem o model já resolvido pelo Laravel, sem precisar buscar manualmente por ID.
- **Scopes locais no Eloquent** (`Plano::ativos()`, `Setor::ativos()`) encapsulam a regra "só mostrar itens ativos", evitando repetir `where('ativo', true)` em vários lugares.
- **Accessor** `beneficios_lista` no model `Plano` transforma o texto salvo (um benefício por linha) num array pronto para a view, sem lógica de parsing espalhada pelo Blade.

---

## Pré-requisitos

Antes de rodar o projeto, tenha instalado:

| Ferramenta | Versão usada no desenvolvimento |
|---|---|
| PHP | 8.2.12 ou superior |
| Composer | 2.10.3 ou superior |
| Node.js | v24.14.1 ou superior (o `npm` já vem junto) |

> Não é necessário instalar o Laravel separadamente — o framework já vem incluso nas dependências do projeto (`composer.json`) e é baixado automaticamente no passo `composer install` abaixo.

---

## Como rodar o projeto localmente

> Antes de começar, confirme que você tem PHP, Composer e Node.js instalados nas versões indicadas na seção [Pré-requisitos](#pré-requisitos) acima.

```bash
# 1. Clone o repositório
git clone https://github.com/SEU-USUARIO/projeto_estagio_2026_2.git
cd projeto_estagio_2026_2

# 2. Instale as dependências PHP (isso baixa o Laravel e todas as bibliotecas)
composer install

# 3. Instale as dependências JavaScript
npm install

# 4. Copie o arquivo de ambiente e gere a chave da aplicação
cp .env.example .env
php artisan key:generate

# 5. Crie o arquivo do banco SQLite (se ainda não existir)
touch database/database.sqlite

# 6. Rode as migrations
php artisan migrate

# 7. Rode os seeders (cria o usuário admin, os planos e os setores padrão)
php artisan db:seed

# 8. Compile os assets de frontend
npm run build

# 9. Suba o servidor
php artisan serve
```

Acesse **http://127.0.0.1:8000** para ver a página pública.

> Durante o desenvolvimento, é mais prático rodar `npm run dev` num terminal separado em vez de `npm run build` — ele recompila o CSS/JS automaticamente a cada alteração.

### 🔑 Criar o usuário administrador

O usuário admin é criado automaticamente pelo `AdminSeeder` no passo 7 acima (`php artisan db:seed`), com as seguintes credenciais:

- **Email**: `admin@fluminense.com`
- **Senha**: `senha123`

Acesse **http://127.0.0.1:8000/login** para entrar no painel administrativo.

> ⚠️ Não existe cadastro público de novos administradores — essa rota foi removida intencionalmente por segurança (veja `DECISOES.md`). Se precisar de outro usuário admin, crie via `php artisan tinker` ou adicione ao `AdminSeeder`.

---

## Enviando e-mails de verdade (SMTP)

Por padrão (`MAIL_MAILER=log`), os e-mails da aplicação não são enviados de verdade — apenas gravados em `storage/logs/laravel.log`. Isso é suficiente para testar o fluxo, mas se você quiser *receber os e-mails de verdade* (por exemplo, para validar o template ou testar como se fosse produção), é possível configurar o envio via SMTP do Gmail.

### 1. Gerar uma senha de app no Google

O Gmail não aceita mais a senha normal da conta para SMTP — é necessário gerar uma *senha de app* específica:

1. Acesse [myaccount.google.com/security](https://myaccount.google.com/security) e ative a *Verificação em duas etapas*, caso ainda não esteja ativa (é pré-requisito).
2. Acesse [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords).
3. Gere uma nova senha de app (ex: nomeie como "Laravel" ou "Projeto Sócio-Torcedor").
4. Copie a senha gerada (16 caracteres).

### 2. Configurar o .env

Substitua as variáveis `MAIL_*` do seu `.env` por:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seuemail@gmail.com
MAIL_PASSWORD="senha-de-app-gerada"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seuemail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> ⚠️ Use a *senha de app*, não a senha normal da sua conta Google. `MAIL_FROM_ADDRESS` deve ser o mesmo e-mail usado em `MAIL_USERNAME`.

### 3. Limpar o cache de configuração

Sempre que alterar o `.env`, limpe o cache de config para que as mudanças tenham efeito:

```bash
php artisan config:clear
```

### 4. Testar

Cadastre um sócio ou aprove/rejeite um cadastro existente pelo dashboard — o e-mail deve chegar de verdade na caixa de entrada informada.

> Para times/produção, o recomendado é usar um serviço dedicado de e-mail transacional (ex: [Mailtrap](https://mailtrap.io) para testes, ou [Amazon SES](https://aws.amazon.com/ses/)/[Postmark](https://postmarkapp.com)/[Resend](https://resend.com) para produção) em vez de uma conta pessoal do Gmail.

---

## Estrutura do cadastro de sócio

| Campo | Descrição |
|---|---|
| Nome | Nome completo do torcedor |
| Email | Email de contato (único por sócio) |
| Plano | Selecionado entre os planos ativos cadastrados pelo admin |
| Data | Data de início desejada da associação |
| Setor | Selecionado entre os setores ativos cadastrados pelo admin |
| Status | Pendente, Confirmado ou Cancelado (nasce sempre como Pendente) |

---

## Idiomas

O site está disponível em **Português** (padrão) e **Inglês**, com um botão de alternância no cabeçalho. A preferência de idioma é salva na sessão do navegador. Planos e setores criados pelo administrador aparecem no idioma original em que foram cadastrados (sem tradução automática), já que são conteúdo dinâmico definido por quem gerencia o sistema.
