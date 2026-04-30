<?php // ─── SEÇÃO: FINANCEIRO ──
$pago = array_sum(array_column(array_filter($FINANCEIRO, fn($f) => $f['status']==='pago'),    'valor'));
$pend = array_sum(array_column(array_filter($FINANCEIRO, fn($f) => $f['status']==='pendente'),'valor'));
$total = $pago + $pend;
?>
<div class="grid-2 anim-1">

  <!-- Mensalidades -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">💳</span> Mensalidades 2025</div>
    <?php foreach ($FINANCEIRO as $f): ?>
    <div class="fin-row">
      <div style="font-size:1.2rem"><?= $f['status']==='pago' ? '✅' : '🔴' ?></div>
      <div style="flex:1">
        <div class="fin-mes"><?= htmlspecialchars($f['mes']) ?></div>
        <div class="fin-venc">Vencimento: <?= $f['venc'] ?></div>
        <?php if ($f['pago_em']): ?>
        <div style="font-size:.68rem;color:var(--teal);font-family:'JetBrains Mono',monospace">Pago em: <?= $f['pago_em'] ?></div>
        <?php endif; ?>
      </div>
      <div class="text-right">
        <div class="fin-valor">R$ <?= number_format($f['valor'],2,',','.') ?></div>
        <span class="badge badge-<?= $f['status']==='pago'?'teal':'rose' ?>">
          <?= $f['status']==='pago' ? '✔ Pago' : '● Pendente' ?>
        </span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Resumo + Boleto -->
  <div>
    <div class="card fin-total-box">
      <div class="card-title">💰 Resumo Financeiro 2025</div>
      <div style="margin-bottom:1rem">
        <div class="tag">Total Pago</div>
        <div style="font-family:'Syne',sans-serif;font-size:2.1rem;font-weight:800;color:var(--teal)">
          R$ <?= number_format($pago,2,',','.') ?>
        </div>
      </div>
      <div class="divider"></div>
      <div style="margin-bottom:1rem">
        <div class="tag">Em Aberto</div>
        <div style="font-family:'Syne',sans-serif;font-size:2.1rem;font-weight:800;color:var(--rose)">
          R$ <?= number_format($pend,2,',','.') ?>
        </div>
      </div>
      <?php $pct = $total > 0 ? round($pago/$total*100) : 100; ?>
      <div class="pbar" style="height:8px;border-radius:4px">
        <div class="pbar-fill pbar-teal" style="width:<?= $pct ?>%"></div>
      </div>
      <div class="text-xs text-muted mono mt-1"><?= $pct ?>% quitado no ano</div>
    </div>

    <?php $pendentes = array_filter($FINANCEIRO, fn($f) => $f['status']==='pendente'); ?>
    <?php if ($pendentes): $p = array_values($pendentes)[0]; ?>
    <div class="card mt-2" style="background:rgba(245,97,122,.05);border-color:rgba(245,97,122,.25)">
      <div class="fw-7 text-rose">🔴 Boleto Pendente</div>
      <div class="text-sm text-muted mt-1"><?= htmlspecialchars($p['mes']) ?> · R$ <?= number_format($p['valor'],2,',','.') ?></div>
      <div class="text-xs mono text-muted mt-1">Vence em <?= $p['venc'] ?></div>
      <button class="btn-gerar-boleto" id="btn-boleto" onclick="handleBoleto('<?= htmlspecialchars($p['mes']) ?>','<?= number_format($p['valor'],2,',','.') ?>')">
        💳 Gerar Boleto
      </button>
      <div id="barcode-box" style="display:none;margin-top:.9rem;background:var(--bg3);border:1px solid var(--line2);border-radius:9px;padding:.85rem">
        <div class="text-xs text-muted mono" style="margin-bottom:.4rem">Código de barras:</div>
        <div id="barcode-val" class="mono text-xs" style="color:var(--teal);word-break:break-all;line-height:1.6"></div>
        <button onclick="copyText(document.getElementById('barcode-val').textContent,'Código de barras')" style="margin-top:.6rem;font-size:.72rem;color:var(--violet);background:none;border:none;cursor:none;display:flex;align-items:center;gap:.3rem">📋 Copiar código</button>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
function handleBoleto(mes, valor) {
  fetch('api/actions.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=gerar_boleto'
  }).then(r => r.json()).then(d => {
    if (d.ok) {
      Toast.show(d.msg, 'success', '💳', 4000);
      const box = document.getElementById('barcode-box');
      const val = document.getElementById('barcode-val');
      if (box && val) { val.textContent = d.barcode; box.style.display = 'block'; }
    }
  });
}
</script>
