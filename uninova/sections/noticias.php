<?php // ─── SEÇÃO: NOTÍCIAS ── ?>
<div class="grid-2 anim-1">

  <!-- Lista de notícias -->
  <div>
    <?php foreach ($NOTICIAS as $n): ?>
    <div class="news-item" style="margin-bottom:.75rem">
      <div class="flex items-center gap" style="margin-bottom:.6rem">
        <?php if ($n['tipo']==='urgente'): ?><span class="badge badge-rose">🔴 Urgente</span>
        <?php elseif ($n['tipo']==='info'):  ?><span class="badge badge-sky">ℹ Info</span>
        <?php else: ?><span class="badge badge-muted">📢 Geral</span><?php endif; ?>
        <span class="tag"><?= $n['data'] ?></span>
        <?php if (!$n['lido']): ?><span style="margin-left:auto;width:7px;height:7px;background:var(--rose);border-radius:50%"></span><?php endif; ?>
      </div>
      <div class="news-titulo"><?= htmlspecialchars($n['titulo']) ?></div>
      <div class="news-resumo" style="margin-top:.3rem"><?= htmlspecialchars($n['resumo']) ?></div>
      <div class="flex gap mt-1" style="margin-top:.8rem">
        <span class="news-link">Leia mais →</span>
        <?php if (!$n['lido']): ?>
        <button onclick="marcarLido(<?= $n['id'] ?>,this)" style="font-size:.72rem;color:var(--muted);background:none;border:none;cursor:none">✓ Marcar como lido</button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Calendário de eventos -->
  <div>
    <div class="card">
      <div class="card-title">📅 Eventos do Mês</div>
      <?php foreach ($EVENTOS as $e):
        $tipo_ico = ['prova'=>'📝','entrega'=>'📤','evento'=>'🎤'][$e['tipo']] ?? '📌';
      ?>
      <div class="flex items-center gap" style="padding:.65rem 0;border-bottom:1px solid var(--line)">
        <span class="badge badge-<?= $e['cor'] ?>" style="min-width:64px;justify-content:center"><?= $e['dia'] ?> <?= $e['mes'] ?></span>
        <div style="flex:1">
          <div class="text-sm fw-7"><?= htmlspecialchars($e['titulo']) ?></div>
          <div class="text-xs text-muted mono"><?= $e['hora'] ?> · <?= $tipo_ico ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="card mt-2" id="mini-calendar"></div>
  </div>
</div>

<script>
function marcarLido(id, btn) {
  fetch('api/actions.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=marcar_lido&id=' + id
  }).then(r => r.json()).then(d => {
    if (d.ok) {
      btn.closest('.news-item').querySelector('span[style*="border-radius:50%"]')?.remove();
      btn.remove();
      Toast.show('Notícia marcada como lida.', 'info', '✓', 2000);
    }
  });
}
</script>
