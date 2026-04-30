<?php
// ═══════════════════════════════════════════════════════════════
//  UNINOVA — PORTAL ACADÊMICO v4.0
//  Sistema Integrado de Gestão Acadêmica
//  index.php — Ponto de entrada principal
// ═══════════════════════════════════════════════════════════════
session_start();
require_once 'includes/data.php';
require_once 'sections/all.php';

// ─── AÇÕES ───────────────────────────────────────────────────
$erro  = '';
$secao = $_GET['s'] ?? 'dashboard';
$calc  = $_SESSION['calc'] ?? null;

// Logout direto (link)
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Login via POST (fallback sem JS)
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = trim($_POST['usuario'] ?? '');
    $p = trim($_POST['senha']   ?? '');
    if (isset($USERS[$u]) && $USERS[$u]['senha'] === $p) {
        $_SESSION['user'] = $u;
        header('Location: index.php?s=dashboard');
        exit;
    }
    $erro = 'Usuário ou senha incorretos.';
}

// Calcular média (fallback sem JS)
if (isset($_POST['action']) && $_POST['action'] === 'calcmedia' && isset($_SESSION['user'])) {
    $m1 = floatval(str_replace(',', '.', $_POST['m1'] ?? 0));
    $m2 = floatval(str_replace(',', '.', $_POST['m2'] ?? 0));
    $m1 = max(0, min(10, $m1));
    $m2 = max(0, min(10, $m2));
    $mf = round(($m1 + $m2 * 2) / 3, 2);
    $sit = $mf >= 7 ? 'Aprovado' : ($mf >= 5 ? 'Recuperação' : 'Reprovado');
    $_SESSION['calc'] = compact('m1','m2','mf','sit');
    $calc = $_SESSION['calc'];
    $secao = 'notas';
}

$logado = isset($_SESSION['user']) && isset($USERS[$_SESSION['user']]);
$user   = $logado ? $USERS[$_SESSION['user']] : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#07070d">
<title>UNINOVA — Portal Acadêmico</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<!-- Cursor personalizado -->
<div id="cur"></div>
<div id="cur-ring"></div>

<!-- Canvas de partículas -->
<canvas id="pcvs"></canvas>

<!-- Toast container -->
<div id="toast-container"></div>

<?php if (!$logado): ?>
<!-- ═══════════════════════════════════════════════════════════
     TELA DE LOGIN
═══════════════════════════════════════════════════════════════ -->
<div class="login-bg"></div>
<div class="login-grid"></div>

<div class="login-page">
  <div class="login-wrap">

    <!-- Lado esquerdo: branding -->
    <div class="login-left">
      <div class="brand">
        <div class="brand-logo">UNINOVA</div>
        <div class="brand-sub">Sistema Acadêmico v4.0</div>
      </div>
      <div class="brand-tagline">
        Sua jornada acadêmica,<br>
        <span>centralizada.</span>
      </div>
      <div class="login-stats">
        <div>
          <div class="login-stat-val">12k+</div>
          <div class="login-stat-lbl">Alunos</div>
        </div>
        <div>
          <div class="login-stat-val">48</div>
          <div class="login-stat-lbl">Cursos</div>
        </div>
        <div>
          <div class="login-stat-val">98%</div>
          <div class="login-stat-lbl">Satisfação</div>
        </div>
      </div>
    </div>

    <!-- Lado direito: formulário -->
    <div class="login-right">
      <div class="login-title">Entrar no Portal</div>
      <div class="login-desc">Acesse com suas credenciais institucionais.</div>

      <!-- Demo accounts (clicáveis) -->
      <div class="demo-accounts">
        <div style="font-size:.65rem;color:var(--violet);margin-bottom:.3rem;font-weight:700">CONTAS DE DEMONSTRAÇÃO</div>
        <div class="demo-item" onclick="fillDemo('aluno','1234')">
          <span class="demo-role"><strong>aluno</strong> / 1234</span>
          <span style="color:var(--teal)">👨‍🎓 Aluno</span>
        </div>
        <div class="demo-item" onclick="fillDemo('prof','prof123')">
          <span class="demo-role"><strong>prof</strong> / prof123</span>
          <span style="color:var(--sky)">👩‍🏫 Professora</span>
        </div>
        <div class="demo-item" onclick="fillDemo('coord','coord456')">
          <span class="demo-role"><strong>coord</strong> / coord456</span>
          <span style="color:var(--amber)">🏛 Coordenador</span>
        </div>
      </div>

      <?php if ($erro): ?>
      <div class="error-msg">⚠️ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form method="post" action="index.php" id="login-form" onsubmit="handleLogin(event)">
        <input type="hidden" name="action" value="login">
        <div class="form-group form-input-icon">
          <label class="form-label" for="login-user">Usuário</label>
          <span class="icon">👤</span>
          <input class="form-input" type="text" name="usuario" id="login-user"
            placeholder="seu.usuario" autocomplete="username" required>
        </div>
        <div class="form-group form-input-icon">
          <label class="form-label" for="login-pass">Senha</label>
          <span class="icon">🔑</span>
          <input class="form-input" type="password" name="senha" id="login-pass"
            placeholder="••••••••" autocomplete="current-password" required>
        </div>
        <button class="btn-login" type="submit" id="btn-login">
          Entrar no Portal →
        </button>
      </form>

      <div style="text-align:center;margin-top:1.5rem;font-size:.75rem;color:var(--muted)">
        Problemas com acesso? <span style="color:var(--violet);cursor:none" onclick="window.location='index.php?s=suporte'">Contate o suporte</span>
      </div>
    </div>
  </div>
</div>

<script>
function handleLogin(e) {
  e.preventDefault();
  const btn = document.getElementById('btn-login');
  btn.textContent = 'Entrando…';
  btn.style.opacity = '.7';
  const data = new FormData(e.target);
  fetch('api/actions.php', { method:'POST', body: new URLSearchParams(data) })
    .then(r => r.json()).then(d => {
      if (d.ok) { window.location = d.redirect; }
      else {
        btn.textContent = 'Entrar no Portal →';
        btn.style.opacity = '1';
        Toast.show(d.msg, 'error', '❌', 4000);
      }
    }).catch(() => { e.target.submit(); }); // fallback
}
</script>

<?php else: ?>
<!-- ═══════════════════════════════════════════════════════════
     APP PRINCIPAL
═══════════════════════════════════════════════════════════════ -->
<div class="app">
  <?php include 'components/layout.php'; ?>

  <!-- Conteúdo principal -->
  <main class="main-content">
    <div class="page-content">
      <?php
      renderSection($secao, [
        'user'                  => $user,
        'secao'                 => $secao,
        'DISCIPLINAS'           => $DISCIPLINAS,
        'NOTICIAS'              => $NOTICIAS,
        'EVENTOS'               => $EVENTOS,
        'FINANCEIRO'            => $FINANCEIRO,
        'BIBLIOTECA'            => $BIBLIOTECA,
        'ACERVO'                => $ACERVO,
        'HORARIOS'              => $HORARIOS,
        'FAQS'                  => $FAQS,
        'SISTEMAS_STATUS'       => $SISTEMAS_STATUS,
        'SERVICOS_SECRETARIA'   => $SERVICOS_SECRETARIA,
        'SOLICITACOES'          => $SOLICITACOES,
        'CALENDARIO_ACADEMICO'  => $CALENDARIO_ACADEMICO,
        'calc'                  => $calc,
      ]);
      ?>
    </div>
  </main>
</div>
<?php endif; ?>

<script src="assets/js/app.js"></script>
</body>
</html>
