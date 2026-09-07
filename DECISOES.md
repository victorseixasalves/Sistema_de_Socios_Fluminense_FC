# Decisões do projeto

## Tema e produto

Escolhi o programa de sócio-torcedor do **Fluminense F.C.** porque o cadastro de sócios atende de forma natural a todos os requisitos do desafio: captação pública de cadastros, triagem com status inicial `pendente` e painel de gestão com controle operacional. O tema permitiu incluir planos com benefícios e destaque, além de setores do estádio, sem complexidade desnecessária no modelo relacional. Ademais, a ideia deste tema se deu por eu ser torcedor do Fluminense e perceber como é precário alguns sistemas que o próprio clube utiliza, que precisam ser atualizados tendo em vista o avanço tanto tecnológico quanto do clube.

## Arquitetura e stack

Optei por **Laravel 12 (PHP 8.2)** com **SQLite** por ser a combinação padrão do próprio framework a partir da versão 11, dispensando qualquer configuração externa de banco de dados e permitindo que o projeto rode localmente com o mínimo de fricção possível para quem for avaliar.

- **Laravel 12 + Eloquent:** O framework entrega roteamento, validação, autenticação e ORM prontos, com padrão MVC bem definido, o que reduz boilerplate e mantém o código organizado em camadas claras (rotas → controllers → models/views).
- **Laravel Breeze (Blade + Alpine.js):** Forneceu a base de autenticação (login, logout, recuperação de senha) já testada e madura, permitindo concentrar esforço nas regras de negócio específicas do sócio-torcedor em vez de reimplementar autenticação do zero.
- **SQLite:** Elimina a necessidade de subir um servidor de banco separado para rodar ou avaliar o projeto — o arquivo `database.sqlite` já resolve tudo.
- **Blade + Tailwind CSS + Chart.js:** O Blade mantém a renderização simples e integrada ao Laravel, o Tailwind acelera a estilização com classes utilitárias e suporte nativo a tema escuro, e o Chart.js cobre a necessidade de gráficos interativos no painel sem exigir um framework JS completo.
- **Scopes locais no Eloquent (`Plano::ativos()`, `Setor::ativos()`):** Encapsulam a regra "só mostrar itens ativos", evitando repetir `where('ativo', true)` em vários lugares do código.
- **Route model binding:** Controllers como `SocioController::confirmar(Socio $socio)` recebem o model já resolvido pelo Laravel a partir do ID na rota, sem necessidade de busca manual.

**O que ganhei com a escolha:**

- Ambiente de desenvolvimento e avaliação sem dependências externas (nada de subir Postgres, Redis ou serviços de terceiros).
- Autenticação robusta e testada (Breeze) sem gastar tempo reimplementando login/logout/reset de senha.
- Menos código repetido graças a scopes e accessors do Eloquent.

**O que perdi (trade-offs):**

- SQLite não é a escolha ideal para um ambiente de produção com alta concorrência; para esse cenário, seria necessário migrar para PostgreSQL ou MySQL.
- Blade + Alpine.js oferece menos interatividade "SPA-like" do que uma stack com React/Vue, o que é aceitável dado o escopo do desafio, mas limitaria a experiência em telas mais complexas.

## Decisões de produto

- **Planos e setores desativados:** Ao desativar um plano ou setor no painel, ele deixa de aparecer no formulário público (via `scopeAtivos`), mas cadastros de sócios já existentes vinculados a eles continuam intactos e visíveis no painel.
- **Plano em destaque único:** Apenas um plano pode estar marcado como "Mais popular" por vez — ao marcar um novo como destaque, o anterior é automaticamente desmarcado.
- **Fluxo de status:** O cadastro nasce sempre como `pendente`. O admin pode **confirmar** (status vira `confirmado` e o sócio recebe email) ou **rejeitar** — nesse caso, o registro é removido definitivamente do banco (não existe um status "cancelado" persistido), após o envio do email de notificação.
- **Prevenção de cadastro duplicado:** Validação `unique:socios,email` impede que o mesmo email seja usado em mais de um cadastro.
- **Proteção anti-spam (honeypot):** Um campo oculto (`site_web`) no formulário público é invisível para humanos, mas costuma ser preenchido por bots. Se vier preenchido, a aplicação finge sucesso sem salvar nada, sem revelar ao bot que foi bloqueado.
- **Rate limiting no cadastro público:** A rota de envio do formulário (`POST /`) tem limite de 3 tentativas por minuto (`throttle:3,1`), reduzindo abuso sem prejudicar o uso legítimo.
- **Remoção da rota pública de registro:** A rota padrão do Breeze para criar novos usuários administradores foi removida de `routes/auth.php`, evitando que qualquer visitante crie uma conta com acesso ao painel.
- **Ordenação clicável no painel:** Qualquer coluna da tabela de sócios pode ser usada para ordenar a listagem, com a coluna e direção validadas contra uma lista de colunas permitidas antes de chegar à query.
- **Idioma como conteúdo dinâmico:** Planos e setores cadastrados pelo admin aparecem no idioma original em que foram digitados, sem tradução automática, já que são conteúdo livre definido por quem gerencia o sistema — diferente das mensagens fixas da interface, que são totalmente traduzidas (pt/en).

## Escopo consciente

- **Constraint de unicidade apenas na aplicação:** A prevenção de email duplicado é feita via validação do Laravel (`unique`), não como constraint física no schema do SQLite. Suficiente para o volume e o cenário do desafio, mas uma característica a evoluir se o projeto crescesse.
- **Envio de email real como opcional:** Por padrão a aplicação usa o driver `log` do Laravel Mail (grava em `storage/logs/laravel.log`), para não depender de credenciais externas na avaliação. O README documenta o passo a passo para configurar SMTP via Gmail caso se queira validar o envio de verdade.
- **Sem notificações em tempo real:** Desnecessárias para a volumetria proposta; o painel é recarregado a cada ação do administrador.

## Testes

O projeto manteve a suíte de testes de autenticação gerada pelo Laravel Breeze (login, registro, verificação de email, reset e confirmação de senha) em `tests/Feature/Auth`, além do teste padrão de perfil (`ProfileTest`) e dos testes de exemplo (`ExampleTest`) gerados pelo `laravel new`.

Já para testes da utilização do sistema, foram feitos por mim próprio pensando em todas as possíbilidades que o usuário poder realizar no sistema.

## Melhorias além do mínimo
 
1. **Tema claro/escuro:** Alternância entre os dois temas com um botão no cabeçalho, com a preferência salva no navegador (persistida entre sessões) e aplicada tanto na página pública quanto no painel. Os gráficos do dashboard também reagem à troca de tema, recalculando a cor da borda das fatias e da legenda em tempo real (ver `resources/js/app.js`).

2. **Tradução completa da interface (pt/en):** Todo o texto visível — títulos, botões, labels de formulário e, principalmente, as mensagens de validação do Laravel (`lang/pt` e `lang/en`) — está traduzido. O idioma escolhido é salvo na sessão do navegador e aplicado a cada requisição pelo middleware `SetLocale`, com uma rota dedicada (`/idioma/{locale}`) para alternância.

3. **Ordenação clicável em qualquer coluna do painel:** A tabela de sócios no dashboard permite ordenar por nome, email, plano, data, setor ou status, clicando no cabeçalho da coluna. No backend, o `SocioController::index` valida a coluna recebida via query string contra uma lista explícita de colunas permitidas antes de montar o `orderBy`, evitando que um parâmetro arbitrário chegue à query. Mais detalhes técnicos em [Decisões de produto](#decisões-de-produto).

4. **Aceitar/rejeitar cadastros com notificação automática por email:** O admin confirma ou rejeita um cadastro diretamente no painel, sem sair da tela. Cada ação dispara um email ao sócio (`SocioStatusMail`, um Mailable em Markdown) informando o resultado. A regra de negócio por trás da rejeição (que remove o registro em vez de apenas marcar como cancelado) está detalhada em [Decisões de produto](#decisões-de-produto).

5. **Notificação por email real (SMTP) além do driver `log`:** Por padrão a aplicação grava os emails em `storage/logs/laravel.log`, o que é suficiente para validar o fluxo sem configurar nada. Mas o README documenta o passo a passo completo para configurar envio real via SMTP do Gmail (senha de app, variáveis `MAIL_*`, `php artisan config:clear`), permitindo testar como se fosse produção sem alterar uma linha de código.

6. **Gráficos interativos no painel:** Dois gráficos de pizza (Chart.js) mostram a distribuição de sócios **confirmados** por plano e por setor, com paleta de cores baseada na identidade visual do clube, efeito de destaque (`hoverOffset`) ao passar o mouse sobre uma fatia, e legenda/bordas que se adaptam ao tema claro ou escuro.

7. **Gerenciamento de planos e setores pelo admin:** CRUD completo para ambos — criar, editar e ativar/desativar — com reflexo imediato no formulário público via os scopes `Plano::ativos()` e `Setor::ativos()`. Planos ainda suportam uma lista de benefícios (um por linha, convertida em array pelo accessor `beneficios_lista`) e uma flag de destaque "Mais popular", que é exclusiva: marcar um novo plano como destaque desmarca automaticamente o anterior.

8. **Prevenção de cadastro duplicado por email:** A validação `unique:socios,email` no `SocioController::store` impede que o mesmo email seja usado em mais de um cadastro de sócio, com mensagem de erro traduzida e amigável ("Este email já possui um cadastro de sócio ativo."). Mais detalhes técnicos, incluindo a limitação de ser uma validação de aplicação e não uma constraint física do banco, em [Decisões de produto](#decisões-de-produto) e [Escopo consciente](#escopo-consciente).

9. **Camada de segurança adicional:** Honeypot no formulário público (campo oculto `site_web` que, se preenchido, faz a aplicação fingir sucesso sem salvar nada — sem revelar ao bot que foi bloqueado), rate limiting de 3 tentativas por minuto na rota de cadastro (`throttle:3,1`), remoção da rota pública de registro de administradores (herdada do Breeze), e cabeçalhos HTTP de segurança (`X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`) aplicados globalmente pelo middleware próprio `SecurityHeaders`.

10. **Identidade visual própria:** Paleta de cores (grená e verde do clube) e tipografia próprias, fundo animado com padrão de escudos na página pública, e layout responsivo testado em desktop e mobile — em vez de usar o visual padrão gerado pelo Breeze.

## Uso de IA

**O que foi delegado vs o que foi feito à mão**

O uso de IA se concentrou principalmente na fase inicial do projeto, como apoio à produtividade e à organização das ideias. Ela foi útil para estruturar o esqueleto inicial de código (boilerplate de controllers, migrations e views), sugerir uma primeira organização para requisitos que eu já tinha em mente, e servir como uma espécie de "consultoria rápida" sempre que eu esbarrava em algo que ainda não sabia fazer no Laravel ou tinha uma dúvida pontual sobre a melhor forma de implementar determinado trecho (ex: parte de segurança).

Já as decisões de arquitetura, a modelagem do banco de dados, as regras de negócio do sócio-torcedor (fluxo de status, regra do plano em destaque único, prevenção de cadastro duplicado, entre outras) e  estruturadas e revisadas por mim.

**Onde a IA errou**

O ponto onde a IA mais errou foi na tradução da interface (pt/en). Ao gerar o dicionário de traduções em lang/en.json, ela cobriu bem as telas que eu descrevia como prioridade (página pública, formulário de cadastro e painel administrativo), mas deixou de fora parte das telas de autenticação herdadas do Breeze. Como o Blade usa o próprio texto em português como chave de busca no JSON, quando não existe uma entrada correspondente ele simplesmente devolve o texto original em português, mesmo com o idioma da sessão definido como inglês.

Outro erro foi na montagem dos gráficos do dashboard. Na primeira versão, a consulta que alimentava os gráficos de pizza contava todos os sócios agrupados por plano e por setor, sem filtrar pelo status do cadastro — ou seja, sócios ainda pendente, que nunca passaram pela aprovação do admin, apareciam misturados com os confirmado nos mesmos gráficos.

**Decisões tomadas contra a sugestão da IA**

Um primeiro ponto de discordância foi sobre a alternância de tema claro/escuro. A IA sugeriu que essa funcionalidade não era necessária para o escopo do desafio, já que não fazia parte dos requisitos obrigatórios e demandaria tempo extra ajustando cores em toda a aplicação (página pública, painel e até os gráficos). Discordei porque considero que esse tipo de detalhe melhora bastante a experiência de quem for avaliar o projeto, além de ser uma forma de mostrar cuidado com a interface além do mínimo pedido. Implementei mesmo assim, incluindo a persistência da preferência entre sessões e o ajuste das cores dos gráficos do Chart.js conforme o tema ativo.

Em alguns momentos também, a IA sugeriu que certas camadas de proteção não eram necessárias dado o escopo "de desafio" do projeto — por exemplo, tratar o honeypot no formulário público, o rate limiting na rota de cadastro ou os cabeçalhos HTTP de segurança como algo dispensável para uma aplicação que não iria para produção de verdade. Discordei dessa visão: mesmo em um projeto de avaliação, considero que é sempre importante já nascer com essas proteções básicas, tanto porque custam pouco para implementar quanto porque demonstram uma preocupação real com segurança, que é justamente um dos critérios que um projeto desse tipo deveria refletir.

Por fim, a IA configurou (na primeira versão) apenas o driver log do Laravel Mail, sem deixar preparado nem documentado um caminho para envio real, argumentando que isso seria suficiente para a avaliação. Achei essa solução incompleta: mesmo mantendo o log como padrão para não depender de credenciais externas, seria importante que qualquer pessoa avaliando o projeto pudesse validar o envio de verdade, se quisesse. Por isso adicionei o suporte a envio real via SMTP (Gmail), documentando no README o passo a passo com senha de app e variáveis MAIL_*, sem alterar nenhuma linha de código para isso funcionar — ficando as duas opções disponíveis, com o log apenas como padrão mais conveniente.