<?php // ─── SEÇÃO: NOTAS ── ?>

<!-- Boletim Completo -->
<div class="card anim-1">
  <div class="section-hd">
    <h2>📊 Boletim Semestral — 2025/1</h2>
    <div class="section-hd-actions">
      <button class="btn-solicitar" onclick="Toast.show('Boletim exportado como PDF!','success','📄',3000)">📥 Exportar PDF</button>
    </div>
  </div>
  <div style="overflow-x:auto">
  <table class="tbl">
    <thead>
      <tr>
        <th>Código</th><th>Disciplina</th><th>Professor</th>
        <th>M1</th><th>M2 (peso 2)</th><th>Média Final</th>
        <th>CH</th><th>Faltas</th><th>Frequência</th><th>Situação</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($DISCIPLINAS as $d):
      $mf = mediaFinal($d); [$sit,$cor] = situacao($mf);
      $freq = 100 - pctFaltas($d['faltas'], $d['max_faltas']);
    ?>
      <tr>
        <td><span class="mono text-xs" style="color:var(--<?= $d['cor'] ?>)"><?= $d['cod'] ?></span></td>
        <td>
          <span class="fw-7"><?= htmlspecialchars($d['nome']) ?></span>
          <div class="text-xs text-muted mono" style="margin-top:.15rem"><?= htmlspecialchars($d['sala'] ?? '') ?></div>
        </td>
        <td class="text-muted text-sm"><?= htmlspecialchars($d['prof']) ?></td>
        <td>
          <?php if ($d['m1'] !== null): ?>
          <span class="mono fw-7" style="color:<?= $d['m1']>=5?'var(--teal)':'var(--rose)' ?>"><?= number_format($d['m1'],1,',','') ?></span>
          <?php else: ?><span class="badge badge-muted">Pendente</span><?php endif; ?>
        </td>
        <td>
          <?php if ($d['m2'] !== null): ?>
          <span class="mono fw-7" style="color:<?= $d['m2']>=5?'var(--teal)':'var(--rose)' ?>"><?= number_format($d['m2'],1,',','') ?></span>
          <?php else: ?><span class="badge badge-muted">Pendente</span><?php endif; ?>
        </td>
        <td>
          <?php if ($mf !== null): ?>
          <span class="fw-7" style="font-family:'Syne',sans-serif;font-size:1.1rem;color:var(--<?=$cor?>)"><?= number_format($mf,2,',','') ?></span>
          <?php else: ?><span style="color:var(--muted)">—</span><?php endif; ?>
        </td>
        <td class="text-muted text-sm mono"><?= $d['ch'] ?>h</td>
        <td><span class="mono text-sm" style="color:<?= pctFaltas($d['faltas'], $d['max_faltas']) > 50 ? 'var(--rose)' : (pctFaltas($d['faltas'], $d['max_faltas']) > 25 ? 'var(--amber)' : 'var(--ink2)') ?>"><?= $d['faltas'] ?>/<?= $d['max_faltas'] ?></span></td>
        <td>
          <div class="flex items-center gap" style="min-width:90px">
            <div class="pbar" style="flex:1;height:4px"><div class="pbar-fill pbar-<?= $freq>=75?'teal':'rose' ?>" style="width:<?= $freq ?>%"></div></div>
            <span class="mono text-xs"><?= $freq ?>%</span>
          </div>
        </td>
        <td><span class="badge badge-<?= $cor ?>"><?= $sit ?></span></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>

<!-- Calculadora + Resumo -->
<div class="grid-2 mt-2 anim-2">

  <!-- Calculadora -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">🧮</span> Calculadora de Média Final</div>
    <div class="calc-form">
      <div class="calc-formula">
        MF = (<strong>M1</strong> + <strong>M2 × 2</strong>) ÷ 3
        <br><span style="font-size:.62rem;opacity:.55">A segunda prova tem peso duplo</span>
      </div>
      <form method="post" action="?s=notas" id="form-calc">
        <input type="hidden" name="action" value="calcmedia">
        <div class="calc-inputs">
          <div>
            <label class="calc-label">M1 — 1ª Avaliação</label>
            <input class="calc-input" type="number" name="m1" id="m1"
              min="0" max="10" step="0.01" placeholder="0,00"
              value="<?= $calc ? htmlspecialchars($calc['m1']) : '' ?>">
          </div>
          <div>
            <label class="calc-label">M2 — 2ª Avaliação (peso 2)</label>
            <input class="calc-input" type="number" name="m2" id="m2"
              min="0" max="10" step="0.01" placeholder="0,00"
              value="<?= $calc ? htmlspecialchars($calc['m2']) : '' ?>">
          </div>
        </div>
        <div id="live-preview">
          <?php if ($calc): ?>
          <span style="color:var(--<?= $calc['sit']==='Aprovado'?'teal':($calc['sit']==='Recuperação'?'amber':'rose') ?>)">
            <?= number_format($calc['mf'],2,',','') ?> — <?= htmlspecialchars($calc['sit']) ?>
          </span>
          <?php else: ?><span style="color:var(--muted)">—</span><?php endif; ?>
        </div>
        <button class="btn-calc" type="submit">⚡ Calcular Média Final</button>
      </form>
    </div>

    <?php if ($calc):
      $rc = $calc['sit']==='Aprovado'?'aprovado':($calc['sit']==='Recuperação'?'recuperacao':'reprovado');
      $rc_cor = $rc==='aprovado'?'teal':($rc==='recuperacao'?'amber':'rose');
    ?>
    <div class="result-card" style="margin-top:1.1rem">
      <div class="result-top <?= $rc ?>">
        <div class="result-mf"><?= number_format($calc['mf'],2,',','') ?></div>
        <div class="result-detail">
          <span class="badge badge-<?= $rc_cor ?>"><?= htmlspecialchars($calc['sit']) ?></span>
          <div class="result-desc" style="margin-top:.4rem">
            <?= $rc==='aprovado'?'🎉 Parabéns! Você está aprovado.':($rc==='recuperacao'?'📋 Avaliação adicional necessária.':'❌ Abaixo do mínimo exigido.') ?>
          </div>
        </div>
      </div>
      <div class="result-breakdown">
        <div class="rb-item"><span class="tag">M1</span><span style="color:var(--<?=$rc_cor?>)"><?= number_format($calc['m1'],2,',','') ?></span></div>
        <div class="rb-item"><span class="tag">M2</span><span style="color:var(--<?=$rc_cor?>)"><?= number_format($calc['m2'],2,',','') ?></span></div>
        <div class="rb-item"><span class="tag">MF</span><span style="color:var(--<?=$rc_cor?>);font-size:1.2rem"><?= number_format($calc['mf'],2,',','') ?></span></div>
        <div class="rb-formula">(<?= number_format($calc['m1'],1,',','') ?> + <?= number_format($calc['m2'],1,',','') ?>×2) ÷ 3</div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Resumo de situação + CR global -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">🎯</span> Resumo de Situação</div>
    <?php
    $aprov=$recup=$reprov=$pend=0;
    foreach ($DISCIPLINAS as $d) {
      $mf = mediaFinal($d);
      if ($mf===null){$pend++;continue;}
      if ($mf>=7)$aprov++;elseif($mf>=5)$recup++;else$reprov++;
    }
    ?>
    <div class="grid-2 gap-sm">
      <div style="background:var(--teal-d);border:1px solid rgba(0,221,176,.2);border-radius:12px;padding:1rem;text-align:center">
        <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;color:var(--teal)"><?= $aprov ?></div>
        <div class="tag">Aprovado</div>
      </div>
      <div style="background:var(--amber-d);border:1px solid rgba(245,166,35,.2);border-radius:12px;padding:1rem;text-align:center">
        <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;color:var(--amber)"><?= $recup ?></div>
        <div class="tag">Recuperação</div>
      </div>
      <div style="background:var(--rose-d);border:1px solid rgba(245,97,122,.2);border-radius:12px;padding:1rem;text-align:center">
        <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;color:var(--rose)"><?= $reprov ?></div>
        <div class="tag">Reprovado</div>
      </div>
      <div style="background:rgba(255,255,255,.03);border:1px solid var(--line2);border-radius:12px;padding:1rem;text-align:center">
        <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;color:var(--muted)"><?= $pend ?></div>
        <div class="tag">Pendente</div>
      </div>
    </div>
    <div class="divider"></div>
    <?php
    $allMF=[]; foreach($DISCIPLINAS as $d){$mf=mediaFinal($d);if($mf!==null)$allMF[]=$mf;}
    $globalCR = count($allMF) ? round(array_sum($allMF)/count($allMF),2) : 0;
    ?>
    <div style="text-align:center;padding:1rem 0">
      <div class="tag" style="margin-bottom:.4rem">Coeficiente de Rendimento Global</div>
      <div style="font-family:'Syne',sans-serif;font-size:3.2rem;font-weight:800;color:var(--violet);text-shadow:0 0 30px rgba(139,108,247,.4)">
        <?= number_format($globalCR,2,',','') ?>
      </div>
      <div class="pbar mt-1" style="height:7px"><div class="pbar-fill pbar-violet" style="width:<?= ($globalCR/10*100) ?>%"></div></div>
      <div class="text-xs text-muted mono mt-1">Mínimo de aprovação: MF ≥ 7,00</div>
    </div>
  </div>

</div>
