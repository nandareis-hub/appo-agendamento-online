# 📅 APPO — Agendamentos Online

Projeto desenvolvido no âmbito da Unidade Curricular **UC615**.

---

## Identificação dos Autores

- Thamires Santos
- Fernanda Reis
- Mairane Gusmão

---

## Descrição da Solução / Problema Resolvido

A gestão manual de horários em salões de beleza e estética frequentemente resulta em conflitos de marcações, falhas de comunicação e perda de produtividade, tanto para os clientes como para os profissionais.

O **APPO** é uma plataforma web responsiva, dinâmica e segura que resolve este problema ao centralizar todo o processo de agendamento: os clientes podem consultar profissionais e serviços disponíveis, marcar, editar e cancelar os seus próprios agendamentos em tempo real, enquanto os profissionais mantêm a sua agenda organizada automaticamente, sem sobreposição de horários.

**Público-alvo:** clientes de salões de beleza que procuram conveniência na marcação de horários, e profissionais (cabeleireiros/esteticistas) que precisam de gerir a sua agenda e atendimento.

---

## Principais Funcionalidades

- **RF01 — Registo de Utilizadores**: registo de novos utilizadores com validação de dados obrigatórios
- **RF02 — Autenticação (Login/Logout)**: acesso restrito ao painel e encerramento seguro da sessão
- **RF03 — Agendamento de Serviços**: seleção de serviço, profissional capacitado, data e hora
- **RF04 — Consulta de Marcações**: listagem detalhada de todos os agendamentos ativos do utilizador
- **RF05 — Edição de Agendamento**: alteração de marcações existentes, mantendo as regras de associação entre profissional e serviço
- **RF06 — Cancelamento de Agendamento**: remoção segura de uma marcação pertencente ao utilizador autenticado

---

## Tecnologias Utilizadas

| Tecnologia   | Função                                                 |
| ------------ | -------------------------------------------------------- |
| HTML5        | Estrutura das páginas                                   |
| CSS3         | Estilo e layout responsivo                               |
| JavaScript   | Validações no lado do cliente e interatividade         |
| PHP          | Lógica de servidor e regras de negócio                 |
| MySQL        | Base de dados relacional                                 |
| PDO          | Acesso seguro à base de dados (Prepared Statements)     |
| XAMPP        | Ambiente de desenvolvimento local (Apache + MySQL + PHP) |
| Git & GitHub | Controlo de versões e colaboração                     |

---

## Arquitetura Resumida

A aplicação segue uma arquitetura modular em 3 camadas, com separação clara de responsabilidades:

Interface Web (HTML/CSS/JS)

│

▼

Backend PHP (autenticação, validações, regras de agendamento)

│

▼

Base de Dados MySQL (utilizadores, profissionais, serviços, marcações)

**Modelo relacional** (5 tabelas):

| Tabela                   | Função Principal                                             |
| ------------------------ | -------------------------------------------------------------- |
| `utilizadores`         | Registo de clientes e utilizadores do sistema                  |
| `profissionais`        | Registo da equipa de colaboradores do salão                   |
| `servicos`             | Catálogo de serviços disponibilizados                        |
| `profissional_servico` | Tabela associativa (N:M) — competências de cada profissional |
| `marcacoes`            | Registo central das reservas de horário                       |

**Segurança:** proteção contra injeção SQL através de Prepared Statements via PDO, e verificação de que uma marcação só pode ser editada/eliminada se pertencer ao próprio utilizador autenticado.

---

## Estrutura do Projeto

```text
appo-agendamento-online/
│
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── registo.php
│
├── css/
│   └── style.css
│
├── database/
│   └── appo.sql
│
├── docs/
│   ├── evidencias_testes/
│   │   ├── Fernanda_Reis_evidencias-testes.mp4
│   │   ├── Mairane-t4aot7-t11.mp4
│   │   └── Thamires_Santos_Auth_Login.mp4
│   ├── 01-analise-requisitos-appo.pdf
│   ├── 02-desenvolvimento-controle-de-versoes.pdf
│   ├── 03-prototipo-funcional.pdf
│   ├── 04-plano-de-testes.pdf
│   └── 04.1-relatorio-do-desenvolvimento-da-app.pdf
│
├── img/
│   └── appo.png
│
├── includes/
│   ├── automacao_sistema.php
│   ├── conexao.php
│   └── funcoes.php
│
├── js/
│   ├── agenda.js
│   └── validacao.js
│
├── painel/
│   ├── editar-marcacao.php
│   ├── home.php
│   ├── minhas-marcacoes.php
│   └── nova-marcacao.php
│
├── .gitignore
├── README.md
└── teste_conexao.php
```

---

## Instruções de Instalação

### Pré-requisitos

- XAMPP (Apache + MySQL + PHP 8+)
- Visual Studio Code (opcional)

### Passos

1. Clonar o repositório:

```bash
git clone https://github.com/nandareis-hub/appo-agendamento-online.git
```

2. Colocar o projeto na pasta `htdocs` do XAMPP.
3. Iniciar o **Apache** e o **MySQL** no painel de controlo do XAMPP.
4. Importar o ficheiro da base de dados:

```text
database/appo.sql
```

---

## Instruções de Execução

1. Abrir o navegador (recomendado: Google Chrome ou Microsoft Edge) em:

```text
http://localhost/appo-agendamento-online
```

2. Criar uma conta em **Registo** ou entrar com uma conta já existente em **Login**.
3. Aceder ao painel principal, onde é possível criar, consultar, editar e cancelar marcações.

---

## Exemplos de Utilização

**Fluxo típico de uma marcação:**

1. O cliente regista-se em `auth/registo.php`, fornecendo nome, email e palavra-passe.
2. Faz login em `auth/login.php` — é redirecionado para `painel/home.php`, onde vê uma saudação personalizada e o total de marcações ativas.
3. Em `painel/nova-marcacao.php`, escolhe um profissional — o sistema apresenta automaticamente apenas os serviços que esse profissional realiza (validado através da tabela `profissional_servico`).
4. Seleciona o serviço, a data e a hora, e confirma. A marcação é guardada com o estado **Pendente**.
5. Em `painel/minhas-marcacoes.php`, consulta a lista de marcações (serviço, profissional, data/hora, preço, estado), podendo **Editar** ou **Cancelar** cada uma.

---

## Limitações Conhecidas

Durante a fase de testes (ver `docs/04-plano-de-testes.pdf`), foram identificadas as seguintes limitações, ainda por corrigir:

- **Marcações em horário já ocupado não são bloqueadas**: o sistema deveria recusar uma nova marcação quando o profissional já tem outro atendimento à mesma data/hora, mas atualmente permite a duplicação (teste T11 — FALHOU).
- **Edição de marcação para data/hora inválida não é validada**: ao editar uma marcação existente, o sistema não reaplica as mesmas regras usadas na criação (ex: não impede datas passadas), permitindo alterações inconsistentes (teste T12 — FALHOU).

---

## Possíveis Desenvolvimentos Futuros

- Implementação de notificações automáticas por e-mail no momento da confirmação ou alteração da marcação
- Integração com um gateway de pagamento online para liquidação prévia do valor do serviço
- Módulo de administração para os profissionais gerirem as suas agendas e indisponibilidades
- Validação de conflitos de horário e de datas inválidas também na edição de marcações (corrigindo as limitações identificadas nos testes T11 e T12)

---

## Vídeo Demonstrativo

🔗 [drive.google.com/file/d/1cxhhwnAQ-F7FbF-TdIt6qnNyElXtghvH/view?usp=sharing](https://drive.google.com/file/d/1cxhhwnAQ-F7FbF-TdIt6qnNyElXtghvH/view?usp=sharing)

---

## Organização das Branches

| Branch                      | Finalidade                    |
| --------------------------- | ----------------------------- |
| `master`                  | Versão estável do projeto   |
| `thami-conexao-bd`        | Base de dados e ligação PHP |
| `feature/update-frontend` | Desenvolvimento do frontend   |
| `feature/update-backend`  | Desenvolvimento do backend    |

---

## Estado do Projeto

✅ Concluído — todas as funcionalidades planeadas foram implementadas e testadas. Duas limitações conhecidas (ver secção acima) ficam registadas como trabalho futuro.
