<?php // ─── SEÇÃO: FREQUÊNCIA ── ?>
<div class="card anim-1">
  <div class="section-hd">
    <h2>📋 Frequência por Disciplina</h2>
  </div>
  <table class="tbl">
    <thead><tr>
      <th>Disciplina</th><th>Faltas</th><th>Limite (25%)</th><th>Frequência</th><th>% Faltas</th><th>Status</th>
    </tr></thead>
    <tbody>
    <?php foreach ($DISCIPLINAS as $d):
      $pct  = pctFaltas($d['faltas'], $d['max_faltas']);
      $freq = 100 - $pct;
      $ok   = $pct <= 25;
      $cor  = $pct > 50 ? 'rose' : ($pct > 25 ? 'amber' : 'teal');
    ?>
      <tr>
        <td>
          <span class="fw-7"><?= htmlspecialchars($d['nome']) ?></span>
          <div class="mono text-xs" style="color:var(--<?= $d['cor'] ?>);margin-top:.1rem"><?= $d['cod'] ?></div>
        </td>
        <td><span class="mono fw-7" style="color:var(--<?= $cor ?>)"><?= $d['faltas'] ?></span></td>
        <td class="mono text-muted text-sm"><?= $d['max_faltas'] ?> aulas</td>
        <td>
          <div class="flex items-center gap" style="min-width:120px">
            <div class="pbar" style="flex:1"><div class="pbar-fill pbar-<?= $ok?'teal':'rose' ?>" style="width:<?= $freq ?>%"></div></div>
            <span class="mono text-xs"><?= $freq ?>%</span>
          </div>
        </td>
        <td>
          <div class="flex items-center gap" style="min-width:100px">
            <div class="pbar" style="flex:1"><div class="pbar-fill pbar-<?= $cor ?>" style="width:<?= $pct ?>%"></div></div>
            <span class="mono text-xs" style="color:var(--<?= $cor ?>)"><?= $pct ?>%</span>
          </div>
        </td>
        <td><span class="badge badge-<?= $ok?'teal':'rose' ?>"><?= $ok ? '✔ Regular' : '⚠ Atenção' ?></span></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php foreach ($DISCIPLINAS as $d):
  $pct = pctFaltas($d['faltas'], $d['max_faltas']);
  if ($pct <= 25) continue;
  $cor = $pct > 50 ? 'rose' : 'amber';
?>
<div class="card mt-2 anim-2" style="background:rgba(<?= $cor==='rose'?'245,97,122':'245,166,35' ?>,.05);border-color:rgba(<?= $cor==='rose'?'245,97,122':'245,166,35' ?>,.22)">
  <div class="flex items-center gap">
    <span style="font-size:1.3rem"><?= $cor==='rose'?'🚨':'⚠️' ?></span>
    <div>
      <div class="fw-7 text-<?= $cor ?>"><?= $cor==='rose'?'Risco crítico':'Atenção' ?>: <?= htmlspecialchars($d['nome']) ?></div>
      <div class="text-sm text-muted mt-1">
        Você possui <strong class="text-<?= $cor ?>"><?= $d['faltas'] ?></strong> faltas nesta disciplina.
        O limite máximo é <?= $d['max_faltas'] ?> faltas (25% de <?= $d['ch'] ?>h).
        <?= $pct > 50 ? 'Reprovação por falta iminente!' : 'Evite novas ausências.' ?>
      </div>
      <div class="pbar mt-1" style="height:6px;max-width:260px"><div class="pbar-fill pbar-<?= $cor ?>" style="width:<?= $pct ?>%"></div></div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<div class="card mt-2 anim-3" style="background:rgba(0,221,176,.04);border-color:rgba(0,221,176,.15)">
  <div class="fw-7 text-teal">ℹ️ Regra de Frequência UNINOVA</div>
  <div class="text-sm text-muted mt-1">
    O aluno que ultrapassar 25% de faltas em qualquer disciplina estará <strong>automaticamente reprovado por falta</strong>,
    independentemente das notas obtidas. Isso vale para todas as disciplinas do semestre.
  </div>
</div>
