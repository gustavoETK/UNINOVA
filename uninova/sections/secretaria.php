<?php // ─── SEÇÃO: SECRETARIA ── ?>
<div class="grid-2 anim-1">

  <!-- Serviços disponíveis -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">🏛</span> Serviços Disponíveis</div>
    <?php foreach ($SERVICOS_SECRETARIA as $s): ?>
    <div class="req-item">
      <div class="req-ico" style="background:var(--<?= $s['cor'] ?>-d,rgba(0,221,176,.1));font-size:1.2rem"><?= $s['ico'] ?></div>
      <div style="flex:1">
        <div class="fw-7 text-sm"><?= htmlspecialchars($s['nome']) ?></div>
        <div class="text-xs text-muted"><?= htmlspecialchars($s['desc']) ?></div>
        <div class="text-xs mono" style="color:var(--<?= $s['cor'] ?>);margin-top:.2rem">Prazo: <?= $s['prazo'] ?></div>
      </div>
      <button class="btn-solicitar" onclick="solicitarServico('<?= htmlspecialchars(addslashes($s['nome'])) ?>')">Solicitar</button>
    </div>
    <?php endforeach; ?>
  </div>

  <div>
    <!-- Minhas solicitações -->
    <div class="card">
      <div class="card-title"><span class="ct-icon">📬</span> Minhas Solicitações</div>
      <?php foreach ($SOLICITACOES as $r): ?>
      <div class="req-item" style="background:var(--bg);border-color:var(--line)">
        <div style="flex:1">
          <div class="fw-7 text-sm"><?= htmlspecialchars($r['nome']) ?></div>
          <div class="text-xs text-muted mono"><?= $r['data'] ?> · <?= $r['protocolo'] ?></div>
        </div>
        <span class="badge badge-<?= $r['status']==='concluido'?'teal':'amber' ?>">
          <?= $r['status']==='concluido' ? '✔ Concluído' : '⏳ Em andamento' ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Contato -->
    <div class="card mt-2" style="background:rgba(0,221,176,.04);border-color:rgba(0,221,176,.15)">
      <div class="fw-7 text-teal">📞 Atendimento</div>
      <div class="text-sm text-muted mt-1">Segunda a Sexta · 08h–22h · Bloco A, Térreo — Sala 002</div>
      <div class="divider"></div>
      <div class="text-sm flex items-center gap">📧 <span style="cursor:none" onclick="copyText('secretaria@uninova.edu.br','E-mail')">secretaria@uninova.edu.br</span> <span style="margin-left:auto;font-size:.7rem;color:var(--violet);cursor:none" onclick="copyText('secretaria@uninova.edu.br','E-mail')">📋 copiar</span></div>
      <div class="text-sm flex items-center gap mt-1">📱 <span>(11) 3456-7890</span></div>
    </div>
  </div>
</div>
