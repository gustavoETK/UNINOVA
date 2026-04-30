<?php // ─── SEÇÃO: CALENDÁRIO ── ?>
<div class="grid-2 anim-1">

  <!-- Calendário Acadêmico oficial -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">📅</span> Calendário Acadêmico 2025/1</div>
    <?php foreach ($CALENDARIO_ACADEMICO as $c): ?>
    <div class="flex items-center gap" style="padding:.75rem 0;border-bottom:1px solid var(--line)">
      <span class="badge badge-<?= $c['tipo'] ?>" style="min-width:120px;justify-content:center;text-align:center"><?= $c['data'] ?></span>
      <span class="text-sm"><?= htmlspecialchars($c['evento']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <div>
    <!-- Mini-calendário interativo -->
    <div class="card">
      <div class="card-title"><span class="ct-icon">🗓</span> Navegação Mensal</div>
      <div id="mini-calendar"></div>
      <div class="divider"></div>
      <div class="text-xs text-muted">
        <span style="display:inline-flex;align-items:center;gap:.35rem;margin-right:.75rem">
          <span style="width:8px;height:8px;background:var(--violet);border-radius:2px;display:inline-block"></span> Hoje
        </span>
        <span style="display:inline-flex;align-items:center;gap:.35rem">
          <span style="width:6px;height:6px;background:var(--rose);border-radius:50%;display:inline-block"></span> Com evento
        </span>
      </div>
    </div>

    <!-- Próximas avaliações -->
    <div class="card mt-2">
      <div class="card-title"><span class="ct-icon">📌</span> Próximas Avaliações</div>
      <?php foreach ($EVENTOS as $e):
        $tipo_ico = ['prova'=>'📝','entrega'=>'📤','evento'=>'🎤'][$e['tipo']] ?? '📌';
      ?>
      <div class="flex items-center gap" style="padding:.75rem;background:var(--bg3);border-radius:10px;border:1px solid var(--line);margin-bottom:.5rem">
        <div style="width:48px;height:48px;border-radius:11px;background:rgba(0,0,0,.3);border:1px solid var(--line2);display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0">
          <span style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--<?= $e['cor'] ?>);line-height:1"><?= $e['dia'] ?></span>
          <span style="font-size:.5rem;color:var(--muted);font-family:'JetBrains Mono',monospace"><?= $e['mes'] ?></span>
        </div>
        <div style="flex:1">
          <div class="fw-7 text-sm"><?= htmlspecialchars($e['titulo']) ?></div>
          <div class="text-xs text-muted mono"><?= $e['hora'] ?> · <?= $tipo_ico ?> <?= ucfirst($e['tipo']) ?></div>
        </div>
        <span class="badge badge-<?= $e['cor'] ?>">Mai <?= $e['dia'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>
