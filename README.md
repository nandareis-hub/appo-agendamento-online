

# 📅 APPO – Agendamentos Online

Projeto desenvolvido no âmbito da Unidade Curricular **UC615**.

O APPO é uma plataforma de gestão de agendamentos online que permite aos clientes marcar serviços de forma rápida e intuitiva, enquanto disponibiliza aos profissionais uma forma simples de gerir a sua agenda.

---

## Funcionalidades

- Registo e autenticação de utilizadores
- Consulta de profissionais e serviços
- Criação de marcações
- Consulta de marcações
- Edição de marcações
- Cancelamento de marcações
- Logout seguro

---

## Tecnologias

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- XAMPP
- Git & GitHub

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

## Documentação

O projeto inclui documentação completa nas várias etapas de desenvolvimento, disponível na pasta `docs/`:

- **01 — Análise de Requisitos**: identificação do problema, objetivos e requisitos funcionais/não funcionais
- **02 — Desenvolvimento e Controlo de Versões**: tecnologias, arquitetura e planeamento do trabalho em equipa
- **03 — Protótipo Funcional**: apresentação do protótipo desenvolvido
- **04 — Plano de Testes**: casos de teste definidos para validação da aplicação
- **04.1 — Relatório do Desenvolvimento**: relatório final sobre o processo de desenvolvimento
- **Evidências de Testes**: vídeos de demonstração dos testes realizados por cada elemento do grupo

---

## Participantes

- Thamires Santos
- Fernanda Reis
- Mairane Gusmão

---

## Organização das Branches

| Branch                | Finalidade                    |
| --------------------- | ----------------------------- |
| `main`              | Versão estável do projeto   |
| `thami-conexao-bd`  | Base de dados e ligação PHP |
| `fernanda-frontend` | Desenvolvimento do frontend   |
| `mairane-backend`   | Desenvolvimento do backend    |

---

## Como executar o projeto

### Pré-requisitos

- XAMPP
- PHP 8+
- MySQL
- Visual Studio Code (opcional)

### Instalação

1. Clonar o repositório:

```bash
git clone https://github.com/nandareis-hub/appo-agendamento-online.git
```

2. Colocar o projeto na pasta `htdocs` do XAMPP.
3. Iniciar o **Apache** e o **MySQL**.
4. Importar o ficheiro:

```text
database/appo.sql
```

5. Abrir o navegador em:

```text
http://localhost/appo-agendamento-online
```

---

## Estado do Projeto

✅ Concluído — todas as funcionalidades planeadas foram implementadas, testadas e documentadas.
