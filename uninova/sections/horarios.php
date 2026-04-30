<?php // ─── SEÇÃO: HORÁRIOS ── ?>
<div class="card anim-1">
  <div class="section-hd">
    <h2>🗓 Grade de Horários — 2025/1</h2>
    <div class="section-hd-actions">
      <button class="btn-solicitar" onclick="Toast.show('Grade exportada!','success','📥',3000)">📥 Exportar</button>
    </div>
  </div>
  <div style="overflow-x:auto">
    <div class="schedule-grid" style="min-width:580px">
      <div class="sched-hdr"></div>
      <?php foreach (['SEG','TER','QUA','QUI','SEX','SÁB'] as $dia): ?>
      <div class="sched-hdr"><?= $dia ?></div>
      <?php endforeach; ?>
      <?php
      $cores = ['CC401'=>'violet','CC402'=>'teal','CC403'=>'amber','CC404'=>'sky','CC405'=>'rose','HU101'=>'teal'];
      $nomes = ['CC401'=>'Algoritmos Av.','CC402'=>'Banco de Dados','CC403'=>'Eng. Software','CC404'=>'Redes','CC405'=>'Int. Artificial','HU101'=>'Ética e Leg.'];
      $salas = ['CC401'=>'Lab 02','CC402'=>'Lab 05','CC403'=>'Sala 12','CC404'=>'Lab 03','CC405'=>'Lab 01','HU101'=>'Sala 08'];
      foreach ($HORARIOS as $h => $cells):
      ?>
      <div class="sched-time"><?= $h ?></div>
      <?php foreach ($cells as $c): if ($c === '—'): ?>
        <div class="sched-cell sched-empty"></div>
      <?php else: ?>
        <div class="sched-cell sched-<?= $cores[$c] ?>" title="<?= $nomes[$c] ?> · <?= $salas[$c] ?>">
          <div class="sched-name"><?= $nomes[$c] ?></div>
          <div class="sched-sala"><?= $salas[$c] ?></div>
        </div>
      <?php endif; endforeach; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="divider"></div>
  <div class="flex gap" style="flex-wrap:wrap">
    <?php foreach ($nomes as $cod => $nome): ?>
    <span class="badge badge-<?= $cores[$cod] ?>"><?= $cod ?> — <?= $nome ?></span>
    <?php endforeach; ?>
  </div>
</div>

<div class="grid-3 mt-2 anim-2">
  <?php foreach ($DISCIPLINAS as $d): ?>
  <div class="card" style="border-left:3px solid var(--<?= $d['cor'] ?>)">
    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.7rem">
      <span class="badge badge-<?= $d['cor'] ?>"><?= $d['cod'] ?></span>
      <span class="fw-7 text-sm"><?= htmlspecialchars($d['nome']) ?></span>
    </div>
    <div class="text-xs text-muted" style="display:flex;flex-direction:column;gap:.3rem">
      <span>👤 <?= htmlspecialchars($d['prof']) ?></span>
      <span>🏫 <?= htmlspecialchars($d['sala'] ?? '—') ?></span>
      <span>⏱ <?= $d['ch'] ?>h de carga horária</span>
    </div>
  </div>
  <?php endforeach; ?>
</div>
