<?php // ─── SEÇÃO: SUPORTE ── ?>
<div class="grid-2 anim-1">

  <!-- FAQ -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">💬</span> Perguntas Frequentes</div>
    <?php foreach ($FAQS as $f): ?>
    <div class="faq-item">
      <div class="faq-q">
        <?= htmlspecialchars($f['q']) ?>
        <span class="faq-chevron">▾</span>
      </div>
      <div class="faq-a"><?= htmlspecialchars($f['a']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div>
    <!-- Abrir chamado -->
    <div class="card">
      <div class="card-title"><span class="ct-icon">📩</span> Abrir Chamado</div>
      <form id="form-chamado" onsubmit="handleChamado(event)">
        <div class="form-group">
          <label class="form-label">Categoria</label>
          <select class="form-input" name="assunto" style="appearance:none;cursor:none">
            <option value="">Selecione uma categoria</option>
            <option>Problemas com notas</option>
            <option>Acesso ao sistema</option>
            <option>Financeiro</option>
            <option>Frequência</option>
            <option>Biblioteca</option>
            <option>Outros</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Mensagem</label>
          <textarea class="form-input" name="mensagem" rows="5" placeholder="Descreva seu problema com detalhes..." style="resize:vertical"></textarea>
        </div>
        <button type="submit" class="btn-enviar">📤 Enviar Chamado</button>
      </form>
    </div>

    <!-- Status dos sistemas -->
    <div class="card mt-2">
      <div class="card-title"><span class="ct-icon">📊</span> Status dos Sistemas</div>
      <?php foreach ($SISTEMAS_STATUS as $s): ?>
      <div class="flex items-center justify-between" style="padding:.6rem 0;border-bottom:1px solid var(--line)">
        <div class="flex items-center gap">
          <span class="status-dot <?= $s['status'] ?>"></span>
          <span class="text-sm"><?= htmlspecialchars($s['nome']) ?></span>
        </div>
        <div class="flex items-center gap">
          <span class="mono text-xs text-muted"><?= $s['uptime'] ?> uptime</span>
          <span class="badge badge-<?= $s['status']==='online'?'teal':'amber' ?>">
            <?= $s['status']==='online' ? '● Online' : '⚙ Manutenção' ?>
          </span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
function handleChamado(e) {
  e.preventDefault();
  const form = document.getElementById('form-chamado');
  const data = new FormData(form);
  data.append('action','abrir_chamado');
  fetch('api/actions.php', { method:'POST', body: new URLSearchParams(data) })
    .then(r => r.json()).then(d => {
      if (d.ok) { Toast.show(d.msg, 'success', '📩', 5000); form.reset(); }
      else       { Toast.show(d.msg, 'warning', '⚠️', 4000); }
    });
}
</script>
