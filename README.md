
# 📅 APPO – Agendamentos Online

Projeto desenvolvido no âmbito da Unidade Curricular **UC615**.

O APPO é uma plataforma de gestão de agendamentos online que permite aos clientes marcar serviços de forma rápida e intuitiva, enquanto disponibiliza aos profissionais uma forma simples de gerir a sua agenda.

---

## Funcionalidades

- Registo e autenticação de utilizadores
- Consulta de profissionais e serviços
- Criação de marcações
- Consulta de marcações
- Cancelamento de marcações

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
│   └── registo.php
│
├── css/
│   └── style.css
│
├── database/
│   └── appo.sql
│
├── docs/
│   ├── 01-analise-requisitos-appo.pdf
│   └── 02-desenvolvimento-controle-de-versoes.pdf
│
├── includes/
│   ├── conexao.php
│   └── funcoes.php
│
├── js/
│   ├── agenda.js
│   └── validacao.js
│
├── painel/
│   ├── home.php
│   ├── minhas-marcacoes.php
│   └── nova-marcacao.php
│
├── .gitignore
└── README.md
```

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

🚧 Em desenvolvimento.
