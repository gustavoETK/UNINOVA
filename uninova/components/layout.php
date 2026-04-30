<?php
// ─── COMPONENTE: SIDEBAR + TOPBAR ────────────────────────────
// Variáveis esperadas: $user, $secao, $NOTICIAS
$nav = [
    'Principal' => [
        ['s'=>'dashboard',  'ico'=>'🏠', 'label'=>'Dashboard'],
    ],
    'Acadêmico' => [
        ['s'=>'notas',      'ico'=>'📊', 'label'=>'Notas & Boletim'],
        ['s'=>'horarios',   'ico'=>'🗓', 'label'=>'Grade de Horários'],
        ['s'=>'frequencia', 'ico'=>'📋', 'label'=>'Frequência'],
        ['s'=>'calendario', 'ico'=>'📅', 'label'=>'Calendário'],
    ],
    'Serviços' => [
        ['s'=>'financeiro',  'ico'=>'💳', 'label'=>'Financeiro'],
        ['s'=>'biblioteca',  'ico'=>'📚', 'label'=>'Biblioteca'],
        ['s'=>'secretaria',  'ico'=>'🏛', 'label'=>'Secretaria'],
    ],
    'Comunidade' => [
        ['s'=>'noticias',   'ico'=>'📰', 'label'=>'Notícias', 'badge'=>true],
        ['s'=>'suporte',    'ico'=>'💬', 'label'=>'Suporte'],
    ],
    'Conta' => [
        ['s'=>'perfil',     'ico'=>'👤', 'label'=>'Meu Perfil'],
    ],
];
$titles = [
    'dashboard'  => 'Dashboard',
    'notas'      => 'Notas & Boletim',
    'horarios'   => 'Grade de Horários',
    'frequencia' => 'Frequência',
    'financeiro' => 'Financeiro',
    'biblioteca' => 'Biblioteca',
    'noticias'   => 'Notícias',
    'secretaria' => 'Secretaria Virtual',
    'suporte'    => 'Suporte & FAQ',
    'perfil'     => 'Meu Perfil',
    'calendario' => 'Calendário Acadêmico',
];
$notifQty = notifCount($NOTICIAS);
?>
<!-- ── SIDEBAR ── -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-mark">UN</div>
    <div class="sidebar-logo-text">
      <div class="sidebar-logo-name">UNINOVA</div>
      <div class="sidebar-logo-sub">Portal Acadêmico</div>
    </div>
  </div>

  <div class="sidebar-user">
    <div class="user-av"><?= htmlspecialchars($user['avatar']) ?></div>
    <div>
      <div class="user-info-name"><?= htmlspecialchars(explode(' ', $user['nome'])[0]) ?> <?= htmlspecialchars(explode(' ', $user['nome'])[1] ?? '') ?></div>
      <div class="user-info-role"><?= htmlspecialchars($user['role']) ?></div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php foreach ($nav as $section => $items): ?>
    <div class="nav-section-lbl"><?= $section ?></div>
    <?php foreach ($items as $n):
      $active = ($secao === $n['s']) ? 'active' : '';
      $badge  = !empty($n['badge']) && $notifQty > 0;
    ?>
    <a href="?s=<?= $n['s'] ?>" class="nav-item <?= $active ?>">
      <span class="nav-icon"><?= $n['ico'] ?></span>
      <?= $n['label'] ?>
      <?php if ($badge): ?>
      <span class="nav-badge"><?= $notifQty ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="?logout=1" class="btn-logout">
      <span>🚪</span> Sair da conta
    </a>
  </div>
</aside>

<!-- ── TOPBAR ── -->
<header class="topbar">
  <div class="topbar-title"><?= $titles[$secao] ?? 'Portal' ?></div>

  <div id="search-wrap" style="position:relative">
    <div class="topbar-search" id="search-bar">
      <span class="search-icon">🔍</span>
      <input type="text" id="global-search" placeholder="Pesquisar... (Ctrl+K)" autocomplete="off">
    </div>
  </div>

  <div class="topbar-actions">
    <span class="topbar-clock" id="topbar-clock">--:--:--</span>

    <!-- Notificações -->
    <button class="topbar-btn" onclick="Modal.open('modal-notif')" title="Notificações">
      🔔
      <?php if ($notifQty > 0): ?>
      <span class="dot"></span>
      <?php endif; ?>
    </button>

    <!-- Ajuda rápida -->
    <button class="topbar-btn" onclick="window.location='?s=suporte'" title="Ajuda">❓</button>
  </div>
</header>

<!-- ── MODAL: Notificações ── -->
<div class="modal-overlay" id="modal-notif">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">🔔 Notificações</div>
      <button class="modal-close" onclick="Modal.close('modal-notif')">×</button>
    </div>
    <?php foreach (array_slice($NOTICIAS, 0, 4) as $n): ?>
    <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.75rem 0;border-bottom:1px solid var(--line)">
      <div style="margin-top:.1rem">
        <?php if ($n['tipo']==='urgente'): ?><span class="badge badge-rose">🔴</span>
        <?php elseif ($n['tipo']==='info'): ?><span class="badge badge-sky">ℹ</span>
        <?php else: ?><span class="badge badge-muted">📢</span><?php endif; ?>
      </div>
      <div>
        <div style="font-size:.88rem;font-weight:600;line-height:1.3"><?= htmlspecialchars($n['titulo']) ?></div>
        <div style="font-size:.78rem;color:var(--ink2);margin-top:.2rem"><?= htmlspecialchars($n['resumo']) ?></div>
        <div style="font-size:.68rem;color:var(--muted);margin-top:.3rem;font-family:'JetBrains Mono',monospace"><?= $n['data'] ?></div>
      </div>
      <?php if (!$n['lido']): ?>
      <div style="width:7px;height:7px;background:var(--rose);border-radius:50%;flex-shrink:0;margin-top:.3rem"></div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <div style="text-align:center;margin-top:1rem">
      <a href="?s=noticias" class="news-link" onclick="Modal.close('modal-notif')">Ver todas as notícias →</a>
    </div>
  </div>
</div>
