<?php // ─── SEÇÃO: BIBLIOTECA ── ?>
<div class="grid-2 anim-1">

  <!-- Empréstimos ativos -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">📚</span> Meus Empréstimos</div>
    <?php foreach ($BIBLIOTECA as $b): ?>
    <div class="book-row">
      <div class="book-icon"><?= $b['capa'] ?></div>
      <div class="book-info">
        <div class="book-title"><?= htmlspecialchars($b['titulo']) ?></div>
        <div class="book-author"><?= htmlspecialchars($b['autor']) ?></div>
        <div class="mono text-xs" style="color:var(--muted);margin-top:.2rem">ISBN: <?= $b['isbn'] ?></div>
        <?php if ($b['devol'] !== '—'): ?>
        <div class="book-devol">↩ Devolução: <?= $b['devol'] ?></div>
        <?php endif; ?>
      </div>
      <div style="display:flex;flex-direction:column;gap:.5rem;align-items:flex-end">
        <span class="badge badge-<?= $b['status']==='emprestado'?'amber':'sky' ?>"><?= $b['status'] ?></span>
        <?php if ($b['status']==='emprestado'): ?>
        <button class="btn-solicitar" onclick="renovarLivro('<?= htmlspecialchars(addslashes($b['titulo'])) ?>')">↺ Renovar</button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <div class="divider"></div>
    <div class="text-xs text-muted">📍 Biblioteca Central — Bloco C, 1º andar · Seg–Sáb 07h–23h</div>
  </div>

  <!-- Buscar acervo -->
  <div class="card">
    <div class="card-title"><span class="ct-icon">🔍</span> Buscar Acervo</div>
    <div class="search-wrapper">
      <span class="search-icon-abs">🔍</span>
      <input type="text" id="acervo-search" class="search-input" placeholder="Título, autor, ISBN, categoria...">
    </div>
    <div id="acervo-list">
      <?php foreach ($ACERVO as $a): ?>
      <div class="book-row acervo-item" data-title="<?= strtolower(htmlspecialchars($a['t'])) ?>" data-autor="<?= strtolower(htmlspecialchars($a['a'])) ?>" data-cat="<?= strtolower($a['cat']) ?>">
        <div class="book-icon" style="background:rgba(56,200,245,.06);border-color:rgba(56,200,245,.18)">📘</div>
        <div class="book-info">
          <div class="book-title"><?= htmlspecialchars($a['t']) ?></div>
          <div class="book-author"><?= htmlspecialchars($a['a']) ?></div>
          <span class="badge badge-muted" style="margin-top:.25rem"><?= $a['cat'] ?></span>
        </div>
        <div style="display:flex;flex-direction:column;gap:.4rem;align-items:flex-end">
          <span class="badge badge-<?= $a['disp']?'teal':'rose' ?>"><?= $a['disp']?'Disponível':'Indisponível' ?></span>
          <?php if ($a['disp']): ?>
          <button class="btn-solicitar" onclick="Toast.show('Reserva de &quot;<?= htmlspecialchars(addslashes($a['t'])) ?>&quot; realizada!','success','📖',4000)">+ Reservar</button>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
document.getElementById('acervo-search').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('.acervo-item').forEach(el => {
    const match = !q || el.dataset.title.includes(q) || el.dataset.autor.includes(q) || el.dataset.cat.includes(q);
    el.style.display = match ? '' : 'none';
  });
});
</script>
