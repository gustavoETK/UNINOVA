<?php
// ─── SEÇÃO: DASHBOARD ────────────────────────────────────────
$totalMF = [];
foreach ($DISCIPLINAS as $d) { $mf = mediaFinal($d); if ($mf !== null) $totalMF[] = $mf; }
$cr = count($totalMF) ? round(array_sum($totalMF) / count($totalMF), 1) : $user['cr'];
$totalFaltas = array_sum(array_column($DISCIPLINAS, 'faltas'));
$pendencias  = count(array_filter($FINANCEIRO, fn($f) => $f['status'] === 'pendente'));
?>

<div class="welcome-banner anim-1">
  <div class="tag">👋 Bem-vindo de volta</div>
  <div class="welcome-name mt-1">Olá, <span><?= htmlspecialchars(explode(' ', $user['nome'])[0]) ?></span>!</div>
  <div class="text-muted text-sm mt-1">
    <?= htmlspecialchars($user['curso']) ?> · <?= htmlspecialchars($user['periodo']) ?>
    · <span class="mono"><?= date('d/m/Y') ?></span>
  </div>
</div>

<!-- KPIs -->
<div class="grid-4 anim-2">
  <div class="stat-card c-violet">
    <div class="stat-icon" style="background:var(--violet-d)">🎓</div>
    <div class="stat-val text-violet"><?= number_format($cr, 1, ',', '') ?></div>
    <div class="stat-lbl">Coef. de Rendimento</div>
    <div class="pbar mt-1"><div class="pbar-fill pbar-violet" style="width:<?= ($cr/10*100) ?>%"></div></div>
  </div>
  <div class="stat-card c-teal" onclick="window.location='?s=notas'" style="cursor:none">
    <div class="stat-icon" style="background:var(--teal-d)">📚</div>
    <div class="stat-val text-teal"><?= count($DISCIPLINAS) ?></div>
    <div class="stat-lbl">Disciplinas Ativas</div>
    <div class="stat-trend text-sm text-muted">Semestre 2025/1</div>
  </div>
  <div class="stat-card c-amber" onclick="window.location='?s=frequencia'" style="cursor:none">
    <div class="stat-icon" style="background:var(--amber-d)">⚠️</div>
    <div class="stat-val text-amber"><?= $totalFaltas ?></div>
    <div class="stat-lbl">Total de Faltas</div>
    <div class="pbar mt-1"><div class="pbar-fill pbar-amber" style="width:<?= min(100, $totalFaltas/40*100) ?>%"></div></div>
  </div>
  <div class="stat-card c-rose" onclick="window.location='?s=financeiro'" style="cursor:none">
    <div class="stat-icon" style="background:var(--rose-d)">💳</div>
    <div class="stat-val text-rose"><?= $pendencias ?></div>
    <div class="stat-lbl">Pendência Financeira</div>
    <div class="stat-trend text-sm" style="color:var(--rose)"><?= $pendencias ? 'Vence 10/05' : 'Em dia ✓' ?></div>
  </div>
</div>

<!-- Gráfico de desempenho + próximos eventos -->
<div class="grid-2 mt-2 anim-3">

  <div class="card">
    <div class="card-title"><span class="ct-icon">📊</span> Desempenho por Disciplina</div>
    <!-- CSS bar chart -->
    <div style="display:flex;align-items:flex-end;gap:6px;height:110px;padding:.5rem 0 0;margin-bottom:.75rem">
      <?php foreach ($DISCIPLINAS as $d):
        $mf = mediaFinal($d);
        $val = $mf ?? ($d['m1'] ?? 0);
        [$sit,$cor] = situacao($mf);
        $pct = ($val / 10) * 100;
      ?>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;height:100%;justify-content:flex-end">
        <span style="font-size:.62rem;color:var(--<?= $cor ?>);font-weight:700;font-family:'Syne',sans-serif"><?= number_format($val,1,',','') ?></span>
        <div style="width:100%;height:<?= $pct ?>%;background:linear-gradient(to top,var(--<?= $cor ?>)44,var(--<?= $cor ?>));border-radius:4px 4px 0 0;transition:height .6s cubic-bezier(.16,1,.3,1);min-height:4px"></div>
        <span style="font-size:.55rem;color:var(--muted);font-family:'JetBrains Mono',monospace;text-align:center;white-space:nowrap"><?= $d['cod'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="divider"></div>
    <?php foreach (array_slice($DISCIPLINAS, 0, 3) as $d):
      $mf = mediaFinal($d); [$sit,$cor] = situacao($mf);
      $pct = $mf !== null ? ($mf/10*100) : ($d['m1'] !== null ? ($d['m1']/10*100) : 0);
    ?>
    <div style="margin-bottom:.7rem">
      <div class="flex items-center justify-between">
        <span class="text-sm fw-7"><?= htmlspecialchars($d['nome']) ?></span>
        <span class="badge badge-<?= $cor ?>"><?= $sit ?></span>
      </div>
      <div class="flex items-center gap mt-1">
        <div class="pbar" style="flex:1"><div class="pbar-fill pbar-<?= $cor ?>" style="width:<?= $pct ?>%"></div></div>
        <span class="mono text-xs text-muted"><?= $mf !== null ? number_format($mf,1,',','') : ($d['m1'] !== null ? 'M1:'.number_format($d['m1'],1,',','') : '—') ?></span>
      </div>
    </div>
    <?php endforeach; ?>
    <a href="?s=notas" class="news-link mt-1">Ver boletim completo →</a>
  </div>

  <div class="card">
    <div class="card-title"><span class="ct-icon">📅</span> Próximos Eventos</div>
    <?php foreach (array_slice($EVENTOS, 0, 5) as $e):
      $colorMap = ['rose'=>'245,97,122','amber'=>'245,166,35','sky'=>'56,200,245','teal'=>'0,221,176','violet'=>'139,108,247'];
      $rgb = $colorMap[$e['cor']] ?? '139,108,247';
      $tipo_ico = ['prova'=>'📝','entrega'=>'📤','evento'=>'🎤'][$e['tipo']] ?? '📌';
    ?>
    <div class="flex items-center gap" style="padding:.65rem 0;border-bottom:1px solid var(--line);cursor:default">
      <div style="width:46px;height:46px;border-radius:11px;background:rgba(<?=$rgb?>,.1);border:1px solid rgba(<?=$rgb?>,.2);display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0">
        <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:.95rem;line-height:1;color:var(--<?=$e['cor']?>)"><?= $e['dia'] ?></span>
        <span style="font-size:.52rem;color:var(--muted);font-family:'JetBrains Mono',monospace"><?= $e['mes'] ?></span>
      </div>
      <div style="flex:1">
        <div class="text-sm fw-7"><?= htmlspecialchars($e['titulo']) ?></div>
        <div class="text-xs text-muted mono"><?= $e['hora'] ?> · <?= $tipo_ico ?> <?= ucfirst($e['tipo']) ?></div>
      </div>
      <span class="badge badge-<?= $e['cor'] ?>"><?= $e['dia'] ?> <?= $e['mes'] ?></span>
    </div>
    <?php endforeach; ?>
    <a href="?s=calendario" class="news-link mt-1">Ver calendário completo →</a>
  </div>

</div>

<!-- Notícias + Alerta de frequência -->
<div class="grid-2 mt-2 anim-4">

  <div class="card">
    <div class="card-title"><span class="ct-icon">📰</span> Últimas Notícias</div>
    <?php foreach (array_slice($NOTICIAS, 0, 3) as $n): ?>
    <div style="padding:.75rem;background:var(--bg3);border:1px solid var(--line);border-radius:10px;margin-bottom:.6rem">
      <div class="flex items-center gap" style="margin-bottom:.4rem">
        <?php if ($n['tipo']==='urgente'): ?><span class="badge badge-rose">🔴 Urgente</span>
        <?php elseif ($n['tipo']==='info'): ?><span class="badge badge-sky">ℹ Info</span>
        <?php else: ?><span class="badge badge-muted">📢 Geral</span><?php endif; ?>
        <span class="tag"><?= $n['data'] ?></span>
        <?php if (!$n['lido']): ?><span style="margin-left:auto;width:7px;height:7px;background:var(--rose);border-radius:50%;flex-shrink:0"></span><?php endif; ?>
      </div>
      <div class="news-titulo" style="font-size:.9rem"><?= htmlspecialchars($n['titulo']) ?></div>
      <div class="news-resumo" style="margin-top:.25rem"><?= htmlspecialchars($n['resumo']) ?></div>
    </div>
    <?php endforeach; ?>
    <a href="?s=noticias" class="news-link">Ver todas as notícias →</a>
  </div>

  <div>
    <!-- Alerta IA -->
    <?php
    $iaDisc = $DISCIPLINAS[4]; // Inteligência Artificial
    $iaPct = pctFaltas($iaDisc['faltas'], $iaDisc['max_faltas']);
    ?>
    <div class="card" style="background:rgba(245,166,35,.05);border-color:rgba(245,166,35,.2);margin-bottom:1.25rem">
      <div class="flex items-center gap">
        <span style="font-size:1.3rem">⚠️</span>
        <div>
          <div class="fw-7 text-amber">Atenção: <?= htmlspecialchars($iaDisc['nome']) ?></div>
          <div class="text-sm text-muted mt-1">Você possui <strong class="text-amber"><?= $iaDisc['faltas'] ?></strong> faltas. Limite: <?= $iaDisc['max_faltas'] ?> (25% de <?= $iaDisc['ch'] ?>h).</div>
          <div class="pbar mt-1" style="height:6px"><div class="pbar-fill pbar-amber" style="width:<?= $iaPct ?>%"></div></div>
          <div class="text-xs text-muted mono mt-1"><?= $iaPct ?>% do limite atingido</div>
        </div>
      </div>
    </div>
    <!-- Pendência financeira -->
    <?php $pend = array_filter($FINANCEIRO, fn($f) => $f['status'] === 'pendente'); ?>
    <?php if ($pend): $p = array_values($pend)[0]; ?>
    <div class="card" style="background:rgba(245,97,122,.05);border-color:rgba(245,97,122,.2)">
      <div class="fw-7 text-rose">🔴 Mensalidade Pendente</div>
      <div class="text-sm text-muted mt-1"><?= $p['mes'] ?> · R$ <?= number_format($p['valor'],2,',','.') ?></div>
      <div class="text-xs mono text-muted mt-1">Vencimento: <?= $p['venc'] ?></div>
      <button class="btn-gerar-boleto" onclick="gerarBoleto('<?= $p['mes'] ?>','<?= number_format($p['valor'],2,',','.') ?>')">
        💳 Gerar Boleto
      </button>
    </div>
    <?php endif; ?>
  </div>

</div>

<!-- Mini Biblioteca e Acesso Rápido -->
<div class="grid-2 mt-2 anim-5">

  <div class="card">
    <div class="card-title"><span class="ct-icon">📚</span> Empréstimos Ativos</div>
    <?php foreach ($BIBLIOTECA as $b): ?>
    <div class="book-row">
      <div class="book-icon"><?= $b['capa'] ?></div>
      <div class="book-info">
        <div class="book-title"><?= htmlspecialchars($b['titulo']) ?></div>
        <div class="book-author"><?= htmlspecialchars($b['autor']) ?></div>
        <?php if ($b['devol'] !== '—'): ?>
        <div class="book-devol">↩ Devolução: <?= $b['devol'] ?></div>
        <?php endif; ?>
      </div>
      <div style="display:flex;flex-direction:column;gap:.4rem;align-items:flex-end">
        <span class="badge badge-<?= $b['status']==='emprestado'?'amber':'sky' ?>"><?= $b['status'] ?></span>
        <?php if ($b['status']==='emprestado'): ?>
        <button style="font-size:.65rem;color:var(--teal);background:none;cursor:none;border:none;padding:0" onclick="renovarLivro('<?= htmlspecialchars($b['titulo']) ?>')">↺ Renovar</button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <a href="?s=biblioteca" class="news-link mt-1">Acessar biblioteca →</a>
  </div>

  <div class="card">
    <div class="card-title"><span class="ct-icon">⚡</span> Acesso Rápido</div>
    <div class="grid-2 gap-sm">
      <?php
      $qs = [
        ['ico'=>'📊','label'=>'Notas','href'=>'?s=notas','cor'=>'violet'],
        ['ico'=>'💳','label'=>'Financeiro','href'=>'?s=financeiro','cor'=>'rose'],
        ['ico'=>'📅','label'=>'Calendário','href'=>'?s=calendario','cor'=>'sky'],
        ['ico'=>'🏛','label'=>'Secretaria','href'=>'?s=secretaria','cor'=>'teal'],
        ['ico'=>'📋','label'=>'Frequência','href'=>'?s=frequencia','cor'=>'amber'],
        ['ico'=>'💬','label'=>'Suporte','href'=>'?s=suporte','cor'=>'violet'],
      ];
      foreach ($qs as $q): ?>
      <a href="<?= $q['href'] ?>" style="background:var(--<?=$q['cor']?>-d);border:1px solid rgba(0,0,0,.2);border-radius:10px;padding:.85rem;display:flex;flex-direction:column;align-items:center;gap:.35rem;transition:transform .15s,border-color .15s;text-decoration:none">
        <span style="font-size:1.4rem"><?= $q['ico'] ?></span>
        <span style="font-size:.72rem;font-weight:700;color:var(--<?=$q['cor']?>);font-family:'JetBrains Mono',monospace;text-transform:uppercase;letter-spacing:.06em"><?= $q['label'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

</div>
