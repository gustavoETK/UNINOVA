<div align="center">

# 🎓 UNINOVA — Portal Acadêmico Integrado

**Português** | [English](#-uninova--integrated-academic-portal-english)

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

*O projeto é um portal universitário*

</div>

---

## 📋 Sobre o Projeto

O **UNINOVA** é um portal acadêmico universitário completo, construído em um único arquivo PHP autocontido. Simula um sistema de gestão acadêmica com design moderno dark-mode, cursor personalizado, partículas animadas e múltiplos módulos funcionais — tudo sem banco de dados, sem frameworks e sem dependências externas além do Google Fonts.

> **Nota:** O projeto utiliza dados fictícios (fake database) em arrays PHP, ideal para fins de demonstração, prototipagem ou aprendizado.

---

## ✨ Funcionalidades

| Módulo | Descrição |
|---|---|
| 🔐 **Login** | Autenticação por sessão com 3 perfis de acesso |
| 🏠 **Dashboard** | Visão geral com stats, desempenho e próximos eventos |
| 📊 **Notas & Médias** | Boletim semestral + calculadora de média final (M1, M2×2) |
| 🗓 **Horários** | Grade de horários semanal interativa |
| 📋 **Frequência** | Controle de faltas por disciplina com alertas |
| 💳 **Financeiro** | Histórico de mensalidades, pendências e resumo financeiro |
| 📚 **Biblioteca** | Empréstimos ativos e busca no acervo |
| 📰 **Notícias** | Comunicados institucionais com categorias |
| 🏛 **Secretaria** | Solicitação de documentos e histórico de pedidos |
| 💬 **Suporte & FAQ** | Perguntas frequentes com accordion + abertura de chamados |
| 👤 **Perfil** | Dados pessoais, segurança e estatísticas |
| 📅 **Calendário** | Calendário acadêmico semestral e próximas avaliações |

---

## 🎨 Design & UI

- **Tema:** Dark mode com paleta roxa/teal
- **Tipografia:** Syne (títulos), Outfit (corpo), JetBrains Mono (código/monospace)
- **Cursor personalizado** com efeito de anel animado
- **Canvas de partículas** animadas no fundo
- **Animações** de entrada com `cubic-bezier` suave
- **Layout responsivo** com breakpoints para mobile e tablet
- **Sistema de design próprio** com tokens CSS (`--teal`, `--violet`, `--rose`, etc.)

---

## 👥 Contas de Acesso (Demo)

| Usuário | Senha | Perfil |
|---|---|---|
| `aluno` | `1234` | Aluno — Lucas Ferreira, CC, 4º Semestre |
| `prof` | `prof123` | Professor — Dra. Ana Paula Rocha |
| `coord` | `coord456` | Coordenador — Prof. Ricardo Almeida |

---

## 🚀 Como Usar

### Pré-requisitos

- PHP **8.0 ou superior**
- Servidor web com suporte a PHP (Apache, Nginx, ou servidor embutido do PHP)

### Instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/uninova-portal.git
   cd uninova-portal
   ```

2. **Execute com o servidor embutido do PHP:**
   ```bash
   php -S localhost:8000
   ```

3. **Acesse no navegador:**
   ```
   http://localhost/uninova
   ```

4. **Faça login** com qualquer uma das contas demo listadas acima.

### Com Apache / Nginx

Basta copiar o arquivo `enviar_v2.0.php` para o diretório raiz do seu servidor web (ex: `htdocs`, `www` ou `public_html`) e acessar pelo navegador.

---

## 🧮 Fórmula de Média

O portal utiliza a seguinte fórmula de cálculo de média final, onde **M2 tem peso duplo**:

```
MF = (M1 + M2 × 2) ÷ 3
```

| Média Final | Situação |
|---|---|
| ≥ 7,00 | ✅ Aprovado |
| ≥ 5,00 e < 7,00 | ⚠️ Recuperação |
| < 5,00 | ❌ Reprovado |

---

## 🗂 Estrutura do Projeto

```
uninova-portal/
└── enviar_v2.0.php     # Aplicação completa (single-file)
```

Por ser uma aplicação single-file, toda a lógica PHP, CSS e JavaScript está contida em um único arquivo:

```
enviar_v2.0.php
├── Fake Database (arrays PHP)
├── Lógica de sessão e autenticação
├── Roteamento por query string (?s=secao)
├── Funções auxiliares (mediaFinal, situacao, pctFaltas)
├── <style> — Sistema de design CSS completo
├── HTML — Login e Portal autenticado
└── <script> — Cursor, partículas e calculadora live
```

---

## ⚙️ Tecnologias

- **PHP** — Lógica de servidor, sessões, roteamento e renderização
- **HTML5 / CSS3** — Markup semântico e sistema de design completo
- **JavaScript Vanilla** — Cursor animado, canvas de partículas e preview live de média
- **Google Fonts** — Syne, Outfit, JetBrains Mono
- **Nenhuma dependência externa de JS** (sem jQuery, sem React, sem nada)

---

## ⚠️ Avisos Importantes

- **Não use em produção com dados reais.** O sistema não possui hash de senhas, proteção CSRF nem validação robusta.
- Os dados são fictícios e reiniciados a cada sessão (sem persistência).
- Destinado exclusivamente a **demonstração, estudo e prototipagem**.

---

## 📄 Licença

Distribuído sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

---

<br>
<br>

---

<div align="center">

# 🎓 UNINOVA — Integrated Academic Portal (English)

[Português](#-uninova--portal-acadêmico-integrado) | **English**

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

*The project is a university portal.*

</div>

---

## 📋 About

**UNINOVA** is a fully-featured university academic portal built as a single self-contained PHP file. It simulates an academic management system with a modern dark-mode design, custom cursor, animated particles, and multiple functional modules — all without a database, without frameworks, and without external dependencies beyond Google Fonts.

> **Note:** The project uses fake data (PHP arrays), making it ideal for demos, prototyping, or learning purposes.

---

## ✨ Features

| Module | Description |
|---|---|
| 🔐 **Login** | Session-based authentication with 3 access profiles |
| 🏠 **Dashboard** | Overview with stats, performance charts and upcoming events |
| 📊 **Grades & GPA** | Semester report card + GPA calculator (M1, M2×2) |
| 🗓 **Schedule** | Interactive weekly class schedule grid |
| 📋 **Attendance** | Absence tracking per subject with alerts |
| 💳 **Financial** | Tuition payment history, pending bills and financial summary |
| 📚 **Library** | Active loans and catalog search |
| 📰 **News** | Institutional announcements with categories |
| 🏛 **Secretary** | Document requests and request history |
| 💬 **Support & FAQ** | Accordion FAQ + ticket submission |
| 👤 **Profile** | Personal data, security settings and statistics |
| 📅 **Calendar** | Academic semester calendar and upcoming assessments |

---

## 🎨 Design & UI

- **Theme:** Dark mode with purple/teal palette
- **Typography:** Syne (headings), Outfit (body), JetBrains Mono (code/mono)
- **Custom cursor** with animated ring effect
- **Animated particle canvas** background
- **Smooth entry animations** using `cubic-bezier` easing
- **Responsive layout** with mobile and tablet breakpoints
- **Custom design system** with CSS tokens (`--teal`, `--violet`, `--rose`, etc.)

---

## 👥 Demo Accounts

| Username | Password | Role |
|---|---|---|
| `aluno` | `1234` | Student — Lucas Ferreira, CS, 4th Semester |
| `prof` | `prof123` | Professor — Dr. Ana Paula Rocha |
| `coord` | `coord456` | Coordinator — Prof. Ricardo Almeida |

---

## 🚀 Getting Started

### Prerequisites

- PHP **8.0 or higher**
- A web server with PHP support (Apache, Nginx, or PHP's built-in server)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/uninova-portal.git
   cd uninova-portal
   ```

2. **Run with PHP's built-in server:**
   ```bash
   php -S localhost:8000
   ```

3. **Open in your browser:**
   ```
   http://localhost/uninova
   ```

4. **Log in** using any of the demo accounts listed above.

### With Apache / Nginx

Simply copy `enviar_v2.0.php` to your web server's root directory (e.g., `htdocs`, `www`, or `public_html`) and navigate to it in your browser.

---

## 🧮 Grade Formula

The portal uses the following GPA formula, where **M2 carries double weight**:

```
Final Grade = (M1 + M2 × 2) ÷ 3
```

| Final Grade | Status |
|---|---|
| ≥ 7.00 | ✅ Passed |
| ≥ 5.00 and < 7.00 | ⚠️ Retake |
| < 5.00 | ❌ Failed |

---

## 🗂 Project Structure

```
uninova-portal/
└── enviar_v2.0.php     # Complete application (single-file)
```

Since this is a single-file application, all PHP logic, CSS, and JavaScript are contained in one file:

```
enviar_v2.0.php
├── Fake Database (PHP arrays)
├── Session & authentication logic
├── Routing via query string (?s=section)
├── Helper functions (mediaFinal, situacao, pctFaltas)
├── <style> — Complete CSS design system
├── HTML — Login page and authenticated portal
└── <script> — Cursor, particles and live GPA calculator
```

---

## ⚙️ Tech Stack

- **PHP** — Server-side logic, sessions, routing and rendering
- **HTML5 / CSS3** — Semantic markup and complete design system
- **Vanilla JavaScript** — Animated cursor, particle canvas and live grade preview
- **Google Fonts** — Syne, Outfit, JetBrains Mono
- **Zero external JS dependencies** (no jQuery, no React, nothing)

---

## ⚠️ Important Disclaimers

- **Do not use in production with real data.** The system has no password hashing, no CSRF protection, and no robust input validation.
- All data is fictional and resets with each session (no persistence).
- Intended exclusively for **demonstration, study and prototyping**.

---

## 📄 License

Distributed under the MIT License. See the `LICENSE` file for more details.
