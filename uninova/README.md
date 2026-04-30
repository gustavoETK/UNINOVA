# UNINOVA — Portal Acadêmico v4.0

Sistema Integrado de Gestão Acadêmica — Projeto full-stack fictício para demonstração.

## 📁 Estrutura do Projeto

```
uninova/
├── index.php                  ← Ponto de entrada principal
├── includes/
│   └── data.php               ← "Banco de dados" simulado + helpers PHP
├── assets/
│   ├── css/
│   │   └── main.css           ← Design system completo (tokens, layout, componentes)
│   └── js/
│       └── app.js             ← Camada interativa (cursor, partículas, toasts, modais, search…)
├── api/
│   └── actions.php            ← Endpoint AJAX (login, chamados, boleto, senha…)
├── components/
│   └── layout.php             ← Sidebar + Topbar + Modal de notificações
└── sections/
    ├── all.php                ← Router de seções
    ├── dashboard.php          ← Dashboard com KPIs, gráfico, eventos, alertas
    ├── notas.php              ← Boletim + Calculadora de Média interativa
    ├── horarios.php           ← Grade de horários visual + cards por disciplina
    ├── frequencia.php         ← Frequência com alertas de risco
    ├── financeiro.php         ← Mensalidades + Gerador de boleto AJAX
    ├── biblioteca.php         ← Empréstimos + busca em tempo real
    ├── noticias.php           ← Feed de notícias + mini-calendário interativo
    ├── secretaria.php         ← Serviços acadêmicos + solicitações
    ├── suporte.php            ← FAQ accordion + chamado AJAX + status de sistemas
    ├── perfil.php             ← Dados pessoais + alterar senha AJAX
    └── calendario.php         ← Calendário acadêmico + mini-calendário
```

## 🚀 Como Executar

Requer PHP 8.0+ com servidor embutido:

```bash
cd uninova
php -S localhost:8000
# Acesse: http://localhost:8000
```

Ou configure no Apache/Nginx apontando para a pasta `uninova/`.

## 🔑 Contas de Demonstração

| Usuário | Senha      | Perfil       |
|---------|------------|--------------|
| aluno   | 1234       | 👨‍🎓 Aluno     |
| prof    | prof123    | 👩‍🏫 Professora |
| coord   | coord456   | 🏛 Coordenador |

## ✨ Funcionalidades

- **Login AJAX** — sem reload, com fallback noscript
- **Cursor personalizado** animado com mix-blend-mode
- **Partículas interativas** com repulsão ao mouse
- **Relógio em tempo real** na topbar
- **Busca global** com dropdown e navegação por teclado (Ctrl+K)
- **Calculadora de média** com preview ao vivo
- **Gráfico de barras CSS** no dashboard
- **Mini-calendário navegável** (JS puro)
- **Toast notifications** (success, error, info, warning)
- **Modal de notificações** com badge
- **FAQ accordion** animado
- **Boleto gerado via AJAX** com código de barras
- **Senha alterada via AJAX** com validação e match em tempo real
- **Busca de acervo** filtro em tempo real
- **Renovação de empréstimo** via AJAX
- **Chamado de suporte** via AJAX com validação
- **Animações de entrada** escalonadas por seção
- **Design system** completo com tokens CSS

## 🛠 Stack

- **Backend:** PHP 8.0+ (sessões, roteamento, helpers)
- **Frontend:** HTML5 + CSS3 (custom properties, grid, animations)
- **JavaScript:** Vanilla ES6+ (sem dependências externas)
- **Fontes:** Syne, Outfit, JetBrains Mono (Google Fonts)
