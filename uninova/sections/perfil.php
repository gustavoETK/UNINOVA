<?php // ─── SEÇÃO: PERFIL ── ?>
<div class="anim-1">
  <div class="profile-hero">
    <div class="profile-avatar"><?= htmlspecialchars($user['avatar']) ?></div>
    <div>
      <div class="profile-info-name"><?= htmlspecialchars($user['nome']) ?></div>
      <div class="profile-info-ra">RA: <?= htmlspecialchars($user['ra']) ?> · <?= htmlspecialchars($user['email'] ?? '—') ?></div>
      <div class="profile-chips mt-1">
        <span class="badge badge-violet"><?= htmlspecialchars($user['curso']) ?></span>
        <span class="badge badge-teal"><?= htmlspecialchars($user['periodo']) ?></span>
        <span class="badge badge-muted">Turno <?= htmlspecialchars($user['turno']) ?></span>
        <span class="badge badge-<?= $user['situacao']==='Regular'||$user['situacao']==='Ativo'?'teal':'amber' ?>"><?= $user['situacao'] ?></span>
        <span class="badge badge-sky">Desde <?= $user['entrada'] ?></span>
      </div>
    </div>
  </div>

  <div class="grid-3 anim-2">

    <!-- Dados pessoais -->
    <div class="card">
      <div class="card-title">👤 Dados Pessoais</div>
      <?php
      $dados = [
        ['Nome Completo',  $user['nome']],
        ['RA / Matrícula', $user['ra']],
        ['Curso',          $user['curso']],
        ['Período',        $user['periodo']],
        ['Turno',          $user['turno']],
        ['E-mail',         $user['email'] ?? '—'],
        ['Telefone',       $user['telefone'] ?? '—'],
        ['Situação',       $user['situacao']],
      ];
      foreach ($dados as [$k,$v]): ?>
      <div class="flex justify-between" style="padding:.5rem 0;border-bottom:1px solid var(--line)">
        <span class="text-xs text-muted"><?= $k ?></span>
        <span class="text-sm mono" style="max-width:160px;text-align:right;word-break:break-all"><?= htmlspecialchars($v) ?></span>
      </div>
      <?php endforeach; ?>
      <button class="btn-action mt-2" onclick="Toast.show('Solicitação de atualização cadastral enviada!','info','📝',4000)">✏️ Atualizar dados</button>
    </div>

    <!-- Alterar senha -->
    <div class="card">
      <div class="card-title">🔐 Segurança</div>
      <form id="form-senha" onsubmit="handleSenha(event)">
        <div class="form-group">
          <label class="form-label">Senha Atual</label>
          <input class="form-input" type="password" name="atual" placeholder="••••••••">
        </div>
        <div class="form-group">
          <label class="form-label">Nova Senha</label>
          <input class="form-input" type="password" name="nova" placeholder="••••••••" id="nova-senha">
        </div>
        <div class="form-group">
          <label class="form-label">Confirmar Nova Senha</label>
          <input class="form-input" type="password" name="conf" placeholder="••••••••" id="conf-senha">
        </div>
        <div id="senha-match" style="font-size:.75rem;margin-bottom:.75rem;min-height:18px"></div>
        <button type="submit" class="btn-action">🔒 Alterar Senha</button>
      </form>
    </div>

    <!-- Estatísticas -->
    <div class="card">
      <div class="card-title">📊 Estatísticas Acadêmicas</div>
      <?php if ($user['cr']): ?>
      <div style="text-align:center;padding:1rem 0">
        <div class="tag" style="margin-bottom:.35rem">Coeficiente de Rendimento</div>
        <div style="font-family:'Syne',sans-serif;font-size:3.4rem;font-weight:800;color:var(--violet);line-height:1;margin:.4rem 0">
          <?= $user['cr'] ?>
        </div>
        <div class="pbar" style="height:6px"><div class="pbar-fill pbar-violet" style="width:<?= $user['cr']/10*100 ?>%"></div></div>
      </div>
      <div class="divider"></div>
      <?php endif; ?>
      <?php
      $stats = [
        ['Disciplinas',      count($DISCIPLINAS)],
        ['Carga Horária',    array_sum(array_column($DISCIPLINAS,'ch')).'h'],
        ['Total de Faltas',  array_sum(array_column($DISCIPLINAS,'faltas'))],
        ['Situação',         $user['situacao']],
        ['Semestre',         '2025/1'],
      ];
      foreach ($stats as [$k,$v]): ?>
      <div class="flex justify-between" style="padding:.5rem 0;border-bottom:1px solid var(--line)">
        <span class="text-xs text-muted"><?= $k ?></span>
        <span class="text-sm mono"><?= htmlspecialchars((string)$v) ?></span>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<script>
// Match de senha em tempo real
const novaSenha = document.getElementById('nova-senha');
const confSenha = document.getElementById('conf-senha');
const matchDiv  = document.getElementById('senha-match');
function checkMatch() {
  if (!novaSenha.value || !confSenha.value) { matchDiv.textContent = ''; return; }
  if (novaSenha.value === confSenha.value) {
    matchDiv.innerHTML = '<span style="color:var(--teal)">✓ Senhas coincidem</span>';
  } else {
    matchDiv.innerHTML = '<span style="color:var(--rose)">✗ Senhas não coincidem</span>';
  }
}
novaSenha?.addEventListener('input', checkMatch);
confSenha?.addEventListener('input', checkMatch);

function handleSenha(e) {
  e.preventDefault();
  const form = document.getElementById('form-senha');
  const data = new FormData(form);
  data.append('action','alterar_senha');
  fetch('api/actions.php', { method:'POST', body: new URLSearchParams(data) })
    .then(r => r.json()).then(d => {
      if (d.ok) { Toast.show(d.msg, 'success', '🔐', 4000); form.reset(); matchDiv.textContent = ''; }
      else       { Toast.show(d.msg, 'error', '❌', 4000); }
    });
}
</script>
